<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee; // Imports the Employee model
use Illuminate\Http\Request; // Imports the Request class for handling search input

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function employees(Request $request)
    {
        // 1. Capture the search keyword from the input field
        $search = $request->input('search');

        // 2. Fetch employees, filtering them if a keyword is present
        $employees = Employee::query()
            ->when($search, function ($query, $search) {
                $query->where('employee_code', 'LIKE', "%{$search}%")
                      ->orWhere('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%");
            })
            ->get();

        // 3. Pass the filtered employees back to the view
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