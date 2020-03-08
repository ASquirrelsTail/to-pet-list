<?php

namespace App\Policies;

use Illuminate\Http\Request;

use App\User;
use App\Share;
use Illuminate\Auth\Access\HandlesAuthorization;

class SharePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->list = $request->route('list');
    }

    protected function checkListMembership(Share $share)
    {
        if ($share->list != $this->list) {
            abort(404);
        }
    }

    /**
     * Determine whether the user can view any shares.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return ($this->list->user_id == $user->id);
    }

    /**
     * Determine whether the user can view the share.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function view(User $user, Share $share)
    {
        $this->checkListMembership($share);
        return ($this->list->user_id == $user->id);
    }

    /**
     * Determine whether the user can create shares.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($this->list->user_id == $user->id);
    }

    /**
     * Determine whether the user can update the share.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function update(User $user, Share $share)
    {
        $this->checkListMembership($share);
        return ($this->list->user_id == $user->id);
    }

    /**
     * Determine whether the user can delete the share.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function delete(User $user, Share $share)
    {
        $this->checkListMembership($share);
        return ($this->list->user_id == $user->id);
    }
}
