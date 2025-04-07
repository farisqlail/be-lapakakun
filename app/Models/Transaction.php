<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'id_account',
        'uuid',
        'transaction_code',
        'customer_name',
        'customer_number',
        'id_product',
        'id_category',
        'status_payment',
        'discount',
        'total_price'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->transaction_code = strtoupper(Str::random(5));
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'id_account'); // pastikan kolomnya sesuai
    }
}
