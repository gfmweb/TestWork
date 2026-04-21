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
                    v-else-if="productsPage.data.length === 0"
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
                        <div class="font-medium text-slate-900">{{ product.name }}</div>
                        <div class="text-sm text-slate-600">{{ formatPrice(product.price) }}</div>
                    </li>
                </ul>

                <AppPagination
                    :current-page="productsPage.current_page"
                    :total-pages="productsPage.last_page"
                    @change="selectPage"
                />
            </GlassCard>
        </div>
    </main>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { fetchCategories, fetchProducts } from '../api/catalogApi';
import AppPagination from '../components/AppPagination.vue';
import GlassCard from '../components/GlassCard.vue';
import type { Category, PaginatedResponse, Product } from '../types/catalog';

const route = useRoute();
const router = useRouter();

const categories = ref<Category[]>([]);
const categoriesLoading = ref(false);
const categoriesError = ref(false);

const productsLoading = ref(false);
const productsError = ref(false);

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

const selectedCategoryId = computed<number | null>(() => {
    const raw = route.query.category_id;
    const value = typeof raw === 'string' ? Number(raw) : Number.NaN;
    return Number.isInteger(value) && value > 0 ? value : null;
});

const currentPage = computed<number>(() => {
    const raw = route.query.page;
    const value = typeof raw === 'string' ? Number(raw) : Number.NaN;
    return Number.isInteger(value) && value > 0 ? value : 1;
});

const totalProducts = computed(() => categories.value.reduce((sum, item) => sum + item.products_count, 0));

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

async function loadProducts(): Promise<void> {
    productsLoading.value = true;
    productsError.value = false;

    try {
        productsPage.value = await fetchProducts({
            categoryId: selectedCategoryId.value ?? undefined,
            page: currentPage.value,
            perPage: 15,
        });
    } catch {
        productsError.value = true;
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

async function selectCategory(categoryId: number | null): Promise<void> {
    await pushQuery({
        category_id: categoryId === null ? undefined : String(categoryId),
        page: '1',
    });
}

async function selectPage(page: number): Promise<void> {
    await pushQuery({
        category_id: selectedCategoryId.value === null ? undefined : String(selectedCategoryId.value),
        page: String(page),
    });
}

watch(
    () => [selectedCategoryId.value, currentPage.value],
    () => {
        void loadProducts();
    },
    { immediate: true },
);

onMounted(() => {
    void loadCategories();
});
</script>
