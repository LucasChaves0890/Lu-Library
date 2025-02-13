<?php

namespace App\Services;

class FormatBookService
{
    public function formatBook($book, $ratingData, $favorite, $read): array
    {
        return [
            'id' => $book['id'],
            'title' => $book['title'],
            'description' => $book['description'],
            'gender' => $book['gender'],
            'book_cover' => $book['book_cover'],
            'price' => $book['price'],
            'author_id' => $book['author_id'],
            'number_of_pages' => $book['number_of_pages'],
            'pages_read' => $book['pages_read'] ?? null,
            'ratingData' => $ratingData,
            'favorite' => $favorite,
            'read' => $read
        ];
    }
}
