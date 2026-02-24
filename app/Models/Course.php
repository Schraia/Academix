<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'block_section_id',
        'code',
        'credits',
        'status',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function blockSection()
    {
        return $this->belongsTo(BlockSection::class);
    }

    public function curriculum()
    {
        return $this->hasMany(Curriculum::class);
    }
}

