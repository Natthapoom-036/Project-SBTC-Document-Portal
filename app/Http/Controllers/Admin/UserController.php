<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
{
    // 1. ตรวจสอบข้อมูล (เอา confirmed ออก)
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8', // <--- แก้ตรงนี้ (ลบ confirmed ทิ้ง)
    ]);

    // 2. กำหนด Role
    // ถ้ามีการเลือก department_id มา -> เป็น user
    // ถ้าไม่มี (หรือเลือก Super Admin) -> เป็น super_admin
    $role = empty($request->department_id) ? 'super_admin' : 'user';
    $dept_id = empty($request->department_id) ? null : $request->department_id;

    // 3. สร้าง User
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'department_id' => $dept_id,
        'role' => $role,
    ]);

    return redirect()->back()->with('success', 'เพิ่มผู้ใช้งานเรียบร้อยแล้ว');
}
public function destroy(User $user) {
    $user->delete();
    return back()->with('success', 'ลบ User เรียบร้อย');
}
}
