<?php

namespace App\Models;

use App\Models\category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class department extends Model
{
use HasFactory;
protected $fillable = [
'name',
'status'
];

public function category()
{
return $this->hasMany(category::class, 'department_id', 'id');
}
}
