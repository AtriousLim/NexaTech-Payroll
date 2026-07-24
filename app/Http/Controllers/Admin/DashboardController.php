<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function employees(Request $request)
    {
        $search = $request->input('search');

        $employees = Employee::with('position')
            ->when($search, function ($query, $search) {
                $query->where('employee_code', 'LIKE', "%{$search}%")
                      ->orWhere('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%");
            })
            ->get();

        return view('admin.employee', compact('employees'));
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
        return view('admin.activity-log');
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
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|max:255',
        ]);

        Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.add-admin')
            ->with('success', 'Admin added successfully.');
    }

    public function createEmployee()
    {
        $positions = DB::table('positions')->select('id','position_title','basic_salary')->get();
        $nextEmployeeCode = $this->generateEmployeeCode();

        // prepare a simple id => salary map for the view (avoid closures in Blade)
        $salaries = $positions->pluck('basic_salary', 'id');

        return view('admin.employee-create', compact('positions', 'nextEmployeeCode', 'salaries'));
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
            ->orderBy('date', 'desc')
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
            'last_name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:50',
            'gmail' => 'required|email|unique:employees,gmail,' . $employee->id,
            'username' => 'required|string|max:100|unique:employees,username,' . $employee->id,
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|string',
            'position_id' => 'required|exists:positions,id',
            'status' => 'required|in:Active,Inactive',
        ]);

        $update = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'address' => $data['address'],
            'contact_number' => $data['contact_number'],
            'gmail' => $data['gmail'],
            'username' => $data['username'],
            'role' => $data['role'] ?? $employee->role,
            'position_id' => $data['position_id'],
            'status' => $data['status'],
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $employee->update($update);

        return redirect()->route('admin.employees')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroyEmployee(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('admin.employees')
            ->with('success', 'Employee deleted successfully.');
    }

    public function storeEmployee(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:50',
            'gmail' => 'required|email|unique:employees,gmail',
            'username' => 'required|string|max:100|unique:employees,username',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string',
            'position_id' => 'required|exists:positions,id',
            'status' => 'required|in:Active,Inactive',
        ]);

        $employeeCode = $this->generateEmployeeCode();
        $posId = $data['position_id'];

        Employee::create([
            'employee_code' => $employeeCode,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'address' => $data['address'],
            'contact_number' => $data['contact_number'],
            'gmail' => $data['gmail'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'employee',
            'position_id' => $posId,
            'status' => $data['status'],
        ]);

        return redirect()->route('admin.employees')
            ->with('success', 'Employee added successfully.');
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
}
