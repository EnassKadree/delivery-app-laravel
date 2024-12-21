<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    
    protected $guarded=[];

    public $translatable=['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
