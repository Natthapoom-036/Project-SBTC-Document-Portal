<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-cogs text-indigo-500 mr-2"></i> {{ __('แผงควบคุมสำหรับผู้ดูแลระบบ') }}
        </h2>
    </x-slot>

    {{-- 🔥 CSS ปรับแต่งพิเศษ (แก้ปุ่มล่องหน + ตารางเต็ม) 🔥 --}}
    <style>
        /* 1. บังคับตารางให้กว้าง 100% เสมอ */
        .full-width-table {
            width: 100% !important;
            border-collapse: collapse;
        }

        /* 2. ปุ่มอัปโหลด (สีน้ำเงินเข้ม) */
        .btn-upload {
            background-color: #2563eb !important;
            color: white !important;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }
        .btn-upload:hover {
            background-color: #1d4ed8 !important;
            transform: translateY(-1px);
        }

        /* 3. ปุ่ม Action ต่างๆ */
        .btn-action {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
            text-decoration: none; border: 1px solid transparent; transition: 0.2s; cursor: pointer;
        }
        
        .btn-load { background-color: #eff6ff; color: #1d4ed8; border-color: #dbeafe; }
        .btn-load:hover { background-color: #dbeafe; }

        .btn-edit { background-color: #fffbeb; color: #b45309; border-color: #fcd34d; }
        .btn-edit:hover { background-color: #fde68a; }

        .btn-del { background-color: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .btn-del:hover { background-color: #fee2e2; }

        /* Tabs */
        .tab-btn {
            padding: 12px 24px; font-weight: 600; color: #6b7280; border-bottom: 3px solid transparent; 
            cursor: pointer; display: flex; align-items: center; gap: 8px;
        }
        .tab-btn.active { color: #2563eb; border-bottom-color: #2563eb; background-color: #eff6ff; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm rounded-r flex items-center">
                    <i class="fas fa-check-circle text-xl mr-3"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow-sm rounded-r flex items-center">
                    <i class="fas fa-exclamation-circle text-xl mr-3"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Navigation Tabs --}}
            <div class="bg-white shadow-sm rounded-t-xl border-b border-gray-200">
                <nav class="flex overflow-x-auto px-2" aria-label="Tabs">
                    {{-- 1. Tab เอกสาร (ทุกคนเห็น) --}}
                    <button onclick="showTab('documents')" id="tab-documents" class="tab-btn active">
                        <i class="fas fa-file-pdf"></i> จัดการเอกสาร
                    </button>

                    {{-- 2. Tab อื่นๆ (เห็นเฉพาะ Super Admin) --}}
                    @if(Auth::user()->role === 'super_admin')
                        <button onclick="showTab('divisions')" id="tab-divisions" class="tab-btn">
                            <i class="fas fa-building"></i> ฝ่ายงาน
                        </button>
                        <button onclick="showTab('departments')" id="tab-departments" class="tab-btn">
                            <i class="fas fa-sitemap"></i> หน่วยงาน
                        </button>
                        <button onclick="showTab('users')" id="tab-users" class="tab-btn">
                            <i class="fas fa-users"></i> ผู้ใช้งาน
                        </button>
                    @endif
                </nav>
            </div>

            {{-- Content Area --}}
            <div class="bg-white shadow-sm rounded-b-xl p-6 min-h-[500px]">

                {{-- ================= TAB 1: DOCUMENTS ================= --}}
                <div id="content-documents" class="tab-content">
                    
                    {{-- Form Upload --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-2"><i class="fas fa-cloud-upload-alt"></i></span>
                            อัปโหลดเอกสารใหม่
                        </h3>
                        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">ชื่อเอกสาร</label>
                                    <input type="text" name="title" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="ระบุชื่อเอกสาร...">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">เลือกไฟล์ (PDF/Word)</label>
                                    <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-blue-700 hover:file:bg-blue-50 border border-gray-200 rounded-lg bg-white">
                                </div>
                            </div>

                            @if(Auth::user()->role === 'super_admin' || is_null(Auth::user()->department_id))
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">เลือกหน่วยงานปลายทาง</label>
                                    <select name="department_id" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500">
                                        <option value="">-- เลือกหน่วยงาน --</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->division->name ?? '-' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">
                            @endif

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="btn-upload">
                                    <i class="fas fa-upload"></i> ยืนยันการอัปโหลด
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Table Documents --}}
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 full-width-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">ชื่อเอกสาร</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">หน่วยงาน</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">ผู้ลงข้อมูล</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-600 uppercase tracking-wider">โหลด</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-600 uppercase tracking-wider">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($documents as $doc)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $doc->title }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $doc->filename }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium border border-gray-200">
                                                {{ $doc->department->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col text-sm text-gray-600">
                                                <span class="font-medium">{{ $doc->user->name ?? 'ไม่ระบุ' }}</span>
                                                <span class="text-xs text-gray-400">{{ $doc->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-700 font-bold text-xs">
                                                {{ number_format($doc->download_count) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('documents.download', $doc->filename) }}" class="btn-action btn-load" title="Download">
                                                    <i class="fas fa-download"></i> โหลด
                                                </a>
                                                
                                                @if(Auth::user()->role === 'super_admin' || (Auth::user()->department_id == $doc->department_id))
                                                    <a href="{{ route('documents.edit', $doc->id) }}" class="btn-action btn-edit" title="Edit">
                                                        <i class="fas fa-edit"></i> แก้ไข
                                                    </a>
                                                    
                                                    <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันลบเอกสาร?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn-action btn-del" title="Delete">
                                                            <i class="fas fa-trash-alt"></i> ลบ
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">ไม่พบเอกสารในระบบ</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================= TAB 2: DIVISIONS (เห็นเฉพาะ Admin) ================= --}}
                @if(Auth::user()->role === 'super_admin')
                <div id="content-divisions" class="tab-content hidden">
                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-bold text-purple-900 mb-4"><i class="fas fa-plus-circle mr-2"></i> เพิ่มฝ่ายงานใหม่</h3>
                        <form action="{{ route('divisions.store') }}" method="POST" class="flex flex-wrap gap-3 items-end">
                            @csrf
                            <div class="flex-1 min-w-[200px]">
                                <input type="text" name="name" placeholder="ชื่อฝ่าย (เช่น ฝ่ายวิชาการ)" class="w-full rounded-lg border-purple-300 focus:ring-purple-500" required>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <input type="text" name="description" placeholder="รายละเอียดสั้นๆ" class="w-full rounded-lg border-purple-300 focus:ring-purple-500">
                            </div>
                            <button type="submit" class="btn-upload bg-purple-600 hover:bg-purple-700">
                                <i class="fas fa-save"></i> บันทึก
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($divisions as $div)
                            <div class="border rounded-xl p-5 flex justify-between items-center bg-white hover:shadow-md transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-xl">
                                        <i class="{{ $div->icon_class ?? 'fas fa-folder' }}"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 text-lg">{{ $div->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $div->description }}</div>
                                    </div>
                                </div>
                                <form action="{{ route('divisions.destroy', $div->id) }}" method="POST" onsubmit="return confirm('ลบฝ่ายงานนี้?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-del">
                                        <i class="fas fa-trash-alt"></i> ลบ
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ================= TAB 3: DEPARTMENTS (เห็นเฉพาะ Admin) ================= --}}
                @if(Auth::user()->role === 'super_admin')
                <div id="content-departments" class="tab-content hidden">
                    <div class="bg-green-50 border border-green-100 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-bold text-green-900 mb-4"><i class="fas fa-plus-circle mr-2"></i> เพิ่มหน่วยงานย่อย</h3>
                        <form action="{{ route('departments.store') }}" method="POST" class="flex flex-wrap gap-3 items-end">
                            @csrf
                            <div class="flex-1 min-w-[200px]">
                                <select name="division_id" class="w-full rounded-lg border-green-300 focus:ring-green-500" required>
                                    <option value="">-- เลือกฝ่ายต้นสังกัด --</option>
                                    @foreach($divisions as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <input type="text" name="name" placeholder="ชื่อแผนก/งาน" class="w-full rounded-lg border-green-300 focus:ring-green-500" required>
                            </div>
                            <button type="submit" class="btn-upload bg-green-600 hover:bg-green-700">
                                <i class="fas fa-save"></i> สร้างหน่วยงาน
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 full-width-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase">ชื่อหน่วยงาน</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase">สังกัดฝ่าย</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-600 uppercase">จำนวนเอกสาร</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-600 uppercase">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($departments as $dept)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $dept->name }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $dept->division->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-bold">
                                                {{ $dept->documents_count }} ไฟล์
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('ลบหน่วยงานนี้?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-del">
                                                    <i class="fas fa-trash-alt"></i> ลบ
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- ================= TAB 4: USERS (เห็นเฉพาะ Admin) ================= --}}
                @if(Auth::user()->role === 'super_admin')
                <div id="content-users" class="tab-content hidden">
                    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-bold text-yellow-800 mb-4"><i class="fas fa-user-plus mr-2"></i> เพิ่มผู้ใช้งานใหม่</h3>
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <input type="text" name="name" placeholder="ชื่อ-นามสกุล" class="w-full rounded-lg border-yellow-300 focus:ring-yellow-500" required>
                                <input type="email" name="email" placeholder="Email (Login)" class="w-full rounded-lg border-yellow-300 focus:ring-yellow-500" required>
                                <input type="text" name="password" placeholder="Password" class="w-full rounded-lg border-yellow-300 focus:ring-yellow-500" required>
                                <select name="department_id" class="w-full rounded-lg border-yellow-300 focus:ring-yellow-500">
                                    <option value="">-- ไม่ระบุ (เป็น Admin) --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="btn-upload bg-yellow-600 hover:bg-yellow-700">
                                    <i class="fas fa-save"></i> บันทึกผู้ใช้งาน
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 full-width-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase">ชื่อ-นามสกุล</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase">Email</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase">สถานะ / สังกัด</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-600 uppercase">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            @if($user->role === 'super_admin')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                    Super Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                    User
                                                </span>
                                                <span class="text-xs text-gray-500 ml-2">{{ $user->department->name ?? 'ไม่ระบุ' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if(Auth::user()->role === 'super_admin' && Auth::id() !== $user->id)
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('ลบผู้ใช้งานนี้?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-action btn-del">
                                                        <i class="fas fa-trash-alt"></i> ลบ
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Script สลับ Tab --}}
    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            
            // เช็คก่อนว่า Element มีอยู่จริงไหม (เผื่อ User ธรรมดาไม่มีสิทธิ์เห็นบาง Tab)
            const targetContent = document.getElementById('content-' + tabName);
            const targetBtn = document.getElementById('tab-' + tabName);

            if(targetContent && targetBtn) {
                targetContent.classList.remove('hidden');
                targetBtn.classList.add('active');
            }
        }
    </script>
</x-app-layout>