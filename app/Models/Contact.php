<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	protected $table = 'contacts';

	protected $fillable = [
		'firstname',
		'lastname',
		'email',
		'message'
	];
}
