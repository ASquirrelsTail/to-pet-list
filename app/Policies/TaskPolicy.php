<?php

namespace App\Policies;

use Illuminate\Http\Request;

use App\Task;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function __construct(Request $request)
    {
        $this->list = $request->route('list');
    }

    protected function checkListMembership(Task $task)
    {
        if ($task->list != $this->list) {
            abort(404);
        }
    }

    /**
     * Determine whether the user can view any tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the task.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function view(User $user, Task $task)
    {
        $this->checkListMembership($task);
        return true;
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($this->list->user_id == $user->id ||
            $this->list->shares()->where('user_id', $user->id)->where('create', true)->first());
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function update(User $user, Task $task)
    {
        $this->checkListMembership($task);
        return ($this->list->user_id == $user->id ||
            $this->list->shares()->where('user_id', $user->id)->where('update', true)->first());
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function delete(User $user, Task $task)
    {
        $this->checkListMembership($task);
        return ($this->list->user_id == $user->id ||
            $this->list->shares()->where('user_id', $user->id)->where('delete', true)->first());
    }

    /**
     * Determine whether the user can restore the task.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function restore(User $user, Task $task)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the task.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function forceDelete(User $user, Task $task)
    {
        //
    }

    public function complete(User $user, Task $task)
    {
        $this->checkListMembership($task);
        return ($this->list->user_id == $user->id ||
            $this->list->shares()->where('user_id', $user->id)->where('complete', true)->first());
    }
}
