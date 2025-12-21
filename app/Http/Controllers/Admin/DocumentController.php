<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Department;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:20480', // 20MB Limit
            // ถ้าเป็น User ธรรมดา เราจะบังคับใส่ department_id เอง
        ]);

        $user = Auth::user();
        $target_department_id = $request->department_id;

        // --- Logic: ถ้าไม่ใช่ Super Admin บังคับลงแผนกตัวเอง ---
        if ($user->role !== 'super_admin') {
            // ถ้า User ไม่มีสังกัด (Error Case)
            if (is_null($user->department_id)) {
                return redirect()->back()->with('error', 'บัญชีของคุณไม่มีสังกัด ไม่สามารถอัปโหลดได้');
            }
            // บังคับเปลี่ยนเป้าหมายเป็นแผนกตัวเอง
            $target_department_id = $user->department_id;
        } else {
            // ถ้าเป็น Super Admin แต่ลืมเลือกแผนก
            if (empty($target_department_id)) {
                return redirect()->back()->with('error', 'กรุณาเลือกหน่วยงานที่จะอัปโหลด');
            }
        }
        // ---------------------------------------------------

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // เก็บไฟล์
            $file->storeAs('public/documents', $filename);

            // บันทึกลง Database
            Document::create([
                'title' => $request->title,
                'filename' => $filename,
                'department_id' => $target_department_id,
                'user_id' => $user->id,
            ]);

            return redirect()->back()->with('success', 'อัปโหลดเอกสารสำเร็จ');
        }

        return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการอัปโหลด');
    }

    public function destroy(Document $document)
    {
        $user = Auth::user();

        // ป้องกัน User ลบไฟล์ข้ามแผนก
        if ($user->role !== 'super_admin') {
            if ($user->department_id !== $document->department_id) {
                abort(403, 'คุณไม่มีสิทธิ์ลบเอกสารนี้');
            }
        }

        if (Storage::exists('public/documents/' . $document->filename)) {
            Storage::delete('public/documents/' . $document->filename);
        }

        $document->delete();

        return redirect()->back()->with('success', 'ลบเอกสารเรียบร้อย');
    }

    public function edit(Document $document)
{
    // เช็คสิทธิ์: ต้องเป็น Super Admin หรือ เจ้าของไฟล์
    if (Auth::user()->role !== 'super_admin' && Auth::id() !== $document->user_id) {
        abort(403, 'คุณไม่มีสิทธิ์แก้ไขเอกสารนี้');
    }

    $departments = \App\Models\Department::with('division')->get();
    return view('admin.documents.edit', compact('document', 'departments'));
}

public function update(Request $request, Document $document)
{
    // เช็คสิทธิ์
    if (Auth::user()->role !== 'super_admin' && Auth::id() !== $document->user_id) {
        abort(403);
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'file' => 'nullable|file|max:20480', // ไฟล์เป็น nullable (ไม่บังคับเปลี่ยน)
    ]);

    // อัปเดตข้อมูลทั่วไป
    $document->title = $request->title;
    
    // ถ้า Super Admin เปลี่ยนแผนก
    if (Auth::user()->role === 'super_admin' && $request->has('department_id')) {
        $document->department_id = $request->department_id;
    }

    // ถ้ามีการอัปโหลดไฟล์ใหม่
    if ($request->hasFile('file')) {
        // 1. ลบไฟล์เก่า
        if (Storage::exists('public/documents/' . $document->filename)) {
            Storage::delete('public/documents/' . $document->filename);
        }
        
        // 2. ลงไฟล์ใหม่
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/documents', $filename);
        
        $document->filename = $filename;
        // รีเซ็ตยอดโหลดไหม? แล้วแต่ชอบ (ปกติไม่รีเซ็ต)
    }

    $document->save();

    return redirect()->route('dashboard')->with('success', 'แก้ไขเอกสารเรียบร้อยแล้ว');
}
}