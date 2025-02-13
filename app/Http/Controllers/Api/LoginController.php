<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response()->json([
                'message' => 'Email ou senha inválidos'
            ], 401);
        }

        try {
            $user = Auth::user();
            $user->tokens()->delete();

            $token = $user->createToken('token', ['is_admin']);


            return response()->json([
                'token' => $token->plainTextToken,
                'user' => $user->id,
                'message' => 'Login realizado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro inesperado.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user) {
                $user->tokens()->delete();

                return response()->json([
                    'message' => 'Você foi desconectado com sucesso.'
                ], 200);
            }

            return response()->json(['message' => 'Usuário não autenticado'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro inesperado.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
