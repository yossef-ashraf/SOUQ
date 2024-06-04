<?php



namespace App\Models;

use Carbon\Carbon;
use App\Http\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


class Shipping extends Model
{
    use HasTranslation;

    public $translatable = ['city'];
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
