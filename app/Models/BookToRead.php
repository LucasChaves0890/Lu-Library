<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookToRead extends Model
{
    use HasFactory;

    protected $table = 'books_to_read';
    protected $fillable = [
        'user_id',
        'book_id',
        'pages_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function getBooksPageAttribute()
    {
        return $this->book->number_of_pages ?? 0;
    }
}
