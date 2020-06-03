<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    //
    public function publishedTweet() {
        // $notification = auth()->user()->notifications->first();
        // $notification->markAsRead();
        // return 
        // request()->user()->notify(new TweetPublished); // insert in notifications table, display the content via ajax
        // use this rather for real notifcation
        // {{current_user()->notifications->first()->data['tweet published']}}
    }
}
