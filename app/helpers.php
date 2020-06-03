<?php

use App\User;

function current_user() {
        return auth()->user();
    }

// function userVar(User $user) {
//     return $user->follows();
// }