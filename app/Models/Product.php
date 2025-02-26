<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category', 'price', 'unit', 'stock_availability', 'counts', 'description', 'rating', 'offer', 'is_best_deal', 'top_selling', 'everyday_needs', 'image', 'new_arrival', 'barcode'];

    // public function wishListUsers() {
    //     return $this->belongsToMany(User::class, 'wishlist');
    // }
    public function wishListedByUsers()
    {
        return $this->belongsToMany(MyUser::class, 'wish_list', 'product_id', 'user_id')->withTimestamps();
    }

   use HasFactory;
}
