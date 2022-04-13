<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Forum;

class ForumCategory extends Model
{
    protected $fillable = [
        'description',
        'user_id',
        'image'
    ];

    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function forum(){
        return $this->hasMany(Forum::class);
    }
}
