<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Ambil ID user dari parameter route
        $userId = $this->route('user');

        // Gunakan caching untuk mengambil user dengan role-nya
        $user = Cache::remember("user_{$userId}_with_roles", 3600, function () use ($userId) {
            return User::with('roles')->findOrFail($userId);
        });

        return [
            'name' => 'sometimes|required|string|max:50|min:3',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:50',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'sometimes|required|string|min:8|confirmed',
            'password_confirmation' => 'sometimes|required_with:password|string|min:8|same:password',
            'role' => 'sometimes|required|string|exists:roles,name',
            'status' => [
                'sometimes',
                'required',
                'in:Aktif,Non Aktif',
                function ($attribute, $value, $fail) use ($user) {
                    // Validasi jika user memiliki role "Super Admin", status tidak bisa diubah menjadi Non Aktif
                    if ($user->hasRole('Super Admin') && $value === 'Non Aktif') {
                        $fail('User dengan role Super Admin tidak dapat di-nonaktifkan.');
                    }
                },
            ],
        ];
    }
}
