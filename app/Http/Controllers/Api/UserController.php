<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    public function index()
    {
        return UserResource::collection(User::paginate(10));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::registrar($request->validated());

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // El cast "hashed" del modelo encripta el password automáticamente
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado'
        ]);
    }
}
