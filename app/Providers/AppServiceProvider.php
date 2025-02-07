<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\Implementations\RoleService;
use App\Services\Implementations\UserService;
use App\Services\Contracts\RoleServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Repositories\Eloquent\PermissionRepository;
use App\Services\Implementations\PermissionService;
use App\Services\Contracts\PermissionServiceInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding PermissionRepositoryInterface to PermissionRepository
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        // Binding PermissionServiceInterface to PermissionService
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);

        // Binding RoleRepositoryInterface to RoleRepository
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        // Binding RoleServiceInterface to RoleService
        $this->app->bind(RoleServiceInterface::class, RoleService::class);

        // Binding UserRepositoryInterface to UserRepository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Binding UserServiceInterface to UserService
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Schema::defaultStringLength(125);
    }
}
