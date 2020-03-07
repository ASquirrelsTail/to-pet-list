<?php

namespace App\Policies;

use App\User;
use App\TList;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;

class ListPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(?User $user)
    {
        return isset($user);
    }

    public function show(?User $user, TList $list)
    {
        return ($list->public || 
                (isset($user) && 
                    ($list->user_id == $user->id || 
                     $list->shares()->where('user_id', $user->id)->first())));
    }

    public function update($user, TList $list)
    {
        return $list->user_id == $user->id;
    }

    public function destroy($user, TList $list)
    {
        return $this->update($user, $list);
    }
}
