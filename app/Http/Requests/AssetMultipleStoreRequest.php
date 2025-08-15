<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetMultipleStoreRequest extends FormRequest
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

            // Untuk upload multiple file
            'files' => 'required_without:file_urls|array',
            'files.*' => 'file|mimetypes:image/jpeg,image/png,image/jpg,image/gif|max:10240',
            'file_titles' => 'sometimes|array',
            'file_titles.*' => 'string|max:255',
            'file_descriptions' => 'sometimes|array',
            'file_descriptions.*' => 'nullable|string',

            // Untuk multiple URL eksternal
            'file_urls' => 'required_without:files|array',
            'file_urls.*' => 'url',
            'file_url_titles' => 'sometimes|array',
            'file_url_titles.*' => 'string|max:255',
            'file_url_descriptions' => 'sometimes|array',
            'file_url_descriptions.*' => 'nullable|string',

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
