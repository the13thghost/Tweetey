<?php

namespace App;

trait Likable {
    
    public function toggleLike($user) 
    {
        $arr = $this->likes()->where('user_id', auth()->id())->pluck('like')->first(); // If the tweet is liked/disliked by current user
        if($arr == 0) { 
            $this->checkDislike($user); // Check if the tweet was previous disliked by auth user, if yes > set to 0
            return $this->like($user); // Update or create > set to 1 or create a new record and set to 1
        } 
        $check = $this->checkIfLiked('dislike', auth()->id()); // Record in DB is 1 > unlike
        if($check == false) {   
             // If dislike is 0 > unlike it > 2 zeros > delete record
            return $this->likes()->where('user_id', auth()->user()->id)->first()->delete();
        }
        return $this->unlike($user);        
    }

    public function toggleDislike($user) 
    {
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

    // Check if like is 1
    public function checkLike($user) 
    {
        $arr = $this->likes()->where('user_id', auth()->user()->id)->pluck('like')->first();
        if((bool) $arr == true) {
            return $this->unlike($user);
        }
    }

    public function checkDislike($user) 
    {
        $arr = $this->likes()->where('user_id', auth()->user()->id)->pluck('dislike')->first();
        if((bool) $arr == true) {
            return $this->undislike($user);
        }
    }

    public function like($user = null, $like = 1) 
    { 
        return $this->likes()->updateOrCreate([
            'user_id' => $user ? $user->id : auth()->id()
        ],[ 
            'like' => $like
        ]);
    }

    public function unlike($user = null) 
    {
        return $this->like($user, 0);
    }

    public function dislike($user = null, $dislike = 1) 
    {
        return $this->likes()->updateOrCreate([
            'user_id' => $user ? $user->id : auth()->id()
        ],
        [
            'dislike' => $dislike
        ]);
    }

    public function undislike($user = null) 
    {
        return $this->dislike($user, 0);
    }

    public function checkIfLiked($liked, $userid) 
    {   // Check if user has liked the tweet
        $arr = $this->likes()->where('user_id', $userid)->pluck($liked)->first();
        if((bool) $arr == true) {
            return true;
        }
        return false; 
    }

    public function scopeWithLikes() 
    { 
        return $this->leftJoinSub('SELECT tweet_id, sum(`like`) `likes`, sum(`dislike`) `dislikes` FROM `likes` group by tweet_id',
        'likes', 'tweets.id', 'likes.tweet_id'); 
    }    

    public function scopeWithUpdatedAt() 
    {
        return $this->leftJoinSub('SELECT tweet_id, likes.updated_at AS updated_at_likes, sum(`like`) `likes`, sum(`dislike`) `dislikes` FROM `likes` group by tweet_id',
        'likes', 'tweets.id', 'likes.tweet_id');
    }   
}
