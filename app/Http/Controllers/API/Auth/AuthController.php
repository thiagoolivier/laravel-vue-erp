<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     */
    #[Post('auth/login', name: "auth.login")]
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     */
    #[Get('auth/me', name: "auth.me")]
    public function me(): JsonResponse
    {
        return response()->json(auth()->user(), 200);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    #[Post('auth/logout', name: "auth.logout")]
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Refresh a token.
     */
    #[Get('auth/refresh', name: "auth.refresh")]
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    #[Get('auth/verify', name: "auth.verify")]
    public function verifyToken(Request $request): JsonResponse
    {
        try {
            $token = JWTAuth::parseToken()->authenticate();
            return response()->json(['message' => 'Token is valid'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token is invalid'], 401);
        }
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
