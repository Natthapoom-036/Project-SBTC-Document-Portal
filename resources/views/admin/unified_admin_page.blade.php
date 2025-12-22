<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm rounded-r" role="alert">
                    <p class="font-bold"><i class="fas fa-check-circle mr-2"></i>สำเร็จ</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow-sm rounded-r" role="alert">
                    <p class="font-bold"><i class="fas fa-exclamation-triangle mr-2"></i>ผิดพลาด</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Tabs Navigation --}}
            <div class="bg-white shadow-sm rounded-lg mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button onclick="showTab('documents')" id="tab-documents" class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                            <i class="fas fa-file-pdf mr-2"></i> เอกสาร
                        </button>
                        <button onclick="showTab('divisions')" id="tab-divisions" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                            <i class="fas fa-building mr-2"></i> ฝ่ายงาน
                        </button>
                        <button onclick="showTab('departments')" id="tab-departments" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                            <i class="fas fa-folder mr-2"></i> หน่วยงาน
                        </button>
                        <button onclick="showTab('users')" id="tab-users" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                            <i class="fas fa-users mr-2"></i> ผู้ใช้งาน
                        </button>
                    </nav>
                </div>
            </div>

            {{-- ================= TAB 1: DOCUMENTS ================= --}}
            <div id="content-documents" class="tab-content">
                {{-- Upload Form --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3 text-indigo-600">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            อัปโหลดเอกสารใหม่
                        </h3>
                        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อเอกสาร</label>
                                    <input type="text" name="title" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="เช่น รายงานการประชุม...">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ไฟล์แนบ (PDF/Word)</label>
                                    <input type="file" name="file" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                </div>
                            </div>

                            {{-- Department Logic --}}
                            @if(Auth::user()->role === 'super_admin' || is_null(Auth::user()->department_id))
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">สำหรับ Super Admin</h3>
                                            <div class="mt-2">
                                                <select name="department_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    <option value="">-- กรุณาเลือกหน่วยงานปลายทาง --</option>
                                                    @foreach($departments as $dept)
                                                        <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->division->name ?? '-' }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">
                                <div class="flex items-center text-sm text-green-700 bg-green-50 p-3 rounded-md border border-green-100">
                                    <i class="fas fa-check-circle mr-2 text-lg"></i>
                                    กำลังอัปโหลดลง: <span class="font-bold ml-1">{{ Auth::user()->department->name ?? 'หน่วยงานของคุณ' }}</span>
                                </div>
                            @endif

                            <div class="pt-2">
                                <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    <i class="fas fa-upload mr-2"></i> อัปโหลดเอกสาร
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Documents Table --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">รายการเอกสารทั้งหมด</h3>
                        @if($documents->isEmpty())
                            <div class="text-center py-10 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">ยังไม่มีเอกสารในระบบ</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ชื่อเอกสาร</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">หน่วยงาน</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ผู้ลงข้อมูล</th>
                                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">ยอดโหลด</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($documents as $doc)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8 text-indigo-500">
                                                            <i class="far fa-file-pdf text-2xl"></i>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $doc->title }}</div>
                                                            <div class="text-xs text-gray-500">{{ $doc->filename }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ $doc->department->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                                            <i class="fas fa-user-circle text-gray-400 mr-1.5"></i>
                                                            {{ $doc->user->name ?? 'ไม่ระบุ' }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 ml-5">
                                                            {{ $doc->created_at->format('d/m/Y H:i') }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                        {{ number_format($doc->download_count) }} ครั้ง
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex items-center space-x-3">
                                                        {{-- Download --}}
                                                        <a href="{{ route('documents.download', $doc->filename) }}" class="text-blue-600 hover:text-blue-900 flex items-center" title="ดาวน์โหลด">
                                                            <i class="fas fa-download"></i> <span class="ml-1">โหลด</span>
                                                        </a>

                                                        @if(Auth::user()->role === 'super_admin' || (Auth::user()->department_id == $doc->department_id))
                                                            <span class="text-gray-300">|</span>
                                                            
                                                            {{-- Edit --}}
                                                            <a href="{{ route('documents.edit', $doc->id) }}" class="text-yellow-600 hover:text-yellow-900 flex items-center" title="แก้ไข">
                                                                <i class="fas fa-edit"></i> <span class="ml-1">แก้</span>
                                                            </a>

                                                            {{-- Delete --}}
                                                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันที่จะลบเอกสารนี้?');">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900 flex items-center ml-1">
                                                                    <i class="fas fa-trash-alt"></i> <span class="ml-1">ลบ</span>
                                                                </button>
                                                            </form>
                                                        @endif
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
            </div>

            {{-- ================= TAB 2: DIVISIONS ================= --}}
            <div id="content-divisions" class="tab-content hidden">
                @if(Auth::user()->role === 'super_admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-purple-100">
                    <div class="p-6 bg-purple-50">
                        <h3 class="text-lg font-bold text-purple-800 mb-4"><i class="fas fa-plus-circle mr-2"></i> เพิ่มฝ่ายงานใหม่</h3>
                        <form action="{{ route('divisions.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <input type="text" name="name" placeholder="ชื่อฝ่าย (เช่น ฝ่ายวิชาการ)" class="border-gray-300 rounded-md w-full focus:border-purple-500 focus:ring-purple-500" required>
                                <input type="text" name="description" placeholder="คำบรรยายสั้นๆ" class="border-gray-300 rounded-md w-full focus:border-purple-500 focus:ring-purple-500">
                                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                                    <i class="fas fa-save mr-1"></i> บันทึก
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">รายชื่อฝ่ายงาน</h3>
                        <ul class="divide-y divide-gray-200">
                            @foreach($divisions as $div)
                                <li class="py-4 flex justify-between items-center hover:bg-gray-50 transition-colors rounded-lg px-2">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                            <i class="{{ $div->icon_class ?? 'fas fa-folder' }}"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $div->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $div->description }}</div>
                                        </div>
                                    </div>
                                    @if(Auth::user()->role === 'super_admin')
                                    <form action="{{ route('divisions.destroy', $div->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition-colors">ลบฝ่าย</button>
                                    </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ================= TAB 3: DEPARTMENTS ================= --}}
            <div id="content-departments" class="tab-content hidden">
                @if(Auth::user()->role === 'super_admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-green-100">
                    <div class="p-6 bg-green-50">
                        <h3 class="text-lg font-bold text-green-800 mb-4"><i class="fas fa-plus-circle mr-2"></i> เพิ่มหน่วยงานย่อย</h3>
                        <form action="{{ route('departments.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <select name="division_id" class="border-gray-300 rounded-md w-full focus:border-green-500 focus:ring-green-500" required>
                                    <option value="">-- เลือกสังกัดฝ่าย --</option>
                                    @foreach($divisions as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="name" placeholder="ชื่อแผนก/งาน" class="border-gray-300 rounded-md w-full focus:border-green-500 focus:ring-green-500" required>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                    <i class="fas fa-save mr-1"></i> สร้างหน่วยงาน
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">รายชื่อหน่วยงาน</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ชื่อหน่วยงาน</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ฝ่ายสังกัด</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">จำนวนเอกสาร</th>
                                        @if(Auth::user()->role === 'super_admin')
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">จัดการ</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($departments as $dept)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $dept->name }}</td>
                                            <td class="px-6 py-4 text-gray-500">{{ $dept->division->name ?? '-' }}</td>
                                            <td class="px-6 py-4">
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2.5 py-1 rounded-full font-bold">
                                                    {{ $dept->documents_count }} ไฟล์
                                                </span>
                                            </td>
                                            @if(Auth::user()->role === 'super_admin')
                                            <td class="px-6 py-4">
                                                <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">ลบ</button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">ไม่พบข้อมูลหน่วยงาน</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= TAB 4: USERS ================= --}}
            <div id="content-users" class="tab-content hidden">
                @if(Auth::user()->role === 'super_admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-yellow-100">
                    <div class="p-6 bg-yellow-50">
                        <h3 class="text-lg font-bold text-yellow-800 mb-4"><i class="fas fa-user-plus mr-2"></i> เพิ่มผู้ใช้งานใหม่</h3>
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <input type="text" name="name" placeholder="ชื่อ-นามสกุล" class="border-gray-300 rounded-md w-full focus:border-yellow-500 focus:ring-yellow-500" required>
                                <input type="email" name="email" placeholder="Email (Login)" class="border-gray-300 rounded-md w-full focus:border-yellow-500 focus:ring-yellow-500" required>
                                <input type="text" name="password" placeholder="Password" class="border-gray-300 rounded-md w-full focus:border-yellow-500 focus:ring-yellow-500" required>
                                <select name="department_id" class="border-gray-300 rounded-md w-full focus:border-yellow-500 focus:ring-yellow-500">
                                    <option value="">-- ไม่ระบุ (เป็น Super Admin) --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition-colors shadow-sm">
                                    <i class="fas fa-check mr-1"></i> เพิ่มผู้ใช้งาน
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">รายชื่อผู้ใช้งาน</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ชื่อ-นามสกุล</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">สิทธิ์</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'super_admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $user->role === 'super_admin' ? 'Super Admin' : 'User' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if(Auth::user()->role === 'super_admin' && Auth::id() !== $user->id)
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete User?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">ลบ</button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script for Tabs --}}
    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-indigo-500', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById('content-' + tabName).classList.remove('hidden');
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-indigo-500', 'text-indigo-600');
        }
    </script>
</x-app-layout>