<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Promo extends Model
{
	use SoftDeletes;
	protected $table = 'promos';

	protected $casts = [
		'value' => 'float',
		'min_value' => 'float',
		'max_value' => 'float',
		'expires_at' => 'datetime'
	];

	protected $fillable = [
		'name',
		'value',
		'min_value',
		'max_value',
		'promo',
		'expires_at',
		'desc',
		'type'
	];
}
