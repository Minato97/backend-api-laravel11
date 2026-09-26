<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Registra un usuario y le genera su token de acceso.
     *
     * @return array{user: User, token: string}
     */
    public function registrar(array $datos): array
    {
        $user = $this->userService->registrar($datos);

        return ['user' => $user, 'token' => $this->crearToken($user)];
    }

    /**
     * Valida las credenciales y genera un token. Devuelve null si son inválidas.
     *
     * @return array{user: User, token: string}|null
     */
    public function login(string $email, string $password): ?array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        return ['user' => $user, 'token' => $this->crearToken($user)];
    }

    // Elimina solo el token con el que se hizo la petición
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    // Elimina todos los tokens del usuario (todos los dispositivos)
    public function logoutAll(User $user): void
    {
        $user->tokens()->delete();
    }

    private function crearToken(User $user): string
    {
        return $user->createToken('api-token')->plainTextToken;
    }
}
