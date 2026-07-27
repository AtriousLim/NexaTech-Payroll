<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cutoffStart = now()->startOfMonth()->toDateString();
$cutoffEnd = now()->endOfMonth()->toDateString();

App\Models\PayrollItem::whereHas('payrollHistory', function ($q) use ($cutoffStart, $cutoffEnd) {
    $q->where('cutoff_start', $cutoffStart)
      ->where('cutoff_end', $cutoffEnd);
})->delete();

App\Models\Payroll::where('pay_period_start', $cutoffStart)
    ->where('pay_period_end', $cutoffEnd)
    ->delete();

App\Models\PayrollHistory::where('cutoff_start', $cutoffStart)
    ->where('cutoff_end', $cutoffEnd)
    ->delete();

echo 'cleared' . PHP_EOL;
