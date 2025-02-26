<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyUser;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class MyUserController extends Controller
{
    public function index()
    {
        try {
            $users = MyUser::all();
            return response()->json(['users' => $users], 200);
        } catch (Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch users!'], 500);
        }
    }


    public function store(Request $request)
    {
    try {
        $validatedData = $this->validateUserData($request);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('users', 'public') : null;
        $validatedData['image'] = $imagePath;

        $user = MyUser::create($validatedData);

        return response()->json([
            'message' => 'User created successfully!',
            'user' => $user
        ], 201);
        } catch (ValidationException $e) {
        return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (Exception $e) {
        Log::error('User store error: ' . $e->getMessage());
        return response()->json(['error' => 'Something went wrong while creating the user!'], 500);
        }
    }


    public function show($id)
    {
        try {
            $user = MyUser::findOrFail($id);
            return response()->json(['user' => $user], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found!'], 404);
        } catch (Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while fetching the user!'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = MyUser::findOrFail($id);
            $validatedData = $this->validateUserData($request, $id);

            if ($request->has('password')) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }

            if ($request->hasFile('image')) {
                if ($user->image) Storage::disk('public')->delete($user->image);
                $validatedData['image'] = $request->file('image')->store('users', 'public');
            }

            $user->update($validatedData);
            return response()->json(['message' => 'User updated successfully!', 'user' => $user], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found!'], 404);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('User update error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while updating the user!'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = MyUser::findOrFail($id);
            
            if ($user->image) Storage::disk('public')->delete($user->image);

            $user->delete();
            return response()->json(['message' => 'User deleted successfully!'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found!'], 404);
        } catch (Exception $e) {
            Log::error('User delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while deleting the user!'], 500);
        }
    }

    
    private function validateUserData(Request $request, $id = null)
    {
    return $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:myusers,email' . ($id ? ",$id" : ''),
        'password' => $id ? 'sometimes|string|min:6' : 'required|string|min:6',
        'card' => 'nullable|string',
        'type' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
    }

}
