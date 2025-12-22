<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
    'title', 
    'filename', 
    'department_id', 
    'user_id',       // <--- ต้องมีคำนี้!
    'download_count' // <--- และคำนี้
];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // เชื่อมไปหาตาราง users
    }
}
