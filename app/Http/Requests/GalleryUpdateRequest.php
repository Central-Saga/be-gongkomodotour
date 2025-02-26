<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryUpdateRequest extends FormRequest
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
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:Aktif,Non Aktif',
            'file' => 'sometimes|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul galeri wajib diisi',
            'title.max' => 'Judul galeri maksimal 255 karakter',
            'category.required' => 'Kategori galeri wajib diisi',
            'category.max' => 'Kategori galeri maksimal 255 karakter',
            'status.required' => 'Status galeri wajib diisi',
            'status.in' => 'Status galeri harus Aktif atau Non Aktif',
            'file.file' => 'File harus berupa file',
            'file.mimes' => 'File harus berupa gambar (jpeg, png, jpg, gif)',
            'file.max' => 'Ukuran file maksimal 2MB',
        ];
    }
}
