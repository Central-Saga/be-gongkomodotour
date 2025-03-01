<?php

namespace App\Providers;

use App\Models\Booking;
use App\Observers\BookingObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\BlogRepository;
use App\Repositories\Eloquent\BoatRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\TripRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\Implementations\BlogService;
use App\Services\Implementations\BoatService;
use App\Services\Implementations\RoleService;
use App\Services\Implementations\TripService;
use App\Services\Implementations\UserService;
use App\Repositories\Eloquent\CabinRepository;
use App\Services\Implementations\CabinService;
use App\Repositories\Eloquent\BookingRepository;
use App\Repositories\Eloquent\GalleryRepository;
use App\Services\Contracts\BlogServiceInterface;
use App\Services\Contracts\BoatServiceInterface;
use App\Services\Contracts\RoleServiceInterface;
use App\Services\Contracts\TripServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Implementations\BookingService;
use App\Services\Implementations\GalleryService;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Services\Contracts\CabinServiceInterface;
use App\Repositories\Eloquent\CustomersRepository;
use App\Repositories\Eloquent\SurchargeRepository;
use App\Services\Implementations\CustomersService;
use App\Repositories\Eloquent\BookingFeeRepository;
use App\Repositories\Eloquent\EmailBlastRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\SubscriberRepository;
use App\Repositories\Eloquent\TripPricesRepository;
use App\Services\Contracts\BookingServiceInterface;
use App\Services\Contracts\GalleryServiceInterface;
use App\Services\Implementations\EmailBlastService;
use App\Services\Implementations\PermissionService;
use App\Services\Implementations\SubscriberService;
use App\Services\Implementations\TripPricesService;
use App\Repositories\Eloquent\BankAccountRepository;
use App\Repositories\Eloquent\ItinerariesRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Services\Implementations\BankAccountService;
use App\Services\Implementations\ItinerariesService;
use App\Services\Implementations\TransactionService;
use App\Repositories\Eloquent\TripDurationRepository;
use App\Services\Contracts\CustomersServiceInterface;
use App\Services\Implementations\TripDurationService;
use App\Repositories\Eloquent\AdditionalFeeRepository;
use App\Services\Contracts\EmailBlastServiceInterface;
use App\Services\Contracts\PermissionServiceInterface;
use App\Services\Contracts\SubscriberServiceInterface;
use App\Services\Contracts\TripPricesServiceInterface;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\Contracts\BoatRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\TripRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\FlightScheduleRepository;
use App\Services\Contracts\BankAccountServiceInterface;
use App\Services\Contracts\ItinerariesServiceInterface;
use App\Services\Contracts\TransactionServiceInterface;
use App\Services\Implementations\FlightScheduleService;
use App\Repositories\Contracts\CabinRepositoryInterface;
use App\Services\Contracts\TripDurationServiceInterface;
use App\Repositories\Eloquent\HotelOccupanciesRepository;
use App\Services\Implementations\HotelOccupanciesService;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\GalleryRepositoryInterface;
use App\Services\Contracts\FlightScheduleServiceInterface;
use App\Repositories\Contracts\CustomersRepositoryInterface;
use App\Repositories\Contracts\SurchargeRepositoryInterface;
use App\Repositories\Eloquent\EmailBlastRecipientRepository;
use App\Services\Contracts\HotelOccupanciesServiceInterface;
use App\Services\Implementations\EmailBlastRecipientService;
use App\Repositories\Contracts\BookingFeeRepositoryInterface;
use App\Repositories\Contracts\EmailBlastRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\SubscriberRepositoryInterface;
use App\Repositories\Contracts\TripPricesRepositoryInterface;
use App\Repositories\Contracts\BankAccountRepositoryInterface;
use App\Repositories\Contracts\ItinerariesRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\TripDurationRepositoryInterface;
use App\Services\Contracts\EmailBlastRecipientServiceInterface;
use App\Repositories\Contracts\AdditionalFeeRepositoryInterface;
use App\Repositories\Contracts\FlightScheduleRepositoryInterface;
use App\Repositories\Contracts\HotelOccupanciesRepositoryInterface;
use App\Repositories\Contracts\EmailBlastRecipientRepositoryInterface;
use App\Services\Contracts\AssetServiceInterface;
use App\Services\Implementations\AssetService;
use App\Repositories\Contracts\AssetRepositoryInterface;
use App\Repositories\Eloquent\AssetRepository;

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

        // Binding AdditionalFeeRepositoryInterface to AdditionalFeeRepository
        $this->app->bind(AdditionalFeeRepositoryInterface::class, AdditionalFeeRepository::class);

        // Binding BookingFeeRepositoryInterface to BookingFeeRepository
        $this->app->bind(BookingFeeRepositoryInterface::class, BookingFeeRepository::class);

        // Binding BookingServiceInterface to BookingService
        $this->app->bind(BookingServiceInterface::class, BookingService::class);

        // Binding BookingRepositoryInterface to BookingRepository
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);

        // Binding SurchargeRepositoryInterface to SurchargeRepository
        $this->app->bind(SurchargeRepositoryInterface::class, SurchargeRepository::class);

        // Binding BankAccountRepositoryInterface to BankAccountRepository
        $this->app->bind(BankAccountRepositoryInterface::class, BankAccountRepository::class);

        // Binding BankAccountServiceInterface to BankAccountService
        $this->app->bind(BankAccountServiceInterface::class, BankAccountService::class);

        // Binding TransactionRepositoryInterface to TransactionRepository
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);

        // Binding TransactionServiceInterface to TransactionService
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);

        // Binding GalleryRepositoryInterface to GalleryRepository
        $this->app->bind(GalleryRepositoryInterface::class, GalleryRepository::class);

        // Binding GalleryServiceInterface to GalleryService
        $this->app->bind(GalleryServiceInterface::class, GalleryService::class);

        // Binding AssetRepositoryInterface to AssetRepository
        $this->app->bind(AssetRepositoryInterface::class, AssetRepository::class);

        // Binding AssetServiceInterface to AssetService
        $this->app->bind(AssetServiceInterface::class, AssetService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Booking::observe(BookingObserver::class);

        Schema::defaultStringLength(125);
    }
}
