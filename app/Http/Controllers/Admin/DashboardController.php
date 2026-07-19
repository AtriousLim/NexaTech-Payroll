<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

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
}