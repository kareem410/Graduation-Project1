<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateProductData($request);

            $imagePath = $request->hasFile('image') 
                ? $request->file('image')->store('products', 'public') 
                : null;

            $product = Product::create(array_merge($validatedData, ['image' => $imagePath]));

            return response()->json(['message' => 'Product created successfully!', 'product' => $product], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Product store error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function show($id)
    {
        $product = Product::find($id);
        return $product ? response()->json($product, 200) : response()->json(['error' => 'Product not found'], 404);
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $validatedData = $this->validateProductData($request, $id);
    
            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($product->image);
                $validatedData['image'] = $request->file('image')->store('products', 'public');
            }
    
            $product->update($validatedData);
    
            return response()->json(['message' => 'Product updated successfully!', 'product' => $product], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Product update error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         $product = Product::findOrFail($id);
            
    //         // Debug the incoming request
    //         Log::info('Raw request data:', $request->all());
    
    //         // Explicitly get each field from the request
    //         $updateData = [];
            
    //         // Text and numeric fields
    //         if ($request->has('name')) $updateData['name'] = $request->input('name');
    //         if ($request->has('category')) $updateData['category'] = $request->input('category');
    //         if ($request->has('price')) $updateData['price'] = $request->input('price');
    //         if ($request->has('barcode')) $updateData['barcode'] = $request->input('barcode');
    //         if ($request->has('unit')) $updateData['unit'] = $request->input('unit');
    //         if ($request->has('counts')) $updateData['counts'] = $request->input('counts');
    //         if ($request->has('description')) $updateData['description'] = $request->input('description');
    //         if ($request->has('rating')) $updateData['rating'] = $request->input('rating');
    
    //         // Boolean fields
    //         if ($request->has('stock_availability')) {
    //             $updateData['stock_availability'] = $request->input('stock_availability') === 'true';
    //         }
    //         if ($request->has('offer')) {
    //             $updateData['offer'] = $request->input('offer') === 'true';
    //         }
    //         if ($request->has('is_best_deal')) {
    //             $updateData['is_best_deal'] = $request->input('is_best_deal') === 'true';
    //         }
    //         if ($request->has('top_selling')) {
    //             $updateData['top_selling'] = $request->input('top_selling') === 'true';
    //         }
    //         if ($request->has('everyday_needs')) {
    //             $updateData['everyday_needs'] = $request->input('everyday_needs') === 'true';
    //         }
    //         if ($request->has('new_arrival')) {
    //             $updateData['new_arrival'] = $request->input('new_arrival') === 'true';
    //         }
    
    //         // Handle image if present
    //         if ($request->hasFile('image')) {
    //             if ($product->image) {
    //                 Storage::disk('public')->delete($product->image);
    //             }
    //             $updateData['image'] = $request->file('image')->store('products', 'public');
    //         }
    
    //         Log::info('Update data:', $updateData);
            
    //         // Update the product
    //         $product->update($updateData);
    
    //         return response()->json([
    //             'message' => 'Product updated successfully!',
    //             'product' => $product->fresh(),
    //             'received_data' => $request->all(),     // Show what was received
    //             'update_data' => $updateData            // Show what was updated
    //         ], 200);
    
    //     } catch (Exception $e) {
    //         Log::error('Update error: ' . $e->getMessage());
    //         return response()->json([
    //             'error' => 'Update failed',
    //             'message' => $e->getMessage(),
    //             'received_data' => $request->all()
    //         ], 500);
    //     }
    // }


    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->image) Storage::disk('public')->delete($product->image);
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully!'], 200);
        } catch (Exception $e) {
            Log::error('Product delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    
    private function validateProductData(Request $request, $id = null)
    {
        // Handle boolean conversions for the fields that should be boolean
        $booleanFields = [
            'stock_availability',
            'is_best_deal',
            'top_selling',
            'everyday_needs',
            'new_arrival'
        ];
    
        foreach ($booleanFields as $field) {
            if ($request->has($field)) {
                $request->merge([
                    $field => filter_var($request->input($field), 
                        FILTER_VALIDATE_BOOLEAN, 
                        FILTER_NULL_ON_FAILURE)
                ]);
            }
        }
    
        $rules = [
            'name' => 'sometimes|string',
            'category' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'barcode' => 'sometimes|string|unique:products,barcode,' . $id,
            'unit' => 'sometimes|string',
            'stock_availability' => 'sometimes|boolean',
            'counts' => 'sometimes|integer',
            'description' => 'sometimes|string',
            'rating' => 'sometimes|numeric|min:1|max:10',
            'offer' => 'sometimes|numeric|min:0',
            'is_best_deal' => 'sometimes|boolean',
            'top_selling' => 'sometimes|boolean',
            'everyday_needs' => 'sometimes|boolean',
            'new_arrival' => 'sometimes|boolean',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    
        // If this is a new product (no id), make all fields required
        if (!$id) {
            $rules = array_map(function($rule) {
                return str_replace('sometimes', 'required', $rule);
            }, $rules);
        }
    
        return $request->validate($rules);
    }

    // Add this helper method
private function convertToBoolean($value)
{
    if (is_string($value)) {
        return strtolower($value) === 'true' || $value === '1';
    }
    return (bool) $value;
}

}
