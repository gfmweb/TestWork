export interface Category {
    id: number;
    name: string;
    products_count: number;
}

export interface Product {
    id: number;
    name: string;
    price: string;
    category_id: number;
    in_stock: boolean;
    rating: number;
    created_at: string;
}

export type ProductSort = 'price_asc' | 'price_desc' | 'rating_desc' | 'newest';

export interface CatalogFilters {
    q?: string;
    priceFrom?: number;
    priceTo?: number;
    categoryId?: number;
    inStock?: boolean;
    ratingFrom?: number;
    sort?: ProductSort;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedResponse<T> {
    current_page: number;
    data: T[];
    from: number | null;
    last_page: number;
    links: PaginationLink[];
    path: string;
    per_page: number;
    to: number | null;
    total: number;
}
