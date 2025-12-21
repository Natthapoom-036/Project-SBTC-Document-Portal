<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>งานเอกสาร วิทยาลัยเทคนิคสิงห์บุรี</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* --- CSS ของคุณ --- */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", "Sarabun", sans-serif;
    }

    body {
      background-color: #f6f9fc;
      color: #333;
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background-color: #ffffff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .logo-section {
      display: flex;
      align-items: center;
    }

    .logo {
      width: 50px;
      height: 50px;
      margin-right: 15px;
      object-fit: contain;
    }

    .title h2 {
      font-size: 20px;
      color: #1d4ed8;
      margin-bottom: 2px;
    }

    .title p {
      font-size: 13px;
      color: #555;
    }

    .login-btn {
      background-color: #1d4ed8;
      color: #fff;
      border: none;
      padding: 8px 18px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 15px;
      transition: 0.3s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .login-btn:hover {
      background-color: #2563eb;
    }

    /* Main Section */
    .main {
      text-align: center;
      margin: 60px auto;
      max-width: 1200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 40vh;
    }
    
    .main h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: #333;
    }

    .subtitle {
      font-size: 16px;
      color: #555;
      margin-bottom: 50px;
      max-width: 600px;
      line-height: 1.6;
    }

    /* Card Layout */
    .card-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: stretch;
      gap: 30px;
      width: 100%;
      max-width: 1000px;
    }

    .card {
      background: #fff;
      border-radius: 16px;
      padding: 30px 25px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      flex: 1 1 220px;
      max-width: 250px;
      transition: transform 0.2s ease, box-shadow 0.3s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      text-align: center;
      text-decoration: none;
      color: inherit;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }
    
    .card .icon {
        font-size: 40px;
        margin-bottom: 15px;
        color: #1d4ed8;
    }
    
    .card h3 {
        margin-bottom: 10px;
        font-size: 1.1rem;
        color: #1d4ed8;
    }
    
    .card p {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.4;
    }

    /* Quick Info Section */
    .info-section {
      width: 100%;
      display: flex;
      justify-content: center;
      margin-top: 60px;
      margin-bottom: 80px;
    }

    .info-container {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      padding: 40px 30px;
      max-width: 900px;
      width: 90%;
      text-align: center;
    }

    .info-container h2 {
      font-size: 20px;
      margin-bottom: 30px;
      color: #222;
    }

    .info-cards {
      display: flex;
      justify-content: center;
      align-items: stretch;
      gap: 60px;
      flex-wrap: wrap;
    }

    .info-card {
      flex: 1 1 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .icon-box {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
      color: #fff;
      font-size: 24px;
    }

    .icon-box.blue { background-color: #3b82f6; }
    .icon-box.green { background-color: #34d399; }
    .icon-box.purple { background-color: #a855f7; }

    .info-card h3 {
      font-size: 16px;
      margin-bottom: 5px;
      color: #333;
    }

    .count {
      font-size: 20px;
      font-weight: bold;
      color: #1d4ed8;
    }

    /* Footer */
    .footer {
      text-align: center;
      padding: 15px;
      margin-top: 50px;
      background-color: #ffffff;
      border-top: 1px solid #ddd;
      color: #444;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <header class="header">
    <div class="logo-section">
      <img src="{{ asset('image/sbtclogo.jpg') }}" alt="Logo" class="logo">
      <div class="title">
        <h2>ระบบงานเอกสาร วิทยาลัยเทคนิคสิงห์บุรี</h2>
        <p>Singburi Technical College Document Portal</p>
      </div>
    </div>
    
    @if (Route::has('login'))
        @auth
            {{-- แก้ไขตรงนี้: เปลี่ยนจาก url('/') เป็น route('dashboard') --}}
            <a href="{{ route('dashboard') }}" class="login-btn">
                <i class="fas fa-user-circle"></i> Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        @endauth
    @endif
  </header>

  <main class="main">
    <h1>ยินดีต้อนรับสู่ระบบ</h1>
    <p class="subtitle">
      ที่นี่จะแสดงเอกสารในแต่ละฝ่ายแต่ละงานให้ผู้ใช้สามารถเลือก Downloads ได้เลย
    </p>

    <div class="card-container">
        @if(isset($divisions) && $divisions->count() > 0)
            @foreach($divisions as $div)
            <div class="card" onclick="window.location.href='{{ route('divisions.show', $div->id) }}'">
                <div class="icon">
                    <i class="{{ $div->icon_class ?? 'fas fa-folder' }}"></i>
                </div>
                <h3>{{ $div->name }}</h3>
                <p>{{ $div->description ?? 'คลิกเพื่อดูรายละเอียด' }}</p>
            </div>
            @endforeach
        @else
            <p style="color: red;">ไม่พบข้อมูลฝ่ายงาน (กรุณาเพิ่มข้อมูลใน Database)</p>
        @endif
    </div>
  </main>

  <section class="info-section">
    <div class="info-container">
      <h2>Quick Information</h2>
      <div class="info-cards">
        <div class="info-card">
          <div class="icon-box blue">
            <i class="fas fa-book"></i>
          </div>
          <h3>จำนวนเอกสารทั้งหมด</h3>
          <p class="count">{{ isset($totalDocs) ? $totalDocs : 0 }}</p>
        </div>

        <div class="info-card">
          <div class="icon-box green">
            <i class="fas fa-user"></i>
          </div>
          <h3>ยอดผู้ชมทั้งหมด</h3>
          <p class="count">0</p>
        </div>

        <div class="info-card">
          <div class="icon-box purple">
            <i class="fas fa-clipboard"></i>
          </div>
          <h3>ยอดดาวน์โหลดทั้งหมด</h3>
          <p class="count">0</p>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    © {{ date('Y') }} วิทยาลัยเทคนิคสิงห์บุรี | Document Management System
  </footer>

</body>
</html>