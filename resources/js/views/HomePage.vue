<template>
    <main class="min-h-screen bg-gradient-to-br from-slate-100 via-sky-100 to-indigo-100 p-6">
        <div class="mx-auto grid w-full max-w-7xl gap-6 lg:grid-cols-[340px_1fr]">
            <GlassCard title="Категории">
                <p
                    v-if="categoriesLoading"
                    class="text-sm text-slate-600"
                >
                    Загрузка категорий...
                </p>
                <p
                    v-else-if="categoriesError"
                    class="text-sm text-rose-700"
                >
                    Не удалось загрузить категории.
                </p>
                <ul
                    v-else
                    class="space-y-2"
                >
                    <li>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg border px-3 py-2 text-left text-sm transition"
                            :class="selectedCategoryId === null
                                ? 'border-slate-700 bg-slate-700 text-white'
                                : 'border-white/40 bg-white/30 text-slate-800 hover:bg-white/50'"
                            @click="selectCategory(null)"
                        >
                            <span>Все категории</span>
                            <span class="font-semibold">{{ totalProducts }}</span>
                        </button>
                    </li>
                    <li
                        v-for="category in categories"
                        :key="category.id"
                    >
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg border px-3 py-2 text-left text-sm transition"
                            :class="selectedCategoryId === category.id
                                ? 'border-slate-700 bg-slate-700 text-white'
                                : 'border-white/40 bg-white/30 text-slate-800 hover:bg-white/50'"
                            @click="selectCategory(category.id)"
                        >
                            <span>{{ category.name }}</span>
                            <span class="font-semibold">{{ category.products_count }}</span>
                        </button>
                    </li>
                </ul>
            </GlassCard>

            <GlassCard title="Товары">
                <template #header-extra>
                    <span class="text-sm text-slate-600">
                        Страница {{ productsPage.current_page }} из {{ productsPage.last_page }}
                    </span>
                </template>

                <div class="mb-4 grid gap-2 md:grid-cols-2 xl:grid-cols-3">
                    <input
                        v-model="filtersForm.q"
                        type="text"
                        placeholder="Поиск по названию"
                        class="rounded-lg border border-white/40 bg-white/30 px-3 py-2 text-sm text-slate-800"
                        @input="scheduleSearchApply"
                    />
                    <input
                        v-model.number="filtersForm.price_from"
                        type="number"
                        min="0"
                        step="0.01"
                        placeholder="Цена от"
                        class="rounded-lg border border-white/40 bg-white/30 px-3 py-2 text-sm text-slate-800"
                        @change="applyFilters"
                    />
                    <input
                        v-model.number="filtersForm.price_to"
                        type="number"
                        min="0"
                        step="0.01"
                        placeholder="Цена до"
                        class="rounded-lg border border-white/40 bg-white/30 px-3 py-2 text-sm text-slate-800"
                        @change="applyFilters"
                    />
                    <select
                        v-model="filtersForm.in_stock"
                        class="rounded-lg border border-white/40 bg-white/30 px-3 py-2 text-sm text-slate-800"
                        @change="applyFilters"
                    >
                        <option value="">Наличие: все</option>
                        <option value="true">Только в наличии</option>
                        <option value="false">Только отсутствующие</option>
                    </select>
                    <div
                        class="flex min-w-0 flex-col gap-2 rounded-lg border border-white/40 bg-white/30 px-3 py-2 sm:col-span-2 xl:col-span-1"
                    >
                        <div class="flex items-center justify-between gap-2 text-xs text-slate-700">
                            <span class="font-medium text-slate-800">Рейтинг от</span>
                            <span class="tabular-nums text-slate-900">{{ ratingFromDisplay }}</span>
                        </div>
                        <input
                            v-model.number="filtersForm.rating_from"
                            type="range"
                            min="0"
                            max="5"
                            step="0.1"
                            class="h-2 w-full cursor-pointer accent-slate-700"
                            @input="scheduleSearchApply"
                        />
                    </div>
                    <select
                        v-model="filtersForm.sort"
                        class="rounded-lg border border-white/40 bg-white/30 px-3 py-2 text-sm text-slate-800"
                        @change="applyFilters"
                    >
                        <option value="newest">Сначала новые</option>
                        <option value="price_asc">Цена по возрастанию</option>
                        <option value="price_desc">Цена по убыванию</option>
                        <option value="rating_desc">Рейтинг по убыванию</option>
                    </select>
                </div>
                <div
                    v-if="productsValidationErrors !== null"
                    class="mb-4 rounded-lg border border-rose-300/80 bg-rose-50/90 px-3 py-2 text-sm text-rose-900"
                    data-testid="products-validation-errors"
                >
                    <p class="font-medium">
                        Некорректные параметры фильтра
                    </p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        <li
                            v-for="([field, messages]) in productsValidationErrorEntries"
                            :key="field"
                        >
                            <span class="font-medium">{{ field }}:</span>
                            {{ messages.join(' ') }}
                        </li>
                    </ul>
                </div>
                <div class="mb-4">
                    <button
                        type="button"
                        class="rounded-lg border border-white/40 bg-white/30 px-3 py-2 text-sm text-slate-800 transition hover:bg-white/50"
                        @click="resetFilters"
                    >
                        Сбросить
                    </button>
                </div>

                <p
                    v-if="productsLoading"
                    class="text-sm text-slate-600"
                >
                    Загрузка товаров...
                </p>
                <p
                    v-else-if="productsError"
                    class="text-sm text-rose-700"
                >
                    Не удалось загрузить товары.
                </p>
                <p
                    v-else-if="productsPage.data.length === 0 && productsValidationErrors === null"
                    class="text-sm text-slate-600"
                >
                    В выбранной категории пока нет товаров.
                </p>
                <ul
                    v-else
                    class="space-y-2"
                >
                    <li
                        v-for="product in productsPage.data"
                        :key="product.id"
                        class="rounded-lg border border-white/40 bg-white/30 px-3 py-2"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-medium text-slate-900">{{ product.name }}</div>
                                <div class="text-sm text-slate-600">{{ formatPrice(product.price) }}</div>
                            </div>
                            <span
                                class="rounded-md px-2 py-1 text-xs font-medium"
                                :class="product.in_stock
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-rose-100 text-rose-700'"
                            >
                                {{ product.in_stock ? 'В наличии' : 'Нет в наличии' }}
                            </span>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-2 text-xs text-slate-600">
                            <span class="rounded-md bg-slate-100 px-2 py-1">
                                Рейтинг: {{ product.rating.toFixed(1) }}
                            </span>
                        </div>
                    </li>
                </ul>

                <AppPagination
                    v-if="!productsLoading && !productsError && productsValidationErrors === null"
                    :current-page="productsPage.current_page"
                    :total-pages="productsPage.last_page"
                    :total="productsPage.total"
                    :from="productsPage.from"
                    :to="productsPage.to"
                    @change="selectPage"
                />
            </GlassCard>
        </div>
    </main>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { ApiHttpError, fetchCategories, fetchProducts, isLaravelValidationBody } from '../api/catalogApi';
