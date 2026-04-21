import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import GlassCard from './GlassCard.vue';

describe('GlassCard', () => {
    it('renders the title', () => {
        const wrapper = mount(GlassCard, {
            props: { title: 'Категории' },
        });

        expect(wrapper.text()).toContain('Категории');
    });

    it('renders slot content', () => {
        const wrapper = mount(GlassCard, {
            props: { title: 'Товары' },
            slots: { default: '<p class="slot-content">список товаров</p>' },
        });

        expect(wrapper.find('.slot-content').exists()).toBe(true);
        expect(wrapper.find('.slot-content').text()).toBe('список товаров');
    });

    it('renders header-extra slot', () => {
        const wrapper = mount(GlassCard, {
            props: { title: 'Товары' },
            slots: { 'header-extra': '<span class="badge">стр. 1 из 3</span>' },
        });

        expect(wrapper.find('.badge').text()).toBe('стр. 1 из 3');
    });
});
