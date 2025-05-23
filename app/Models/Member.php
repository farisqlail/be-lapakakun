<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Member extends Model
{
    protected $fillable = [
        'uuid', 'name', 'email', 'number', 'birth_date', 'address', 'image', 'password'
    ];
    
    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    
    public function getRouteKeyName()
    {
        return 'uuid';
    }  
}
