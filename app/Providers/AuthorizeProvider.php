<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class AuthorizeProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Log::info("AuthorizeProvider registered.");
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Gate::define('manage-categories', function ($admin) {
        //     $hasPermission = $admin->hasPermission('manage-categories');
        //     return $hasPermission;
        // });
    
        // Gate::define('manage-products', function ($admin) {
        //     $hasPermission = $admin->hasPermission('manage-products');
        //     return $hasPermission;
        // });

        // Gate::define('manage-orders', function ($admin) {
        //    $hasPermission = $admin->hasPermission('manage-orders');
        //     return $hasPermission;
        // });

        // Gate::define('manage-dashboard', function ($admin) {
        //     $hasPermission = $admin->hasPermission('manage-dashboard');
        //      return $hasPermission;
        // });

        // Gate::define('manage-orders', function ($admin) {
        //    $hasPermission = $admin->hasPermission('manage-orders');
        //     return $hasPermission;
        // });

        // Gate::define('manage-static-blocks', function ($admin) {
        //     $hasPermission = $admin->hasPermission('manage-static-blocks');
        //      return $hasPermission;
        // });

        // Gate::define('manage-static-page', function ($admin) {
        //     $hasPermission = $admin->hasPermission('manage-static-page');
        //      return $hasPermission;
        // });
        $permissions = [
            'manage-categories',
            'manage-products',
            'manage-orders',
            'manage-dashboard',
            'manage-static-blocks',
            'manage-static-page',
            'manage-users'
        ];
        foreach ($permissions as $permission) {
            Gate::define($permission, function ($admin) use ($permission) {
                return $admin->hasPermission($permission);
            });
        }
             
        Gate::before(function ($admin, $permission) {
            return $admin->isSuperAdmin() ? true : null;
        });
    
    }
    
}