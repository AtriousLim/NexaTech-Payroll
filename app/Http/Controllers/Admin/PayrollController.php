<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Bonus;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Incentive;
use App\Models\Payroll;
use App\Models\PayrollHistory;
use App\Models\PayrollItem;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('employees.department_id', $request->department);
        }

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

        return view('admin.payroll', compact('employees', 'departments'));
    }

    public function getPositions($department)
    {
        return Position::where('department_id', $department)
            ->orderBy('position_title')
            ->get(['id', 'position_title']);
    }

    public function preview(Employee $employee)
    {
        $employee->load(['department', 'position']);

        $attendance = Attendance::where('employee_id', $employee->id)->get();
        $presentDays = $attendance->whereIn('status', ['Present', 'Late'])->count();
        $lateMinutes = $attendance->sum('late_minutes');
        $overtimeMinutes = $attendance->sum('overtime_minutes');

        $monthlySalary = $employee->position->basic_salary;
        $dailyRate = $monthlySalary / 22;
        $hourlyRate = $dailyRate / 8;
        $minuteRate = $hourlyRate / 60;

        $grossPay = $monthlySalary;
        $lateDeduction = $minuteRate * $lateMinutes;
        $overtimePay = ($overtimeMinutes / 60) * $hourlyRate * 1.25;

        $bonuses = Bonus::where('department_id', $employee->department_id)
            ->where('position_id', $employee->position_id)
            ->where('is_active', 1)
            ->get();
        $bonusTotal = $bonuses->sum('bonus_amount');

        $incentives = Incentive::where('department_id', $employee->department_id)
            ->where('position_id', $employee->position_id)
            ->where('is_active', 1)
            ->get();
        $incentiveTotal = $incentives->sum('incentive_amount');

        $deductions = Deduction::where('department_id', $employee->department_id)
            ->where('is_active', 1)
            ->get();
        $departmentDeduction = $deductions->sum('deduction_amount');

        // Statutory deductions at full monthly rates
        $sss = 675.00;
        $philhealth = 400.00;
        $pagibig = 100.00;

        $totalDeductions = $lateDeduction + $departmentDeduction + $sss + $philhealth + $pagibig;
        $netPay = $grossPay + $bonusTotal + $incentiveTotal + $overtimePay - $totalDeductions;

        $existingPayroll = PayrollHistory::where('employee_id', $employee->id)
            ->where('cutoff_start', now()->startOfMonth()->toDateString())
            ->where('cutoff_end', now()->endOfMonth()->toDateString())
            ->first();

        return view('admin.payroll-preview', compact(
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
            'netPay',
            'existingPayroll'
        ));
    }

    public function process(Request $request, Employee $employee)
    {
        $request->validate([
            'bonuses' => ['nullable', 'array'],
            'incentives' => ['nullable', 'array'],
            'deductions' => ['nullable', 'array'],
        ]);

        $employee->load(['department', 'position']);

        $cutoffStart = now()->startOfMonth()->toDateString();
        $cutoffEnd = now()->endOfMonth()->toDateString();

        $existingPayroll = PayrollHistory::where('employee_id', $employee->id)
            ->where('cutoff_start', $cutoffStart)
            ->where('cutoff_end', $cutoffEnd)
            ->first();

        if ($existingPayroll) {
            return back()->with('warning', 'Payroll already exists for this cutoff period.');
        }

        $calculated = $this->calculatePayrollValues($employee, $request);

        $payrollHistory = null;
        $payrollEntry = null;

        try {
            DB::transaction(function () use ($employee, $calculated, &$payrollHistory, &$payrollEntry, $cutoffStart, $cutoffEnd) {
                $payrollHistory = PayrollHistory::create([
                    'employee_id' => $employee->id,
                    'processed_by' => Auth::guard('admin')->id(),
                    'cutoff_start' => $cutoffStart,
                    'cutoff_end' => $cutoffEnd,
                    'gross_pay' => round($calculated['grossPay'], 2),
                    'sss_deduction' => round($calculated['sss'], 2),
                    'philhealth_deduction' => round($calculated['philhealth'], 2),
                    'pagibig_deduction' => round($calculated['pagibig'], 2),
                    'late_deductions' => round($calculated['lateDeduction'], 2),
                    'net_pay' => round($calculated['netPay'], 2),
                    'status' => 'Pending',
                ]);

                $payrollEntry = Payroll::create($this->buildPayrollPayload($employee, $calculated, $cutoffStart, $cutoffEnd, $this->generatePayrollNumber(), $payrollHistory->id));

                $this->saveItems($payrollHistory, $calculated['selectedBonuses'], $calculated['selectedIncentives'], $calculated['selectedDeductions']);
            });
        } catch (\Throwable $exception) {
            return back()->with('warning', 'Payroll could not be saved. Please try again.')->withErrors(['database' => $exception->getMessage()]);
        }

        $employeeName = trim($employee->first_name . ' ' . $employee->last_name);
        $this->logActivity("Processed payroll for {$employeeName}.");

        return redirect()->route('admin.payroll-history')->with('success', 'Payroll for ' . $employeeName . ' has been saved successfully.');
    }

    public function processAll(Request $request)
    {
        $employees = Employee::with(['department', 'position'])->get();

        if ($employees->isEmpty()) {
            return back()->with('warning', 'No employees found to process.');
        }

        $cutoffStart = now()->startOfMonth()->toDateString();
        $cutoffEnd = now()->endOfMonth()->toDateString();

        $processedCount = 0;

        try {
            DB::transaction(function () use ($employees, $cutoffStart, $cutoffEnd, &$processedCount) {
                foreach ($employees as $employee) {
                    $existingPayroll = PayrollHistory::where('employee_id', $employee->id)
                        ->where('cutoff_start', $cutoffStart)
                        ->where('cutoff_end', $cutoffEnd)
                        ->exists();

                    if ($existingPayroll) {
                        continue;
                    }

                    $calculated = $this->calculatePayrollValues($employee, new Request());

                    $payrollHistory = PayrollHistory::create([
                        'employee_id' => $employee->id,
                        'processed_by' => Auth::guard('admin')->id(),
                        'cutoff_start' => $cutoffStart,
                        'cutoff_end' => $cutoffEnd,
                        'gross_pay' => round($calculated['grossPay'], 2),
                        'sss_deduction' => round($calculated['sss'], 2),
                        'philhealth_deduction' => round($calculated['philhealth'], 2),
                        'pagibig_deduction' => round($calculated['pagibig'], 2),
                        'late_deductions' => round($calculated['lateDeduction'], 2),
                        'net_pay' => round($calculated['netPay'], 2),
                        'status' => 'Pending',
                    ]);

                    Payroll::create($this->buildPayrollPayload($employee, $calculated, $cutoffStart, $cutoffEnd, $this->generatePayrollNumber(), $payrollHistory->id));

                    $this->saveItems($payrollHistory, $calculated['selectedBonuses'], $calculated['selectedIncentives'], $calculated['selectedDeductions']);
                    $processedCount++;
                }
            });
        } catch (\Throwable $exception) {
            return back()->with('warning', 'Batch payroll could not be completed.')->withErrors(['database' => $exception->getMessage()]);
        }

        $this->logActivity('Processed payroll for all employees.');

        return redirect()->route('admin.payroll')->with('success', 'Processed payroll for ' . $processedCount . ' employees as a grouped batch transaction.');
    }

    public function history()
    {
        $payrolls = PayrollHistory::with(['employee.department', 'employee.position', 'items', 'payrollEntry'])
            ->latest()
            ->paginate(10);

        return view('admin.payroll-history', compact('payrolls'));
    }

    public function showPayslip(PayrollHistory $payroll)
    {
        $payroll->load(['employee.department', 'employee.position', 'items', 'payrollEntry']);

        return view('admin.payslip', compact('payroll'));
    }

    public function edit(PayrollHistory $payroll)
    {
        $payroll->load(['employee.department', 'employee.position', 'items']);
        $employee = $payroll->employee;
        $employee->load(['department', 'position']);

        $attendance = Attendance::where('employee_id', $employee->id)->get();
        $presentDays = $attendance->whereIn('status', ['Present', 'Late'])->count();
        $lateMinutes = $attendance->sum('late_minutes');
        $overtimeMinutes = $attendance->sum('overtime_minutes');

        $monthlySalary = $employee->position->basic_salary;
        $dailyRate = $monthlySalary / 22;
        $hourlyRate = $dailyRate / 8;
        $minuteRate = $hourlyRate / 60;

        $grossPay = $monthlySalary;
        $lateDeduction = $minuteRate * $lateMinutes;
        $overtimePay = ($overtimeMinutes / 60) * $hourlyRate * 1.25;

        $bonuses = Bonus::where('department_id', $employee->department_id)
            ->where('position_id', $employee->position_id)
            ->where('is_active', 1)
            ->get();

        $incentives = Incentive::where('department_id', $employee->department_id)
            ->where('position_id', $employee->position_id)
            ->where('is_active', 1)
            ->get();

        $deductions = Deduction::where('department_id', $employee->department_id)
            ->where('is_active', 1)
            ->get();

        $selectedBonuses = $payroll->items()->where('item_type', 'Bonus')->pluck('reference_id')->toArray();
        $selectedIncentives = $payroll->items()->where('item_type', 'Incentive')->pluck('reference_id')->toArray();
        $selectedDeductions = $payroll->items()->where('item_type', 'Deduction')->pluck('reference_id')->toArray();

        return view('admin.payroll-edit', compact(
            'payroll',
            'employee',
            'bonuses',
            'incentives',
            'deductions',
            'selectedBonuses',
            'selectedIncentives',
            'selectedDeductions',
            'grossPay',
            'lateDeduction',
            'overtimePay'
        ));
    }

    public function update(Request $request, PayrollHistory $payroll)
    {
        $request->validate([
            'bonuses' => ['nullable', 'array'],
            'incentives' => ['nullable', 'array'],
            'deductions' => ['nullable', 'array'],
            'gross_pay' => ['nullable', 'numeric'],
            'late_deductions' => ['nullable', 'numeric'],
            'sss_deduction' => ['nullable', 'numeric'],
            'philhealth_deduction' => ['nullable', 'numeric'],
            'pagibig_deduction' => ['nullable', 'numeric'],
            'overtime_pay' => ['nullable', 'numeric'],
        ]);

        $employee = $payroll->employee;
        $employee->load(['department', 'position']);

        $calculated = $this->calculatePayrollValues($employee, $request, true);
        $grossPay = (float) $request->input('gross_pay', $calculated['grossPay']);
        $lateDeduction = (float) $request->input('late_deductions', $calculated['lateDeduction']);
        $sss = (float) $request->input('sss_deduction', $calculated['sss']);
        $philhealth = (float) $request->input('philhealth_deduction', $calculated['philhealth']);
        $pagibig = (float) $request->input('pagibig_deduction', $calculated['pagibig']);
        $overtimePay = (float) $request->input('overtime_pay', $calculated['overtimePay']);
        $bonusTotal = (float) $calculated['bonusTotal'];
        $incentiveTotal = (float) $calculated['incentiveTotal'];
        $departmentDeduction = (float) $calculated['departmentDeduction'];
        $netPay = $grossPay + $bonusTotal + $incentiveTotal + $overtimePay - ($lateDeduction + $departmentDeduction + $sss + $philhealth + $pagibig);

        try {
            DB::transaction(function () use ($payroll, $grossPay, $lateDeduction, $sss, $philhealth, $pagibig, $bonusTotal, $incentiveTotal, $departmentDeduction, $overtimePay, $netPay, $request) {
                $payroll->update([
                    'gross_pay' => round($grossPay, 2),
                    'sss_deduction' => round($sss, 2),
                    'philhealth_deduction' => round($philhealth, 2),
                    'pagibig_deduction' => round($pagibig, 2),
                    'late_deductions' => round($lateDeduction, 2),
                    'net_pay' => round($netPay, 2),
                ]);

                $payrollEntry = $payroll->payrollEntry;

                if ($payrollEntry) {
                    $payrollEntry->update([
                        'total_bonus' => round($bonusTotal, 2),
                        'total_incentive' => round($incentiveTotal, 2),
                        'total_deduction' => round($departmentDeduction, 2),
                        'overtime_pay' => round($overtimePay, 2),
                        'late_deduction' => round($lateDeduction, 2),
                        'gross_salary' => round($grossPay, 2),
                        'net_salary' => round($netPay, 2),
                    ]);
                }

                $payroll->items()->delete();
                $this->saveItems($payroll, $request->input('bonuses', []), $request->input('incentives', []), $request->input('deductions', []));
            });
        } catch (\Throwable $exception) {
            return back()->with('warning', 'The payroll update could not be completed.')->withErrors(['database' => $exception->getMessage()]);
        }

        $employeeName = trim($employee->first_name . ' ' . $employee->last_name);
        $this->logActivity("Updated payroll for {$employeeName}.");

        return redirect()->route('admin.payroll-history')->with('success', 'Payroll updated successfully.');
    }

    public function approve(PayrollHistory $payroll)
    {
        if ($payroll->status === 'Paid') {
            return back()->with('warning', 'This payroll is already approved.');
        }

        try {
            DB::transaction(function () use ($payroll) {
                $payroll->update([
                    'status' => 'Paid',
                    'payment_date' => now(),
                ]);

                $payrollEntry = $payroll->payrollEntry;

                if ($payrollEntry) {
                    $payrollEntry->update([
                        'status' => 'Paid',
                        'paid_at' => now(),
                    ]);
                }
            });
        } catch (\Throwable $exception) {
            return back()->with('warning', 'The payroll could not be approved.')->withErrors(['database' => $exception->getMessage()]);
        }

        $employeeName = trim($payroll->employee?->first_name . ' ' . $payroll->employee?->last_name);
        $this->logActivity("Approved payroll for {$employeeName}.");

        return redirect()->route('admin.payroll-history')->with('success', 'Payroll approved successfully.');
    }

    public function approveAllPending()
    {
        $pendingPayrolls = PayrollHistory::where('status', 'Pending')->get();

        if ($pendingPayrolls->isEmpty()) {
            return back()->with('warning', 'No pending payroll transactions to approve.');
        }

        try {
            DB::transaction(function () use ($pendingPayrolls) {
                foreach ($pendingPayrolls as $payroll) {
                    $payroll->update([
                        'status' => 'Paid',
                        'payment_date' => now(),
                    ]);

                    $payrollEntry = $payroll->payrollEntry;

                    if ($payrollEntry) {
                        $payrollEntry->update([
                            'status' => 'Paid',
                            'paid_at' => now(),
                        ]);
                    }
                }
            });
        } catch (\Throwable $exception) {
            return back()->with('warning', 'The pending payrolls could not be approved.')->withErrors(['database' => $exception->getMessage()]);
        }

        $this->logActivity('Approved all pending payroll transactions.');

        return redirect()->route('admin.payroll-history')->with('success', 'All pending payroll transactions were approved successfully.');
    }

    public function destroy(PayrollHistory $payroll)
    {
        try {
            DB::transaction(function () use ($payroll) {
                $payrollEntry = $payroll->payrollEntry;

                if ($payrollEntry) {
                    $payrollEntry->delete();
                }

                $payroll->items()->delete();
                $payroll->delete();
            });
        } catch (\Throwable $exception) {
            return back()->with('warning', 'The payroll could not be deleted.')->withErrors(['database' => $exception->getMessage()]);
        }

        $employeeName = trim($payroll->employee?->first_name . ' ' . $payroll->employee?->last_name);
        $this->logActivity("Deleted payroll for {$employeeName}.");

        return redirect()->route('admin.payroll-history')->with('success', 'Payroll deleted successfully.');
    }

    private function calculatePayrollValues(Employee $employee, Request $request, bool $isEdit = false): array
    {
        $employee->load(['department', 'position']);

        $monthlySalary = $employee->position->basic_salary;
        $dailyRate = $monthlySalary / 22;
        $hourlyRate = $dailyRate / 8;
        $minuteRate = $hourlyRate / 60;

        // Attendance used only for late deductions and overtime calculations
        $attendance = Attendance::where('employee_id', $employee->id)->get();
        $presentDays = $attendance->whereIn('status', ['Present', 'Late'])->count();
        $lateMinutes = $attendance->sum('late_minutes');
        $overtimeMinutes = $attendance->sum('overtime_minutes');

        // Gross pay is the FULL monthly salary (not prorated by attendance days)
        $grossPay = $monthlySalary;
        $lateDeduction = $minuteRate * $lateMinutes;
        $overtimePay = ($overtimeMinutes / 60) * $hourlyRate * 1.25;

        $selectedBonuses = $request->input('bonuses', []);
        $bonuses = Bonus::whereIn('id', $selectedBonuses)->get();
        $bonusTotal = $bonuses->sum('bonus_amount');

        $selectedIncentives = $request->input('incentives', []);
        $incentives = Incentive::whereIn('id', $selectedIncentives)->get();
        $incentiveTotal = $incentives->sum('incentive_amount');

        $selectedDeductions = $request->input('deductions', []);
        $deductions = Deduction::whereIn('id', $selectedDeductions)->get();
        $departmentDeduction = $deductions->sum('deduction_amount');

        // Statutory deductions at full monthly rates
        $sss = 675.00;
        $philhealth = 400.00;
        $pagibig = 100.00;

        $totalDeductions = $lateDeduction + $departmentDeduction + $sss + $philhealth + $pagibig;
        $netPay = $grossPay + $bonusTotal + $incentiveTotal + $overtimePay - $totalDeductions;

        return [
            'grossPay' => round($grossPay, 2),
            'lateDeduction' => round($lateDeduction, 2),
            'overtimePay' => round($overtimePay, 2),
            'bonusTotal' => round($bonusTotal, 2),
            'incentiveTotal' => round($incentiveTotal, 2),
            'departmentDeduction' => round($departmentDeduction, 2),
            'sss' => round($sss, 2),
            'philhealth' => round($philhealth, 2),
            'pagibig' => round($pagibig, 2),
            'totalDeductions' => round($totalDeductions, 2),
            'netPay' => round($netPay, 2),
            'selectedBonuses' => $selectedBonuses,
            'selectedIncentives' => $selectedIncentives,
            'selectedDeductions' => $selectedDeductions,
        ];
    }

    private function buildPayrollPayload(Employee $employee, array $calculated, string $cutoffStart, string $cutoffEnd, string $payrollNumber, ?int $payrollHistoryId = null): array
    {
        $payload = [
            'employee_id' => $employee->id,
            'payroll_history_id' => $payrollHistoryId,
            'basic_salary' => round((float) $employee->position?->basic_salary ?? 0, 2),
            'payroll_number' => $payrollNumber,
            'pay_period_start' => $cutoffStart,
            'pay_period_end' => $cutoffEnd,
            'total_bonus' => round($calculated['bonusTotal'], 2),
            'total_incentive' => round($calculated['incentiveTotal'], 2),
            'total_deduction' => round($calculated['departmentDeduction'], 2),
            'overtime_pay' => round($calculated['overtimePay'], 2),
            'late_deduction' => round($calculated['lateDeduction'], 2),
            'gross_salary' => round($calculated['grossPay'], 2),
            'net_salary' => round($calculated['netPay'], 2),
            'processed_by' => Auth::guard('admin')->id(),
            'status' => 'Pending',
        ];

        if (Schema::hasColumn('payrolls', 'department')) {
            $payload['department'] = $employee->department?->department_name ?? 'N/A';
        }

        if (Schema::hasColumn('payrolls', 'position')) {
            $payload['position'] = $employee->position?->position_title ?? 'N/A';
        }

        return $payload;
    }

    private function saveItems(PayrollHistory $payrollHistory, array $selectedBonuses, array $selectedIncentives, array $selectedDeductions): void
    {
        foreach ($selectedBonuses as $bonusId) {
            $bonus = Bonus::find($bonusId);
            if ($bonus) {
                PayrollItem::create([
                    'payroll_id' => $payrollHistory->id,
                    'item_type' => 'Bonus',
                    'item_name' => $bonus->bonus_name,
                    'amount' => round($bonus->bonus_amount, 2),
                    'reference_id' => $bonus->id,
                ]);
            }
        }

        foreach ($selectedIncentives as $incentiveId) {
            $incentive = Incentive::find($incentiveId);
            if ($incentive) {
                PayrollItem::create([
                    'payroll_id' => $payrollHistory->id,
                    'item_type' => 'Incentive',
                    'item_name' => $incentive->incentive_name,
                    'amount' => round($incentive->incentive_amount, 2),
                    'reference_id' => $incentive->id,
                ]);
            }
        }

        foreach ($selectedDeductions as $deductionId) {
            $deduction = Deduction::find($deductionId);
            if ($deduction) {
                PayrollItem::create([
                    'payroll_id' => $payrollHistory->id,
                    'item_type' => 'Deduction',
                    'item_name' => $deduction->deduction_name,
                    'amount' => round($deduction->deduction_amount, 2),
                    'reference_id' => $deduction->id,
                ]);
            }
        }
    }

    private function generatePayrollNumber(): string
    {
        $lastPayroll = Payroll::latest('id')->first();
        $sequence = $lastPayroll ? ((int) $lastPayroll->id + 1) : 1;

        return 'PR-' . now()->format('Ymd') . '-' . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

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