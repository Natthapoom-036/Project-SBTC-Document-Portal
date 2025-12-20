<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'icon_class'];

    // ต้องมีฟังก์ชันนี้ ระบบถึงจะดึงแผนกย่อยออกมาได้
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}