import AppPagination from '../components/AppPagination.vue';
import GlassCard from '../components/GlassCard.vue';
import type { CatalogFilters, Category, PaginatedResponse, Product, ProductSort } from '../types/catalog';

const route = useRoute();
const router = useRouter();

const categories = ref<Category[]>([]);
const categoriesLoading = ref(false);
const categoriesError = ref(false);

const productsLoading = ref(false);
const productsError = ref(false);
const productsValidationErrors = ref<Record<string, string[]> | null>(null);

const productsPage = ref<PaginatedResponse<Product>>({
    current_page: 1,
    data: [],
    from: null,
    last_page: 1,
    links: [],
    path: '',
    per_page: 15,
    to: null,
    total: 0,
});

type FiltersFormState = {
    q: string;
    price_from: number | undefined;
    price_to: number | undefined;
    in_stock: '' | 'true' | 'false';
    /** 0 = без фильтра; иначе минимальный рейтинг 0.1–5 */
    rating_from: number;
    sort: ProductSort;
};

const allowedSorts: ProductSort[] = ['newest', 'price_asc', 'price_desc', 'rating_desc'];

const selectedCategoryId = computed<number | null>(() => parsePositiveInt(route.query.category_id));
const currentPage = computed<number>(() => parsePositiveInt(route.query.page) ?? 1);
const currentFilters = computed<CatalogFilters>(() => ({
    q: parseString(route.query.q),
    priceFrom: parseNumber(route.query.price_from),
    priceTo: parseNumber(route.query.price_to),
    categoryId: selectedCategoryId.value ?? undefined,
    inStock: parseBoolean(route.query.in_stock),
    ratingFrom: parseNumber(route.query.rating_from),
    sort: parseSort(route.query.sort),
}));

