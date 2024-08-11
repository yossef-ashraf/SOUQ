<?php
namespace App\Http\Traits;
use Spatie\Translatable\HasTranslations as BaseHasTranslations;
//https://github.com/spatie/laravel-translatable
trait HasTranslation
{
    use BaseHasTranslations;

    // use App\Http\Traits\HasTranslation;
    // class NewsItem extends Model
    // {
        // use HasTranslation;
    //     public $translatable = ['name'];
    // }

    public function toArray()
    {
        $attributes = parent::toArray();
            if (session('getLocaleStop', false)) {
            foreach ($this->getTranslatableAttributes() as $field) {
                $attributes[$field] = $this->getTranslation($field, \App::getLocale());
            }
        }

        // dd($attributes);
        return $attributes;
    }
}
