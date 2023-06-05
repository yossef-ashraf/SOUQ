<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Shipping
 *Shippingontroller
 * @property int $id
 * @property string $city
 * @property int $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Shipping extends Model
{
	protected $table = 'shippings';

	protected $casts = [
		'price' => 'int'
	];

	protected $fillable = [
		'city',
		'price'
	];

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
