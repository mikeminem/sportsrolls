<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\User;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //

        view()->composer("*", function($view)
        {
            if (Auth::check()) {
                $role = User::getRole(Auth::user()->role);
                $view->with('role', $role);
            }
            
        });
    }
}
