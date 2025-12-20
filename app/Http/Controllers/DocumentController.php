<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Department;
use App\Models\Division;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class DocumentController extends Controller
{
    /**
     * Unified index - shows admin or public view based on authentication.
     */
    public function index()
    {
        if (Auth::check()) {
            // User is logged in - Load Admin Data
            $documents = Document::with('department')->latest()->get();
            $departments = Department::withCount('documents')->get();
            
            return view('admin.unified_admin_page', compact('documents', 'departments'));
        } else {
            // User is not logged in - Load Public Data
            $divisions = Division::all();
            
            return view('public.unified_home', compact('divisions'));
        }
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