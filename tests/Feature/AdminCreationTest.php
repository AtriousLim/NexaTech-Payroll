<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->timestamps();
        });
    }

    public function test_admin_can_be_created_from_add_admin_form(): void
    {
        $currentAdmin = Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin@nexatech.ph',
            'password' => bcrypt('password123'),
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($currentAdmin, 'admin')
            ->post('/admin/add-admin', [
                'name' => 'New Admin',
                'email' => 'newadmin@nexatech.ph',
                'password' => 'secret123',
                'role' => 'HR Manager',
            ]);

        $response->assertRedirect('/admin/add-admin');
        $this->assertDatabaseHas('admins', [
            'email' => 'newadmin@nexatech.ph',
            'role' => 'HR Manager',
        ]);
    }
}
