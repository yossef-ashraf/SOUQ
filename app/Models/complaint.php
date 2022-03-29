<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class complaint extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'complaint'
        ];

        public function user()
        {
        return $this->belongsTo(User::class, 'user_id', 'id')->select( 'id', 'name' , 'email');
        }

}
