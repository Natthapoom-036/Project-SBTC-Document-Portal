<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('department_id')->constrained()->onDelete('cascade'); // เชื่อมกับตาราง departments
        $table->string('title');         // ชื่อเอกสาร
        $table->string('filename');      // ชื่อไฟล์ที่เก็บใน storage
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
