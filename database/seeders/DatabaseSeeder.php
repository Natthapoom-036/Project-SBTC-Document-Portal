<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. สร้างฝ่ายงาน 4 ฝ่าย (พร้อมไอคอน)
        DB::table('divisions')->insert([
            [
                'name' => 'ฝ่ายบริหารทรัพยากร',
                'description' => 'งานบริหารทั่วไป, บุคลากร, การเงิน, พัสดุ, อาคารสถานที่',
                'icon_class' => 'fas fa-book',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'ฝ่ายแผนและความร่วมมือ',
                'description' => 'งานวางแผน, งบประมาณ, ศูนย์ข้อมูล, ความร่วมมือ',
                'icon_class' => 'fas fa-clipboard',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'ฝ่ายพัฒนากิจการนักเรียนนักศึกษา',
                'description' => 'งานกิจกรรม, ครูที่ปรึกษา, ปกครอง, แนะแนว',
                'icon_class' => 'fas fa-users',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'ฝ่ายวิชาการ',
                'description' => 'งานหลักสูตร, วัดผล, ทะเบียน, วิทยบริการ',
                'icon_class' => 'fas fa-graduation-cap',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);

        // 2. สร้าง Super Admin (เผื่อยังไม่มี)
        // เช็คก่อนว่ามี user นี้หรือยัง ถ้ายังไม่มีค่อยสร้าง
        if (DB::table('users')->where('email', 'admin@tech.ac.th')->doesntExist()) {
            DB::table('users')->insert([
                'name' => 'Super Admin',
                'email' => 'admin@tech.ac.th',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}