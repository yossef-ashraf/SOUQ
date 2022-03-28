<?php

namespace App\Models;

use App\Models\product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class cart extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'product_id',
        'count'
    ];
    public function products()
    {
        return $this->belongsTo(product::class, 'product_id', 'id')->select(
        'id',
        'name',
        'img',
        'price',
        'stock',
        'status',
        'discount',
        'desc');
    }
}
