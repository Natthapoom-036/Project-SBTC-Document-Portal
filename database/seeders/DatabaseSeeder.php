<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Division;
use App\Models\Department;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. สร้าง Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@tech.ac.th', // อีเมลสำหรับ Login
            'password' => Hash::make('12345678'), // รหัสผ่าน
            'role' => 'super_admin', // กำหนดเป็น Super Admin
            'department_id' => null, // ไม่สังกัดฝ่าย
        ]);

        // 2. สร้างข้อมูลฝ่ายงานหลัก (ตัวอย่าง)
        $divisions = [
            ['name' => 'ฝ่ายบริหารทรัพยากร', 'icon_class' => 'fas fa-building'],
            ['name' => 'ฝ่ายวิชาการ', 'icon_class' => 'fas fa-book'],
            ['name' => 'ฝ่ายพัฒนากิจการนักเรียนฯ', 'icon_class' => 'fas fa-users'],
            ['name' => 'ฝ่ายแผนงานและความร่วมมือ', 'icon_class' => 'fas fa-handshake'],
        ];

        foreach ($divisions as $div) {
            Division::create($div);
        }

        // 3. สร้างตัวอย่างแผนก (เพื่อให้มีข้อมูลทดสอบ)
        $academic = Division::where('name', 'ฝ่ายวิชาการ')->first();
        if ($academic) {
            Department::create([
                'name' => 'งานพัฒนาหลักสูตร',
                'division_id' => $academic->id
            ]);
        }
    }
}