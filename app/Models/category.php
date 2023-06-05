<?php

namespace App\Models;

use App\Http\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{

    use HasFactory,HasTranslation;
    protected $fillable = [
    'name',
    ];

    public $translatable = ['name'];

    public function Product()
    {
        return $this->hasMany(Product::class, 'category_id', 'id')->with('ProductSize');
    }
}
