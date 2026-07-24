<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\Attendance;
use App\Models\Bonus;
use App\Models\PayrollHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\Incentive;
use App\Models\Deduction;

use App\Models\PayrollItem;


class PayrollController extends Controller
{
        public function index(Request $request)
    {
        $query = Employee::with(['department', 'position']);

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('employee_code', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%");

            });
        }

        // Department
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Position
        if ($request->filled('position')) {
            $query->where('position_id', $request->position);
        }

        $employees = $query->get();
        

        // Sort after loading
        if ($request->salary == 'low') {

            $employees = $employees->sortBy(function ($employee) {
                return $employee->position->basic_salary;
            });

        } else {

            $employees = $employees->sortByDesc(function ($employee) {
                return $employee->position->basic_salary;
            });

        }

        $departments = Department::orderBy('department_name')->get();

        return view('admin.payroll', compact(
            'employees',
            'departments'
        ));
    }

    public function getPositions($department)
{
    return Position::where('department_id', $department)
        ->orderBy('position_title')
        ->get([
            'id',
            'position_title'
        ]);
}


public function preview(Employee $employee)
{
    $employee->load([
        'department',
        'position'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Attendance Summary
    |--------------------------------------------------------------------------
    */

    $attendance = Attendance::where('employee_id',$employee->id)
        ->get();

    $presentDays = $attendance
        ->whereIn('status',['Present','Late'])
        ->count();

    $lateMinutes = $attendance
        ->sum('late_minutes');

    $overtimeMinutes = $attendance
        ->sum('overtime_minutes');

    /*
    |--------------------------------------------------------------------------
    | Salary
    |--------------------------------------------------------------------------
    */

    $monthlySalary = $employee->position->basic_salary;

    $dailyRate = $monthlySalary / 22;

    $hourlyRate = $dailyRate / 8;

    $minuteRate = $hourlyRate / 60;

    $grossPay = $dailyRate * $presentDays;

    $lateDeduction = $minuteRate * $lateMinutes;

    $overtimePay =
        ($overtimeMinutes / 60)
        *
        $hourlyRate
        *
        1.25;

    /*
    |--------------------------------------------------------------------------
    | Bonuses
    |--------------------------------------------------------------------------
    */

    $bonuses = Bonus::where('department_id',$employee->department_id)
        ->where('position_id',$employee->position_id)
        ->where('is_active',1)
        ->get();

    $bonusTotal = $bonuses->sum('bonus_amount');

    $incentives = Incentive::where(
    'department_id',
    $employee->department_id)
        ->where(
            'position_id',
            $employee->position_id
        )
        ->where(
            'is_active',
            1
        )
        ->get();

        $incentiveTotal = $incentives->sum(
            'incentive_amount'
        );

    $deductions = Deduction::where(
    'department_id',
    $employee->department_id)
        ->where(
            'is_active',
            1
        )
        ->get();

        $departmentDeduction = $deductions->sum(
            'deduction_amount'
        );

    /*
    |--------------------------------------------------------------------------
    | Government Deductions
    |--------------------------------------------------------------------------
    */

    $sss = 675;

    $philhealth = 400;

    $pagibig = 100;

    /*
    |--------------------------------------------------------------------------
    | Net Pay
    |--------------------------------------------------------------------------
    */

$totalDeductions =
    $lateDeduction
    +
    $departmentDeduction
    +
    $sss
    +
    $philhealth
    +
    $pagibig;

$netPay =
    $grossPay
    +
    $bonusTotal
    +
    $incentiveTotal
    +
    $overtimePay
    -
    $totalDeductions;

    return view(
        'admin.payroll-preview',
       compact(
        'employee',
        'attendance',

        'presentDays',
        'lateMinutes',
        'overtimeMinutes',

        'monthlySalary',
        'dailyRate',
        'grossPay',

        'bonusTotal',
        'bonuses',

        'incentiveTotal',
        'incentives',

        'departmentDeduction',
        'deductions',

        'lateDeduction',
        'overtimePay',

        'sss',
        'philhealth',
        'pagibig',

        'totalDeductions',

        'netPay'
        )
    );
}

public function process(Employee $employee)
{
    return "Payroll processed successfully!";
}
}