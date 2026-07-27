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
        Schema::table('employees', function (Blueprint $table) {
            // First, update all null hire_date values to current timestamp
            DB::statement('UPDATE employees SET hire_date = NOW() WHERE hire_date IS NULL');
            
            // Change the column to NOT NULL with a default value
            $table->date('hire_date')->nullable(false)->default(DB::raw('CURRENT_DATE'))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('hire_date')->nullable()->change();
        });
    }
};
