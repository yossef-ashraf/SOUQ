<?php

namespace App\Models;

use App\Models\order_item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status_order',
        'total_price'
    ];

    public function order_item()
    {
        return $this->hasMany(order_item::class, 'order_id', 'id')->select('id','order_id', 'product_id', 'count', 'total_price')->with('products');
    }

}
