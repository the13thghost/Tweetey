<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['tweet_id', 'user_id', 'reply'];

    public function tweet() {
        return $this->belongsTo(Tweet::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function likes() {
        return $this->hasMany(Like::class, 'tweet_id', 'tweet_id');
    }

    // from reply get tweet you are replying to > if its a retweet > get the original tweet info
    public function originalTweet() {
        return $this->tweet->where('id', $this->tweet->retweeted_from)->first();
    }

    

    public function scopeWithLikesReply() {
        // $query->leftJoinSub($query, $as, $first)
        return $this->leftJoinSub('SELECT tweet_id, sum(`like`) `likes`, sum(`dislike`) `dislikes` FROM `likes` group by tweet_id',
        'likes', 'replies.tweet_id', 'likes.tweet_id');
    }



}
