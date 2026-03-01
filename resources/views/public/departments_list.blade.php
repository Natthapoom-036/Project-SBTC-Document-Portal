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

    /* ปุ่มย้อนกลับ */
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

    /* Grid Layout */
    .card-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 25px;
    }

    /* Card Design */
    .card {
      background: #fff; border-radius: 12px; padding: 30px 25px;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
      transition: all 0.3s ease; cursor: pointer; text-align: center; border: 1px solid #f3f4f6;
      text-decoration: none; display: block;
    }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: #bfdbfe; }
    
    /* Icon Style */
    .card .icon { 
        font-size: 28px; 
        /* ลบสีตายตัวออก เพื่อให้ใช้สีจาก Inline Style ที่เราจะสร้าง */
        /* color: #3b82f6; */ 
        /* background: #ecfdf5; */ 
        margin-bottom: 20px; 
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
        <h2>ระบบจัดการเอกสารภายในวิทยาลัยเทคนิคสิงห์บุรี</h2>
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
    </div>

    {{-- ตาราง Grid แสดงหน่วยงาน --}}
    <div class="card-grid">
        @forelse($departments as $dept)
            {{-- 🔥🔥🔥 อัปเดต Logic เลือกไอคอนให้ครอบคลุมหน่วยงานในภาพ 🔥🔥🔥 --}}
           {{-- 🔥🔥🔥 วิธีจับคู่แบบยืดหยุ่น (Smart Keyword Mapping) 🔥🔥🔥 --}}
            @php
                $name = $dept->name;
                
                // 1. กำหนดค่าเริ่มต้น (Default) เผื่อหาไม่เจอ
                $icon = 'fas fa-folder'; 
                $color = '#94a3b8';     // สีเทา
                $bg = '#f1f5f9';        // พื้นหลังเทาอ่อน

                // 2. รายการจับคู่ (ฝั่งซ้ายคือ "คำค้นหา" สั้นๆ / ฝั่งขวาคือไอคอน)
                // ⚠️ เอาคำเฉพาะเจาะจงไว้บนสุด คำทั่วไปไว้ล่างสุดครับ
                $configs = [
                    // --- กลุ่มบริหาร/ธุรการ/การเงิน ---
                    'บริหาร'     => ['icon' => 'fas fa-briefcase',       'color' => '#475569', 'bg' => '#f1f5f9'], // 💼 (บริหารงานทั่วไป)
                    'การเงิน'    => ['icon' => 'fas fa-coins',           'color' => '#eab308', 'bg' => '#fef9c3'], // 💰
                    'บัญชี'      => ['icon' => 'fas fa-calculator',      'color' => '#ca8a04', 'bg' => '#fef08a'], // 🧮
                    'พัสดุ'      => ['icon' => 'fas fa-box-open',        'color' => '#d97706', 'bg' => '#fed7aa'], // 📦
                    'อาคาร'      => ['icon' => 'fas fa-city',            'color' => '#10b981', 'bg' => '#d1fae5'], // 🏢
                    'สถานที่'     => ['icon' => 'fas fa-map-marked-alt',  'color' => '#059669', 'bg' => '#d1fae5'], // 📍
                    'ทะเบียน'    => ['icon' => 'fas fa-file-contract',   'color' => '#0891b2', 'bg' => '#cffafe'], // 📝
                    'ประชาสัมพันธ์'=> ['icon' => 'fas fa-bullhorn',        'color' => '#2563eb', 'bg' => '#dbeafe'], // 📢
                    'บุคลากร'    => ['icon' => 'fas fa-user-tie',        'color' => '#ec4899', 'bg' => '#fbcfe8'], // 👔
                    'สารบรรณ'    => ['icon' => 'fas fa-mail-bulk',       'color' => '#8b5cf6', 'bg' => '#ede9fe'], // 📨
                    'ธุรการ'     => ['icon' => 'fas fa-folder-open',     'color' => '#6366f1', 'bg' => '#e0e7ff'], // 📂

                    // --- กลุ่มวิชาการ ---
                    'หลักสูตร'    => ['icon' => 'fas fa-scroll',          'color' => '#ef4444', 'bg' => '#fee2e2'], // 📜
                    'วัดผล'      => ['icon' => 'fas fa-chart-bar',       'color' => '#f97316', 'bg' => '#ffedd5'], // 📊
                    'ประเมิน'    => ['icon' => 'fas fa-check-double',    'color' => '#fb923c', 'bg' => '#ffedd5'], // ✅
                    'ห้องสมุด'    => ['icon' => 'fas fa-book-reader',     'color' => '#14b8a6', 'bg' => '#ccfbf1'], // 📖
                    'วิทยบริการ'  => ['icon' => 'fas fa-server',          'color' => '#0d9488', 'bg' => '#ccfbf1'], // 🖥️
                    'ทวิภาคี'     => ['icon' => 'fas fa-handshake',       'color' => '#4f46e5', 'bg' => '#e0e7ff'], // 🤝
                    'สื่อ'        => ['icon' => 'fas fa-photo-film',      'color' => '#db2777', 'bg' => '#fce7f3'], // 🎬
                    'วิจัย'       => ['icon' => 'fas fa-microscope',      'color' => '#7c3aed', 'bg' => '#ddd6fe'], // 🔬
                    'นวัตกรรม'    => ['icon' => 'fas fa-lightbulb',       'color' => '#c026d3', 'bg' => '#fae8ff'], // 💡

                    // --- กลุ่มกิจกรรม/ปกครอง ---
                    'กิจกรรม'    => ['icon' => 'fas fa-users',           'color' => '#059669', 'bg' => '#a7f3d0'], // 👥
                    'ครูที่ปรึกษา'  => ['icon' => 'fas fa-chalkboard-user', 'color' => '#65a30d', 'bg' => '#d9f99d'], // 👨‍🏫
                    'ปกครอง'     => ['icon' => 'fas fa-gavel',           'color' => '#1e293b', 'bg' => '#e2e8f0'], // 🔨
                    'แนะแนว'     => ['icon' => 'fas fa-compass',         'color' => '#be123c', 'bg' => '#ffe4e6'], // 🧭
                    'พยาบาล'     => ['icon' => 'fas fa-heartbeat',       'color' => '#dc2626', 'bg' => '#fee2e2'], // 💓
                    'ลูกเสือ'     => ['icon' => 'fas fa-campground',      'color' => '#15803d', 'bg' => '#bbf7d0'], // ⛺

                    // --- กลุ่มแผนงาน ---
                    'แผน'       => ['icon' => 'fas fa-chart-pie',       'color' => '#4338ca', 'bg' => '#e0e7ff'], // 🥧
                    'งบประมาณ'   => ['icon' => 'fas fa-money-check-alt', 'color' => '#3730a3', 'bg' => '#e0e7ff'], // 💳
                    'ศูนย์ข้อมูล'   => ['icon' => 'fas fa-database',        'color' => '#0ea5e9', 'bg' => '#bae6fd'], // 💾
                    'ความร่วมมือ'  => ['icon' => 'fas fa-globe-asia',      'color' => '#0369a1', 'bg' => '#e0f2fe'], // 🌏
                    'ประกัน'      => ['icon' => 'fas fa-certificate',     'color' => '#be185d', 'bg' => '#fce7f3'], // 🏵️
                    'การค้า'      => ['icon' => 'fas fa-store',           'color' => '#15803d', 'bg' => '#bbf7d0'], // 🏪
                    
                    // --- ศูนย์/โครงการพิเศษ ---
                    'ศูนย์'       => ['icon' => 'fas fa-landmark',        'color' => '#b45309', 'bg' => '#ffedd5'], // 🏛️
                    'โครงการ'    => ['icon' => 'fas fa-project-diagram', 'color' => '#ea580c', 'bg' => '#ffedd5'], // 🏗️
                    'ช่าง'       => ['icon' => 'fas fa-tools',           'color' => '#475569', 'bg' => '#f1f5f9'], // 🛠️
                    'สวัสดิการ'   => ['icon' => 'fas fa-hand-holding-heart', 'color' => '#e11d48', 'bg' => '#ffe4e6'], // 🤲❤️
                    'อาชีวศึกษา'  => ['icon' => 'fas fa-university',         'color' => '#4c1d95', 'bg' => '#ede9fe'], // 🏛️ (สีม่วงเข้ม)
                ];

                // 3. วนลูปเช็คว่า "ชื่อหน่วยงาน" มี "คำค้นหา" อยู่ข้างในไหม?
                foreach ($configs as $keyword => $conf) {
                    if (str_contains($name, $keyword)) {
                        $icon = $conf['icon'];
                        $color = $conf['color'];
                        $bg = $conf['bg'];
                        break; // เจอแล้วหยุดเลย (จะได้ไม่โดนคำอื่นทับ)
                    }
                }
            @endphp
            <a href="{{ route('departments.show', $dept->id) }}" class="card">
                {{-- นำตัวแปรที่ได้จาก Logic มาใส่ใน Style --}}
                <div class="icon" style="color: {{ $color }}; background-color: {{ $bg }};">
                    <i class="{{ $icon }}"></i>
                </div>
                
                <h3>{{ $dept->name }}</h3>
                <p>คลิกเพื่อเลือกเอกสารที่ต้องการ</p>
                
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
    <p>© {{ date('Y') }} วิทยาลัยเทคนิคสิงห์บุรี | พัฒนาระบบโดย แผนกสาขาเทคโนโลยีสารสนเทศ</p>
  </footer>

</body>
</html>