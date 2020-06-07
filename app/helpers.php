<?php

use App\User;

function current_user() {
        return auth()->user();
}