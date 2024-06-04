<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductSize extends Model
{
    use SoftDeletes;
	protected $table = 'product_sizes';

	protected $casts = [
		'product_detail_id' => 'int',
		'quantity' => 'int',
		'price' => 'int',
		'sell_price' => 'int',
		'order' => 'float',
		'order_return' => 'float',
		'roi' => 'float'
	];

	protected $fillable = [
		'product_detail_id',
		'size',
		'quantity',
        'base_price',
		'price',
		'sell_price',
		'order',
		'order_return',
		'roi'
	];

	public function product_detail()
	{
		return $this->belongsTo(ProductDetail::class);
	}

	public function carts()
	{
		return $this->hasMany(Cart::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}
}
