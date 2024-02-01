<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'hr',
                'email' => 'hr@hr.com',
                'password' => Hash::make('123456')
            ],
            [
                'name' => 'admin',
                'email' => 'admin@hr.com',
                'password' => Hash::make('admin')
            ]
        ];

        // Insert users into the database
        DB::table('users')->insert($users);
    }
}
