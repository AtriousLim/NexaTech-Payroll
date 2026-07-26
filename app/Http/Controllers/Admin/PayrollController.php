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
            $query->where('employees.department_id', $request->department);
        }

        // Position
        if ($request->filled('position')) {
            $query->where('employees.position_id', $request->position);
        }

        $query->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->select('employees.*');

        if ($request->salary === 'low') {
            $query->orderBy('positions.basic_salary', 'asc');
        } else {
            $query->orderBy('positions.basic_salary', 'desc');
        }

        $employees = $query
            ->paginate(10)
            ->appends($request->query());

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

    public function process(Request $request, Employee $employee)
    {
        $employee->load(['department', 'position']);

        // Recalculate calculations for backend validation
        $attendance = Attendance::where('employee_id', $employee->id)->get();
        $presentDays = $attendance->whereIn('status', ['Present', 'Late'])->count();
        $lateMinutes = $attendance->sum('late_minutes');
        $overtimeMinutes = $attendance->sum('overtime_minutes');

        $monthlySalary = $employee->position->basic_salary;
        $dailyRate = $monthlySalary / 22;
        $hourlyRate = $dailyRate / 8;
        $minuteRate = $hourlyRate / 60;

        $grossPay = $dailyRate * $presentDays;
        $lateDeduction = $minuteRate * $lateMinutes;
        $overtimePay = ($overtimeMinutes / 60) * $hourlyRate * 1.25;

        // Calculate selected Bonuses
        $selectedBonuses = $request->input('bonuses', []);
        $bonuses = Bonus::whereIn('id', $selectedBonuses)->get();
        $bonusTotal = $bonuses->sum('bonus_amount');

        // Calculate selected Incentives
        $selectedIncentives = $request->input('incentives', []);
        $incentives = Incentive::whereIn('id', $selectedIncentives)->get();
        $incentiveTotal = $incentives->sum('incentive_amount');

        // Calculate selected Deductions from checkboxes
        $selectedDeductions = $request->input('deductions', []);
        $deductions = Deduction::whereIn('id', $selectedDeductions)->get();
        $departmentDeduction = $deductions->sum('deduction_amount');

        // Fixed Government Deductions
        $sss = 675;
        $philhealth = 400;
        $pagibig = 100;

        $totalDeductions = $lateDeduction + $departmentDeduction + $sss + $philhealth + $pagibig;
        $netPay = $grossPay + $bonusTotal + $incentiveTotal + $overtimePay - $totalDeductions;

        // 1. Create the Payroll History record (matching exact database schema)
        $payroll = PayrollHistory::create([
            'employee_id'          => $employee->id,
            'processed_by'         => Auth::id() ?? 1,
            'cutoff_start'         => now()->startOfMonth()->toDateString(),
            'cutoff_end'           => now()->endOfMonth()->toDateString(),
            'gross_pay'            => $grossPay,
            'sss_deduction'        => $sss,
            'philhealth_deduction' => $philhealth,
            'pagibig_deduction'    => $pagibig,
            'late_deductions'      => $lateDeduction,
            'total_deductions'     => $totalDeductions,
            'net_pay'              => $netPay,
            'status'               => 'Processed',
        ]);

        // 2. Save individual itemized bonuses
        foreach ($bonuses as $bonus) {
            PayrollItem::create([
                'payroll_id' => $payroll->id,
                'item_type'  => 'bonus',
                'item_name'  => $bonus->bonus_name,
                'amount'     => $bonus->bonus_amount,
            ]);
        }

        // 3. Save individual itemized incentives
        foreach ($incentives as $incentive) {
            PayrollItem::create([
                'payroll_id' => $payroll->id,
                'item_type'  => 'incentive',
                'item_name'  => $incentive->incentive_name,
                'amount'     => $incentive->incentive_amount,
            ]);
        }

        // 4. Save individual itemized custom deductions
        foreach ($deductions as $deduction) {
            PayrollItem::create([
                'payroll_id' => $payroll->id,
                'item_type'  => 'deduction',
                'item_name'  => $deduction->deduction_name,
                'amount'     => $deduction->deduction_amount,
            ]);
        }

        return redirect()->route('admin.payroll-history')
            ->with('success', 'Payroll for ' . $employee->first_name . ' ' . $employee->last_name . ' has been confirmed successfully!');
    }

    public function history()
    {
        // Added 'items' relationship here so deductions/bonuses/incentives can be accessed in views
        $payrolls = PayrollHistory::with(['employee.department', 'employee.position', 'items'])
            ->latest()
            ->paginate(10);

        return view('admin.payroll-history', compact('payrolls'));
    }
}