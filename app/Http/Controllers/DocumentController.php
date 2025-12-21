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
        // 1. ดึงข้อมูลเอกสาร
        $documents = Document::with('department')->latest()->get();
        
        // 2. ดึงข้อมูลหน่วยงาน
        $departments = Department::with('division')->get();
        
        // 3. ดึงข้อมูลฝ่ายงาน (ตัวที่ Error คือตัวนี้หายไป!)
        $divisions = Division::all(); 
        
        // 4. ดึงข้อมูลผู้ใช้ (เผื่อหน้า Admin ต้องใช้)
        $users = User::all();

        // 5. ส่งตัวแปรทั้งหมดไปที่หน้า View (อย่าลืมใส่ชื่อตัวแปรใน compact)
        return view('admin.unified_admin_page', compact('documents', 'departments', 'divisions', 'users'));
    }

    /**
     * Show documents for a specific department.
     */
    public function show(Department $department)
    {
        // Load documents for this department
        $documents = $department->documents()->latest()->get();
        return view('public.department_docs', compact('department', 'documents'));
    }

    /**
     * Download a file.
     */
    public function download($filename)
    {
        $path = 'public/documents/' . $filename;

        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (!Storage::exists($path)) {
            abort(404);
        }

        // คืนค่าไฟล์ให้เบราว์เซอร์ดาวน์โหลด
        return Storage::download($path, $filename);
    }

    /**
     * View a file inline.
     */
    public function viewFile($filename)
    {
        $path = 'public/documents/' . $filename;

        if (!Storage::exists($path)) {
            abort(404);
        }

        return Storage::response($path, $filename);
    }

    public function listDepartments(Division $division)
    {
        // ดึงหน่วยงานที่สังกัดฝ่ายนี้ พร้อมนับจำนวนเอกสาร
        $departments = $division->departments()->withCount('documents')->get();

        // ส่งไปหน้า View ใหม่ (ที่คุณกำลังจะสร้างในขั้นตอนต่อไป)
        return view('public.division_departments', compact('division', 'departments'));
    }
}