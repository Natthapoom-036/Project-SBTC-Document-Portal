<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Tabs Navigation --}}
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                        <button onclick="showTab('documents')" id="tab-documents" class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-file-pdf mr-2"></i> เอกสาร
                        </button>
                        <button onclick="showTab('divisions')" id="tab-divisions" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-building mr-2"></i> ฝ่ายงาน
                        </button>
                        <button onclick="showTab('departments')" id="tab-departments" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-folder mr-2"></i> หน่วยงาน
                        </button>
                        <button onclick="showTab('users')" id="tab-users" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-users mr-2"></i> ผู้ใช้งาน
                        </button>
                    </nav>
                </div>
            </div>

            {{-- ================= TAB 1: DOCUMENTS ================= --}}
            <div id="content-documents" class="tab-content">
                {{-- Form Upload (Available to everyone, logic inside) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-cloud-upload-alt mr-2 text-indigo-600"></i> อัพโหลดเอกสารใหม่
                        </h3>
                        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ชื่อเอกสาร</label>
                                <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="ระบุชื่อเอกสาร...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">เลือกไฟล์ (PDF/Docx)</label>
                                <input type="file" name="file" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>

                            {{-- Department Selection Logic --}}
                            @if(Auth::user()->role === 'super_admin' || is_null(Auth::user()->department_id))
                                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-md">
                                    <p class="text-sm text-yellow-700 mb-2"><strong>Super Admin:</strong> กรุณาเลือกหน่วยงานปลายทาง</p>
                                    <select name="department_id" required class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                        <option value="">-- เลือกหน่วยงาน --</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->division->name ?? '-' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">
                                <div class="p-2 bg-green-50 rounded-md text-sm text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i> กำลังอัปโหลดลง: <strong>{{ Auth::user()->department->name ?? 'หน่วยงานของคุณ' }}</strong>
                                </div>
                            @endif

                            <div class="pt-2">
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i> อัปโหลดเอกสาร
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Documents Table --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">เอกสารทั้งหมด</h3>
                        @if($documents->isEmpty())
                            <p class="text-center text-gray-500 py-4">ไม่พบเอกสาร</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ชื่อเอกสาร</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">หน่วยงาน</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">การดำเนินการ </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($documents as $doc)
                                            <tr>
                                                <td class="px-6 py-4 font-medium text-gray-900">{{ $doc->title }}</td>
                                                <td class="px-6 py-4 text-gray-500">{{ $doc->department->name ?? '-' }}</td>
                                                <td class="px-6 py-4 text-sm font-medium">
                                                    <a href="{{ route('documents.download', $doc->filename) }}" class="text-blue-600 hover:text-blue-900 mr-3">ดาวน์โหลด</a>
                                                    {{-- อนุญาตให้ Super Admin หรือ เจ้าของไฟล์ แก้ไขได้ --}}
                                                    @if(Auth::user()->role === 'super_admin' || Auth::id() === $doc->user_id)
                                                        <a href="{{ route('documents.edit', $doc->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">
                                                            <i class="fas fa-edit"></i> แก้ไข
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">ลบ</button>
                                                    </form>
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
                {{-- Create Form (Super Admin Only) --}}
                @if(Auth::user()->role === 'super_admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-purple-50 border border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-800 mb-4"><i class="fas fa-plus-circle mr-2"></i> เพิ่มฝ่ายงานใหม่</h3>
                        <form action="{{ route('divisions.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <input type="text" name="name" placeholder="ชื่อฝ่าย (เช่น ฝ่ายวิชาการ)" class="border-gray-300 rounded-md w-full" required>
                                <input type="text" name="description" placeholder="คำบรรยายสั้นๆ" class="border-gray-300 rounded-md w-full">
                                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Divisions Table --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">รายชื่อฝ่ายงาน</h3>
                        <ul class="divide-y divide-gray-200">
                            @foreach($divisions as $div)
                                <li class="py-3 flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                            <i class="{{ $div->icon_class ?? 'fas fa-folder' }}"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $div->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $div->description }}</div>
                                        </div>
                                    </div>
                                    @if(Auth::user()->role === 'super_admin')
                                    <form action="{{ route('divisions.destroy', $div->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
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
                {{-- Create Form (Super Admin Only) --}}
                @if(Auth::user()->role === 'super_admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-green-50 border border-green-200">
                        <h3 class="text-lg font-semibold text-green-800 mb-4"><i class="fas fa-plus-circle mr-2"></i> เพิ่มหน่วยงานย่อย</h3>
                        <form action="{{ route('departments.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <select name="division_id" class="border-gray-300 rounded-md w-full" required>
                                    <option value="">-- เลือกสังกัดฝ่าย --</option>
                                    @foreach($divisions as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="name" placeholder="ชื่อแผนก/งาน" class="border-gray-300 rounded-md w-full" required>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">สร้างหน่วยงาน</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Departments Table --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">รายชื่อหน่วยงาน</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ชื่อหน่วยงาน</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ฝ่ายสังกัด</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">เอกสาร</th>
                                        @if(Auth::user()->role === 'super_admin')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">การดำเนินการ</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($departments as $dept)
                                        <tr>
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $dept->name }}</td>
                                            <td class="px-6 py-4 text-gray-500">{{ $dept->division->name ?? '-' }}</td>
                                            <td class="px-6 py-4"><span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $dept->documents_count }}</span></td>
                                            @if(Auth::user()->role === 'super_admin')
                                            <td class="px-6 py-4">
                                                <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">ลบ</button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">ไม่พบข้อมูล</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= TAB 4: USERS ================= --}}
            <div id="content-users" class="tab-content hidden">
                {{-- Create Form (Super Admin Only) --}}
                @if(Auth::user()->role === 'super_admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-yellow-50 border border-yellow-200">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-4"><i class="fas fa-user-plus mr-2"></i> เพิ่มผู้ใช้งานใหม่</h3>
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <input type="text" name="name" placeholder="ชื่อ-นามสกุล" class="border-gray-300 rounded-md w-full" required>
                                <input type="email" name="email" placeholder="Email (Login)" class="border-gray-300 rounded-md w-full" required>
                                <input type="text" name="password" placeholder="Password" class="border-gray-300 rounded-md w-full" required>
                                <select name="department_id" class="border-gray-300 rounded-md w-full">
                                    <option value="">-- ไม่ระบุ (เป็น Super Admin) --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700">เพิ่มผู้ใช้งาน</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Users Table --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">รายชื่อผู้ใช้งาน</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ชื่อ-นามสกุล</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">สิทธิ์</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4">{{ $user->name }}</td>
                                            <td class="px-6 py-4">{{ $user->email }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'super_admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $user->role === 'super_admin' ? 'Super Admin' : 'User' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if(Auth::user()->role === 'super_admin' && Auth::id() !== $user->id)
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete User?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">ลบ</button>
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