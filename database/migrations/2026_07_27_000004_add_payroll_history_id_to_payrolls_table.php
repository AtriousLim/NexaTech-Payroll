<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            if (! Schema::hasColumn('payrolls', 'payroll_history_id')) {
                $table->unsignedBigInteger('payroll_history_id')->nullable()->after('id');
                $table->foreign('payroll_history_id')
                      ->references('id')
                      ->on('payroll_history')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            if (Schema::hasColumn('payrolls', 'payroll_history_id')) {
                $table->dropForeign(['payroll_history_id']);
                $table->dropColumn('payroll_history_id');
            }
        });
    }
};

