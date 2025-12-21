<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\DocumentController;


// Main Route - Unified for both Public and Admin
Route::get('/', [DocumentController::class, 'index'])->name('home');

// Division Routes
Route::get('divisions/{division}', [DocumentController::class, 'listDepartments'])->name('divisions.show');

// Public Routes
Route::get('departments/{department}', [DocumentController::class, 'show'])->name('departments.show');
Route::get('documents/download/{filename}', [DocumentController::class, 'download'])->name('documents.download');
Route::get('documents/view/{filename}', [DocumentController::class, 'viewFile'])->name('documents.view');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   // 1. จัดการ Users (เพิ่ม/ลบ ผู้ใช้งาน)
    Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::delete('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // 2. จัดการ Divisions (เพิ่ม/ลบ ฝ่ายงาน) -> ตัวที่ทำให้ Error ตอนนี้คือบรรทัดนี้ครับ
    Route::post('divisions', [App\Http\Controllers\Admin\DivisionController::class, 'store'])->name('divisions.store');
    Route::delete('divisions/{division}', [App\Http\Controllers\Admin\DivisionController::class, 'destroy'])->name('divisions.destroy');
    
    // Document CRUD Actions
    Route::get('documents/create', [AdminDocumentController::class, 'create'])->name('documents.create');
    Route::post('documents', [AdminDocumentController::class, 'store'])->name('documents.store');
    Route::get('documents/{document}/edit', [AdminDocumentController::class, 'edit'])->name('documents.edit');
    Route::put('documents/{document}', [AdminDocumentController::class, 'update'])->name('documents.update');
    Route::delete('documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');
    
    // Department CRUD Actions
    Route::get('departments/create', [AdminDepartmentController::class, 'create'])->name('departments.create');
    Route::post('departments', [AdminDepartmentController::class, 'store'])->name('departments.store');
    Route::get('departments/{department}/edit', [AdminDepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('departments/{department}', [AdminDepartmentController::class, 'update'])->name('departments.update');
    Route::delete('departments/{department}', [AdminDepartmentController::class, 'destroy'])->name('departments.destroy');
});

require __DIR__.'/auth.php';
