<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'uuid',
        'title',
        'duration',
        'max_user',
        'price',
        'logo',
        'banner',
        'scheme',
        'information',
        'benefit'
    ];

    protected $casts = [
        'benefit' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
