<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove credentials because employees do not log in to this application.
     */
    public function up(): void
    {
        $columns = array_filter(['username', 'password'], fn ($column) => Schema::hasColumn('employees', $column));

        if ($columns) {
            Schema::table('employees', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }

    /**
     * Restore the columns if this migration is rolled back.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'username')) {
                $table->string('username')->nullable();
            }

            if (!Schema::hasColumn('employees', 'password')) {
                $table->string('password')->nullable();
            }
        });
    }
};
