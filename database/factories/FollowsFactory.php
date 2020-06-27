<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Follows;
use App\User;

$factory->define(Follows::class, function () 
{
    return [
        'user_id' => factory(User::class),
        'following_user_id' => factory(User::class)
    ];
});
