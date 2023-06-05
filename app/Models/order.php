<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status_order',
        'total_price',
        'streetAddress',
        'discountcode',
        'city',
        'state',
        'specialMark',
        'paymentMethod'
    ];

    public function order_item()
    {
        return $this->hasMany(OrderItem::class, 'order_id','id');
    }
}
