<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$admin = App\Models\Admin::where('email', 'admin@nexatech.ph')->first();

if (!$admin) {
    echo "admin-missing\n";
    exit(1);
}

echo 'admin-found' . PHP_EOL;
echo (Illuminate\Support\Facades\Hash::check('admin123', $admin->password) ? 'password-ok' : 'password-failed') . PHP_EOL;
