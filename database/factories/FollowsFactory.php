<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Follows;
use App\Model;
use App\User;
use Faker\Generator as Faker;

$factory->define(Follows::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'following_user_id' => factory(User::class)
    ];
});
