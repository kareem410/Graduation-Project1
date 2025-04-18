<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     try {
    //         // Validate data
    //         $validatedData = $this->validateRegister($request);

    //         // Handle imageUrl upload
    //         $imageUrlPath = null;
    //         if ($request->hasFile('imageUrl')) {
    //             $imageUrlPath = $request->file('imageUrl')->store('users', 'public');
    //         }

    //         // Hash password
    //         $validatedData['password'] = Hash::make($validatedData['password']);
    //         $validatedData['imageUrl'] = $imageUrlPath;

    //         // Create user
    //         $user = MyUser::create($validatedData);
    //         $token = $user->createToken('auth_token')->plainTextToken;

    //         return response()->json([
    //             'message' => 'User registered successfully!',
    //             'user' => $user,
    //             'token' => $token
    //         ], 201);
    //     } catch (ValidationException $e) {
    //         return response()->json(['error' => $e->errors()], 422);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Something went wrong!'], 500);
    //     }
    // }

    public function register(Request $request)
    {
        try {
            // Validate data
            $validatedData = $this->validateRegister($request);

            // Handle imageUrl upload
            $imageUrlPath = null;
            if ($request->hasFile('imageUrl')) {
                $imageUrlPath = $request->file('imageUrl')->store('users', 'public');
            }

            // Hash password
            $validatedData['password'] = Hash::make($validatedData['password']);
            $validatedData['imageUrl'] = $imageUrlPath;

            // Create user
            $user = MyUser::create($validatedData);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully!',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (ValidationException $e) {
            Log::error('Validation error during registration: ' . json_encode($e->errors()));
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('User register error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    private function validateRegister(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:myusers,email', // Changed my_users to myusers
            'password' => 'required|string|min:6',
            'type' => 'required|in:customer,admin',
            'card' => 'nullable|string',
            'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ])->validate();
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = MyUser::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful!',
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully!']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    // Separate validation function for register
    // private function validateRegister(Request $request)
    // {
    //     return Validator::make($request->all(), [
    //         'name' => 'required|string',
    //         'email' => 'required|email|unique:my_users,email',
    //         'password' => 'required|string|min:6',
    //         'type' => 'required|in:customer,admin',
    //         'card' => 'nullable|string',
    //         'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ])->validate();
    // }
}


