<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\User;
use App\Models\Forum;
use App\Models\Like;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'body',
        'user_id',
        'forum_id',
        'image'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function forum(){
        return $this->belongsTo(Forum::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }
}
