<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Support\Facades\Auth; // <--- 1. ต้องเพิ่มบรรทัดนี้ครับ

class DivisionController extends Controller
{
    public function store(Request $request) {
        // 2. ดักสิทธิ์: ถ้าไม่ใช่ Super Admin ห้ามเพิ่ม!
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'คุณไม่มีสิทธิ์ทำรายการนี้');
        }

        // (แถม) ตรวจสอบว่าใส่ชื่อมาจริงไหม กัน Error
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Division::create($request->all());
        return back()->with('success', 'เพิ่มฝ่ายงานเรียบร้อย');
    }

    public function destroy(Division $division) {
        // 3. ดักสิทธิ์: ถ้าไม่ใช่ Super Admin ห้ามลบ!
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'คุณไม่มีสิทธิ์ลบข้อมูลนี้');
        }

        $division->delete();
        return back()->with('success', 'ลบฝ่ายงานเรียบร้อย');
    }
}