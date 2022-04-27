<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
use HasFactory;


protected $fillable = [
'name',
'img',
'price',
'stock',
'status',
'discount',
'desc',
'category_id'
];

public function category()
{
return $this->belongsTo(category::class, 'category_id', 'id')->select( 'id', 'name' );
}

public function images(){

    return $this->hasMany(product_image::class, 'product_id', 'id');

}

}
