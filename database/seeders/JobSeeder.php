<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'designer',
                'min_salary' => 1000,
                'max_salary' => 5000
            ],
            [
                'title' => 'account manager',
                'min_salary' => 1000,
                'max_salary' => 8000
            ]
        ];

        // Insert users into the database
        DB::table('jobs')->insert($jobs);
    }
}
