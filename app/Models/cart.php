<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Cart extends Model
{
	protected $table = 'carts';

	protected $casts = [
		'user_id' => 'int',
		'product_id' => 'int',
		'product_detail_id' => 'int',
		'product_size_id' => 'int',
		'count' => 'int',
		'soldout' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'product_id',
		'product_detail_id',
		'product_size_id',
		'count',
		'soldout'
	];

	public function product_detail()
	{
		return $this->belongsTo(ProductDetail::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function product_size()
	{
		return $this->belongsTo(ProductSize::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
