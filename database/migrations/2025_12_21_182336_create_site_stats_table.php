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
    // เช็คก่อนว่ามีตารางไหม ถ้ามีแล้วให้ข้ามไปเลย (กัน Error)
    if (!Schema::hasTable('site_stats')) {
        Schema::create('site_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('total_visits')->default(0);
            $table->timestamps();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_stats');
    }
};