const filtersForm = ref<FiltersFormState>({
    q: '',
    price_from: undefined,
    price_to: undefined,
    in_stock: '',
    rating_from: 0,
    sort: 'newest',
});
let searchDebounceTimeout: ReturnType<typeof setTimeout> | undefined;

const totalProducts = computed(() => categories.value.reduce((sum, item) => sum + item.products_count, 0));

const ratingFromDisplay = computed(() =>
    filtersForm.value.rating_from === 0 ? 'любой' : filtersForm.value.rating_from.toFixed(1),
);

const productsValidationErrorEntries = computed(() =>
    productsValidationErrors.value === null ? [] : Object.entries(productsValidationErrors.value),
);

function formatPrice(price: string): string {
    const numericPrice = Number(price);
    return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        maximumFractionDigits: 2,
    }).format(numericPrice);
}

async function loadCategories(): Promise<void> {
    categoriesLoading.value = true;
    categoriesError.value = false;

    try {
        categories.value = await fetchCategories();
    } catch {
        categoriesError.value = true;
    } finally {
        categoriesLoading.value = false;
    }
}

function emptyProductsPage(): PaginatedResponse<Product> {
    return {
        current_page: 1,
        data: [],
        from: null,
        last_page: 1,
        links: [],
        path: '',
        per_page: 15,
        to: null,
        total: 0,
    };
}

async function loadProducts(): Promise<void> {
    productsLoading.value = true;
    productsError.value = false;
    productsValidationErrors.value = null;

    try {
        productsPage.value = await fetchProducts({
            filters: currentFilters.value,
            page: currentPage.value,
            perPage: 15,
        });
    } catch (error: unknown) {
        if (error instanceof ApiHttpError && error.status === 422 && isLaravelValidationBody(error.body)) {
            productsPage.value = emptyProductsPage();
            productsValidationErrors.value = error.body.errors;
        } else {
            productsError.value = true;
        }
    } finally {
        productsLoading.value = false;
    }
}

async function pushQuery(query: Record<string, string | undefined>): Promise<void> {
    const nextQuery = Object.fromEntries(
        Object.entries(query).filter(([, value]) => value !== undefined),
    );
    await router.push({ query: nextQuery });
}

function parsePositiveInt(value: unknown): number | null {
    if (typeof value !== 'string') {
        return null;
    }

    const parsed = Number(value);
    if (!Number.isInteger(parsed) || parsed <= 0) {
        return null;
    }

    return parsed;
}

function parseNumber(value: unknown): number | undefined {
    if (typeof value !== 'string') {
        return undefined;
    }

    const parsed = Number(value);
    return Number.isFinite(parsed) ? parsed : undefined;
}

function parseString(value: unknown): string | undefined {
    return typeof value === 'string' && value.trim() !== '' ? value.trim() : undefined;
}

function parseBoolean(value: unknown): boolean | undefined {
    if (value === 'true') {
        return true;
    }
    if (value === 'false') {
        return false;
    }
    return undefined;
}

function parseSort(value: unknown): ProductSort {
    if (typeof value === 'string' && allowedSorts.includes(value as ProductSort)) {
        return value as ProductSort;
    }
    return 'newest';
}

