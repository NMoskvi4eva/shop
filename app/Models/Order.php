<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Спеціальний масив $fillable дозволяє Laravel масово записувати дані в ці поля
    protected $fillable = [
        'name',
        'phone',
        'email',
        'comment',
        'total_amount',
        'status',
        'liqpay_order_id',
        'products_list'
    ];
}