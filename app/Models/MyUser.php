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

    protected $fillable = ['name', 'email', 'password', 'image', 'card', 'type'];

    protected $hidden = ['password'];

    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    public function wishList(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlist', 'user_id', 'product_id')->withTimestamps();
    }
}


