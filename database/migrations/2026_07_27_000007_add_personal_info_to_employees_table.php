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
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('gmail');
            }

            if (!Schema::hasColumn('employees', 'civil_status')) {
                $table->enum('civil_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->after('gender');
            }

            if (!Schema::hasColumn('employees', 'nationality')) {
                $table->string('nationality')->nullable()->after('civil_status');
            }

            if (!Schema::hasColumn('employees', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('nationality');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $columns = ['gender', 'civil_status', 'nationality', 'date_of_birth'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('employees', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
