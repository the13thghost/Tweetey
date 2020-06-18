<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function() {
    Route::get('/tweets', 'TweetController@index')->name('home');
    Route::post('/tweets', 'TweetController@store');
    Route::post('/retweets/{tweet}', 'TweetController@retweet');
    Route::get('/tweet/{tweet}', 'TweetController@show'); //thread
    Route::post('/retweets/{tweet}/getTweet', 'TweetController@getTweet'); 
    Route::post('/retweets/{tweet}/comment', 'TweetController@retweetComment');
    Route::put('/profile/{user}/bio', 'ProfileController@updateBio');
    Route::post('/replies/{tweet}', 'ReplyController@store'); 
    //dynamic nav
    Route::get('/profile/{user:username}/with-replies-res', 'ProfileController@withRepliesRes');
    Route::get('/profile/{user:username}/with-replies', 'ProfileController@withReplies');
    Route::get('/profile/{user:username}/tweets', 'ProfileController@tweetsNav');
    Route::get('/profile/{user:username}/media-res', 'ProfileController@mediaRes');
    Route::get('/profile/{user:username}/media', 'ProfileController@media');
    Route::get('/profile/{user:username}/likes-res', 'ProfileController@likesRes');
    Route::get('/profile/{user:username}/likes', 'ProfileController@likes');



    Route::delete('/unretweets/{tweet}', 'TweetController@unretweet');
    Route::post('/profile/{user:username}/follow', 'FollowsController');
    Route::get('/profile/{user:username}', 'ProfileController@show')->name('profile');
    Route::patch('/profile/{user:username}', 'ProfileController@update');
    Route::post('/tweets/{tweet}/like', 'TweetsLikeController@storeLike');
    Route::post('/tweets/{tweet}/dislike', 'TweetsLikeController@storeDislike');
    Route::delete('/tweets/{tweet:id}/delete', 'TweetController@destroy');
    Route::delete('/tweets/comment/{tweet:id}/delete', 'TweetController@deleteComment'); 
    Route::get('/profile/{user:username}/edit', 'ProfileController@edit')->middleware('can:edit,user')->name('edit-profile');
});
