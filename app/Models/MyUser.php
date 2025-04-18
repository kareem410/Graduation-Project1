<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class MyUser extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'myusers';

    protected $fillable = ['name', 'email', 'password', 'imageUrl', 'card', 'type'];

    protected $hidden = ['password'];

    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    public function wishList(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlist', 'user_id', 'product_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function cart()
    {
        return $this->belongsToMany(Product::class, 'cart', 'user_id', 'product_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

}


