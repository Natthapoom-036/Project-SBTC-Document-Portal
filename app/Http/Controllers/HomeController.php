<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteStat;     // เรียกใช้ Model นับวิว
use App\Models\Document;     // เรียกใช้ Model เอกสาร
use App\Models\Division;     // เรียกใช้ Model ฝ่ายงาน
use Illuminate\Support\Facades\Session; // เรียกใช้ Session

class HomeController extends Controller
{
    public function index()
    {
        // 1. --- ระบบนับยอดเข้าชม (Count Visits) ---
        // เช็ค Session เพื่อไม่ให้ F5 แล้วยอดพุ่งรัวๆ (นับ 1 ครั้งต่อการเปิด Browser)
        if (!Session::has('visited_site')) {
            $stat = SiteStat::first();
            // ถ้ายังไม่มีข้อมูลใน DB ให้สร้างใหม่เริ่มต้นที่ 0
            if (!$stat) {
                $stat = SiteStat::create(['total_visits' => 0]);
            }
            $stat->increment('total_visits'); // บวก 1
            Session::put('visited_site', true); // จำว่าเข้านับแล้ว
        }

        // 2. --- เตรียมตัวเลขไปโชว์ ---
        $stat = SiteStat::first();
        $totalViews = $stat ? $stat->total_visits : 0;
        
        // นับจำนวนเอกสารทั้งหมด
        $totalDocs = Document::count();
        
        // นับจำนวนการดาวน์โหลดรวมทุกไฟล์
        $totalDownloads = Document::sum('download_count');

        // ข้อมูลฝ่ายงาน (ของเดิมที่ต้องใช้แสดงผล)
        $divisions = Division::all(); 

        // 3. ส่งทุกอย่างไปที่หน้า View
        return view('public.unified_home', compact('divisions', 'totalDocs', 'totalViews', 'totalDownloads'));
    }
}   