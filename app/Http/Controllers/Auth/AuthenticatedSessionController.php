<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\UserResource;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        // $request->authenticate();

        // $request->session()->regenerate();

        // return response()->noContent();

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = Auth::user()->load('customer');

        if ($user->status !== 'Aktif') {
            Auth::logout();
            return response()->json([
                'message' => 'Akun anda tidak aktif.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $roles = $user->getRoleNames();
        $permissions = $user->getPermissionsViaRoles()->pluck('name');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
            'customer' => $user->customer ? new CustomerResource($user->customer) : null,
            'roles' => $roles,
            'permissions' => $permissions,
            'status' => 'Login successful'
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        if ($request->user()) {
            $token = $request->user()->currentAccessToken();
            if ($token && !$token instanceof \Laravel\Sanctum\TransientToken) {
                $token->delete();
            }
            return response()->json(['message' => 'Logout successful']);
        }
        return response()->json(['message' => 'No authenticated user'], 401);
    }
}
