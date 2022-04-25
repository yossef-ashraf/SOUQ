<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class complaint extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'title_id',
        'complaint',
        'status',
        'answer'
        ];

        public function user()
        {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(
        'id',
        'first_name',
        'last_name',
        'email'
    );
        }

        public function title()
        {
        return $this->belongsTo(title_complaint::class, 'title_id', 'id')->select( 'id', 'title');
        }

}
