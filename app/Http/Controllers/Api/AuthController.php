<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function register(StoreUserRequest $request)
    {
        $resultado = $this->authService->registrar($request->validated());

        return response()->json([
            'user' => new UserResource($resultado['user']),
            'token' => $resultado['token']
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $resultado = $this->authService->login($request->email, $request->password);

        if (! $resultado) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        return response()->json([
            'user' => new UserResource($resultado['user']),
            'token' => $resultado['token']
        ]);
    }

    public function profile(Request $request)
    {
        return new UserResource($request->user());
    }

    // 🔐 Logout (elimina el token actual)
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    // 🔐 Logout en todos los dispositivos
    public function logoutAll(Request $request)
    {
        $this->authService->logoutAll($request->user());

        return response()->json([
            'message' => 'Sesión cerrada en todos los dispositivos correctamente'
        ]);
    }
}
