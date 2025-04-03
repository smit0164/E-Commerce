<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Permission;

class AuthorizeProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Log the start of the boot method
        Log::info('AuthorizeProvider boot method started.');

        // Defer gate definitions until after all other boot methods have run
        $this->app->booted(function () {
            // Retrieve all permissions
            $permissions = Permission::all();

            // Log the number of permissions retrieved
            Log::info('Number of permissions retrieved: ' . $permissions->count());

            // Define Gates for Permissions
            $permissions->each(function ($permission) {
                Gate::define($permission->slug, function ($admin) use ($permission) {
                    Log::info("inside gate");
                    $hasPermission = $admin->role->permissions->contains('slug', $permission->slug);

                    // Log the result of the permission check
                    Log::info("Gate check for permission '{$permission->slug}': " . ($hasPermission ? 'Granted' : 'Denied'));

                    return $hasPermission;
                });

                // Log the definition of each gate
                Log::info("Gate defined for permission: {$permission->slug}");
            });
        });

        // Log the end of the boot method
        Log::info('AuthorizeProvider boot method completed.');
    }
}