<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), 
            'role_id' => DB::table('roles')->where('name', 'admin')->first()->id, // Ambil role admin dari tabel roles
            'email_verified_at' => now(),
        ]);
    }
}