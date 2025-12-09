<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Department;
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
            $departments = Department::withCount('documents')->get();
            
            return view('public.unified_home', compact('departments'));
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
}