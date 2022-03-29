<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class answer_complaint extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'complaint_id',
        'answer'
        ];


        public function user()
        {
        return $this->belongsTo(user::class, 'user_id', 'id')->select( 'id', 'name' , 'email');
        }

        public function complaint()
        {
        return $this->belongsTo(complaint::class, 'complaint_id', 'id')->select( 'id', 'complaint');
        }

}
