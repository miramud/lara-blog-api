<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use App\Models\ForumCategory;
use App\Models\Like;


class Forum extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'summary',
        'user_id',
        'image'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(ForumCategory::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

}

