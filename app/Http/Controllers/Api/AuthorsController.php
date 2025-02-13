<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorFormRequest;
use App\Models\Author;
use App\Services\AuthorsService;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    public function __construct(
        private UsersService $usersService,
        private AuthorsService $service
    ) {}

    public function searchAuthors(Request $request)
    {
        try {
            $query = $request->input('name');

            return $this->service->searchAuthorsByName($query);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Erro ao carregar os autores: $e"
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $authUser = $this->usersService->getAuthUserWithFollows();
            $authors = $this->service->listAuthorsWithBookCount();

            return response()->json([
                'authUser' => $authUser,
                'authors' => $authors
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Erro ao carregar página."
            ], 500);
        }
    }

    public function store(AuthorFormRequest $request): JsonResponse
    {
        try {
            $author = $this->service->createAuthor($request);

            return response()->json([
                'message' => "Autor(a) criado(a) com sucesso: $author->name"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro inesperado ao criar autor.'
            ], 500);
        }
    }

    public function show(int $authorId): JsonResponse
    {
        try {
            $authUser = $this->usersService->getAuthUserWithFollows();
            $data = $this->service->getAuthorDetailsWithBooks($authorId);

            return response()->json([
                'authUser' => $authUser,
                'author' => $data['author'],
                'books' => $data['books']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Erro ao Carregar autor: $e"
            ], 500);
        }
    }

    public function update(Author $author, AuthorFormRequest $request): JsonResponse
    {
        try {
            $authorUpdated = $this->service->updateAuthor($author, $request);

            return response()->json([
                'message' => "Autor(a) atualizado com Sucesso: $authorUpdated->name"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Erro ao atualizar autor."
            ], 500);
        }
    }

    public function destroy(Author $author): JsonResponse
    {
        try {
            $this->service->deleteAuthor($author);

            return response()->json([
                'message' => 'Autor excluído com sucesso.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível excluir o autor.'
            ], 500);
        }
    }
}
