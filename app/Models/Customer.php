<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'username',
        'gender',
        'country',
        'city',
        'phone',
        'clear_password',
    ];

    public function setClearPasswordAttribute($value)
    {
        $this->attributes['clear_password'] = md5($value);
    }
}
