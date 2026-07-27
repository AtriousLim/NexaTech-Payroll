<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\PayrollHistory;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Employee Statistics
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $activePercentage = $totalEmployees > 0 ? round(($activeEmployees / $totalEmployees) * 100, 1) : 0;
        $newEmployeesThisMonth = Employee::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Payroll Statistics
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $payrollThisMonth = PayrollHistory::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('gross_pay');
        
        $pendingPayroll = PayrollHistory::where('status', 'pending')->count();

        // Department and Position Statistics
        $totalDepartments = Department::count();
        $totalPositions = \App\Models\Position::count();

        // Payroll Runs (count distinct payroll periods)
        $payrollRunsThisYear = DB::table('payroll_history')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('COUNT(DISTINCT cutoff_start) as runs')
            ->first()
            ->runs ?? 0;

        // Next Payroll (assuming monthly payroll on the 1st of next month)
        $nextPayroll = now()->addMonth()->startOfMonth();

        // Recent Activities and Payrolls
        $recentActivities = DB::table('activity_logs')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['action', 'created_at']);
        
        $recentPayrolls = PayrollHistory::with('employee')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'activePercentage',
            'newEmployeesThisMonth',
            'payrollThisMonth',
            'pendingPayroll',
            'totalDepartments',
            'totalPositions',
            'payrollRunsThisYear',
            'nextPayroll',
            'recentActivities',
            'recentPayrolls'
        ));
    }

    public function employees(Request $request)
    {
        $employees = $this->filteredEmployees($request)
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        $departments = Department::orderBy('department_name')->get(['id', 'department_name']);

        return view('admin.employee', compact('employees', 'departments'));
    }

    /**
     * Download the employee list as XML, using the same filters as the employee page.
     */
    public function exportEmployeesXml(Request $request)
    {
        $employees = $this->filteredEmployees($request)->get();
        $fileName = 'employees-' . now()->format('Y-m-d-His') . '.xml';

        return response()->streamDownload(function () use ($employees) {
            $escape = static fn ($value) => htmlspecialchars((string) ($value ?? ''), ENT_XML1 | ENT_COMPAT, 'UTF-8');

            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            echo '<employees exported_at="' . $escape(now()->toIso8601String()) . '">' . "\n";

            foreach ($employees as $employee) {
                echo "  <employee>\n";
                echo '    <employee_code>' . $escape($employee->employee_code) . "</employee_code>\n";
                echo '    <first_name>' . $escape($employee->first_name) . "</first_name>\n";
                echo '    <last_name>' . $escape($employee->last_name) . "</last_name>\n";
                echo '    <contact_number>' . $escape($employee->contact_number) . "</contact_number>\n";
                echo '    <gmail>' . $escape($employee->gmail) . "</gmail>\n";
                echo '    <address>' . $escape($employee->address) . "</address>\n";
                echo '    <department>' . $escape($employee->position?->department?->department_name) . "</department>\n";
                echo '    <position>' . $escape($employee->position?->position_title) . "</position>\n";
                echo '    <status>' . $escape($employee->status) . "</status>\n";
                echo "  </employee>\n";
            }

            echo '</employees>';
        }, $fileName, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * Apply employee-list filters for both the on-screen listing and XML export.
     */
    private function filteredEmployees(Request $request)
    {
        $search = $request->input('search');
        $department = $request->input('department');
        $status = $request->input('status');

        return Employee::with('position.department')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('employee_code', 'LIKE', "%{$search}%")
                        ->orWhere('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhereHas('position', function ($query) use ($search) {
                            $query->where('position_title', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($department, function ($query, $department) {
                $query->whereHas('position.department', function ($query) use ($department) {
                    $query->whereKey($department);
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            });
    }

    public function payroll()
    {
        return view('admin.payroll');
    }

    public function payrollHistory()
    {
        return view('admin.payroll-history');
    }

    public function activityLog()
    {
        $logs = DB::table('activity_logs')
            ->leftJoin('admins', 'activity_logs.admin_id', '=', 'admins.id')
            ->select('activity_logs.action', 'activity_logs.created_at', 'admins.name as admin_name')
            ->orderByDesc('activity_logs.created_at')
            ->paginate(15);

        return view('admin.activity-log', compact('logs'));
    }

    public function addAdmin()
    {
        return view('admin.add-admin');
    }

    public function storeAdmin(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed|regex:/[\W_]/', // <-- Updated rule
            'role' => 'required|string|max:255',
        ]);

        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        $this->logActivity("Added admin account for {$admin->name}.");

        return redirect()->route('admin.add-admin')
            ->with('success', 'Admin added successfully.');
    }

    public function createEmployee()
    {
        $positions = DB::table('positions')
            ->leftJoin('departments', 'positions.department_id', '=', 'departments.id')
            ->select('positions.id','positions.position_title','positions.basic_salary','positions.department_id')
            ->get();
        
        $departments = DB::table('departments')->select('id','department_name')->get();
        
        $nextEmployeeCode = $this->generateEmployeeCode();

        // prepare a simple id => salary map for the view (avoid closures in Blade)
        $salaries = $positions->pluck('basic_salary', 'id');
        
        // map positions to departments for frontend filtering
        $positionsByDepartment = [];
        foreach ($positions as $pos) {
            if (!isset($positionsByDepartment[$pos->department_id])) {
                $positionsByDepartment[$pos->department_id] = [];
            }
            $positionsByDepartment[$pos->department_id][] = [
                'id' => $pos->id,
                'position_title' => $pos->position_title,
                'basic_salary' => $pos->basic_salary
            ];
        }

        return view('admin.employee-create', compact('positions', 'departments', 'nextEmployeeCode', 'salaries', 'positionsByDepartment'));
    }

    public function viewEmployee(Employee $employee)
    {
        $employeeDetail = DB::table('employees')
            ->where('employees.id', $employee->id)
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->select('employees.*', 'positions.position_title')
            ->first();

        $attendance = DB::table('attendances')
            ->where('employee_id', $employee->id)
            ->orderBy('attendance_date', 'desc')
            ->get();

        return view('admin.employee-view', compact('employeeDetail', 'attendance'));
    }

    public function editEmployee(Employee $employee)
    {
        $positions = DB::table('positions')->select('id','position_title')->get();

        return view('admin.employee-edit', compact('employee', 'positions'));
    }

    public function updateEmployee(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:50',
            'gmail' => 'required|email|unique:employees,gmail,' . $employee->id,
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed',
            'nationality' => 'required|string|max:100',
            'role' => 'nullable|string',
            'position_id' => 'required|exists:positions,id',
            'status' => 'required|in:Active,Inactive',
        ]);

        $update = [
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'suffix' => $data['suffix'] ?? null,
            'address' => $data['address'],
            'contact_number' => $data['contact_number'],
            'gmail' => $data['gmail'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'civil_status' => $data['civil_status'],
            'nationality' => $data['nationality'],
            'employment_type' => $employee->employment_type,
            'role' => $data['role'] ?? $employee->role,
            'position_id' => $data['position_id'],
            'status' => $data['status'],
        ];

        $employee->update($update);

        $this->logActivity("Updated employee {$employee->employee_code} ({$employee->first_name} {$employee->last_name}).");

        return redirect()->route('admin.employees')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroyEmployee(Employee $employee)
    {
        $employeeCode = $employee->employee_code;
        $employeeName = trim("{$employee->first_name} {$employee->last_name}");
        $employee->delete();

        $this->logActivity("Deleted employee {$employeeCode} ({$employeeName}).");

        return redirect()->route('admin.employees')
            ->with('success', 'Employee deleted successfully.');
    }

    public function storeEmployee(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:50',
            'gmail' => 'required|email|unique:employees,gmail',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed',
            'nationality' => 'required|string|max:100',
            'employment_type' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            'role' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'status' => 'required|in:Active,Inactive',
        ]);

        $positionBelongsToDepartment = DB::table('positions')
            ->where('id', $data['position_id'])
            ->where('department_id', $data['department_id'])
            ->exists();

        if (! $positionBelongsToDepartment) {
            return back()
                ->withErrors(['position_id' => 'Please select a position in the selected department.'])
                ->withInput();
        }

        $employeeCode = $this->generateEmployeeCode();
        $posId = $data['position_id'];

        try {
            $employee = Employee::create([
                'employee_code' => $employeeCode,
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'suffix' => $data['suffix'] ?? null,
                'address' => $data['address'],
                'contact_number' => $data['contact_number'],
                'gmail' => $data['gmail'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'civil_status' => $data['civil_status'],
                'nationality' => $data['nationality'],
                'employment_type' => $data['employment_type'] ?? null,
                'department_id' => $data['department_id'],
                'role' => $data['role'] ?? 'employee',
                'position_id' => $posId,
                'status' => $data['status'],
            ]);

            $this->logActivity("Added employee {$employee->employee_code} ({$employee->first_name} {$employee->last_name}).");

            return redirect()->route('admin.employees')
                ->with('success', 'Employee added successfully.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to create employee: ' . $e->getMessage()])
                ->withInput();
        }
    }

    protected function generateEmployeeCode()
    {
        $lastId = Employee::max('id');
        $nextId = $lastId ? $lastId + 1 : 1;
        $code = 'EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        while (Employee::where('employee_code', $code)->exists()) {
            $nextId++;
            $code = 'EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    /** Record actions performed by the currently signed-in administrator. */
    private function logActivity(string $action): void
    {
        $adminId = auth('admin')->id();

        if ($adminId) {
            DB::table('activity_logs')->insert([
                'admin_id' => $adminId,
                'action' => $action,
                'created_at' => now(),
            ]);
        }
    }
}
