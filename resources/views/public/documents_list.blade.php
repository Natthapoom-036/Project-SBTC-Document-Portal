<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $pageTitle ?? 'รายการเอกสาร' }}</title>
  <link rel="icon" href="{{ asset('image/sbtcLogo.jpg') }}" type="image/jpeg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* CSS ชุดเดิม */
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Figtree", "Sarabun", sans-serif; }
    body { background-color: #f3f4f6; color: #1f2937; }
    .header { display: flex; justify-content: space-between; padding: 15px 5%; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
    .logo-section { display: flex; align-items: center; gap: 15px; }
    .logo { width: 45px; } 
    .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
    
    .doc-table-wrapper { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; }
    .table-header { padding: 20px 25px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; }
    .table-header h2 { font-size: 1.2rem; color: #1f2937; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .btn-back { background: #f3f4f6; color: #4b5563; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.9rem; transition: 0.2s; }
    .btn-back:hover { background: #e5e7eb; color: #1f2937; }

    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th { text-align: left; padding: 15px 25px; background: #f9fafb; color: #4b5563; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .custom-table td { padding: 16px 25px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    .custom-table tr:hover { background-color: #f8fafc; }
    
    .doc-name { font-weight: 600; color: #1f2937; display: block; }
    .badge-dept { background: #eef2ff; color: #4f46e5; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .user-info { display: flex; align-items: center; gap: 8px; }
    .user-text { display: flex; flex-direction: column; }
    .user-name { font-size: 0.85rem; font-weight: 500; color: #374151; }
    .user-date { font-size: 0.75rem; color: #9ca3af; }
    .count-badge { background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; }

    /* 🔥 CSS ปุ่มกด (เพิ่มใหม่) 🔥 */
    .btn-group { display: flex; gap: 8px; justify-content: center; }
    
    /* ปุ่มดู (สีขาวขอบเทา) */
    .btn-view { 
        background-color: #ffffff; color: #374151; padding: 6px 12px; border-radius: 6px; 
        text-decoration: none; font-size: 0.85rem; transition: 0.2s; display: inline-flex; 
        align-items: center; gap: 6px; border: 1px solid #d1d5db; 
    }
    .btn-view:hover { background-color: #f3f4f6; border-color: #9ca3af; color: #111827; }

    /* ปุ่มโหลด (สีน้ำเงิน) */
    .btn-download { 
        background-color: #2563eb; color: white; padding: 6px 12px; border-radius: 6px; 
        text-decoration: none; font-size: 0.85rem; transition: 0.2s; display: inline-flex; 
        align-items: center; gap: 6px; border: 1px solid transparent;
    }
    .btn-download:hover { background-color: #1d4ed8; }
  </style>
</head>
<body>

  <header class="header">
    <div class="logo-section">
      <img src="{{ asset('image/sbtclogo.jpg') }}" alt="Logo" class="logo">
      <div>
        <h2 style="color:#1e40af; font-weight:bold;">ระบบจัดการเอกสารภายในวิทยาลัยเทคนิคสิงห์บุรี</h2>
        <p style="color:#6b7280; font-size:0.8rem;">วิทยาลัยเทคนิคสิงห์บุรี</p>
      </div>
    </div>
    <a href="{{ route('home') }}" class="btn-back"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
  </header>

  <div class="container">
    <div class="doc-table-wrapper">
        {{-- 🔥 แก้ไขส่วนหัวตารางตรงนี้ครับ (ใส่ช่องค้นหาเข้าไป) 🔥 --}}
        <div class="table-header" style="flex-wrap: wrap; gap: 15px;">
            
            {{-- ส่วนที่ 1: ชื่อหัวข้อและจำนวน (อยู่ซ้าย) --}}
            <div style="display: flex; align-items: center; gap: 10px;">
                <h2 style="font-size: 1.2rem; color: #1f2937; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-folder-open text-indigo-500"></i> {{ $pageTitle }}
                </h2>
                <span style="color:#6b7280; font-size:0.9rem;">พบ {{ $documents->count() }} รายการ</span>
            </div>

            {{-- ส่วนที่ 2: ช่องค้นหา (อยู่ขวา) --}}
            <form action="{{ route('documents.search') }}" method="GET" style="display: flex; gap: 10px; flex: 1; max-width: 400px; justify-content: flex-end; margin-left: auto;">
    <div style="position: relative; width: 100%;">
        <input type="text" name="q" placeholder="ค้นหาชื่อเอกสาร..." 
               style="width: 100%; padding: 8px 35px 8px 35px; border: 1px solid #d1d5db; border-radius: 50px; outline: none; font-size: 0.9rem; transition: 0.2s;"
               onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
               onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
               value="{{ request('q') }}"
        >
        <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.85rem;"></i>
        
        {{-- 🔥 เพิ่มปุ่มกากบาท (X) ตรงนี้: จะโชว์เฉพาะตอนที่มีการพิมพ์ค้นหาค้างไว้ --}}
        @if(request('q'))
            <a href="{{ route('documents.search') }}" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; cursor: pointer; text-decoration: none;" title="ล้างค่า">
                <i class="fas fa-times-circle hover:text-red-500 transition-colors"></i>
            </a>
        @endif
    </div>
    
    <button type="submit" style="background: #2563eb; color: white; border: none; padding: 0 20px; border-radius: 50px; cursor: pointer; font-size: 0.9rem; transition: 0.2s; white-space: nowrap;">
        ค้นหา
    </button>
</form>
        </div>
        
        @if($documents->isEmpty())
            <div style="text-align: center; padding: 50px; color: #9ca3af;">
                <i class="far fa-folder-open" style="font-size: 40px; margin-bottom: 10px;"></i>
                <p>ยังไม่มีเอกสารในหมวดนี้</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">ชื่อเอกสาร</th>
                            <th style="width: 15%;">หน่วยงาน</th>
                            <th style="width: 20%;">ผู้ลงข้อมูล</th>
                            <th style="width: 10%; text-align: center;">ยอดโหลด</th>
                            {{-- ขยายช่องจัดการให้กว้างขึ้นหน่อย --}}
                            <th style="width: 20%; text-align: center;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                        <tr>
                            <td>
                                <span class="doc-name">{{ $doc->title }}</span>
                                <span style="font-size:0.8rem; color:#9ca3af;">{{ $doc->filename }}</span>
                            </td>
                            <td>
                                <span class="badge-dept">{{ $doc->department->name ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="user-info">
                                    <div style="width:30px; height:30px; background:#e5e7eb; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#6b7280;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-text">
                                        <span class="user-name">{{ $doc->user->name ?? 'ไม่ระบุ' }}</span>
                                        <span class="user-date">{{ $doc->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="count-badge">
                                    <i class="fas fa-download" style="font-size: 12px;"></i>
                                    {{ number_format($doc->download_count) }}
                                </div>
                            </td>
                            <td style="text-align: center;">
                                {{-- 🔥 เพิ่มปุ่ม View ตรงนี้ 🔥 --}}
                                <div class="btn-group">
                                    <a href="{{ route('documents.view', $doc->filename) }}" target="_blank" class="btn-view" title="ดูเอกสาร">
                                        <i class="fas fa-eye"></i> ดู
                                    </a>
                                    <a href="{{ route('documents.download', $doc->filename) }}" class="btn-download" title="ดาวน์โหลด">
                                        <i class="fas fa-download"></i> โหลด
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
  </div>

</body>
</html>