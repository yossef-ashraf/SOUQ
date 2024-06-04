<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class WalletHistory extends Model
{
	protected $table = 'wallet_histories';

	protected $casts = [
		'user_wallet_id' => 'int'
	];

	protected $fillable = [
		'user_wallet_id',
		'value'
	];

	public function user_wallet()
	{
		return $this->belongsTo(UserWallet::class);
	}
}
