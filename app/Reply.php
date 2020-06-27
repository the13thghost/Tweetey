<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['tweet_id', 'user_id', 'reply'];

    public function tweet() 
    {
        return $this->belongsTo(Tweet::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function likes() 
    {
        return $this->hasMany(Like::class, 'tweet_id', 'tweet_id');
    }  

    public function scopeWithLikesReply() 
    {
        // $query->leftJoinSub($query, $as, $first)
        return $this->leftJoinSub('SELECT tweet_id, sum(`like`) `likes`, sum(`dislike`) `dislikes` FROM `likes` group by tweet_id',
        'likes', 'replies.tweet_id', 'likes.tweet_id');
    }
}
