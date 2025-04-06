<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = ['uuid', 'bank_name', 'account', 'name'];

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
