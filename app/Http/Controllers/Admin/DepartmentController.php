<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- 1. ต้องเพิ่มบรรทัดนี้ครับ

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::withCount('documents')->get();
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 2. ป้องกันคนรู้ Link แอบเข้าหน้าสร้าง
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 3. ป้องกันการยิง API มาสร้าง
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'คุณไม่มีสิทธิ์เพิ่มหน่วยงาน');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'division_id' => 'required|exists:divisions,id', // ควรเช็คว่าเลือกฝ่ายงานหรือยัง
        ]);

        Department::create($request->all());

        // เปลี่ยนเป็น back() เพื่อให้ยังคงอยู่ในหน้า Admin
        return back()->with('success', 'เพิ่มหน่วยงานเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // ไม่ได้ใช้งานใน Admin Panel
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขข้อมูลนี้');
        }
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        // 4. ป้องกันการแก้ไข
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขข้อมูลนี้');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            // division_id อาจจะไม่ต้อง validate ถ้าไม่ได้ให้แก้
        ]);

        $department->update($request->all());

        return back()->with('success', 'อัปเดตข้อมูลหน่วยงานเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        // 5. ป้องกันการลบ
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'คุณไม่มีสิทธิ์ลบข้อมูลนี้');
        }

        $department->delete();

        return back()->with('success', 'ลบหน่วยงานเรียบร้อยแล้ว');
    }
}