<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductImage extends Model
{
    use SoftDeletes ;
	protected $table = 'product_images';

	protected $casts = [
		'product_detail_id' => 'int'
	];

	protected $fillable = [
		'image',
		'product_detail_id'
	];

    protected $hidden = [
        'image',
    ];
    protected $appends = ['imgurl'];
    public function getImgurlAttribute()
    {
        return isset($this->attributes['image']) ?  asset($this->attributes['image']) : null ;
    }

	public function product_detail()
	{
		return $this->belongsTo(ProductDetail::class);
	}
}
