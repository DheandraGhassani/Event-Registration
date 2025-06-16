<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Finance extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 3,
            'name' => 'Finance Dummy',
            'email' => 'finance@example.com',
            'password' => Hash::make('password'), // password = "password"
            'role' => 3,
            'is_active' => 1,
        ]);
    }
}
