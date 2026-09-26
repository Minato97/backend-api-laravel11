<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function index()
    {
        return UserResource::collection($this->userService->listar());
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->registrar($request->validated());

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userService->actualizar($user, $request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $this->userService->eliminar($user);

        return response()->json([
            'message' => 'Usuario eliminado'
        ]);
    }
}
