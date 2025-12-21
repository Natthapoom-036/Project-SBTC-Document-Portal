<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                        <button onclick="showTab('documents')" id="tab-documents" class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-file-pdf mr-2"></i> Documents
                        </button>
                        <button onclick="showTab('divisions')" id="tab-divisions" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-building mr-2"></i> Divisions (ฝ่าย)
                        </button>
                        <button onclick="showTab('departments')" id="tab-departments" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-folder mr-2"></i> Departments (งาน)
                        </button>
                        <button onclick="showTab('users')" id="tab-users" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-users mr-2"></i> Users
                        </button>
                    </nav>
                </div>
            </div>

            <div id="content-documents" class="tab-content">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-cloud-upload-alt mr-2 text-indigo-600"></i> อัพโหลดเอกสารใหม่
                        </h3>
                        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf

    {{-- 1. ช่องชื่อเอกสาร (ต้องมีทุกคน) --}}
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">ชื่อเอกสาร</label>
        <input type="text" name="title" id="title" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
               placeholder="ระบุชื่อเอกสาร...">
    </div>

    {{-- 2. ช่องเลือกไฟล์ (ต้องมีทุกคน) --}}
    <div>
        <label for="file" class="block text-sm font-medium text-gray-700">เลือกไฟล์ (PDF/Docx)</label>
        <input type="file" name="file" id="file" required
               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    </div>

    {{-- 3. ช่องเลือกแผนก (เฉพาะ Super Admin เท่านั้นที่เห็น) --}}
    @if(Auth::user()->role === 'super_admin' || is_null(Auth::user()->department_id))
        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        คุณคือ <strong>Super Admin</strong> กรุณาเลือกหน่วยงานที่จะนำเอกสารนี้ไปฝากไว้
                    </p>
                    <select name="department_id" required class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">-- กรุณาเลือกหน่วยงานปลายทาง --</option>
                        @foreach($departments as $dept)
                            {{-- แสดงชื่อแผนก และชื่อฝ่ายงานในวงเล็บ --}}
                            <option value="{{ $dept->id }}">
                                {{ $dept->name }} ({{ $dept->division->name ?? 'ไม่ระบุฝ่าย' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @else
        {{-- ถ้าเป็น User ธรรมดา ให้แอบส่งค่า department_id ของตัวเองไปเงียบๆ --}}
        <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">
        <div class="p-2 bg-green-50 rounded-md text-sm text-green-700">
            <i class="fas fa-check-circle mr-1"></i> 
            กำลังอัปโหลดลง: <strong>{{ Auth::user()->department->name ?? 'หน่วยงานของคุณ' }}</strong>
        </div>
    @endif

    {{-- 4. ปุ่มอัปโหลด (ต้องมีทุกคน!) --}}
    <div class="pt-2">
        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-cloud-upload-alt mr-2"></i> อัปโหลดเอกสาร
        </button>
    </div>

</form>
                    </div>
                </div>

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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($documents as $doc)
                                            <tr>
                                                <td class="px-6 py-4 font-medium text-gray-900">{{ $doc->title }}</td>
                                                <td class="px-6 py-4 text-gray-500">{{ $doc->department->name }}</td>
                                                <td class="px-6 py-4 text-sm font-medium">  
                                                    <a href="{{ route('documents.download', $doc->filename) }}" class="text-blue-600 hover:text-blue-900 mr-3">ดาวน์โหลด</a>
                                                    <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this document?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
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

            <div id="content-divisions" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4"><i class="fas fa-plus-circle mr-2 text-purple-600"></i> Create Main Division</h3>
                        <form action="{{ route('divisions.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <input type="text" name="name" placeholder="ชื่อฝ่าย (เช่น ฝ่ายวิชาการ)" class="border-gray-300 rounded-md shadow-sm w-full" required>
                                <input type="text" name="description" placeholder="คำบรรยายสั้นๆ" class="border-gray-300 rounded-md shadow-sm w-full">
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 shadow-md transition duration-150">
                                    <i class="fas fa-plus-circle mr-2"></i> บันทึกฝ่ายงาน
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Existing Divisions</h3>
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
                                    <form action="{{ route('divisions.destroy', $div->id) }}" method="POST" onsubmit="return confirm('Delete Division?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div id="content-departments" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4"><i class="fas fa-plus-circle mr-2 text-green-600"></i> Create Department</h3>
                        <form action="{{ route('departments.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Under Division <span class="text-red-500">*</span></label>
                                    <select name="division_id" class="border-gray-300 rounded-md shadow-sm w-full" required>
                                        <option value="">-- เลือกสังกัดฝ่าย --</option>
                                        @foreach($divisions as $div)
                                            <option value="{{ $div->id }}">{{ $div->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Department Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" placeholder="ชื่อแผนก/งาน" class="border-gray-300 rounded-md shadow-sm w-full" required>
                                </div>
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 shadow-md transition duration-150">
                                    <i class="fas fa-plus-circle mr-2"></i> สร้างหน่วยงาน
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Existing Departments</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent Division</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documents</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($departments as $dept)
                                        <tr>
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $dept->name }}</td>
                                            <td class="px-6 py-4 text-gray-500">{{ $dept->division->name ?? '-' }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $dept->documents_count }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium">
                                                <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content-users" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4"><i class="fas fa-user-plus mr-2 text-yellow-600"></i> Add New User</h3>
                        <form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
            <input type="text" name="name" placeholder="Name" class="border-gray-300 rounded-md shadow-sm w-full" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">สังกัดหน่วยงาน</label> 
            <select name="department_id" class="border-gray-300 rounded-md shadow-sm w-full">
                <option value="">-- ไม่ระบุ (Super Admin) --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->division->name }})</option>
                @endforeach
    </select>
</div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล (Login) <span class="text-red-500">*</span></label>
            <input type="email" name="email" placeholder="Email" class="border-gray-300 rounded-md shadow-sm w-full" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน <span class="text-red-500">*</span></label>
            <input type="text" name="password" placeholder="Password" class="border-gray-300 rounded-md shadow-sm w-full" required>
        </div>
    </div>

    {{-- ปุ่มกดอยู่ตรงนี้ --}}
    <div class="flex justify-end pt-2">
        <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 shadow-md transition duration-150">
            <i class="fas fa-user-plus mr-2"></i> เพิ่มผู้ใช้งาน
        </button>
    </div>
</form>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ $user->role }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete User?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                        </form>
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

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active styles from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-indigo-500', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active styles to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-indigo-500', 'text-indigo-600');
        }
    </script>
</x-app-layout>