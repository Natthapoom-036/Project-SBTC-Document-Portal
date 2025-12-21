<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name',
    'division_id'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
