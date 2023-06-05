<?php
namespace App\Http\Traits;
use Spatie\Translatable\HasTranslations as BaseHasTranslations;

trait HasTranslation
{
    use BaseHasTranslations;


    public function toArray()
    {
        $attributes = parent::toArray();
        // dd(app()->getLocale() , app()->getLocale() !== 'admin');
        // var_dump($attributes);
            if (app()->getLocale() !== 'admin') {
            // dd($this->getTranslatableAttributes());
            foreach ($this->getTranslatableAttributes() as $field) {
                // var_dump($field);
                $attributes[$field] = $this->getTranslation($field, \App::getLocale());
            }
        }

        // dd($attributes);
        return $attributes;
    }
}
