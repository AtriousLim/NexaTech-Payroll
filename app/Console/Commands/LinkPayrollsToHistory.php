<?php

namespace App\Console\Commands;

use App\Models\Payroll;
use App\Models\PayrollHistory;
use Illuminate\Console\Command;

class LinkPayrollsToHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payrolls:link-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link existing payroll records to their corresponding payroll_history records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payrolls = Payroll::whereNull('payroll_history_id')->get();
        $linked = 0;
        $errors = 0;

        foreach ($payrolls as $payroll) {
            // Find matching payroll_history by employee_id and cutoff dates
            $history = PayrollHistory::where('employee_id', $payroll->employee_id)
                ->where('cutoff_start', $payroll->pay_period_start)
                ->where('cutoff_end', $payroll->pay_period_end)
                ->first();

            if ($history) {
                $payroll->update(['payroll_history_id' => $history->id]);
                $this->info("Linked Payroll #{$payroll->id} to PayrollHistory #{$history->id}");
                $linked++;
            } else {
                // Try matching without date filters (fallback)
                $history = PayrollHistory::where('employee_id', $payroll->employee_id)->first();
                if ($history) {
                    $payroll->update(['payroll_history_id' => $history->id]);
                    $this->warn("Linked Payroll #{$payroll->id} to PayrollHistory #{$history->id} (fallback - no date match)");
                    $linked++;
                } else {
                    $this->error("Could not find any PayrollHistory for Payroll #{$payroll->id} (employee_id: {$payroll->employee_id})");
                    $errors++;
                }
            }
        }

        $this->info("Done! Linked: {$linked}, Errors: {$errors}");
    }
}

