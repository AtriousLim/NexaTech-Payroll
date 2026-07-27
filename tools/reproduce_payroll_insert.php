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

        echo 'history-created:' . $payrollHistory->id . PHP_EOL;
    });
} catch (Throwable $e) {
    echo 'history-error:' . $e->getMessage() . PHP_EOL;
}
