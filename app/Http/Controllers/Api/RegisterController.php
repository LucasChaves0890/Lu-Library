<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFormRequest;
use App\Services\UsersService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(private UsersService $service) {}

    public function store(UserFormRequest $request): JsonResponse
    {
        try {
            $user = $this->service->createUser($request);
            event(new Registered($user));
            Auth::login($user);

            return response()->json([
                $user,
                'message' => 'Perfil criado com sucesso.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar perfil, tente novamente mais tarde.'
            ], 500);
        }
    }
}
