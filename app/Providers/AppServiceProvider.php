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
use App\Services\Contracts\CustomersServiceInterface;
use App\Repositories\Contracts\CustomersRepositoryInterface;
use App\Repositories\Eloquent\CustomersRepository;
use App\Services\Implementations\CustomersService;
use App\Repositories\Contracts\HotelOccupanciesRepositoryInterface;
use App\Services\Contracts\HotelOccupanciesServiceInterface;
use App\Repositories\Eloquent\HotelOccupanciesRepository;
use App\Services\Implementations\HotelOccupanciesService;
use App\Services\Implementations\PermissionService;
use App\Services\Contracts\PermissionServiceInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\BoatServiceInterface;
use App\Services\Implementations\BoatService;
use App\Repositories\Eloquent\BoatRepository;
use App\Repositories\Contracts\BoatRepositoryInterface;
use App\Repositories\Eloquent\CabinRepository;
use App\Repositories\Contracts\CabinRepositoryInterface;
use App\Services\Contracts\CabinServiceInterface;
use App\Services\Implementations\CabinService;
use App\Repositories\Eloquent\EmailBlastRepository;
use App\Repositories\Contracts\EmailBlastRepositoryInterface;
use App\Services\Contracts\EmailBlastServiceInterface;
use App\Services\Implementations\EmailBlastService;
use App\Repositories\Eloquent\EmailBlastRecipientRepository;
use App\Repositories\Contracts\EmailBlastRecipientRepositoryInterface;
use App\Services\Contracts\EmailBlastRecipientServiceInterface;
use App\Services\Implementations\EmailBlastRecipientService;
use App\Repositories\Eloquent\BlogRepository;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Services\Contracts\BlogServiceInterface;
use App\Services\Implementations\BlogService;
use App\Repositories\Eloquent\SubscriberRepository;
use App\Repositories\Contracts\SubscriberRepositoryInterface;
use App\Services\Contracts\SubscriberServiceInterface;
use App\Services\Implementations\SubscriberService;
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

        // Binding CustomersRepositoryInterface to CustomersRepository
        $this->app->bind(CustomersRepositoryInterface::class, CustomersRepository::class);

        // Binding CustomersServiceInterface to CustomersService
        $this->app->bind(CustomersServiceInterface::class, CustomersService::class);

        // Binding HotelOccupanciesRepositoryInterface to HotelOccupanciesRepository
        $this->app->bind(HotelOccupanciesRepositoryInterface::class, HotelOccupanciesRepository::class);

        // Binding HotelOccupanciesServiceInterface to HotelOccupanciesService
        $this->app->bind(HotelOccupanciesServiceInterface::class, HotelOccupanciesService::class);

        // Binding BoatRepositoryInterface to BoatRepository
        $this->app->bind(BoatRepositoryInterface::class, BoatRepository::class);

        // Binding BoatServiceInterface to BoatService
        $this->app->bind(BoatServiceInterface::class, BoatService::class);

        // Binding CabinRepositoryInterface to CabinRepository
        $this->app->bind(CabinRepositoryInterface::class, CabinRepository::class);

        // Binding CabinServiceInterface to CabinService
        $this->app->bind(CabinServiceInterface::class, CabinService::class);

        // Binding EmailBlastRepositoryInterface to EmailBlastRepository
        $this->app->bind(EmailBlastRepositoryInterface::class, EmailBlastRepository::class);

        // Binding EmailBlastServiceInterface to EmailBlastService
        $this->app->bind(EmailBlastServiceInterface::class, EmailBlastService::class);

        // Binding EmailBlastRecipientRepositoryInterface to EmailBlastRecipientRepository
        $this->app->bind(EmailBlastRecipientRepositoryInterface::class, EmailBlastRecipientRepository::class);

        // Binding EmailBlastRecipientServiceInterface to EmailBlastRecipientService
        $this->app->bind(EmailBlastRecipientServiceInterface::class, EmailBlastRecipientService::class);

        // Binding BlogRepositoryInterface to BlogRepository
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);

        // Binding BlogServiceInterface to BlogService
        $this->app->bind(BlogServiceInterface::class, BlogService::class);

        // Binding SubscriberRepositoryInterface to SubscriberRepository    
        $this->app->bind(SubscriberRepositoryInterface::class, SubscriberRepository::class);

        // Binding SubscriberServiceInterface to SubscriberService
        $this->app->bind(SubscriberServiceInterface::class, SubscriberService::class);
        
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
