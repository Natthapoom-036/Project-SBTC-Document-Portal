<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            แก้ไขเอกสาร: {{ $document->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- ชื่อเอกสาร --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">ชื่อเอกสาร</label>
                            <input type="text" name="title" value="{{ $document->title }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- เลือกแผนก (เฉพาะ Super Admin แก้ได้) --}}
                        @if(Auth::user()->role === 'super_admin')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">หน่วยงาน</label>
                            <select name="department_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $document->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} ({{ $dept->division->name ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- ไฟล์แนบ (ถ้าไม่เปลี่ยน ไม่ต้องเลือก) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">ไฟล์เอกสาร (อัปโหลดใหม่เพื่อเปลี่ยนไฟล์เดิม)</label>
                            <input type="file" name="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:bg-indigo-50 file:text-indigo-700">
                            <p class="text-xs text-gray-500 mt-1">ไฟล์ปัจจุบัน: {{ $document->filename }}</p>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">ยกเลิก</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">บันทึกการแก้ไข</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>