<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document Management System</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="resources/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-indigo-600 hover:text-indigo-700 transition">
                            <span class="flex items-center">
                                <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                DMS Portal
                            </span>
                        </a>
                    </div>
                    <div class="flex items-center">
                        <a href="{{ route('login') }}" class="login-btn">
                            🔐เข้าสู่ระบบ
                        </a>
                    </div>
                </div>
            </div>
        </nav>

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
