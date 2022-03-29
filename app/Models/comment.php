<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
use HasFactory;

protected $fillable = [
'user_id',
'product_id',
'comments'
];

public function user()
{
return $this->belongsTo(user::class, 'user_id', 'id')->select( 'id', 'name' , 'email');
}

public function product()
{   
return $this->belongsTo(product::class, 'product_id', 'id')->select( 'id', 'name' , 'img' );
}
}
