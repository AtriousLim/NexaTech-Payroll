<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_items', function (Blueprint $table) {
            // Drop the old constraint that points to 'payrolls'
            $table->dropForeign('payroll_items_ibfk_1');

            // Add the new constraint pointing to 'payroll_history'
            $table->foreign('payroll_id')
                  ->references('id')
                  ->on('payroll_history')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_items', function (Blueprint $table) {
            $table->dropForeign(['payroll_id']);
            $table->foreign('payroll_id')
                  ->references('id')
                  ->on('payrolls')
                  ->onDelete('cascade');
        });
    }
};