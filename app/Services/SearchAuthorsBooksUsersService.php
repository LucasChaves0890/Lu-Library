<?php

namespace App\Services;

use Illuminate\Http\Request;

class SearchAuthorsBooksUsersService
{
    public function __construct(
        private BookService $bookService,
        private AuthorsService $authorsService,
        private UsersService $usersService
    ) {}

    public function searchAllByQuery(Request $request): object
    {
        $query = $request->input('query');

        $books = $this->bookService->searchBookByTitle($query);
        $authors = $this->authorsService->searchAuthorsByName($query);
        $users = $this->usersService->searchUserByName($query);

        return (object) [
            'books' => $books,
            'authors' => $authors,
            'users' => $users,
        ];
    }
}
