<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function employees()
    {
        $employees = DB::table('employees')
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->select('employees.*', 'positions.position_title')
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

    public function createEmployee()
    {
        $positions = DB::table('positions')->select('id','position_title')->get();
        $nextEmployeeCode = $this->generateEmployeeCode();

        return view('admin.employee-create', compact('positions', 'nextEmployeeCode'));
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
