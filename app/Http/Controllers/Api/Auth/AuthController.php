<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request) {        
        $request->validate([
            "email" => [
                "required",
                "email",
                "max:255",
            ],
            "password" => [
                "required",
                "max:32",
            ],
            "device_name" => [
                "required",
                "max:32",
            ],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(
                ['email' => 'The provided credentials are incorrect'],
            );
        }

        $user->tokens()->delete();

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token'=> $token,
        ]);        
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            "message" => "User logout success",
        ]);
    }
}
