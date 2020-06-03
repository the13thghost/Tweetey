<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{

    use Likable;

    protected $fillable = ['user_id', 'body', 'retweeted_from', 'comment'];

    public function user() { 
        return $this->belongsTo(User::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function retweets() { // like for counting how many retweets has one tweet
        return $this->hasMany(Retweet::class);
    }

    public function retweetsNumber() {
        return $this->retweets->count();
    }

    public function retweetOrigi() {
        return $this->where('id', $this->retweeted_from)->first();
    }

    public function retweetsFromAuthUser() {
        return $this->user->is(auth()->user()) && !is_null($this->retweeted_from) && is_null($this->comment);
    }
   
    
}

