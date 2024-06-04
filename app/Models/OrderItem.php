<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class OrderItem extends Model
{
	protected $table = 'order_items';

	protected $casts = [
		'order_id' => 'int',
		'product_detail_id' => 'int',
		'product_size_id' => 'int',

		'count' => 'float',
		'price' => 'float',
		'discount' => 'float',
		'sub_total' => 'float',
		'total_price' => 'float'
	];

	protected $fillable = [
		'order_id',
		'product_detail_id',
		'product_size_id',
		'count',
		'price',
		'discount',
		'sub_total',
		'total_price'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function product_detail()
	{
		return $this->belongsTo(ProductDetail::class);
	}

	public function product_size()
	{
		return $this->belongsTo(ProductSize::class);
	}

    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            ProductDetail::class,
            'id', // الخاص بنموذج OrderItem
            'id', // الخاص بنموذج ProductDetail
            'product_detail_id', // الخاص بنموذج OrderItem في ProductDetail
            'product_id' // الخاص بنموذج Product
        );
    }
}
