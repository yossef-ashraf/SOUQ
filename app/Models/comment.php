<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'comments';

	protected $casts = [
		'user_id' => 'int',
		'product_id' => 'int'
	];

	protected $fillable = [
		'comment',
		'user_id',
		'product_id'
	];
    protected $appends = ['name'];
    public function getNameAttribute()
    {

        $user = $this->user()->select('name')->first();

        if ($user) {
            return $user->makeHidden(['roles', 'permissions']);
        }

        return null; // أو يمكنك تعيين قيمة افتراضية أخرى إذا لم يتم العثور على المستخدم
    }
	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
