<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to drop and recreate foreign key with CASCADE DELETE
        DB::statement('ALTER TABLE `payrolls` DROP FOREIGN KEY `payrolls_ibfk_1`');
        
        DB::statement('ALTER TABLE `payrolls` ADD CONSTRAINT `payrolls_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE');

        // Also fix payroll_history if needed
        try {
            DB::statement('ALTER TABLE `payroll_history` DROP FOREIGN KEY `payroll_history_ibfk_1`');
            DB::statement('ALTER TABLE `payroll_history` ADD CONSTRAINT `payroll_history_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // payroll_history constraint might not exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NO ACTION
        try {
            DB::statement('ALTER TABLE `payrolls` DROP FOREIGN KEY `payrolls_ibfk_1`');
            DB::statement('ALTER TABLE `payrolls` ADD CONSTRAINT `payrolls_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE NO ACTION');
        } catch (\Exception $e) {
            //
        }
    }
};
