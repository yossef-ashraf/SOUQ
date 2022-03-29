<?php

namespace App\Models;

use App\Models\product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class order_item extends Model
{


use HasFactory;


protected $fillable = [ 'order_id', 'product_id', 'count', 'total_price'];


public function products()
{
return $this->belongsTo(product::class, 'product_id', 'id')->select( 'id', 'name', 'img', 'price','discount', 'desc', 'categorie_id');
}

}
