<?php

namespace App;

trait Likable {
    
    public function toggleLike($user) {
        $arr = $this->likes()->where('user_id', auth()->id())->pluck('like')->first(); // if the tweet is liked/disliked by current user
        if($arr == 0) { 
            $this->checkDislike($user); //check if the tweet was previous disliked by auth user, if yes > set to 0
            return $this->like($user); //update or create > set to 1 or create a new record and set to 1
        } 
        $check = $this->checkIfLiked('dislike', auth()->id()); // record in DB is 1 > unlike
        if($check == false) {     
            return $this->likes()->where('user_id', auth()->user()->id)->first()->delete();
             // if dislike is 0 > we want to unlike > 2 zeros > delete record
        }
        return $this->unlike($user);        
    }

    public function toggleDislike($user) {
        $arr = $this->likes()->where('user_id', auth()->user()->id)->pluck('dislike')->first();
        if($arr == 0) {
            $this->checkLike($user);
            return $this->dislike($user);
        }
        $check = $this->checkIfLiked('like', auth()->user()->id); 
        if($check == false) {   
            return $this->likes()->where('user_id', auth()->user()->id)->first()->delete();
        }
        return $this->undislike($user);
    }

    // check if like is 1
    public function checkLike($user) {
        $arr = $this->likes()->where('user_id', auth()->user()->id)->pluck('like')->first();
        if((bool) $arr == true) {
            return $this->unlike($user);
        }
    }

    public function checkDislike($user) {
        $arr = $this->likes()->where('user_id', auth()->user()->id)->pluck('dislike')->first();
        if((bool) $arr == true) {
            return $this->undislike($user);
        }
    }

    public function like($user = null, $like = 1) { 
        return $this->likes()->updateOrCreate([
            'user_id' => $user ? $user->id : auth()->id()
        ],[ 
            'like' => $like
        ]);
    }

    public function unlike($user = null) {
        return $this->like($user, 0);
    }

    public function dislike($user = null, $dislike = 1) {
        return $this->likes()->updateOrCreate([
            'user_id' => $user ? $user->id : auth()->id()
        ],
        [
            'dislike' => $dislike
        ]);
    }

    public function undislike($user = null) {
        return $this->dislike($user, 0);
    }

    public function checkIfLiked($liked, $userid) { // check if user has liked the tweet
        $arr = $this->likes()->where('user_id', $userid)->pluck($liked)->first();
        if((bool) $arr == true) {
            return true;
        }
        return false; 
    }

    public function scopeWithLikes() { 
        // $query->leftJoinSub($query, $as, $first)
        return $this->leftJoinSub('SELECT tweet_id, sum(`like`) `likes`, sum(`dislike`) `dislikes` FROM `likes` group by tweet_id',
        'likes', 'tweets.id', 'likes.tweet_id'); 
    }    

    public function scopeWithUpdatedAt() {
        return $this->leftJoinSub('SELECT tweet_id, updated_at AS updated_at_likes FROM likes', 'likes', 'tweets.id', 'likes.tweet_id');
    }

    // select * from `tweets` left join (select `tweet_id`, `updated_at` AS `updated_at_likes` from `likes`) 
    // likes on tweets.id = likes.tweet_id
}
