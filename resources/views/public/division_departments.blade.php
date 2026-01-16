<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ฝ่ายงานในสังกัด: {{ isset($division) ? $division->name : 'ไม่ระบุ' }}</title>
  <link rel="icon" href="{{ asset('image/sbtcLogo.jpg') }}" type="image/jpeg">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
      /* Style ชุดเดียวกับหน้าแรก เพื่อความสวยงาม */
      .header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 15px 40px;
          background-color: #ffffff;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }
      .logo-section { display: flex; align-items: center; }
      .logo { width: 50px; height: 50px; margin-right: 15px; object-fit: contain; }
      .title h2 { font-size: 20px; color: #1d4ed8; margin: 0; font-weight: bold; }
      .title p { font-size: 13px; color: #555; margin: 0; }
      
      .back-btn {
          background-color: #64748b; /* สีเทา */
          color: #fff;
          border: none;
          padding: 8px 18px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 15px;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          gap: 5px;
          transition: 0.3s;
      }
      .back-btn:hover { background-color: #475569; }
  </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

  <header class="header">
    <div class="logo-section">
      <img src="{{ asset('image/sbtclogo.jpg') }}" alt="Logo" class="logo">
      <div class="title">
        {{-- ใส่ isset ป้องกัน error ถ้าตัวแปรไม่มา --}}
        <h2>{{ isset($division) ? $division->name : 'ไม่พบข้อมูลฝ่าย' }}</h2>
        <p>รายชื่อฝ่ายงานในสังกัด</p>
      </div>
    </div>
    
    {{-- ปุ่มย้อนกลับ --}}
    <a href="{{ route('home') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> ย้อนกลับหน้าหลัก
    </a>
  </header>

  <main class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900">เลือกหน่วยงานที่ต้องการ</h1>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                @if(!isset($departments) || $departments->isEmpty())
                    <div class="text-center py-10">
                        <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">ยังไม่มีหน่วยงานในสังกัดนี้</p>
                        <p class="text-xs text-gray-400 mt-2">(กรุณาเพิ่มข้อมูลในตาราง departments และใส่ division_id)</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($departments as $dept)
                        {{-- ลิงก์ไปหน้าเอกสาร --}}
                        <a href="{{ route('departments.show', $dept->id) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 hover:shadow-lg transition duration-200 group">
                            <div class="flex items-center mb-2">
                                <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <i class="fas fa-folder text-xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition">{{ $dept->name }}</h3>
                            </div>
                            <div class="pl-16">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $dept->documents_count }} เอกสาร
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
  </main>

  <footer class="bg-white border-t border-gray-100 py-6 mt-auto">
    <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
        วิทยาลัยเทคนิคสิงห์บุรี © {{ date('Y') }}
    </div>
  </footer>
</body>
</html>