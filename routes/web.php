<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DocumentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ใน routes/web.php (ส่วนของ Admin Middleware)
Route::middleware(['auth'])->group(function () {
    // Route สำหรับ Admin (CRUD เอกสาร)
    Route::resource('admin/documents', DocumentController::class);

    // Route สำหรับ CRUD ฝ่ายงาน (optional)
    // ถ้าคุณยังไม่ได้สร้าง DepartmentController ให้ Comment Out บรรทัดนี้ไว้ก่อน
    // Route::resource('admin/departments', DepartmentController::class);
});

require __DIR__.'/auth.php';
