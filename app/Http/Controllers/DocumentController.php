<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Department;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Show list of all departments.
     */
    public function index()
    {
        // Fetch departments with document count
        $departments = Department::withCount('documents')->get();
        return view('public.index', compact('departments'));
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