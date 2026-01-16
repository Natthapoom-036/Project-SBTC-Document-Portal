<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $division->name }} - เลือกหน่วยงาน</title>
  <link rel="icon" href="{{ asset('image/sbtcLogo.jpg') }}" type="image/jpeg">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* --- CSS ชุดเดียวกับหน้าแรก (Home Theme) --- */
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Figtree", "Sarabun", sans-serif; }
    body { background-color: #f3f4f6; color: #1f2937; min-height: 100vh; display: flex; flex-direction: column; }

    /* Header */
    .header {
      display: flex; justify-content: space-between; align-items: center;
      padding: 15px 5%; background-color: #ffffff;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100;
    }
    .logo-section { display: flex; align-items: center; gap: 15px; }
    .logo { width: 45px; height: 45px; object-fit: contain; }
    .title h2 { font-size: 1.25rem; color: #1e40af; font-weight: 700; line-height: 1.2; }
    .title p { font-size: 0.85rem; color: #6b7280; }

    /* ปุ่มย้อนกลับ (สไตล์เดียวกับปุ่ม Login แต่เป็นสีเทาอ่อนๆ ให้ดูเป็นรอง) */
    .btn-back {
      background-color: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; padding: 8px 20px;
      border-radius: 50px; cursor: pointer; font-size: 0.9rem; font-weight: 500;
      transition: 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-back:hover { background-color: #e5e7eb; color: #1f2937; transform: translateY(-1px); }

    /* Main Container */
    .main-content { flex: 1; padding: 40px 20px; max-width: 1100px; margin: 0 auto; width: 100%; }

    /* Title Section */
    .page-header { text-align: center; margin-bottom: 50px; }
    .page-header h1 { font-size: 2rem; font-weight: 800; color: #111827; margin-bottom: 10px; }
    .page-header p { color: #6b7280; font-size: 1.1rem; }
    .division-badge { 
        display: inline-block; background: #eff6ff; color: #2563eb; 
        padding: 6px 16px; border-radius: 20px; font-size: 0.9rem; font-weight: 600; margin-bottom: 15px;
    }

    /* Grid Layout (เหมือนหน้าแรก) */
    .card-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 25px;
    }

    /* Card Design (เหมือนหน้าแรก) */
    .card {
      background: #fff; border-radius: 12px; padding: 30px 25px;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
      transition: all 0.3s ease; cursor: pointer; text-align: center; border: 1px solid #f3f4f6;
      text-decoration: none; display: block;
    }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: #bfdbfe; }
    
    /* Icon Style */
    .card .icon { 
        font-size: 28px; color: #3b82f6;
        margin-bottom: 20px; background: #ecfdf5; 
        width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; 
        border-radius: 50%; margin-left: auto; margin-right: auto; 
    }
    
    .card h3 { font-size: 1.15rem; color: #1f2937; margin-bottom: 8px; font-weight: 700; }
    .card p { font-size: 0.9rem; color: #6b7280; line-height: 1.5; }
    .card .count-badge {
        display: inline-block; margin-top: 15px; padding: 4px 12px;
        background: #f3f4f6; color: #4b5563; border-radius: 15px; font-size: 0.8rem; font-weight: 600;
    }

    /* Footer */
    .footer {
      text-align: center; padding: 30px; margin-top: auto;
      background-color: #ffffff; border-top: 1px solid #e5e7eb;
      color: #6b7280; font-size: 0.9rem;
    }
  </style>
</head>
<body>

  {{-- Header --}}
  <header class="header">
    <div class="logo-section">
      <img src="{{ asset('image/sbtclogo.jpg') }}" alt="Logo" class="logo">
      <div class="title">
        <h2>ระบบงานเอกสาร</h2>
        <p>วิทยาลัยเทคนิคสิงห์บุรี</p>
      </div>
    </div>
    
    {{-- ปุ่มย้อนกลับ --}}
    <a href="{{ route('home') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> กลับหน้าหลัก
    </a>
  </header>

  {{-- Main Content --}}
  <div class="main-content">
    
    {{-- หัวข้อหน้า --}}
    <div class="page-header">
        <span class="division-badge"><i class="fas fa-layer-group mr-2"></i> ฝ่ายงาน</span>
        <h1>{{ $division->name }}</h1>
        <p>เลือกหน่วยงานย่อยเพื่อดูเอกสาร</p>
    </div>

    {{-- ตาราง Grid แสดงหน่วยงาน --}}
    <div class="card-grid">
        @forelse($departments as $dept)
            <a href="{{ route('departments.show', $dept->id) }}" class="card">
                <div class="icon">
                    <i class="fas fa-sitemap"></i>
                </div>
                <h3>{{ $dept->name }}</h3>
                <p>คลิกเพื่อดูเอกสารภายในหน่วยงาน</p>
                
                {{-- แสดงจำนวนเอกสาร (ถ้ามีตัวแปร count ส่งมา) --}}
                <span class="count-badge">
                    <i class="fas fa-file-alt mr-1"></i> {{ $dept->documents_count ?? 0 }} รายการ
                </span>
            </a>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 60px; color: #9ca3af; background: white; border-radius: 12px; border: 2px dashed #e5e7eb;">
                <i class="far fa-folder-open" style="font-size: 48px; margin-bottom: 15px; color: #d1d5db;"></i>
                <p class="text-lg">ยังไม่มีข้อมูลหน่วยงานในฝ่ายนี้</p>
            </div>
        @endforelse
    </div>

  </div>

  <footer class="footer">
    <p>© {{ date('Y') }} วิทยาลัยเทคนิคสิงห์บุรี | พัฒนาระบบโดย แผนกเทคโนโลยีสารสนเทศ</p>
  </footer>

</body>
</html>