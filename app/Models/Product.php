<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category', 'price', 'unit', 'stockAvailability', 'inStock', 'description', 'rating', 'offers', 'bestDeal', 'topSelling', 'everydayNeeds', 'imageUrl', 'new_arrival', 'barcode'];

    // public function wishListUsers() {
    //     return $this->belongsToMany(User::class, 'wishlist');
    // }
    public function wishListedByUsers()
    {
        return $this->belongsToMany(MyUser::class, 'wish_list')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

   use HasFactory;

   public function usersInCart()
{
    return $this->belongsToMany(MyUser::class, 'cart')
        ->withPivot('quantity')
        ->withTimestamps();
}

}
