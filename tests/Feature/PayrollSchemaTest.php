<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PayrollSchemaTest extends TestCase
{
    public function test_payroll_items_migration_adds_reference_id_column(): void
    {
        $this->artisan('migrate', [
            '--path' => 'database/migrations/2026_07_27_000001_extend_payroll_support_tables.php',
            '--force' => true,
        ])->assertExitCode(0);

        $this->assertTrue(Schema::hasTable('payroll_items'));
        $this->assertTrue(Schema::hasColumn('payroll_items', 'reference_id'));
    }
}
