<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavoriteBook extends Model
{
    use HasFactory;

    protected $table = 'user_favorites_books';
    protected $fillable = [
        'user_id',
        'book_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function bookToRead()
    {
        return $this->hasOne(BookToRead::class, 'book_id', 'book_id')
            ->whereColumn('user_id', 'user_id'); 
    }

    /**
     * @return int|null
     */
    public function getPagesReadAttribute()
    {
        return $this->bookToRead?->pages_read;
    }
}
