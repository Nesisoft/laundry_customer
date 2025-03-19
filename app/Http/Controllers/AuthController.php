<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Verify Access Token
     */
    public function VerifyAccessToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string|min:16|max:16|exists:businesses,access_token',
        ]);

        if ($validator->fails()) {
            Log::info("Access token verification failed");
            return response()->json([
                'message' => 'Access token verification failed',
                'data' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'message' => 'Access token verified successfully',
            'data' => $validator->validated()
        ], 200);
    }

    /**
     * Register a new admin with address
     */
    public function register(Request $request): JsonResponse
    {
        // Validate User Data & Address Data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex' => 'required|in:male,female',
            'address' => 'required|array',
            'address.street' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.state' => 'required|string|max:255',
            'address.zip_code' => 'nullable|string|max:20',
            'address.country' => 'required|string|max:100',
            'address.latitude' => 'nullable|numeric',
            'address.longitude' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create user with admin role
        $user = User::create([
            'role' => 'admin',
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Create the address and associate it with the admin
        $address = new Address($request->address);
        $user->admin()->save($address); // This attaches the address to the admin polymorphically

        // Create admin profile
        $admin = Admin::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'phone_number' => $request->phone_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'sex' => $request->sex,
        ]);

        return response()->json([
            'message' => 'Admin registered successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Admin Login
     */
    public function login(Request $request, string $role): JsonResponse
    {
        // Validate login input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid login credentials',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find admin user
        $user = User::where('email', $request->email)->where('role', $role)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid admin credentials'], 401);
        }

        // Revoke previous tokens (optional security measure)
        $user->tokens()->delete();

        // Create a new access token for the admin
        $token = $user->createToken('admin-access-token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]
        ]);
    }

    /**
     * Admin Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Admin logged out successfully']);
    }
}
