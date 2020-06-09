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

    // from reply get tweet you are replying to > if its a retweet > get the original tweet info
    public function originalTweet() {
        return $this->tweet->where('id', $this->tweet->retweeted_from)->first();
    }

    // $reply($tweet in file)->tweet->retweeted_from i need the number
}
