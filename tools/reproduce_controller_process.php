<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$employee = App\Models\Employee::first();
if (!$employee) {
    echo "no-employee\n";
    exit(0);
}

Illuminate\Support\Facades\Auth::guard('admin')->loginUsingId(1);

$request = new Illuminate\Http\Request();
$request->setMethod('POST');
$request->request->add([
    'bonuses' => [],
    'incentives' => [],
    'deductions' => [],
]);

$controller = new App\Http\Controllers\Admin\PayrollController();

try {
    $response = $controller->process($request, $employee);
    echo get_class($response) . PHP_EOL;
    echo $response->getTargetUrl() . PHP_EOL;
    echo $response->getSession()->get('success') ?: $response->getSession()->get('warning') ?: '' . PHP_EOL;
} catch (Throwable $e) {
    echo 'controller-error:' . $e->getMessage() . PHP_EOL;
}
