<?php



namespace App\Models;

use Carbon\Carbon;
use App\Http\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
	use SoftDeletes, HasTranslation;

    public $translatable = [
     'name',
    'description',
    'material'
];

	protected $table = 'products';

	protected $casts = [
		'category_id' => 'int',
		'brand_id' => 'int',
		'discount' => 'int',
		'has_discount_category' => 'bool',
		'has_discount_brand' => 'bool',
		'like_num' => 'int',
		'rate_average' => 'int',
		'rate_num' => 'int'
	];

	protected $fillable = [
        'name',
		'description',
		'material',
		'img',
        'general_price',
        'views',
        
		'category_id',
		'brand_id',

		'discount',
		'has_discount_category',
		'has_discount_brand',

		'like_num',
		'rate_average',
		'rate_num'
	];
    protected $hidden = [
        'img',
    ];
    protected $appends = ['imgurl'];
    public function getImgurlAttribute()
    {
        return isset($this->attributes['img']) ?  asset($this->attributes['img']) : null ;
    }
	public function brand()
	{
		return $this->belongsTo(Brand::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function blogs()
	{
		return $this->hasMany(Blog::class);
	}

	public function carts()
	{
		return $this->hasMany(Cart::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function favs()
	{
		return $this->hasMany(Fav::class);
	}

	public function product_details()
	{
		return $this->hasMany(ProductDetail::class);
	}

	public function rates()
	{
		return $this->hasMany(Rate::class);
	}

    public function myRate()
	{
        $id= isset(auth()->user()->id) ? auth()->user()->id : 0;
        return $this->hasMany(Rate::class)->where('user_id', $id);
		// return $this->hasMany(Rate::class);
	}
    public function myComment()
	{
        $id= isset(auth()->user()->id) ? auth()->user()->id : 0;
        return $this->hasMany(Comment::class)->where('user_id', $id);
    }

    public function myFav()
	{
        $id= isset(auth()->user()->id) ? auth()->user()->id : 0;
        return $this->hasOne(Fav::class)->where('user_id', $id) ;
	}

    public function brand_trash()
    {
        return $this->belongsTo(Brand::class)->withTrashed();
    }
    public function category_trash()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }
    public function blogs_trash()
    {
        return $this->hasMany(Blog::class)->withTrashed();
    }
    public function product_details_trash()
    {
        return $this->hasMany(ProductDetail::class)->withTrashed();
    }

}
