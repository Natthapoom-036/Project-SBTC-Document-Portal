<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Department;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('department')->get(); // โหลดเอกสารพร้อมข้อมูลฝ่ายงาน
        $departments = Department::all();
        return view('public.index', compact('documents', 'departments'));
    }

    public function show(Document $document)
    {
        // For now, just show the index or redirect to download if intended.
        // Or implement a preview page. For this task, I'll redirect to download
        // or just return the index view with a specific focus if needed.
        // Let's make it simple and just show the file info or download it directly?
        // The route list implies a 'show' page. Let's create a basic return for now
        // to prevent crashes, but usually show might preview the PDF.
        
        return view('public.index', compact('document')); // Re-using index for simplicity unless a show view is requested.
        // Actually, the user might just want to download. 
        // But let's stick to the user's snippet logic usually, but the user snippet DID NOT have show.
        // The user ADDED Route::get('documents/{document}', [DocumentController::class, 'show'])
        // So I must provide 'show'.
    }

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