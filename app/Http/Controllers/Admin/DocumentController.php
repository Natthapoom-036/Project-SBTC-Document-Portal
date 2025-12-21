<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth; // <--- ต้องมีบรรทัดนี้ ไม่งั้น Error
use Illuminate\Support\Facades\Storage;

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

    // อย่าลืมฟังก์ชัน create, edit, update ถ้ามี (หรือปล่อยว่างไว้ก่อนได้)
}