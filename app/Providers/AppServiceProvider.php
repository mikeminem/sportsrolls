<?php

namespace App\Providers;

use App\PageCategory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        //
        view()->composer("*", function($view)
        {
            $pageCategories = PageCategory::where('status','Mostrar')->get();
            $view->with('pageCategories', $pageCategories);
        });

        
    }
}
