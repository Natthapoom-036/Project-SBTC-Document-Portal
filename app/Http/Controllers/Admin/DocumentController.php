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
        'file' => 'required|file|max:20480',
    ]);

    $user = Auth::user();
    $target_department_id = $request->department_id;

    // Logic จัดการแผนก (เหมือนเดิม)
    if ($user->role !== 'super_admin') {
        if (is_null($user->department_id)) {
            return redirect()->back()->with('error', 'บัญชีของคุณไม่มีสังกัด');
        }
        $target_department_id = $user->department_id;
    } else {
        if (empty($target_department_id)) {
            return redirect()->back()->with('error', 'กรุณาเลือกหน่วยงาน');
        }
    }

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/documents', $filename);

        // --- จุดสำคัญอยู่ตรงนี้ครับ ---
        Document::create([
            'title' => $request->title,
            'filename' => $filename,
            'department_id' => $target_department_id,
            'user_id' => $user->id, // <--- บรรทัดนี้ต้องมี! ถ้าไม่มีมันจะไม่รู้ว่าใครอัพ
        ]);

        return redirect()->back()->with('success', 'อัปโหลดเอกสารสำเร็จ');
    }

    return redirect()->back()->with('error', 'เกิดข้อผิดพลาด');
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

    // เช็คสิทธิ์ (เหมือนเดิม)
    if ($user->role !== 'super_admin' && $user->department_id !== $document->department_id) {
        abort(403, 'คุณไม่มีสิทธิ์แก้ไขเอกสารข้ามหน่วยงาน');
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'file' => 'nullable|file|max:20480',
    ]);

    // 1. อัปเดตชื่อเอกสาร
    $document->title = $request->title;

    // 2. 🔥 เพิ่มบรรทัดนี้: เปลี่ยนชื่อเจ้าของไฟล์ เป็นคนที่กำลังแก้ไขอยู่ 🔥
    $document->user_id = $user->id; 
    
    // 3. ถ้า Super Admin เปลี่ยนแผนก
    if ($user->role === 'super_admin' && $request->filled('department_id')) {
        $document->department_id = $request->department_id;
    }

    // 4. ถ้ามีการอัปโหลดไฟล์ใหม่
    if ($request->hasFile('file')) {
        if (Storage::exists('public/documents/' . $document->filename)) {
            Storage::delete('public/documents/' . $document->filename);
        }
        
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/documents', $filename);
        
        $document->filename = $filename;
    }

    // 5. บันทึก (Timestamp updated_at จะเปลี่ยนให้อัตโนมัติ)
    $document->save();

    return redirect()->route('dashboard')->with('success', 'แก้ไขเอกสารเรียบร้อย (เปลี่ยนชื่อผู้แก้ไขแล้ว)');
}
}