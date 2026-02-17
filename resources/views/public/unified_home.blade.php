<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>งานเอกสาร วิทยาลัยเทคนิคสิงห์บุรี</title>
  <link rel="icon" href="{{ asset('image/sbtcLogo.jpg') }}" type="image/jpeg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* CSS ชุดเดิม (ย่อให้สั้นลงเพื่อความสะดวก) */
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Figtree", "Sarabun", sans-serif; }
    body { background-color: #f3f4f6; color: #1f2937; }
    .header { display: flex; justify-content: space-between; padding: 15px 5%; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
    .logo-section { display: flex; align-items: center; gap: 15px; }
    .logo { width: 45px; } 
    .title h2 { color: #1e40af; font-weight: 700; font-size: 1.2rem; }
    .login-btn { background: #2563eb; color: white; padding: 8px 20px; border-radius: 50px; text-decoration: none; display: flex; align-items: center; gap: 8px; font-size: 0.9rem; transition: 0.2s; }
    .login-btn:hover { background: #1d4ed8; }
    
    .hero { text-align: center; padding: 60px 20px 40px; }
    .hero h1 { font-size: 2.2rem; color: #111827; margin-bottom: 10px; font-weight: 800; }
    
    .section-container { max-width: 1100px; margin: 0 auto 60px; padding: 0 20px; }
    .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; }
    .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); text-align: center; cursor: pointer; transition: 0.3s; border: 1px solid #f3f4f6; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: #bfdbfe; }
    .card .icon { font-size: 32px; color: #3b82f6; margin: 0 auto 15px; background: #eff6ff; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
    
    .stats-grid { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; margin-top: 40px; }
    .stat-card { background: white; padding: 25px; border-radius: 16px; text-align: center; flex: 1; min-width: 200px; max-width: 250px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 20px; color: white; }
    .bg-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .bg-green { background: linear-gradient(135deg, #10b981, #059669); }
    .bg-purple { background: linear-gradient(135deg, #a855f7, #7c3aed); }
    .stat-card .value { font-size: 1.8rem; font-weight: 800; color: #111827; }
    
    .footer { text-align: center; padding: 30px; margin-top: 60px; background: white; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 0.9rem; }
  </style>
</head>
<body>

  {{-- Header --}}
  <header class="header">
    <div class="logo-section">
      <img src="{{ asset('image/sbtcLogo.jpg') }}" alt="Logo" class="logo">
      <div class="title">
        <h2>ระบบจัดการเอกสารภายในวิทยาลัยเทคนิคสิงห์บุรี</h2>
        <p>วิทยาลัยเทคนิคสิงห์บุรี</p>
      </div>
    </div>
    
    @if (Route::has('login'))
        @auth
            <a href="{{ route('dashboard') }}" class="login-btn"><i class="fas fa-columns"></i>จัดการระบบ</a>
            
        @else
            <a href="{{ route('login') }}" class="login-btn"><i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ</a>
        @endauth
    @endif
  </header>

  {{-- Hero Section --}}
  <main class="hero">
    <h1>ยินดีต้อนรับเข้าสู่ระบบจัดการเอกสารภายในวิทยาลัยเทคนิคสิงห์บุรี</h1>
    <p>เลือกฝ่ายงานด้านล่างเพื่อเข้าถึงเอกสารที่ต้องการดาวน์โหลด</p>
  </main>

  <div class="section-container">
    
    {{-- เมนูฝ่ายงาน (Cards) --}}
    <div style="margin-bottom: 50px;">
        <h3 style="margin-bottom: 20px; font-size: 1.2rem; color: #374151; font-weight: 600; border-left: 4px solid #2563eb; padding-left: 15px;">
            หน่วยงานภายใน
        </h3>
        <div class="card-grid">
            @if(isset($divisions) && $divisions->count() > 0)
                @foreach($divisions as $div)
                {{-- ลิงก์ไปหน้าใหม่ --}}
                <div class="card" onclick="window.location.href='{{ route('divisions.show', $div->id) }}'">
                    <div class="icon"><i class="{{ $div->icon_class ?? 'fas fa-folder' }}"></i></div>
                    <h3>{{ $div->name }}</h3>
                    <!-- <p>{{ $div->description ?? 'คลิกเพื่อดูหน่วยงาน ' }}</p> -->
                </div>
                @endforeach
            @else
                <p style="color: #ef4444;">ไม่พบข้อมูลฝ่ายงาน</p>
            @endif
        </div>
    </div>

    {{-- สถิติระบบ --}}
    <div>
        <h3 style="margin-bottom: 20px; font-size: 1.2rem; color: #374151; font-weight: 600; text-align: center;">ภาพรวมระบบ</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-blue"><i class="fas fa-book"></i></div>
                <h4>จำนวนเอกสาร</h4>
                <div class="value">{{ number_format($totalDocs) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-green"><i class="fas fa-users"></i></div>
                <h4>ยอดเข้าชม</h4>
                <div class="value">{{ number_format($totalViews) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-purple"><i class="fas fa-download"></i></div>
                <h4>การดาวน์โหลด</h4>
                <div class="value">{{ number_format($totalDownloads) }}</div>
            </div>
        </div>
    </div>

  </div>

  <footer class="footer">
    <p>© {{ date('Y') }} วิทยาลัยเทคนิคสิงห์บุรี | พัฒนาระบบโดย แผนกสาขาเทคโนโลยีสารสนเทศ</p>
  </footer>

</body>
</html>