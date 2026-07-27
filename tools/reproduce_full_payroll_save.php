<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$employee = App\Models\Employee::first();

if (!$employee) {
    echo "no-employee\n";
    exit(0);
}

$cutoffStart = now()->startOfMonth()->toDateString();
$cutoffEnd = now()->endOfMonth()->toDateString();

try {
    Illuminate\Support\Facades\DB::transaction(function () use ($employee, $cutoffStart, $cutoffEnd) {
        $payrollHistory = App\Models\PayrollHistory::create([
            'employee_id' => $employee->id,
            'processed_by' => 1,
            'cutoff_start' => $cutoffStart,
            'cutoff_end' => $cutoffEnd,
            'gross_pay' => 100,
            'sss_deduction' => 10,
            'philhealth_deduction' => 10,
            'pagibig_deduction' => 10,
            'late_deductions' => 5,
            'net_pay' => 65,
            'status' => 'Pending',
        ]);

        $payrollEntry = App\Models\Payroll::create([
            'employee_id' => $employee->id,
            'department' => $employee->department?->department_name ?? 'N/A',
            'position' => $employee->position?->position_title ?? 'N/A',
            'basic_salary' => 10000,
            'payroll_number' => 'PR-TEST-001',
            'pay_period_start' => $cutoffStart,
            'pay_period_end' => $cutoffEnd,
            'total_bonus' => 0,
            'total_incentive' => 0,
            'total_deduction' => 0,
            'overtime_pay' => 0,
            'late_deduction' => 5,
            'gross_salary' => 100,
            'net_salary' => 65,
            'processed_by' => 1,
            'status' => 'Pending',
        ]);

        echo 'payroll-created:' . $payrollEntry->id . PHP_EOL;
    });
} catch (Throwable $e) {
    echo 'full-error:' . $e->getMessage() . PHP_EOL;
}
