<?php

namespace App\Models;

use App\Http\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory,HasTranslation;
    protected $fillable = [
    'name',
    'description',
    'discount',
    'state',
    'image',
    'quantity',
    'price',
    'category_id'
];

public $translatable = ['name', 'description'];




}
