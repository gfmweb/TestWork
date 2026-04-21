import type { Category, PaginatedResponse, Product } from '../types/catalog';

interface CategoriesResponse {
    data: Category[];
}

function toQueryString(params: Record<string, number | undefined>): string {
    const searchParams = new URLSearchParams();

    Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined) {
            searchParams.set(key, String(value));
        }
    });

    const query = searchParams.toString();

    return query.length > 0 ? `?${query}` : '';
}

export async function fetchCategories(): Promise<Category[]> {
    const response = await fetch('/api/product-categories');
    const payload = (await response.json()) as CategoriesResponse;

    return payload.data;
}

export async function fetchProducts(params: {
    categoryId?: number;
    page?: number;
    perPage?: number;
}): Promise<PaginatedResponse<Product>> {
    const query = toQueryString({
        category_id: params.categoryId,
        page: params.page,
        per_page: params.perPage,
    });
    const response = await fetch(`/api/products${query}`);

    return (await response.json()) as PaginatedResponse<Product>;
}
