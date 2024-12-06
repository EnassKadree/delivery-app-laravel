<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded=[];
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
        return $this->belongsToMany(Order::class,'order_item','order_id','product_id');
    }
    public function costumers()
    {
        return $this->belongsToMany(Customer::class, 'favorites', 'customer_id', 'product_id');
    } 
    
    public function carts()
    {
        return $this->belongsToMany(Cart::class,'cart_item','product_id','cart_id');
    }    
}
