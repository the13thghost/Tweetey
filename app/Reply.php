<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    //
    protected $fillable = ['tweet_id', 'user_id', 'reply'];

    public function tweet() {
        return $this->belongsTo(Tweet::class);
    }
}
