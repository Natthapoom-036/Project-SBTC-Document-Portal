<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document Management System</title>
    <style>
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
}

.title h2 {
  font-size: 20px;
  color: #1d4ed8;
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
}

.login-btn:hover {
  background-color: #2563eb;
}
    </style>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        
        <!-- Header -->

  <header class="header">
    <div class="logo-section">
      <img src="img/sbtclogo.jpg" alt="Logo" class="logo">
      <div class="title">
        <h2>ระบบงานเอกสาร วิทยาลัยเทคนิคสิงห์บุรี</h2>
        <p>Singburi Technical College Document Portal</p>
      </div>
    </div>
    <a href="{{ route('login') }}" class="login-btn">
                            🔐เข้าสู่ระบบ
                        </a>
  </header>
        
        <!-- Main Content -->
        <main class="flex-grow">
    
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="text-center mb-10">
                        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">ยินดีต้อนรับสู่ระบบ</h1>
                        <p class="mt-4 text-lg text-gray-500">ที่นี่จะแสดงเอกสารในแต่ละฝ่ายแต่ละงานให้ผู้ใช้สามารถเลือก Downloads ได้เลย</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            @if($departments->isEmpty())
                                <p class="text-center text-gray-500 py-4">ไม่พบข้อมูล</p>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($departments as $dept)
                                        <a href="{{ route('departments.show', $dept->id) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 hover:shadow-lg transition duration-200">
                                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $dept->name }}</h5>
                                            <p class="font-normal text-gray-700">
                                                {{ $dept->documents_count }} เอกสาร
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">© {{ date('Y') }} Document Management System. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
