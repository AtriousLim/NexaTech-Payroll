<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultDataSeeder extends Seeder
{
    public function run()
    {
        // Ensure a default department exists
        $dept = DB::table('departments')->where('department_name', 'General')->first();

        if (! $dept) {
            $deptId = DB::table('departments')->insertGetId([
                'department_name' => 'General',
                'default_deduction' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $deptId = $dept->id;
        }

        // Ensure default positions exist
        $defaultPositions = [
            'Unassigned',
            'Front-End Developer',
            'Back-End Developer',
            'Full-Stack Developer',
            'Mobile Developer',
            'Software Architect',
        ];

        foreach ($defaultPositions as $positionTitle) {
            $exists = DB::table('positions')->where('position_title', $positionTitle)->exists();

            if (! $exists) {
                DB::table('positions')->insert([
                    'department_id' => $deptId,
                    'position_title' => $positionTitle,
                    'basic_salary' => 0.00,
                    'has_bonus' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
