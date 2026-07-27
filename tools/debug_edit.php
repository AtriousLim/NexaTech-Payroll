<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PayrollHistory;
use App\Models\Payroll;
use App\Models\PayrollItem;
use Illuminate\Support\Facades\DB;

$payrollId = intval($argv[1] ?? 60);
echo "Testing update for PayrollHistory ID: $payrollId\n";

$payroll = PayrollHistory::with('employee')->find($payrollId);
if (!$payroll) {
    echo "PayrollHistory not found!\n";
    exit(1);
}

echo "PayrollHistory: ID={$payroll->id} EmpID={$payroll->employee_id} Status={$payroll->status}\n";
echo "Gross: {$payroll->gross_pay} Net: {$payroll->net_pay}\n";

// Test the relationship
echo "\n--- Testing payrollEntry relationship ---\n";
$entry = $payroll->payrollEntry;
if ($entry) {
    echo "Found Payroll entry: ID={$entry->id} HistID=" . ($entry->payroll_history_id ?? 'NULL') . "\n";
    echo "GrossSalary: {$entry->gross_salary} NetSalary: {$entry->net_salary}\n";
} else {
    echo "NO Payroll entry found via relationship!\n";
    
    // Try to find it manually
    echo "Trying manual lookup...\n";
    $manual = Payroll::where('employee_id', $payroll->employee_id)
        ->where('pay_period_start', $payroll->cutoff_start)
        ->where('pay_period_end', $payroll->cutoff_end)
        ->first();
    if ($manual) {
        echo "Found manually: ID={$manual->id} HistID=" . ($manual->payroll_history_id ?? 'NULL') . "\n";
    } else {
        echo "NOT found manually either!\n";
    }
}

// Test items
echo "\n--- Testing items ---\n";
$items = $payroll->items;
echo "Item count: " . $items->count() . "\n";
foreach ($items as $item) {
    echo "  Item ID:{$item->id} Type:{$item->item_type} Name:{$item->item_name} Amount:{$item->amount}\n";
}

// Test update
echo "\n--- Testing direct update ---\n";
try {
    DB::transaction(function () use ($payroll) {
        $payroll->update([
            'gross_pay' => 50000.00,
            'net_pay' => 47000.00,
        ]);
        echo "PayrollHistory updated successfully\n";
        
        $entry = $payroll->payrollEntry;
        if ($entry) {
            $entry->update([
                'gross_salary' => 50000.00,
                'net_salary' => 47000.00,
            ]);
            echo "Payroll entry updated successfully\n";
        }
        
        $payroll->items()->delete();
        echo "Items deleted successfully\n";
    });
    echo "Transaction completed successfully!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

