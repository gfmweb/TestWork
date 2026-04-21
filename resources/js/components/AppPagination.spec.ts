import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import AppPagination from './AppPagination.vue';

describe('AppPagination', () => {
    it('renders nothing when totalPages <= 1 and total is not passed', () => {
        const wrapper = mount(AppPagination, {
            props: { currentPage: 1, totalPages: 1 },
        });

        expect(wrapper.find('nav').exists()).toBe(false);
        expect(wrapper.text()).not.toContain('В выборке');
    });

    it('shows selection count when total is passed even on a single page', () => {
        const wrapper = mount(AppPagination, {
            props: {
                currentPage: 1,
                totalPages: 1,
                total: 7,
                from: 1,
                to: 7,
            },
        });

        expect(wrapper.find('nav').exists()).toBe(false);
        expect(wrapper.text()).toContain('Показано 1–7 из 7');
    });

    it('shows total when from/to are missing', () => {
        const wrapper = mount(AppPagination, {
            props: { currentPage: 1, totalPages: 1, total: 12 },
        });

        expect(wrapper.text()).toContain('В выборке доступно записей: 12');
    });

    it('renders page buttons when totalPages > 1', () => {
        const wrapper = mount(AppPagination, {
            props: { currentPage: 1, totalPages: 3 },
        });

        expect(wrapper.find('nav').exists()).toBe(true);

        const buttons = wrapper.findAll('button');
        // Назад + 3 страницы + Вперед
        expect(buttons).toHaveLength(5);
    });

    it('renders at most 5 page numbers with ellipsis for long pagination', () => {
        const wrapper = mount(AppPagination, {
            props: { currentPage: 10, totalPages: 20 },
        });

        const pageButtons = wrapper
            .findAll('button')
            .map((button) => button.text())
            .filter((text) => /^\d+$/.test(text));

        expect(pageButtons.length).toBeLessThanOrEqual(7);
        expect(wrapper.text()).toContain('...');
    });

    it('emits change event when a page button is clicked', async () => {
        const wrapper = mount(AppPagination, {
            props: { currentPage: 1, totalPages: 3 },
        });

        const buttons = wrapper.findAll('button');
        // buttons[0] = Назад, buttons[1] = стр.1, buttons[2] = стр.2
        await buttons[2].trigger('click');

        expect(wrapper.emitted('change')).toBeTruthy();
        expect(wrapper.emitted('change')![0]).toEqual([2]);
    });

    it('disables "Назад" on the first page', () => {
        const wrapper = mount(AppPagination, {
            props: { currentPage: 1, totalPages: 3 },
        });

        const prevButton = wrapper.findAll('button')[0];
        expect(prevButton.attributes('disabled')).toBeDefined();
    });

    it('disables "Вперед" on the last page', () => {
        const wrapper = mount(AppPagination, {
            props: { currentPage: 3, totalPages: 3 },
        });

        const buttons = wrapper.findAll('button');
        const nextButton = buttons[buttons.length - 1];
        expect(nextButton.attributes('disabled')).toBeDefined();
    });
});
