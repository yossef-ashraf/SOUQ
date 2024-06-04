<?php



namespace App\Models;

use Carbon\Carbon;
use App\Http\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Blog extends Model
{
	use SoftDeletes, HasTranslation;

    public $translatable = [
    'title',
    'description',
    'content'
];
	protected $table = 'blogs';

	protected $casts = [
		'product_id' => 'int'
	];

	protected $fillable = [
		'product_id',
		'img',
		'title',
		'description',
		'content'
	];
    protected $hidden = [
        'img',
    ];
    protected $appends = ['imgurl'];
    public function getImgurlAttribute()
    {
        return isset($this->attributes['img']) ?  asset($this->attributes['img']) : null ;
    }
	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
