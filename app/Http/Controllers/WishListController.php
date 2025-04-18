<?php

namespace App\Http\Controllers;
//use App\Models\WishList;
use App\Models\MyUser;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WishListController extends Controller
{
    public function index()
    {
        return response()->json([], 200); // Adjust if you have a WishList model
    }

    public function store(Request $request)
    {
        try {
            // Validate only product_id and quantity (no user_id)
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            // Get the authenticated user
            $user = MyUser::find(auth()->id());
            $product = Product::findOrFail($validatedData['product_id']);

            // Check if product already exists in user's wishlist
            if ($user->wishlist()->where('product_id', $product->id)->exists()) {
                return response()->json(['message' => 'Product already in wishlist'], 409);
            }

            // Add product to wishlist
            $user->wishlist()->attach($product, ['quantity' => $validatedData['quantity']]);

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
            // Validate the user exists
            $user = MyUser::findOrFail($user_id);

            // Get wishlist items with product details
            $wishlistItems = $user->wishlist()->withPivot('quantity')->get();

            if ($wishlistItems->isEmpty()) {
                return response()->json([
                    'wishlist' => [],
                    'count' => 0,
                    'total_price' => 0,
                    'message' => 'Wishlist is empty'
                ], 200);
            }

            $totalPrice = 0;

            // Format wishlist items
            $formattedWishlist = $wishlistItems->map(function ($product) use (&$totalPrice) {
                $price = $product->price;
                $offersPercentage = $product->offers;
                $quantity = $product->pivot->quantity;

                // Calculate discount if applicable (as percentage)
                $discount = ($offersPercentage > 0 && $offersPercentage <= 100) ? ($price * $offersPercentage / 100) : 0;
                $finalPrice = $price - $discount;

                $subtotal = $finalPrice * $quantity;
                $totalPrice += $subtotal;

                return [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'offers' => $offersPercentage,
                    'unit' => $product->unit,
                    'quantity' => $quantity,
                    'final_price' => round($finalPrice, 2),
                    'subtotal' => round($subtotal, 2),
                    'imageUrl' => $product->imageUrl
                ];
            });

            return response()->json([
                'wishlist' => $formattedWishlist,
                'count' => $formattedWishlist->count(),
                'total_price' => round($totalPrice, 2)
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (Exception $e) {
            Log::error('Wishlist show error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function update(Request $request, $product_id)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'quantity' => 'integer|min:1', // Optional, for updating quantity
                'new_product_id' => 'exists:products,id', // Optional, for replacing product
                'action' => 'in:update,replace,delete' // Optional, to specify intent
            ]);

            // Get the authenticated user
            $user = MyUser::find(auth()->id());
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            // Find the existing product in the wishlist
            $oldProduct = Product::findOrFail($product_id);
            if (!$user->wishlist()->where('product_id', $oldProduct->id)->exists()) {
                return response()->json(['message' => 'Product not found in wishlist'], 404);
            }

            // Determine the action (defaults to 'update' if not specified)
            $action = $request->input('action', 'update');

            if ($action === 'delete') {
                // Delete the product from the wishlist
                $user->wishlist()->detach($oldProduct);
                return response()->json(['message' => 'Product removed from wishlist'], 200);
            } elseif ($action === 'replace') {
                // Replace with a new product
                if (!isset($validatedData['new_product_id'])) {
                    return response()->json(['error' => 'new_product_id is required for replace action'], 422);
                }
                $newProduct = Product::findOrFail($validatedData['new_product_id']);
                if ($user->wishlist()->where('product_id', $newProduct->id)->exists()) {
                    return response()->json(['message' => 'New product already in wishlist'], 409);
                }
                $quantity = $validatedData['quantity'] ?? 1; // Default to 1 if not provided
                $user->wishlist()->detach($oldProduct);
                $user->wishlist()->attach($newProduct, ['quantity' => $quantity]);
                return response()->json(['message' => 'Product replaced in wishlist'], 200);
            } else {
                // Update quantity of the existing product
                if (!isset($validatedData['quantity'])) {
                    return response()->json(['error' => 'quantity is required for update action'], 422);
                }
                $user->wishlist()->updateExistingPivot($oldProduct->id, ['quantity' => $validatedData['quantity']]);
                return response()->json(['message' => 'Wishlist quantity updated'], 200);
            }

        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        } catch (Exception $e) {
            Log::error('Wishlist update error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function destroy($product_id)
    {
        try {
            // Get the authenticated user
            $user = MyUser::find(auth()->id());
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            // Find the product
            $product = Product::findOrFail($product_id);

            // Check if the product exists in the user's wishlist
            if (!$user->wishlist()->where('product_id', $product->id)->exists()) {
                return response()->json(['message' => 'Product not found in wishlist'], 404);
            }

            // Remove the product from the wishlist
            $user->wishlist()->detach($product);

            return response()->json(['message' => 'Product removed from wishlist'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        } catch (Exception $e) {
            Log::error('Wishlist destroy error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }
}

// class WishListController extends Controller
// {
//     public function index()
//     {
//         return response()->json(WishList::all(), 200);
//     }

//     public function store(Request $request)
//     {
//         try {
//             $validatedData = $this->validateWishListData($request);

//             $user = MyUser::findOrFail($validatedData['user_id']);
//             $product = Product::findOrFail($validatedData['product_id']);

//             if ($user->wishList()->where('product_id', $product->id)->exists()) {
//                 return response()->json(['message' => 'Product already in wishlist'], 409);
//             }

//             $user->wishList()->attach($product);
//             return response()->json(['message' => 'Product added to wishlist'], 201);
//         } catch (ValidationException $e) {
//             return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
//         } catch (Exception $e) {
//             Log::error('Wishlist store error: ' . $e->getMessage());
//             return response()->json(['error' => 'Something went wrong!'], 500);
//         }
//     }

//     public function show($user_id)
//     {
//         try {
//             $user = MyUser::findOrFail($user_id);
//             $wishlist = $user->wishList()->get();
//             $wishlistCount = $wishlist->count();

//             return response()->json([
//                 'wishlist' => $wishlist,
//                 'count' => $wishlistCount
//             ], 200);
//         } catch (Exception $e) {
//             Log::error('Wishlist show error: ' . $e->getMessage());
//             return response()->json(['error' => 'Something went wrong!'], 500);
//         }
//     }

//     public function update(Request $request, $user_id, $product_id)
//     {
//         try {
//             $validatedData = $request->validate([
//                 'new_product_id' => 'required|exists:products,id',
//             ]);

//             $user = MyUser::findOrFail($user_id);
//             $oldProduct = Product::findOrFail($product_id);
//             $newProduct = Product::findOrFail($validatedData['new_product_id']);

//             if (!$user->wishList()->where('product_id', $oldProduct->id)->exists()) {
//                 return response()->json(['message' => 'Product not found in wishlist'], 404);
//             }

//             $user->wishList()->detach($oldProduct);
//             $user->wishList()->attach($newProduct);

//             return response()->json(['message' => 'Wishlist updated successfully'], 200);
//         } catch (ValidationException $e) {
//             return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
//         } catch (Exception $e) {
//             Log::error('Wishlist update error: ' . $e->getMessage());
//             return response()->json(['error' => 'Something went wrong!'], 500);
//         }
//     }




//     public function destroy(Request $request)
//     {
//         try {
//             $validatedData = $this->validateWishListData($request);

//             $user = MyUser::findOrFail($validatedData['user_id']);
//             $product = Product::findOrFail($validatedData['product_id']);

//             if (!$user->wishList()->where('product_id', $product->id)->exists()) {
//                 return response()->json(['message' => 'Product not found in wishlist'], 404);
//             }

//             $user->wishList()->detach($product);
//             return response()->json(['message' => 'Product removed from wishlist'], 200);
//         } catch (ValidationException $e) {
//             return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
//         } catch (Exception $e) {
//             Log::error('Wishlist delete error: ' . $e->getMessage());
//             return response()->json(['error' => 'Something went wrong!'], 500);
//         }
//     }

    
//     private function validateWishListData(Request $request)
//     {
//         return $request->validate([
//             'user_id' => 'required|exists:myusers,id',
//             'product_id' => 'required|exists:products,id',
//         ]);
//     }
// }
