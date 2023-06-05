<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment',
        'user_id',
        'product_id',
        'status',
    ];

    public function User()
    {
    return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function Product()
    {
    return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
