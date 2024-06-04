<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model
{
    use SoftDeletes ;
	protected $table = 'product_details';

	protected $casts = [
		'product_id' => 'int'
	];

	protected $fillable = [
		'product_id',
		'color'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function carts()
	{
		return $this->hasMany(Cart::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function product_images()
	{
		return $this->hasMany(ProductImage::class);
	}

	public function product_sizes()
	{
		return $this->hasMany(ProductSize::class);
	}
    public function product_sizes_trash()
	{
		return $this->hasMany(ProductSize::class)->withTrashed();
	}
}
