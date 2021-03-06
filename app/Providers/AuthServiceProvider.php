<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\TList;
use App\Policies\ListPolicy;
use App\Task;
use App\Policies\TaskPolicy;
use App\Share;
use App\Policies\SharePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        TList::class => ListPolicy::class,
        Task::class => TaskPolicy::class,
        Share::class => SharePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
