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
            if (! Schema::hasColumn('payrolls', 'payroll_number')) {
                $table->string('payroll_number')->nullable()->after('payroll_history_id');
            }

            if (! Schema::hasColumn('payrolls', 'pay_period_start')) {
                $table->date('pay_period_start')->nullable()->after('basic_salary');
            }

            if (! Schema::hasColumn('payrolls', 'pay_period_end')) {
                $table->date('pay_period_end')->nullable()->after('pay_period_start');
            }

            if (! Schema::hasColumn('payrolls', 'total_bonus')) {
                $table->decimal('total_bonus', 10, 2)->default(0)->after('pay_period_end');
            }

            if (! Schema::hasColumn('payrolls', 'total_incentive')) {
                $table->decimal('total_incentive', 10, 2)->default(0)->after('total_bonus');
            }

            if (! Schema::hasColumn('payrolls', 'total_deduction')) {
                $table->decimal('total_deduction', 10, 2)->default(0)->after('total_incentive');
            }

            if (! Schema::hasColumn('payrolls', 'overtime_pay')) {
                $table->decimal('overtime_pay', 10, 2)->default(0)->after('total_deduction');
            }

            if (! Schema::hasColumn('payrolls', 'late_deduction')) {
                $table->decimal('late_deduction', 10, 2)->default(0)->after('overtime_pay');
            }

            if (! Schema::hasColumn('payrolls', 'gross_salary')) {
                $table->decimal('gross_salary', 10, 2)->default(0)->after('late_deduction');
            }

            if (! Schema::hasColumn('payrolls', 'net_salary')) {
                $table->decimal('net_salary', 10, 2)->default(0)->after('gross_salary');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $columns = [
                'payroll_number',
                'pay_period_start',
                'pay_period_end',
                'total_bonus',
                'total_incentive',
                'total_deduction',
                'overtime_pay',
                'late_deduction',
                'gross_salary',
                'net_salary',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('payrolls', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

