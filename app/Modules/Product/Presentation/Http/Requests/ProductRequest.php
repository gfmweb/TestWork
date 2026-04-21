<?php

namespace App\Modules\Product\Presentation\Http\Requests;

use App\Modules\Product\Application\ProductCriteriaDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            'q' => ['nullable', 'string', 'min:1', 'max:255'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0', 'gte:price_from'],
            'category_id' => ['nullable', 'integer', 'exists:product_categories,id'],
            'in_stock' => ['nullable', Rule::in(['0', '1', 'true', 'false'])],
            'rating_from' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'sort' => ['nullable', Rule::in(['price_asc', 'price_desc', 'rating_desc', 'newest'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'q.string' => __('product::validation.q.string'),
            'q.min' => __('product::validation.q.min'),
            'q.max' => __('product::validation.q.max'),
            'price_from.numeric' => __('product::validation.price_from.numeric'),
            'price_from.min' => __('product::validation.price_from.min'),
            'price_to.numeric' => __('product::validation.price_to.numeric'),
            'price_to.min' => __('product::validation.price_to.min'),
            'price_to.gte' => __('product::validation.price_to.gte'),
            'category_id.integer' => __('product::validation.category_id.integer'),
            'category_id.exists' => __('product::validation.category_id.exists'),
            'in_stock.in' => __('product::validation.in_stock.in'),
            'rating_from.numeric' => __('product::validation.rating_from.numeric'),
            'rating_from.min' => __('product::validation.rating_from.min'),
            'rating_from.max' => __('product::validation.rating_from.max'),
            'sort.in' => __('product::validation.sort.in'),
            'per_page.integer' => __('product::validation.per_page.integer'),
            'per_page.min' => __('product::validation.per_page.min'),
            'per_page.max' => __('product::validation.per_page.max'),
            'page.integer' => __('product::validation.page.integer'),
            'page.min' => __('product::validation.page.min'),
        ];
    }

    public function toCriteria(): ProductCriteriaDTO
    {
        $validated = $this->validated();

        $searchQuery = isset($validated['q']) ? trim((string) $validated['q']) : null;
        if ($searchQuery === '') {
            $searchQuery = null;
        }

        $inStock = null;
        if (array_key_exists('in_stock', $validated) && $validated['in_stock'] !== null) {
            $inStock = filter_var($validated['in_stock'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        }

        return new ProductCriteriaDTO(
            perPage: (int) ($validated['per_page'] ?? 15),
            sort: (string) ($validated['sort'] ?? 'newest'),
            searchQuery: $searchQuery,
            priceFrom: isset($validated['price_from']) ? (float) $validated['price_from'] : null,
            priceTo: isset($validated['price_to']) ? (float) $validated['price_to'] : null,
            categoryId: isset($validated['category_id']) ? (int) $validated['category_id'] : null,
            inStock: $inStock,
            ratingFrom: isset($validated['rating_from']) ? (float) $validated['rating_from'] : null,
        );
    }
}
