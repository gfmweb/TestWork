import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { createRouter, createWebHashHistory } from 'vue-router';
import HomePage from './HomePage.vue';

const mockCategories = [
    { id: 1, name: 'Электроника', products_count: 10 },
    { id: 2, name: 'Одежда', products_count: 5 },
];

const makePaginatedProducts = (count = 15, total = 20) => ({
    current_page: 1,
    data: Array.from({ length: count }, (_, i) => ({
        id: i + 1,
        name: `Товар ${i + 1}`,
        price: '999.00',
        category_id: 1,
        in_stock: true,
        rating: 4.5,
        created_at: '2026-01-01T00:00:00.000000Z',
    })),
    from: 1,
    last_page: 2,
    links: [],
    path: '/api/products',
    per_page: 15,
    to: count,
    total,
});

function makeFetchMock(categoriesData = mockCategories, productsData = makePaginatedProducts()) {
    return vi.fn().mockImplementation((url: string) => {
        if (typeof url === 'string' && url.includes('product-categories')) {
            return Promise.resolve({
                ok: true,
                status: 200,
                json: () => Promise.resolve({ data: categoriesData }),
            });
        }

        return Promise.resolve({
            ok: true,
            status: 200,
            json: () => Promise.resolve(productsData),
        });
    });
}

function makeRouter() {
    return createRouter({
        history: createWebHashHistory(),
        routes: [{ path: '/', component: HomePage }],
    });
}

describe('HomePage', () => {
    beforeEach(() => {
        vi.stubGlobal('fetch', makeFetchMock());
    });

    afterEach(() => {
        vi.restoreAllMocks();
    });

    it('renders two glass cards: categories and products', async () => {
        const router = makeRouter();
        await router.push('/');

        const wrapper = mount(HomePage, {
            global: { plugins: [router] },
        });

        await flushPromises();

        const headings = wrapper.findAll('h2');
        const titles = headings.map((h) => h.text());
        expect(titles).toContain('Категории');
        expect(titles).toContain('Товары');
    });

    it('renders category names with product counts', async () => {
        const router = makeRouter();
        await router.push('/');

        const wrapper = mount(HomePage, {
            global: { plugins: [router] },
        });

        await flushPromises();

        expect(wrapper.text()).toContain('Электроника');
        expect(wrapper.text()).toContain('Одежда');
    });

    it('renders product list on initial load', async () => {
        const router = makeRouter();
        await router.push('/');

        const wrapper = mount(HomePage, {
            global: { plugins: [router] },
        });

        await flushPromises();

        expect(wrapper.text()).toContain('Товар 1');
    });

    it('calls products API with category_id when category is selected', async () => {
        const fetchMock = makeFetchMock();
        vi.stubGlobal('fetch', fetchMock);

        const router = makeRouter();
        await router.push('/');

        const wrapper = mount(HomePage, {
            global: { plugins: [router] },
        });

        await flushPromises();

        const categoryButtons = wrapper.findAll('button').filter((btn) =>
            btn.text().includes('Электроника'),
        );
        expect(categoryButtons).toHaveLength(1);

        await categoryButtons[0].trigger('click');
        await flushPromises();

        const productCalls = fetchMock.mock.calls.filter((call) => {
            const [url] = call as [string];
            return typeof url === 'string' && url.includes('products') && url.includes('category_id=1');
        });
        expect(productCalls.length).toBeGreaterThan(0);
    });

    it('includes filter params in products API request', async () => {
        const fetchMock = makeFetchMock();
        vi.stubGlobal('fetch', fetchMock);

        const router = makeRouter();
        await router.push('/?q=phone&price_from=100&price_to=500&rating_from=4&in_stock=true&sort=price_desc');

        mount(HomePage, {
            global: { plugins: [router] },
        });

        await flushPromises();

        const filteredCalls = fetchMock.mock.calls.filter((call) => {
            const [url] = call as [string];
            return typeof url === 'string'
                && url.includes('/api/products')
                && url.includes('q=phone')
                && url.includes('price_from=100')
                && url.includes('price_to=500')
                && url.includes('rating_from=4')
                && url.includes('in_stock=true')
                && url.includes('sort=price_desc');
        });

        expect(filteredCalls.length).toBeGreaterThan(0);
    });

    it('shows pagination when products span multiple pages', async () => {
        const router = makeRouter();
        await router.push('/');

        const wrapper = mount(HomePage, {
            global: { plugins: [router] },
        });

        await flushPromises();

        expect(wrapper.find('nav[aria-label="Pagination"]').exists()).toBe(true);
    });

    it('calls products API with page=2 when next page button is clicked', async () => {
        const fetchMock = makeFetchMock();
        vi.stubGlobal('fetch', fetchMock);

        const router = makeRouter();
        await router.push('/');

        const wrapper = mount(HomePage, {
            global: { plugins: [router] },
        });

        await flushPromises();

        const nextButton = wrapper.findAll('button').find((btn) => btn.text() === 'Вперед');
        expect(nextButton).toBeDefined();

        await nextButton!.trigger('click');
        await flushPromises();

        const page2Calls = fetchMock.mock.calls.filter((call) => {
            const [url] = call as [string];
            return typeof url === 'string' && url.includes('page=2');
        });
        expect(page2Calls.length).toBeGreaterThan(0);
    });

    it('resets page to 1 when filters are changed', async () => {
        vi.useFakeTimers();
        const fetchMock = makeFetchMock();
        vi.stubGlobal('fetch', fetchMock);

        const router = makeRouter();
        await router.push('/?page=2');

        const wrapper = mount(HomePage, {
            global: { plugins: [router] },
        });

        await flushPromises();

        const searchInput = wrapper.find('input[placeholder="Поиск по названию"]');
        await searchInput.setValue('test');
        await searchInput.trigger('input');
        vi.advanceTimersByTime(300);
        await flushPromises();

        expect(router.currentRoute.value.query.page).toBe('1');
        vi.useRealTimers();
    });

    it('clears all query filters when reset button is clicked', async () => {
        const fetchMock = makeFetchMock();
        vi.stubGlobal('fetch', fetchMock);

        const router = makeRouter();
        await router.push('/?q=phone&price_from=10&price_to=50&rating_from=4&in_stock=true&sort=price_desc&category_id=1&page=3');

        const wrapper = mount(HomePage, {
            global: { plugins: [router] },
        });

        await flushPromises();

        const resetButton = wrapper.findAll('button').find((btn) => btn.text() === 'Сбросить');
        expect(resetButton).toBeDefined();

        await resetButton!.trigger('click');
        await flushPromises();

        expect(router.currentRoute.value.query).toEqual({ page: '1' });
    });
});
