import type { CatalogFilters, Category, PaginatedResponse, Product } from '../types/catalog';

interface CategoriesResponse {
    data: Category[];
}

const jsonHeaders: HeadersInit = {
    Accept: 'application/json',
};

export class ApiHttpError extends Error {
    public readonly status: number;

    public readonly body: unknown;

    public constructor(status: number, body: unknown) {
        super(`HTTP ${String(status)}`);
        this.name = 'ApiHttpError';
        this.status = status;
        this.body = body;
    }
}

export function isLaravelValidationBody(body: unknown): body is { errors: Record<string, string[]> } {
    if (body === null || typeof body !== 'object') {
        return false;
    }

    const errors = (body as { errors?: unknown }).errors;
    if (errors === null || typeof errors !== 'object') {
        return false;
    }

    return Object.values(errors).every(
        (messages) => Array.isArray(messages) && messages.every((m) => typeof m === 'string'),
    );
}

function toQueryString(params: Record<string, string | number | boolean | undefined>): string {
    const searchParams = new URLSearchParams();

    Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined) {
            searchParams.set(key, String(value));
        }
    });

    const query = searchParams.toString();

    return query.length > 0 ? `?${query}` : '';
}

async function readJsonOrThrow(response: Response): Promise<unknown> {
    try {
        return await response.json();
    } catch {
        throw new ApiHttpError(response.status, { message: 'Ответ сервера не является JSON' });
    }
}

function assertOkResponse(response: Response, payload: unknown): void {
    if (response.ok !== false) {
        return;
    }

    throw new ApiHttpError(response.status, payload);
}

export async function fetchCategories(): Promise<Category[]> {
    const response = await fetch('/api/product-categories', { headers: jsonHeaders });
    const payload = await readJsonOrThrow(response);
    assertOkResponse(response, payload);

    return (payload as CategoriesResponse).data;
}

export async function fetchProducts(params: {
    filters?: CatalogFilters;
    page?: number;
    perPage?: number;
}): Promise<PaginatedResponse<Product>> {
    const filters = params.filters ?? {};
    const query = toQueryString({
        q: filters.q,
        price_from: filters.priceFrom,
        price_to: filters.priceTo,
        category_id: filters.categoryId,
        in_stock: filters.inStock,
        rating_from: filters.ratingFrom,
        sort: filters.sort,
        page: params.page,
        per_page: params.perPage,
    });
    const response = await fetch(`/api/products${query}`, { headers: jsonHeaders });
    const payload = await readJsonOrThrow(response);
    assertOkResponse(response, payload);

    return payload as PaginatedResponse<Product>;
}
