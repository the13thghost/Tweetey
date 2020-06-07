<?php

namespace App;

trait Followable {

    public function follows() {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id');
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'follows', 'following_user_id', 'user_id');
    }

    public function follow(User $user) {
        return $this->follows()->attach($user); // save as save($user)
    }
 
    public function unfollow(User $user) {
        return $this->follows()->detach($user);
    }

    public function isFollowing(User $user) {
        return $this->follows->contains('id', $user->id);
    }

    public function toggleFollow(User $user) {
        if(current_user()->isFollowing($user)) { 
            return $this->unfollow($user);
        }
        return $this->follow($user);
    }
}