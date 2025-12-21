<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // สร้าง Super Admin 1 คน
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'admin@tech.ac.th', // ใช้เมลนี้ Login
            'password' => Hash::make('12345678'), // รหัสผ่าน
            'role' => 'admin', // กำหนดสิทธิ์เป็น admin
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}