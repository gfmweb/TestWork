<?php

namespace App\Modules\ProductCategory\Presentation\Http\Requests;

use App\Modules\ProductCategory\Application\ProductCategoryCriteriaDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'with_trashed' => ['nullable', Rule::in(['0', '1', 'true', 'false'])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'with_trashed.in' => __('product-category::validation.with_trashed.in'),
        ];
    }

    public function toCriteria(): ProductCategoryCriteriaDTO
    {
        $validated = $this->validated();

        $withTrashed = false;
        if (array_key_exists('with_trashed', $validated) && $validated['with_trashed'] !== null) {
            $withTrashed = filter_var($validated['with_trashed'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? false;
        }

        return new ProductCategoryCriteriaDTO(
            withTrashed: $withTrashed,
        );
    }
}
