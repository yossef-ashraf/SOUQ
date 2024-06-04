<?php

namespace App\Models;

use Carbon\Carbon;
use App\Http\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes , HasTranslation;

    public $translatable = ['name'];
	protected $table = 'categories';

	protected $casts = [
		'offer' => 'int'
	];

	protected $fillable = [
		'name',
		'img',
		'offer'
	];

    protected $hidden = [
        'img',
    ];
    protected $appends = ['imgurl'];
    public function getImgurlAttribute()
    {
        return isset($this->attributes['img']) ?  asset($this->attributes['img']) : null ;
    }

	public function products()
	{
		return $this->hasMany(Product::class);
	}

    // public function product()
	// {
    //  return $this->hasManyThrough(
    //     Offer::class,
    //     User::class ,
    //     'user_id', // Foreign key on the types table...
    //     'offer_id', // Foreign key on the items table...
    //     'id', // Local key on the users table...
    //     'id');
	// }

}
