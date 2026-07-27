<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = ['payroll_history', 'payrolls', 'payroll_items', 'activity_logs'];
foreach ($tables as $table) {
    echo "Table: $table\n";
    echo Schema::hasTable($table) ? 'exists' : 'missing';
    echo PHP_EOL;
    if (Schema::hasTable($table)) {
        $columns = Schema::getColumnListing($table);
        echo implode(', ', $columns) . PHP_EOL;
    }
    echo PHP_EOL;
}
