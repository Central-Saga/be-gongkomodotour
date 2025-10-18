<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetStoreRequest extends FormRequest
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
            'model_type' => 'required|string|in:gallery,boat,cabin,transaction,blog,trip,carousel',
            'model_id' => 'required|integer|exists:' . $this->getModelTable($this->model_type) . ',id',
            'file' => 'required_without:file_url|file|mimes:jpeg,png,jpg,gif,webp,avif|max:10240',
            'file_url' => 'required_without:file|url',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_external' => 'nullable|boolean',
        ];
    }


    /**
     * Get model table name based on model type.
     *
     * @param string $modelType
     * @return string
     */
    protected function getModelTable($modelType)
    {
        $tables = [
            'gallery' => 'galleries',
            'boat' => 'boat',
            'cabin' => 'cabin',
            'transaction' => 'transactions',
            'blog' => 'blog',
            'trip' => 'trips',
            'carousel' => 'carousel',
        ];

        return $tables[$modelType] ?? 'galleries';
    }
}
