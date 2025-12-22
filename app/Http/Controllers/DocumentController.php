<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Department;
use App\Models\Division;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Unified index - shows admin or public view based on authentication.
     */
    public function index()
{
    $user = auth()->user();

    // เช็คว่า role คือ 'super_admin' (ไม่ใช่เลข 1)
    if ($user->role === 'super_admin' || is_null($user->department_id)) {
        // Super Admin เห็นทุกอย่าง
        $documents = Document::with('department')->latest()->get();
        $departments = Department::with('division')->get();
    } else {
        // User ธรรมดา เห็นแค่ของตัวเอง
        $documents = Document::where('department_id', $user->department_id)
                             ->with('department')
                             ->latest()
                             ->get();
        
        $departments = Department::where('id', $user->department_id)->get();
    }

    $divisions = Division::all();
    $users = User::with('department')->get();

    return view('admin.unified_admin_page', compact('documents', 'departments', 'divisions', 'users'));
}

    /**
     * Show documents for a specific department.
     */
    // 2. เมื่อกดเลือก "หน่วยงาน" -> ให้แสดง "ตารางเอกสาร" (แบบสวยๆ)
public function show(Department $department)
{
    // ดึงเอกสารในหน่วยงานนี้
    $documents = Document::where('department_id', $department->id)
        ->with('user', 'department')
        ->latest()
        ->get();

    $pageTitle = "เอกสาร: " . $department->name;

    // ส่งไปหน้า documents_list (ที่เป็นตารางสวยๆ ที่คุณมีอยู่แล้ว)
    return view('public.documents_list', compact('documents', 'pageTitle'));
}

    /**
     * Download a file.
     */
    public function download($filename)
{
    // 1. หาเอกสารใน Database
    $document = Document::where('filename', $filename)->first();
    $path = 'public/documents/' . $filename;

    if (!Storage::exists($path)) {
        abort(404);
    }

    // 2. --- เพิ่มตรงนี้: สั่งบวกยอดดาวน์โหลดทีละ 1 ---
    if ($document) {
        $document->increment('download_count');
    }
    // ---------------------------------------------

    return Storage::download($path, $filename);
}

    /**
     * View a file inline.
     */
    public function viewFile($filename)
    {
        $path = 'public/documents/' . $filename;
        if (!Storage::exists($path)) abort(404);
        return Storage::response($path, $filename);
    }

    // 🔥 แก้ฟังก์ชันนี้: กดเลือกฝ่าย (Division) แล้วไปหน้าตารางสวยๆ
    // 1. เมื่อกดเลือก "ฝ่าย" -> ให้แสดงรายชื่อ "หน่วยงาน"
public function listDepartments(Division $division)
{
    // ดึงหน่วยงานทั้งหมดของฝ่ายนี้ พร้อมนับจำนวนเอกสาร
    $departments = $division->departments()->withCount('documents')->get();

    // ส่งไปหน้า departments_list (ที่เราเพิ่งสร้างในข้อ 1)
    return view('public.departments_list', compact('division', 'departments'));
}
}