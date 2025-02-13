<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'following_user_id',
        'followed_user_id'
    ];

    public function followingUser()
    {
        return $this->belongsTo(Follow::class, 'following_user_id');
    }

    public function followedUser()
    {
        return $this->belongsTo(Follow::class, 'followed_user_id');
    }
}
