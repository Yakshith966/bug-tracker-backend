<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'role_id' => 'required|exists:roles,id',
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);
    
            return response()->json(['message' => 'User registered successfully', 'user' => $user]);
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
        
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json(['token' => $token,'id'=>\Auth::id()]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
    public function getProfile(Request $request) {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request) {
        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function changePassword(Request $request) {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

}
