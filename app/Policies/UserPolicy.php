<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function edit(User $currentuser, User $user) 
    {
        return $currentuser->is($user)
                ? Response::allow()
                : Response::deny('The page you are looking for can\'t be accessed');
        
    } 

    public function noTweetsMsg(User $currentuser, User $user) 
    {
        return $currentuser->is($user);
    }
}
