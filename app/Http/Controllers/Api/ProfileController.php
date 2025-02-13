<?php

namespace App\Http\Controllers\Api;

use App\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController
{
    public function __construct(private UsersService $service) {}

    public function showProfile(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'data' => $this->service->showProfile($request->user), 
                200]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possivel carregar perfil, tente novamente mais tarde.'
            ], 500);
        }
    }
}
