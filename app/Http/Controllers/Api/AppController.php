<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SearchAuthorsBooksUsersService;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function __construct(
        private SearchAuthorsBooksUsersService $searchAuthorsBooksUsersService,
        private UsersService $usersService
    ) {}

    public function home(): JsonResponse
    {
        try {
            return response()->json([
                'data' => $this->usersService->home()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possivel carregar a sua home page, tente novamente mais tarde.'
            ], 500);
        }
    }
    public function searchAllByQuery(Request $request): JsonResponse
    {
        try {
            return response()->json([
                $this->searchAuthorsBooksUsersService->searchAllByQuery($request),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha na pesquisa, tente novamente mais tarde.'
            ], 500);
        }
    }
}
