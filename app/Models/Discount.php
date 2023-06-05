<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Discount
 *
 * @property int $id
 * @property string $code
 * @property float $amount
 * @property Carbon|null $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Discount extends Model
{
	protected $table = 'discounts';

	protected $casts = [
		'amount' => 'float',
		'expires_at' => 'date'
	];

	protected $fillable = [
		'code',
		'amount',
		'expires_at'
	];
    public function scopeUnexpired($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
        });
    }
}
