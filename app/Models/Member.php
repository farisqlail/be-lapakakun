<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name', 'email', 'number', 'birth_date', 'address', 'image', 'password'
    ];
    
    protected $hidden = [
        'password',
    ];    
}
