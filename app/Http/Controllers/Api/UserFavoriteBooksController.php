<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFavoriteBookFormRequest;
use App\Services\BooksToReadService;
use App\Services\UserFavoriteBooksService;
use Illuminate\Http\JsonResponse;

class UserFavoriteBooksController extends Controller
{
    public function __construct(
        private BooksToReadService $bookToReadService,
        private UserFavoriteBooksService $service
    ) {}
    
    public function store(UserFavoriteBookFormRequest $request): JsonResponse
    {
        try {
            return response()->json(
                $this->service->toggleUserFavoriteBook($request),
                200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao Favoritar Livro, tente novamente mais tarde.'
            ], 500);
        }
    }
}
