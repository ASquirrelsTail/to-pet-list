<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\User;
use App\Share;
use App\Task;

class AppServiceProvider extends ServiceProvider
{
    protected function updateShares($user)
    {

    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::created(function($user) {
            Share::where('email', $user->email)->each(function ($share, $key) use ($user) {
                $share->user()->associate($user);
                $share->save();
            });
        });

        User::updated(function($user) {
            Share::where('email', $user->email)->each(function ($share, $key) use ($user) {
                $share->user()->associate($user);
                $share->save();
            });
        });

        Task::created(function($task) {
            $last_task = $task->list->tasks()->orderBy('position', 'desc')->first();
            if ($last_task) {
                $task->position = $last_task->position + 1;
                $task->save();
            }
        });
    }
}
