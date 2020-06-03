<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username','avatar','cover','email', 'password', 'bio' 
    ];

    public function setPasswordAttribute($value) {
        return $this->attributes['password'] = bcrypt($value);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute($value) {
        //return 'https://i.pravatar.cc/120';
        if(empty($value)) {
            return asset('/images/universal.png');
        }
        return asset('/storage/' . $value);
    }

    public function getCoverAttribute($value) {
        if(empty($value)) {
            return asset('/images/cover-universal.png');
        }
        return asset('/storage/' . $value);
    }

    public function tweets() {
        return $this->hasMany(Tweet::class);
    }

    public function latestTweet() {
        return $this->tweets()->latest()->first()->created_at->diffForHumans();
    }

    public function timeline() { // this is only for me for auth user
        // i want all mine tweets as well as of my friends
        //1. get all my tweets via tweets(), find out who i am following and get their tweets, org by latest
        //2. get my id, get ids of my friends via follows() put in an array get tweets wherein org by latest <-

        $friends = $this->follows->pluck('id');
        $timeline = $friends->push($this->id);
        return Tweet::whereIn('user_id', $timeline)->withLikes()->latest()->paginate(15);
    }

    public function path() {
        return route('profile', $this->username);
    }

    public function likes() {
        return $this->hasMany(Like::class); // shows all the connections collection to likes table both likes/dislikes
    }

    public function retweets() { // all that user has retweeted
        return $this->hasMany(Retweet::class);
    }


    public function retweeted(Tweet $tweet) {
        return $this->retweets()->where('tweet_id', $tweet->retweeted_from)->where('comment', 0)->exists();
    }

    public function retweetedOri(Tweet $tweet) {
        return $this->retweets()->where('tweet_id', $tweet->id)->where('comment', 0)->exists();
    }

    public function retweetedComment(Tweet $tweet) {
        return $this->retweets()->where('tweet_id', $tweet->retweeted_from)->where('comment', 1)->exists();
    }

    public function retweetedCommentOri(Tweet $tweet) {
        return $this->retweets()->where('tweet_id', $tweet->id)->where('comment', 1)->exists();

    }
  

}
