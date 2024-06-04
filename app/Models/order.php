<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{
	use SoftDeletes;
	protected $table = 'orders';

	protected $casts = [
		'user_id' => 'int',
		'shipping_id' => 'int',
		'address_id' => 'int',
		'discount' => 'float',
		'sub_total' => 'float',
		'total_price' => 'float'
	];

	protected $fillable = [
		'user_id',
		'shipping_id',
		'address_id',
		'payment_type',
		'promo',

		'discount',
		'sub_total',
		'total_price',
		'driver_by',
        'pay_id',
        'phone',
		'status'
	];

	public function address()
	{
		return $this->belongsTo(Address::class);
	}

	public function shipping()
	{
		return $this->belongsTo(Shipping::class);
	}

	public function user()
	{
        return $this->belongsTo(User::class)->select(['id','name', 'email', 'phone']);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function order_returns()
	{
		return $this->hasMany(OrderReturn::class);
	}
}
