<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookFormRequest;
use App\Models\Book;
use App\Services\BookDetailsService;
use App\Services\BookService;
use App\Services\BookshelfService;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct(
        private BookService $service,
        private BookshelfService $bookshelfService,
        private BookDetailsService $bookDetailsService,
        private UsersService $usersService
    ) {}

    public function searchBooks(Request $request)
    {
        try {
            $query = $request->input('title');

            return $this->service->searchBookByTitle($query);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha na pesquisa, tente novamente mais tarde.'
            ], 500);
        }
    }
   
    public function index(): JsonResponse
    {
        try {
            $authUser = $this->usersService->getAuthUserWithFollows();

            return response()->json([
                'data' => $this->service->getBooksWithAuthor(),
                'authUser' => $authUser
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha na consulta de livros, tente novamente mais tarde.'
            ], 500);
        }
    }

    public function store(BookFormRequest $request): JsonResponse
    {
        try {
            $book = $this->service->createBook($request);

            return response()->json([
                'message' => "Livro criado com sucesso: $book->title"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro inseperado ao criar livro.'
            ], 500);
        }
    }

    public function show(int $book): JsonResponse
    {
        try {
            $authUser = $this->usersService->getAuthUserWithFollows();
            $data = $this->service->getBookDetails($book, $authUser);

            return response()->json([
                'book' => $data['book'],
                'ratingData' => $data['ratingData'],
                'posts' => $data['posts'],
                'favorite' => $data['favorite'],
                'read' => $data['read'],
                'recomendation' => $data['recomendation'],
                'authUser' => $authUser
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Erro ao carregar livro: $e"
            ], 500);
        }
    }

    public function update(Book $book, BookFormRequest $request): JsonResponse
    {
        try {
            $authUser = auth()->user();
            $books = $this->service->updateBook($book, $request);

            return response()->json([
                'message' => 'Livro atualizado com sucesso!',
                'authUser' => $authUser,
                'books' => $books
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar livro.'
            ], 500);
        }
    }

    public function destroy(Book $book): JsonResponse
    {
        try {
            $this->service->deleteBook($book);

            return response()->json(
                [
                    'message' => 'Livro excluído com sucesso.'
                ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possícel excluír o livro'
            ], 500);
        }
    }
}
