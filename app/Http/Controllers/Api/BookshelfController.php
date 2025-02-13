<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookshelfService;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;

class BookshelfController extends Controller
{
    public function __construct(
        private UsersService $usersService,
        private BookshelfService $service
    ) {}

    public function getUserReadedBooks($userId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->getUserReadedBooksFormatted($userId),
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro na consulta, tente novamente mais tarde.'
            ], 500);
        }
    }

    public function getUserReadingBooks($userId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->getUserReadingBooksFormatted($userId),
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro na consulta, tente novamente mais tarde.'
            ], 500);
        }
    }

    public function getUserFavoriteBooks(int $userId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->getUserFavoriteBooksFormatted($userId),
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recupar os livros favoritados, tente novamente mais tarde.'
            ]);
        }
    }

    public function bookShelfPage(int $userId): JsonResponse
    {
        try {
            $authUser = $this->usersService->getAuthUserWithFollows();
            $data = $this->service->getBookshelf($userId);

            return response()->json([
                'authUser' => $authUser,
                'totalOfReadPages' => $data['totalOfReadPages'],
                'bookshelf' => $data['bookshelf'],
                'booksQty' => $data['booksQty']
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Falha ao recuperar estante de livros. $e"
            ], 500);
        }
    }

    public function getBookShelf(int $userId): JsonResponse
    {
        try {
            return response()->json(
                $this->service->getBookShelf($userId),
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha na pesquisa.'
            ], 500);
        }
    }

    public function searchBookShelf(int $userId, $title): JsonResponse
    {
        try {
            $data = $this->service->searchBookShelfByTitle($userId, $title);

            return response()->json([
                'bookshelf' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha na pesquisa.'
            ], 500);
        }
    }
}
