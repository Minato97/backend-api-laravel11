<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Un usuario puede editarse a sí mismo; un administrador puede editar a cualquiera.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->is($model);
    }

    /**
     * Un usuario puede eliminarse a sí mismo; un administrador puede eliminar a cualquiera.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->is($model);
    }
}
