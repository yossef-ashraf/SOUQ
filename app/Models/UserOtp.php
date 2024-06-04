<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class UserOtp extends Model
{
	protected $table = 'user_otps';

	protected $casts = [
		'user_id' => 'int',
		'expire_at' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'otp',
		'expire_at'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
