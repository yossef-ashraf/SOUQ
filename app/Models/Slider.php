<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $table = 'sliders';

	protected $fillable = [
		'image',
    ];
    protected $hidden = [
        'image',
    ];
    protected $appends = ['imgurl'];
    public function getImgurlAttribute()
    {
        return isset($this->attributes['image']) ?  asset($this->attributes['image']) : null ;
    }
}
