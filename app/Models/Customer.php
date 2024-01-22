<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'username',
        'gender',
        'country',
        'city',
        'phone',
        'password',
    ];

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getEmail()
    {
        return $this->attributes['email'];
    }
}
