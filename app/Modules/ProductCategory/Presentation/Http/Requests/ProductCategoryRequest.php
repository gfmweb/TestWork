<?php

namespace App\Modules\ProductCategory\Presentation\Http\Requests;

use App\Modules\ProductCategory\Application\ProductCategoryCriteriaDTO;
use Illuminate\Foundation\Http\FormRequest;

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
        return [];
    }

    public function toCriteria(): ProductCategoryCriteriaDTO
    {
        return new ProductCategoryCriteriaDTO;
    }
}