function syncFiltersFormFromQuery(): void {
    filtersForm.value = {
        q: currentFilters.value.q ?? '',
        price_from: currentFilters.value.priceFrom,
        price_to: currentFilters.value.priceTo,
        in_stock: currentFilters.value.inStock === undefined ? '' : String(currentFilters.value.inStock) as 'true' | 'false',
        rating_from: currentFilters.value.ratingFrom ?? 0,
        sort: currentFilters.value.sort ?? 'newest',
    };
}

async function applyFilters(): Promise<void> {
    await pushQuery({
        q: filtersForm.value.q.trim() === '' ? undefined : filtersForm.value.q.trim(),
        price_from: filtersForm.value.price_from === undefined ? undefined : String(filtersForm.value.price_from),
        price_to: filtersForm.value.price_to === undefined ? undefined : String(filtersForm.value.price_to),
        category_id: selectedCategoryId.value === null ? undefined : String(selectedCategoryId.value),
        in_stock: filtersForm.value.in_stock === '' ? undefined : filtersForm.value.in_stock,
        rating_from: filtersForm.value.rating_from === 0
            ? undefined
            : String(Math.round(filtersForm.value.rating_from * 10) / 10),
        sort: filtersForm.value.sort === 'newest' ? undefined : filtersForm.value.sort,
        page: '1',
    });
}

function scheduleSearchApply(): void {
    if (searchDebounceTimeout !== undefined) {
        clearTimeout(searchDebounceTimeout);
    }

    searchDebounceTimeout = setTimeout(() => {
        void applyFilters();
    }, 300);
}

async function selectCategory(categoryId: number | null): Promise<void> {
    await pushQuery({
        q: currentFilters.value.q,
        price_from: currentFilters.value.priceFrom === undefined ? undefined : String(currentFilters.value.priceFrom),
        price_to: currentFilters.value.priceTo === undefined ? undefined : String(currentFilters.value.priceTo),
        category_id: categoryId === null ? undefined : String(categoryId),
        in_stock: currentFilters.value.inStock === undefined ? undefined : String(currentFilters.value.inStock),
        rating_from: currentFilters.value.ratingFrom === undefined ? undefined : String(currentFilters.value.ratingFrom),
        sort: currentFilters.value.sort === undefined || currentFilters.value.sort === 'newest' ? undefined : currentFilters.value.sort,
        page: '1',
    });
}

async function selectPage(page: number): Promise<void> {
    await pushQuery({
        q: currentFilters.value.q,
        price_from: currentFilters.value.priceFrom === undefined ? undefined : String(currentFilters.value.priceFrom),
        price_to: currentFilters.value.priceTo === undefined ? undefined : String(currentFilters.value.priceTo),
        category_id: selectedCategoryId.value === null ? undefined : String(selectedCategoryId.value),
        in_stock: currentFilters.value.inStock === undefined ? undefined : String(currentFilters.value.inStock),
        rating_from: currentFilters.value.ratingFrom === undefined ? undefined : String(currentFilters.value.ratingFrom),
        sort: currentFilters.value.sort === undefined || currentFilters.value.sort === 'newest' ? undefined : currentFilters.value.sort,
        page: String(page),
    });
}

async function resetFilters(): Promise<void> {
    if (searchDebounceTimeout !== undefined) {
        clearTimeout(searchDebounceTimeout);
    }

    productsValidationErrors.value = null;

    filtersForm.value = {
        q: '',
        price_from: undefined,
        price_to: undefined,
        in_stock: '',
        rating_from: 0,
        sort: 'newest',
    };

    await pushQuery({
        page: '1',
    });
}

watch(
    () => [
        selectedCategoryId.value,
        currentPage.value,
        currentFilters.value.q,
        currentFilters.value.priceFrom,
        currentFilters.value.priceTo,
        currentFilters.value.inStock,
        currentFilters.value.ratingFrom,
        currentFilters.value.sort,
    ],
    () => {
        syncFiltersFormFromQuery();
        void loadProducts();
    },
    { immediate: true },
);

onMounted(() => {
    void loadCategories();
});

onBeforeUnmount(() => {
    if (searchDebounceTimeout !== undefined) {
        clearTimeout(searchDebounceTimeout);
    }
});
</script>
