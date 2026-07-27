<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$historyCount = App\Models\PayrollHistory::count();
$payrollCount = App\Models\Payroll::count();
$latest = App\Models\PayrollHistory::latest('id')->first();

echo 'payroll_history=' . $historyCount . PHP_EOL;
echo 'payrolls=' . $payrollCount . PHP_EOL;

if ($latest) {
    echo 'latest-id=' . $latest->id . ' employee=' . $latest->employee_id . ' net=' . $latest->net_pay . PHP_EOL;
}
