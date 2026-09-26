<?php

namespace App\Services;

use App\Models\Estatus;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function listar(int $porPagina = 10): LengthAwarePaginator
    {
        return User::paginate($porPagina);
    }

    /**
     * Crea un usuario desde un registro público: siempre con rol "Usuario" y estatus "Activo",
     * sin permitir que el cliente elija su propio rol.
     */
    public function registrar(array $datos): User
    {
        return User::create([
            ...$datos,
            'roles_id' => Rol::where('rol', Rol::USUARIO)->value('id'),
            'estatus_id' => Estatus::where('estatus', Estatus::ACTIVO)->value('id'),
        ]);
    }

    public function actualizar(User $user, array $datos): User
    {
        // El cast "hashed" del modelo encripta el password automáticamente
        $user->update($datos);

        return $user;
    }

    public function eliminar(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->delete();
        });
    }
}
