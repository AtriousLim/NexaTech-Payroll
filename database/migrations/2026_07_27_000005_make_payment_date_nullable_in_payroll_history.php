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
        Schema::table('payroll_history', function (Blueprint $table) {
            if (Schema::hasColumn('payroll_history', 'payment_date')) {
                $table->timestamp('payment_date')->nullable()->default(null)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_history', function (Blueprint $table) {
            if (Schema::hasColumn('payroll_history', 'payment_date')) {
                $table->timestamp('payment_date')->nullable(false)->default(now())->change();
            }
        });
    }
};

