<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFormRequest;
use App\Models\User;
use App\Services\ImageService;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    public function __construct(
        private ImageService $imageService,
        private UsersService $service
    ) {}

    public function index(): JsonResponse
    {
        try {
            $auhtUser = $this->service->getAuthUserWithFollows();

            return response()->json([
                'users' => $this->service->getAllUsersFormatted(),
                'authUser' => $auhtUser
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao mostar usuarios, tente novamente mais tarde.',
            ], 500);
        }
    }

    public function update(User $user, UserFormRequest $request)
    {
        try {
            return response()->json([
                'message' => 'Perfil atualizado com sucesso!',
                'user' => $this->service->updateUser($request, $user),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar perfil, tente novamente mais tarde.'
            ], 500);
        }
    }
}
