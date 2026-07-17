<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function employees()
    {
        return view('admin.employee');
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