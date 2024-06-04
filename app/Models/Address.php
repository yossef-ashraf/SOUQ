<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Address extends Model
{
	use SoftDeletes;
	protected $table = 'addresses';

	protected $casts = [
		'user_id' => 'int',
		'building_number' => 'int',
		'flat_number' => 'int'
	];

	protected $fillable = [
		'user_id',
		'city',
		'address',
		'street_name',
		'building_number',
		'flat_number'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
