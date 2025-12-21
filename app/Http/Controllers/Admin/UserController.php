<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // <--- 1. ห้ามลืมบรรทัดนี้ครับ!

class UserController extends Controller
{
    public function store(Request $request)
    {
        // 2. ดักสิทธิ์: ถ้าไม่ใช่ Super Admin ห้ามสร้าง User!
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'คุณไม่มีสิทธิ์เพิ่มผู้ใช้งาน');
        }

        // 3. ตรวจสอบข้อมูล (เอา confirmed ออกตามที่ขอ เพื่อให้สร้างได้ผ่านฉลุย)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', 
        ]);

        // 4. กำหนด Role อัตโนมัติ
        // ถ้าเลือกแผนก -> เป็น User ธรรมดา
        // ถ้าไม่เลือกแผนก (ค่าว่าง) -> เป็น Super Admin
        $role = empty($request->department_id) ? 'super_admin' : 'user';
        $dept_id = empty($request->department_id) ? null : $request->department_id;

        // 5. บันทึกลงฐานข้อมูล
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $dept_id,
            'role' => $role,
        ]);

        return back()->with('success', 'เพิ่มผู้ใช้งานเรียบร้อยแล้ว');
    }

    public function destroy(User $user) 
    {
        // 6. ดักสิทธิ์: ถ้าไม่ใช่ Super Admin ห้ามลบ!
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'คุณไม่มีสิทธิ์ลบผู้ใช้งาน');
        }

        $user->delete();
        return back()->with('success', 'ลบ User เรียบร้อย');
    }
}