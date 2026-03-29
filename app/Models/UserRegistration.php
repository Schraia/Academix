<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'age',
        'nationality',
        'gender',
        'contact_number',
        'address_line',
        'city',
        'province',
        'zip_code',
        'guardian_name',
        'guardian_contact_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

