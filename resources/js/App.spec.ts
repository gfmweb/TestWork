import { shallowMount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import App from './App.vue';

describe('App', () => {
    it('mounts app root component', () => {
        const wrapper = shallowMount(App, {
            global: {
                stubs: {
                    RouterView: true,
                },
            },
        });

        expect(wrapper.exists()).toBe(true);
    });
});
