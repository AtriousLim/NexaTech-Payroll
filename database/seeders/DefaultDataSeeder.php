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

        // Ensure default positions exist with sample salaries
        $defaultPositions = [
            ['title' => 'Unassigned', 'salary' => 0.00],
            ['title' => 'Front-End Developer', 'salary' => 25000.00],
            ['title' => 'Back-End Developer', 'salary' => 30000.00],
            ['title' => 'Full-Stack Developer', 'salary' => 35000.00],
            ['title' => 'Mobile Developer', 'salary' => 28000.00],
            ['title' => 'Software Architect', 'salary' => 45000.00],
        ];

        foreach ($defaultPositions as $pos) {
            $exists = DB::table('positions')->where('position_title', $pos['title'])->exists();

            if (! $exists) {
                DB::table('positions')->insert([
                    'department_id' => $deptId,
                    'position_title' => $pos['title'],
                    'basic_salary' => $pos['salary'],
                    'has_bonus' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Also update existing rows to ensure sample salaries are set
        foreach ($defaultPositions as $pos) {
            DB::table('positions')
                ->where('position_title', $pos['title'])
                ->update(['basic_salary' => $pos['salary'], 'updated_at' => now()]);
        }
    }
}
