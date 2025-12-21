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
    public function show(Department $department)
    {
        $documents = $department->documents()->latest()->get();
        return view('public.department_docs', compact('department', 'documents'));
    }

    /**
     * Download a file.
     */
    public function download($filename)
    {
        $path = 'public/documents/' . $filename;
        if (!Storage::exists($path)) abort(404);
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

    public function listDepartments(Division $division)
    {
        $departments = $division->departments()->withCount('documents')->get();
        return view('public.division_departments', compact('division', 'departments'));
    }
}