<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // เพิ่มช่อง department_id และตั้งให้เป็น NULL ได้ (สำหรับ Super Admin ที่คุมทุกอย่าง)
        $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
        // เพิ่มระดับของ User (0 = Admin แผนก, 1 = Super Admin)
        $table->integer('role')->default(0); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
