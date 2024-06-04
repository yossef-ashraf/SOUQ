<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Rate extends Model
{
	protected $table = 'rates';

	protected $casts = [
		'rate' => 'int',
		'user_id' => 'int',
		'product_id' => 'int'
	];

	protected $fillable = [
		'rate',
		'user_id',
		'product_id'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
