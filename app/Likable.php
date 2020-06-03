<?php

namespace App;

trait Likable {
    
    
    public function toggleLike($user) {
        $arr = $this->likes()->where('user_id', auth()->user()->id)->pluck('like')->first(); // get from collection: the conn, if this tweet is liked/dislike by current user
        if($arr == 0) { // if in the db is 
            $this->checkDislike($user); //check if this tweet  was previous disliked by me, if yes > set to 0
            return $this->like($user); //update or create now update it and set to 1, or create a new record set 1
        } 
        $check = $this->checkIfLiked('dislike', auth()->user()->id); // our record in DB was 1 (we want to unlike)->check the dislike (it cant be 1)
        if($check == false) {     
            return $this->likes()->where('user_id', auth()->user()->id)->first()->delete();
             // if the dislike is 0 (now we want to unlike which means 2 zeros ->delete)
            //but need to check if it was disliked by anyone else (otherwise it will remove all the records)
            // silly, just delete where the tweet id is this much and the current user id is this much
        }
        return $this->unlike($user);        
    }

    public function toggleDislike($user) {
        $arr = $this->likes()->where('user_id', auth()->user()->id)->pluck('dislike')->first();
        if($arr == 0) {
            $this->checkLike($user);
            return $this->dislike($user);
        }
        $check = $this->checkIfLiked('like', auth()->user()->id); // cant be true  because it cant be in DB 1, 1 only 1, 0 or 0,1
        if($check == false) {   
            return $this->likes()->where('user_id', auth()->user()->id)->first()->delete();
        }
        return $this->undislike($user);
    }

       //check if like is 1
    //return (bool) $arr; true or false

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

    public function like($user = null, $like = 1) { // save or update record
        return $this->likes()->updateOrCreate([
            'user_id' => $user ? $user->id : auth()->id()
        ],[ 
            'like' => $like
        ]);
    }

    public function unlike($user = null) {
        return $this->like($user, 0);
    }

    public function dislike($user = null, $dislike = 1) { // save or update record
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

    public function checkIfLiked($liked, $userid) { // check if the user has liked it
        $arr = $this->likes()->where('user_id', $userid)->pluck($liked)->first();
        if((bool) $arr == true) {
            return true;
        }
        return false;
    }

    public function scopeWithLikes() { // aditional query
        // $query->leftJoinSub($query, $as, $first)
        return $this->leftJoinSub('SELECT tweet_id, sum(`like`) `likes`, sum(`dislike`) `dislikes` FROM `likes` group by tweet_id',
    'likes', 'tweets.id', 'likes.tweet_id'); 
    }    

    
}
