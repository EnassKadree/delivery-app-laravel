<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasTranslations;
    use SoftDeletes;

    protected $guarded=[];

    public $translatable=['name','description'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_item','product_id','order_id');
    }
    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'favorites', 'product_id', 'customer_id');
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class,'cart_item','product_id','cart_id');
    }

}
