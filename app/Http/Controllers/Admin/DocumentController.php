<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use App\Models\Department; 

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('department')->get();
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.documents.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'department_id' => 'required|exists:departments,id',
            'document_file' => 'required|file|mimes:pdf|max:10240', // ไฟล์ PDF ไม่เกิน 10MB
        ]);

        // 1. จัดเก็บไฟล์
        $file = $request->file('document_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/documents', $filename); 

        // 2. บันทึกข้อมูลลงฐานข้อมูล
        Document::create([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'filename' => $filename,
        ]);

        return redirect()->route('home')->with('success', 'เพิ่มเอกสารเรียบร้อยแล้ว');
    }

    public function destroy(Document $document)
    {
        // 1. ลบไฟล์ออกจาก storage
        if (Storage::exists('public/documents/' . $document->filename)) {
            Storage::delete('public/documents/' . $document->filename);
        }

        // 2. ลบข้อมูลจากฐานข้อมูล
        $document->delete();

        return redirect()->route('home')->with('success', 'ลบเอกสารเรียบร้อยแล้ว');
    }

    public function edit(Document $document)
    {
        $departments = Department::all();
        return view('admin.documents.edit', compact('document', 'departments'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|max:255',
            'department_id' => 'required|exists:departments,id',
            'document_file' => 'nullable|file|mimes:pdf|max:10240', // File is optional on update
        ]);

        // 1. Update basic info
        $document->title = $request->title;
        $document->department_id = $request->department_id;

        // 2. Handle file upload if present
        if ($request->hasFile('document_file')) {
            // Delete old file
            if (Storage::exists('public/documents/' . $document->filename)) {
                Storage::delete('public/documents/' . $document->filename);
            }

            // Store new file
            $file = $request->file('document_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/documents', $filename);
            
            // Update filename in model
            $document->filename = $filename;
        }

        $document->save();

        return redirect()->route('home')->with('success', 'แก้ไขเอกสารเรียบร้อยแล้ว');
    }
}