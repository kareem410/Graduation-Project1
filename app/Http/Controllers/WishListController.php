<?php

namespace App\Http\Controllers;

use App\Models\WishList;
use App\Models\MyUser;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Log;

class WishListController extends Controller
{
    public function index()
    {
        return response()->json(WishList::all(), 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateWishListData($request);

            $user = MyUser::findOrFail($validatedData['user_id']);
            $product = Product::findOrFail($validatedData['product_id']);

            if ($user->wishList()->where('product_id', $product->id)->exists()) {
                return response()->json(['message' => 'Product already in wishlist'], 409);
            }

            $user->wishList()->attach($product);
            return response()->json(['message' => 'Product added to wishlist'], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Wishlist store error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function show($user_id)
    {
        try {
            $user = MyUser::findOrFail($user_id);
            $wishlist = $user->wishList()->get();  
            return response()->json(['wishlist' => $wishlist], 200);
        } catch (Exception $e) {
            Log::error('Wishlist show error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function update(Request $request, $user_id, $product_id)
    {
        try {
            $validatedData = $request->validate([
                'new_product_id' => 'required|exists:products,id',
            ]);

            $user = MyUser::findOrFail($user_id);
            $oldProduct = Product::findOrFail($product_id);
            $newProduct = Product::findOrFail($validatedData['new_product_id']);

            if (!$user->wishList()->where('product_id', $oldProduct->id)->exists()) {
                return response()->json(['message' => 'Product not found in wishlist'], 404);
            }

            $user->wishList()->detach($oldProduct);
            $user->wishList()->attach($newProduct);

            return response()->json(['message' => 'Wishlist updated successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Wishlist update error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }




    public function destroy(Request $request)
    {
        try {
            $validatedData = $this->validateWishListData($request);

            $user = MyUser::findOrFail($validatedData['user_id']);
            $product = Product::findOrFail($validatedData['product_id']);

            if (!$user->wishList()->where('product_id', $product->id)->exists()) {
                return response()->json(['message' => 'Product not found in wishlist'], 404);
            }

            $user->wishList()->detach($product);
            return response()->json(['message' => 'Product removed from wishlist'], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Wishlist delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    
    private function validateWishListData(Request $request)
    {
        return $request->validate([
            'user_id' => 'required|exists:myusers,id',
            'product_id' => 'required|exists:products,id',
        ]);
    }
}
