<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController; // <--- เรียกใช้ Controller หน้าแรกที่เราสร้าง
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DivisionController;


// 1. หน้าแรก (Public) - เข้าได้ทุกคน ไม่ต้อง Login
// แก้ไข: ให้เรียกผ่าน HomeController เพื่อให้นับสถิติได้
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. หน้าดูข้อมูลย่อย (Public)
// ดูรายชื่อแผนกในฝ่าย
Route::get('divisions/{division}', [DocumentController::class, 'listDepartments'])->name('divisions.show');
// ดูเอกสารในแผนก
Route::get('departments/{department}', [DocumentController::class, 'show'])->name('departments.show');
// โหลด/ดูไฟล์
Route::get('documents/download/{filename}', [DocumentController::class, 'download'])->name('documents.download');
Route::get('documents/view/{filename}', [DocumentController::class, 'viewFile'])->name('documents.view');


// 3. โซน Admin (ต้อง Login เท่านั้น)
Route::middleware(['auth'])->group(function () {
    
    // หน้า Dashboard ของ Admin (หน้าที่เราทำระบบกรองสิทธิ์ไว้)
    Route::get('/dashboard', [DocumentController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // จัดการ Users
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // จัดการ Divisions
    Route::post('divisions', [DivisionController::class, 'store'])->name('divisions.store');
    Route::delete('divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');
    
    // Document CRUD (จัดการเอกสาร - Admin)
    Route::get('documents/create', [AdminDocumentController::class, 'create'])->name('documents.create');
    Route::post('documents', [AdminDocumentController::class, 'store'])->name('documents.store');
    Route::get('documents/{document}/edit', [AdminDocumentController::class, 'edit'])->name('documents.edit');
    Route::put('documents/{document}', [AdminDocumentController::class, 'update'])->name('documents.update');
    Route::delete('documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');
    
    // Department CRUD (จัดการหน่วยงาน - Admin)
    Route::get('departments/create', [AdminDepartmentController::class, 'create'])->name('departments.create');
    Route::post('departments', [AdminDepartmentController::class, 'store'])->name('departments.store');
    Route::get('departments/{department}/edit', [AdminDepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('departments/{department}', [AdminDepartmentController::class, 'update'])->name('departments.update');
    Route::delete('departments/{department}', [AdminDepartmentController::class, 'destroy'])->name('departments.destroy');
});

require __DIR__.'/auth.php';