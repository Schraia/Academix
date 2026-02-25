<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function curriculum()
    {
        return $this->hasMany(Curriculum::class);
    }

    public function collegeSections()
    {
        return $this->hasMany(CollegeSection::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
