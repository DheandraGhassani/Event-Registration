<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Committee extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 4,
            'name' => 'Committee Dummy',
            'email' => 'committee@example.com',
            'password' => Hash::make('password'), // password = "password"
            'role' => 4,
            'is_active' => 1,
        ]);
    }
}
