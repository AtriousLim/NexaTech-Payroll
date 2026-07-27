<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PayrollHistory;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

echo "=== FIXING EXISTING PAYROLL DATA ===\n\n";

// Step 1: Check payrolls table columns
echo "Payrolls table columns:\n";
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('payrolls');
echo implode(', ', $columns) . "\n\n";

// Step 2: Recalculate and fix existing payroll_history records
echo "Fixing payroll_history records...\n";
$histories = PayrollHistory::with('employee.position')->get();
$fixedCount = 0;

foreach ($histories as $h) {
    if (!$h->employee || !$h->employee->position) {
        echo "  SKIP ID:{$h->id} - Missing employee or position\n";
        continue;
    }

    $monthlySalary = (float) $h->employee->position->basic_salary;
    $attendance = Attendance::where('employee_id', $h->employee_id)->get();
    $lateMinutes = $attendance->sum('late_minutes');
    $overtimeMinutes = $attendance->sum('overtime_minutes');

    $dailyRate = $monthlySalary / 22;
    $hourlyRate = $dailyRate / 8;
    $minuteRate = $hourlyRate / 60;

    // New: Gross pay = full monthly salary
    $grossPay = $monthlySalary;
    $lateDeduction = $minuteRate * $lateMinutes;
    $overtimePay = ($overtimeMinutes / 60) * $hourlyRate * 1.25;

    // Get items already saved
    $items = $h->items;
    $bonusTotal = $items->where('item_type', 'Bonus')->sum('amount');
    $incentiveTotal = $items->where('item_type', 'Incentive')->sum('amount');
    $departmentDeduction = $items->where('item_type', 'Deduction')->sum('amount');

    $sss = 675.00;
    $philhealth = 400.00;
    $pagibig = 100.00;

    $totalDeductions = $lateDeduction + $departmentDeduction + $sss + $philhealth + $pagibig;
    $netPay = $grossPay + $bonusTotal + $incentiveTotal + $overtimePay - $totalDeductions;

    $oldGross = $h->gross_pay;
    $oldNet = $h->net_pay;

    $h->update([
        'gross_pay' => round($grossPay, 2),
        'sss_deduction' => round($sss, 2),
        'philhealth_deduction' => round($philhealth, 2),
        'pagibig_deduction' => round($pagibig, 2),
        'late_deductions' => round($lateDeduction, 2),
        'net_pay' => round($netPay, 2),
    ]);

    echo "  FIXED ID:{$h->id} Emp:{$h->employee_id} Gross: {$oldGross} -> {$grossPay} Net: {$oldNet} -> {$netPay}\n";
    $fixedCount++;
}

echo "\nFixed {$fixedCount} payroll_history records.\n";

// Step 3: Fix payrolls table entry for each history
echo "\nFixing payrolls table entries...\n";
$fixedPayrollCount = 0;

$histories = PayrollHistory::with('employee.position')->get();
foreach ($histories as $h) {
    $entry = $h->payrollEntry;
    if (!$entry) {
        echo "  SKIP HistID:{$h->id} - No payroll entry found\n";
        continue;
    }

    $items = $h->items;
    $bonusTotal = $items->where('item_type', 'Bonus')->sum('amount');
    $incentiveTotal = $items->where('item_type', 'Incentive')->sum('amount');
    $departmentDeduction = $items->where('item_type', 'Deduction')->sum('amount');

    $entry->update([
        'basic_salary' => (float) $h->employee->position->basic_salary,
        'total_bonus' => round($bonusTotal, 2),
        'total_incentive' => round($incentiveTotal, 2),
        'total_deduction' => round($departmentDeduction, 2),
        'overtime_pay' => round($h->payrollEntry?->overtime_pay ?? 0, 2),
        'late_deduction' => round($h->late_deductions, 2),
        'gross_salary' => round($h->gross_pay, 2),
        'net_salary' => round($h->net_pay, 2),
    ]);

    echo "  FIXED PayrollID:{$entry->id} for HistID:{$h->id}\n";
    $fixedPayrollCount++;
}

echo "\nFixed {$fixedPayrollCount} payroll entries.\n";

echo "\n=== VERIFICATION ===\n";
$test = PayrollHistory::with('payrollEntry')->where('id', 60)->first();
echo "Sample - ID:{$test->id} Gross:{$test->gross_pay} Net:{$test->net_pay}\n";
if ($test->payrollEntry) {
    $e = $test->payrollEntry;
    echo "Payroll Entry - ID:{$e->id} GrossSalary:{$e->gross_salary} NetSalary:{$e->net_salary}\n";
}

