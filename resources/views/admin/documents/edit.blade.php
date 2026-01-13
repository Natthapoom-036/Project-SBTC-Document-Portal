<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fas fa-edit text-indigo-500"></i> {{ __('แก้ไขเอกสาร') }}
        </h2>
    </x-slot>

    {{-- 🔥 CSS แต่งปุ่มพิเศษ (แก้ปัญหาปุ่มล่องหน) 🔥 --}}
    <style>
        .edit-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 30px;
            border: 1px solid #e5e7eb;
        }

        .form-label {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 10px 15px;
            transition: all 0.2s;
        }
        .form-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        /* ปุ่มบันทึก (สีน้ำเงิน) */
        .btn-save {
            background-color: #2563eb !important;
            color: white !important;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
        }
        .btn-save:hover { background-color: #1d4ed8 !important; transform: translateY(-1px); }

        /* ปุ่มยกเลิก (สีเทา) */
        .btn-cancel {
            background-color: #f3f4f6 !important;
            color: #4b5563 !important;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid #e5e7eb;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
        }
        .btn-cancel:hover { background-color: #e5e7eb !important; color: #1f2937 !important; }
        
        .current-file-box {
            background: #eff6ff;
            border: 1px dashed #bfdbfe;
            padding: 10px;
            border-radius: 8px;
            color: #1e40af;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="edit-card">
                
                {{-- หัวข้อใน Card --}}
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-800">รายละเอียดเอกสาร</h3>
                    <p class="text-sm text-gray-500">แก้ไขข้อมูลหรืออัปโหลดไฟล์ใหม่ทับของเดิม</p>
                </div>

                <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- 1. ชื่อเอกสาร --}}
                    <div class="mb-6">
                        <label class="form-label">ชื่อเอกสาร</label>
                        <input type="text" name="title" value="{{ old('title', $document->title) }}" class="form-input" required>
                    </div>

                    {{-- 2. ไฟล์เอกสาร --}}
                    <div class="mb-6">
                        <label class="form-label">ไฟล์แนบ</label>
                        
                        {{-- แสดงชื่อไฟล์เดิม --}}
                        <div class="current-file-box">
                            <i class="fas fa-file-alt"></i>
                            <div>
                                <span class="font-bold">ไฟล์ปัจจุบัน:</span> {{ $document->filename }}
                            </div>
                        </div>

                        <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg cursor-pointer">
                        <p class="text-xs text-gray-500 mt-2">* อัปโหลดเฉพาะเมื่อต้องการเปลี่ยนไฟล์ใหม่ (ถ้าไม่เลือก จะใช้ไฟล์เดิม)</p>
                    </div>

                    {{-- 3. เลือกหน่วยงาน (เฉพาะ Admin หรือเจ้าของที่ได้รับสิทธิ์) --}}
                    @if(Auth::user()->role === 'super_admin')
                        <div class="mb-8">
                            <label class="form-label">หน่วยงานเจ้าของเอกสาร</label>
                            <select name="department_id" class="form-input">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $document->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} ({{ $dept->division->name ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- ปุ่ม Action --}}
                    <div class="flex items-center gap-3 pt-4 border-t">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> บันทึกการแก้ไข
                        </button>

                        <a href="{{ route('dashboard') }}" class="btn-cancel">
                            <i class="fas fa-times"></i> ยกเลิก
                        </a>
                    </div>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>