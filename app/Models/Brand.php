<?php



namespace App\Models;

use Carbon\Carbon;
use App\Http\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
	use SoftDeletes, HasTranslation;

    public $translatable = ['name'];
	protected $table = 'brands';

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
}
