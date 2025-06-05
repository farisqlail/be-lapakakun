<?php

namespace App\Http\Controllers\Api;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new member
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members',
            'number' => 'required|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except('password', 'password_confirmation', 'image');
            $data['password'] = Hash::make($request->password);

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('members', 'public');
            }

            $member = Member::create($data);

            // Create token for the member
            $token = $member->createToken('member-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Member registered successfully',
                'data' => [
                    'member' => [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'number' => $member->number,
                        'birth_date' => $member->birth_date,
                        'address' => $member->address,
                        'image' => $member->image ? asset('storage/' . $member->image) : null,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login member
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $member = Member::where('email', $request->email)->first();

            if (!$member || !Hash::check($request->password, $member->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Revoke all existing tokens (optional - for single device login)
            // $member->tokens()->delete();

            // Create new token
            $token = $member->createToken('member-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'member' => [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'number' => $member->number,
                        'birth_date' => $member->birth_date,
                        'address' => $member->address,
                        'image' => $member->image ? asset('storage/' . $member->image) : null,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout member
     */
    public function logout(Request $request)
    {
        try {
            $member = auth('member')->user();
        
            if ($member) {
                $member->currentAccessToken()->delete();
            }    

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current member profile
     */
    public function profile(Request $request)
    {
        try {
            $member = auth('member')->user();

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'debug' => [
                        'token_exists' => !empty($token)
                    ]
                ], 401);
            }


            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => [
                    'member' => [
                        'uuid' => $member->uuid,
                        'name' => $member->name,
                        'email' => $member->email,
                        'number' => $member->number,
                        'birth_date' => $member->birth_date,
                        'address' => $member->address,
                        'image' => $member->image ? asset('storage/' . $member->image) : null,
                        'created_at' => $member->created_at,
                        'updated_at' => $member->updated_at,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $member = auth('member')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'number' => 'required|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except('password', 'password_confirmation', 'image');

            // Update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($member->image && \Storage::disk('public')->exists($member->image)) {
                    \Storage::disk('public')->delete($member->image);
                }
                $data['image'] = $request->file('image')->store('members', 'public');
            }

            $member->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'member' => [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'number' => $member->number,
                        'birth_date' => $member->birth_date,
                        'address' => $member->address,
                        'image' => $member->image ? asset('storage/' . $member->image) : null,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
