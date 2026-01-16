<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ระบบงานเอกสาร</title>
    
    {{-- Font & Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* --- Login Theme (เข้าชุดกับหน้าแรก) --- */
        * { box-sizing: border-box; font-family: "Figtree", "Sarabun", sans-serif; }
        
        body {
            background-color: #f3f4f6; /* สีพื้นหลังเดียวกับหน้า Home */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-card {
            background: white;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        /* Logo Section */
        .logo-area { text-align: center; margin-bottom: 30px; }
        .logo-img { width: 80px; height: 80px; object-fit: contain; margin-bottom: 15px; }
        .app-name { font-size: 1.5rem; font-weight: 800; color: #1e40af; margin-bottom: 5px; }
        .app-desc { color: #6b7280; font-size: 0.9rem; }

        /* Form Elements */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 8px; }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s;
            background-color: #f9fafb;
        }
        
        .form-input:focus {
            border-color: #2563eb;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* Button */
        .btn-login {
            width: 100%;
            background-color: #2563eb;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }
        .btn-login:hover { background-color: #1d4ed8; }

        /* Error Message */
        .error-msg { color: #dc2626; font-size: 0.85rem; margin-top: 5px; display: block; }
        
        .back-link {
            display: block; text-align: center; margin-top: 25px;
            color: #6b7280; font-size: 0.9rem; text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: #2563eb; }
    </style>
</head>
<body>

    <div class="login-card">
        {{-- 1. ส่วนโลโก้ (ใช้รูปเดียวกับหน้าแรก) --}}
        <div class="logo-area">
            <center>
                <img src="{{ asset('image/sbtclogo.jpg') }}" alt="Logo" class="logo-img">
                <h1 class="app-name">ระบบงานเอกสาร</h1>
                <p class="app-desc">เข้าสู่ระบบเพื่อจัดการข้อมูล</p>
            </center>
        </div>

        {{-- 2. ฟอร์ม Login --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email" class="form-label">อีเมล / ชื่อผู้ใช้</label>
                <input id="email" type="email" name="email" class="form-input" placeholder="admin@example.com" required autofocus value="{{ old('email') }}">
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input id="password" type="password" name="password" class="form-input" placeholder="••••••••" required>
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            {{-- Remember Me --}}
            <div class="form-group" style="display: flex; align-items: center;">
                <input type="checkbox" id="remember_me" name="remember" style="width: 16px; height: 16px; cursor: pointer;">
                <label for="remember_me" style="margin-left: 8px; font-size: 0.9rem; color: #4b5563; cursor: pointer;">จำการเข้าสู่ระบบ</label>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ
            </button>
        </form>

        {{-- ปุ่มกลับหน้าหลัก --}}
        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> กลับไปหน้าหลัก
        </a>
    </div>

</body>
</html>