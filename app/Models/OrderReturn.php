<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrderReturn extends Model
{
	use SoftDeletes;
	protected $table = 'order_returns';

	protected $casts = [
		'order_id' => 'int',
		'return' => 'bool',
		'return_price' => 'float'
	];

	protected $fillable = [
		'order_id',
		'reason',
		'return',
		'return_price'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}
}
