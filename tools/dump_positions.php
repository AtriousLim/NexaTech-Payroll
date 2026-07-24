<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$db = app('db');
$rows = $db->table('positions')->select('id','position_title','basic_salary')->get();
foreach ($rows as $r) {
    echo $r->id . "\t" . $r->position_title . "\t" . $r->basic_salary . "\n";
}
