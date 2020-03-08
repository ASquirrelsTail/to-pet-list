<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\User;
use App\Share;

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
    }
}
