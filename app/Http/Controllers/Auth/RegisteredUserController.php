<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use App\Models\Subscriber;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\UserResource;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        $customer = Customers::create([
            'user_id' => $user->id,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'nasionality' => $request->nasionality,
            'region' => $request->region,
            'status' => 'Aktif', // Status otomatis diatur ke "Aktif"
        ]);

        // Membuat subscriber baru
        $subscriber = Subscriber::create([
            'customer_id' => $customer->id,
            'email' => $request->email,
            'name' => $request->name,
            'status' => 'Aktif',
        ]);

        // Cari role 'Pelanggan'
        $pelangganRole = Role::where('name', 'Pelanggan')->first();

        // Jika role 'Pelanggan' ditemukan, berikan role tersebut ke user
        if ($pelangganRole) {
            $user->assignRole($pelangganRole);
        } else {
            // Jika role 'Pelanggan' tidak ditemukan, log error
            \Log::error('Role Pelanggan tidak ditemukan.');
            // Opsi lain: buat role jika belum ada
            $pelangganRole = Role::create(['name' => 'Pelanggan']);
            $user->assignRole($pelangganRole);
        }

        event(new Registered($user));

        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;
        $roles = $user->getRoleNames();
        $permissions = $user->getPermissionsViaRoles()->pluck('name');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
            'customer' => new CustomerResource($user->customer),
            'roles' => $roles,
            'permissions' => $permissions,
            'status' => 'Registration successful'
        ]);
    }
}
