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
        ]);

        $user = Auth::user();
        $target_department_id = $request->department_id;

        // --- Logic: ถ้าไม่ใช่ Super Admin บังคับลงแผนกตัวเอง ---
        if ($user->role !== 'super_admin') {
            if (is_null($user->department_id)) {
                return redirect()->back()->with('error', 'บัญชีของคุณไม่มีสังกัด ไม่สามารถอัปโหลดได้');
            }
            $target_department_id = $user->department_id;
        } else {
            if (empty($target_department_id)) {
                return redirect()->back()->with('error', 'กรุณาเลือกหน่วยงานที่จะอัปโหลด');
            }
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $file->storeAs('public/documents', $filename);

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
        $user = Auth::user();

        // เช็คสิทธิ์: ยอมให้ผ่านถ้าเป็น Super Admin หรือ อยู่แผนกเดียวกัน
        if ($user->role !== 'super_admin' && $user->department_id !== $document->department_id) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขเอกสารข้ามหน่วยงาน');
        }

        $departments = \App\Models\Department::with('division')->get();
        return view('admin.documents.edit', compact('document', 'departments'));
    }

    // --- ฟังก์ชันที่แก้ไขให้แล้วครับ ---
    public function update(Request $request, Document $document)
    {
        $user = Auth::user();

        // เช็คสิทธิ์: ยอมให้ผ่านถ้าเป็น Super Admin หรือ อยู่แผนกเดียวกัน
        // (แก้จากเดิมที่เช็ค user_id เป็นเช็ค department_id แทน)
        if ($user->role !== 'super_admin' && $user->department_id !== $document->department_id) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขเอกสารข้ามหน่วยงาน');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|max:20480',
        ]);

        // อัปเดตข้อมูลทั่วไป
        $document->title = $request->title;
        
        // ถ้า Super Admin เปลี่ยนแผนก
        if ($user->role === 'super_admin' && $request->filled('department_id')) {
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
        }

        $document->save();

        return redirect()->route('dashboard')->with('success', 'แก้ไขเอกสารเรียบร้อยแล้ว');
    }
}