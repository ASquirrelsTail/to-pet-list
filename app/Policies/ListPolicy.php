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

    public function create(?User $user)
    {
        return isset($user);
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(?User $user, ?TList $list)
    {
        if (!$list) {
            return isset($user);
        }
        return ($list->public || 
                (isset($user) && 
                    ($list->user_id == $user->id || 
                     $list->shares()->where('user_id', $user->id)->first())));
    }

    public function update($user, TList $list)
    {
        return $list->user_id == $user->id;
    }

    public function delete($user, TList $list)
    {
        return $this->update($user, $list);
    }
}
