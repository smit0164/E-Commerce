<?php

namespace App\Providers;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Model::preventLazyLoading(true);
        View::composer('layouts.users.app', function ($view) {
            $view->with('categories', Category::where('status','active')->get());
        });
        
    }
}
