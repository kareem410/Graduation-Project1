<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MyUserController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;


// Authentication Routes (Public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Protected Routes (Authenticated Users)
Route::middleware('auth:sanctum')->group(function () {
    
    // **Customer & Admin Common Routes**
    Route::get('/myusers/{id}', [MyUserController::class, 'show']); // Get user by ID
    Route::put('/myusers/{id}', [MyUserController::class, 'update']); // Update user
    Route::delete('/myusers/{id}', [MyUserController::class, 'destroy']); // Delete user
    
    // **Wishlist Routes (Customers)**
    // Route::post('/wishlist', [WishListController::class, 'store']);
    // Route::delete('/wishlist/{user_id}/{product_id}', [WishListController::class, 'destroy']);
    // Route::put('/wishlist/{user_id}/{old_product_id}', [WishListController::class, 'update']);

    // **wishlist Routes (Customers)**
    Route::post('/wishlist', [WishListController::class, 'store']); // Add product to wishlist
    Route::delete('/wishlist/{product_id}', [WishListController::class, 'destroy']); // Remove product from wishlist
    // Route::put('/wishlist/{user_id}/{product_id}', [WishListController::class, 'update']); // Update wishlist product
    Route::get('/wishlist/{user_id}', [WishListController::class, 'show']); // Show user wishlist with product details
    Route::put('/wishlist/{product_id}', [WishListController::class, 'update']); // Update wishlist product

    // **Cart Routes (Customers)**
    Route::post('/cart', [CartController::class, 'store']); // Add product to cart
    Route::delete('/cart/{product_id}', [CartController::class, 'destroy']); // Remove product from cart
    // Route::put('/cart/{user_id}/{product_id}', [CartController::class, 'update']); // Update cart product
    Route::get('/cart/{user_id}', [CartController::class, 'show']); // Show user cart with product details
    Route::put('/cart/{product_id}', [CartController::class, 'update']); // Update cart product
});

// Public Routes (No Authentication Required)
Route::get('/products', [ProductController::class, 'index']); // Get all products
Route::get('/products/{id}', [ProductController::class, 'show']); // Get product by ID
// Route::get('/wishlist', [WishListController::class, 'index']); // View wishlists
// Route::get('/wishlist/{user_id}', [WishListController::class, 'show']); // View a user's wishlist
// Route::get('/cart', [CartController::class, 'index']); // View all carts
// Route::get('/cart/{user_id}', [CartController::class, 'show']); // View a user's cart
Route::get('/products/category/{category}', [ProductController::class, 'getByCategory']); // Get products by category


// **Admin-Only Routes**
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    
    // Manage Users
    Route::get('/myusers', [MyUserController::class, 'index']); // Get all users
    Route::post('/myusers', [MyUserController::class, 'store']); // Create a new user
    
    // Manage Products
    Route::post('/products', [ProductController::class, 'store']); // Create product
    Route::put('/products/{id}', [ProductController::class, 'update']); // Update product
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Delete product
});