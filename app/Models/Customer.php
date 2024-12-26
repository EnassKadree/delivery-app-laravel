<?php

namespace App\Models;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model implements AuthenticatableContract
{
    use  Authenticatable;
    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites', 'customer_id', 'product_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    
}
