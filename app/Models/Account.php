<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'id_product',
        'email',
        'password',
        'due_date',
        'number',
        'stock',
        'link'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_account');
    }
}
