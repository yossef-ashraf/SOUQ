<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class UserWallet extends Model
{
	protected $table = 'user_wallets';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'wallet'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function wallet_histories()
	{
		return $this->hasMany(WalletHistory::class);
	}
}
