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
                $role = Auth::user()->roles->pluck('name')->implode(', ');
                $view->with('role', $role);
            }
            
        });
    }
}
