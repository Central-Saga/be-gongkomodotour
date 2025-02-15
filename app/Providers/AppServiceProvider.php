<?php

namespace App\Providers;

use App\Repositories\Contracts\FlightScheduleRepositoryInterface;
use App\Repositories\Contracts\ItinerariesRepositoryInterface;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\TripRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\Implementations\RoleService;
use App\Services\Implementations\TripService;
use App\Services\Implementations\UserService;
use App\Services\Contracts\RoleServiceInterface;
use App\Services\Contracts\TripServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\TripPricesRepository;
use App\Services\Contracts\CustomersServiceInterface;
use App\Repositories\Contracts\CustomersRepositoryInterface;
use App\Repositories\Eloquent\CustomersRepository;
use App\Services\Implementations\CustomersService;
use App\Repositories\Contracts\HotelOccupanciesRepositoryInterface;
use App\Services\Contracts\HotelOccupanciesServiceInterface;
use App\Repositories\Eloquent\HotelOccupanciesRepository;
use App\Services\Implementations\HotelOccupanciesService;
use App\Services\Implementations\PermissionService;
use App\Services\Implementations\TripPricesService;
use App\Repositories\Eloquent\TripDurationRepository;
use App\Services\Contracts\PermissionServiceInterface;
use App\Services\Contracts\TripPricesServiceInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\TripRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\BoatServiceInterface;
use App\Services\Implementations\BoatService;
use App\Repositories\Eloquent\BoatRepository;
use App\Repositories\Contracts\BoatRepositoryInterface;
use App\Repositories\Eloquent\CabinRepository;
use App\Repositories\Contracts\CabinRepositoryInterface;
use App\Services\Contracts\CabinServiceInterface;
use App\Services\Implementations\CabinService;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\TripPricesRepositoryInterface;
use App\Repositories\Contracts\TripDurationRepositoryInterface;
use App\Repositories\Eloquent\FlightScheduleRepository;
use App\Repositories\Eloquent\ItinerariesRepository;
use App\Services\Contracts\FlightScheduleServiceInterface;
use App\Services\Contracts\ItinerariesServiceInterface;
use App\Services\Implementations\FlightScheduleService;
use App\Services\Implementations\ItinerariesService;

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

        // Binding TripRepositoryInterface to TripRepository
        $this->app->bind(TripRepositoryInterface::class, TripRepository::class);

        // Binding TripServiceInterface to TripService
        $this->app->bind(TripServiceInterface::class, TripService::class);

        // Binding TripPricesRepositoryInterface to TripPricesRepository
        $this->app->bind(TripPricesRepositoryInterface::class, TripPricesRepository::class);

        // Binding TripPricesServiceInterface to TripPricesService
        $this->app->bind(TripPricesServiceInterface::class, TripPricesService::class);

        // Binding TripDurationRepositoryInterface to TripDurationRepository
        $this->app->bind(TripDurationRepositoryInterface::class, TripDurationRepository::class);

        // Binding TripDurationServiceInterface to TripDurationService
        $this->app->bind(TripDurationServiceInterface::class, TripDurationService::class);

        // Binding ItinerariesRepositoryInterface to ItinerariesRepository
        $this->app->bind(ItinerariesRepositoryInterface::class, ItinerariesRepository::class);

        // Binding ItinerariesServiceInterface to ItinerariesService
        $this->app->bind(ItinerariesServiceInterface::class, ItinerariesService::class);

        // Binding FlightScheduleRepositoryInterface to FlightScheduleRepository
        $this->app->bind(FlightScheduleRepositoryInterface::class, FlightScheduleRepository::class);

        // Binding FlightScheduleServiceInterface to FlightScheduleService
        $this->app->bind(FlightScheduleServiceInterface::class, FlightScheduleService::class);

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
