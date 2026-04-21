import { afterEach, describe, expect, it, vi } from 'vitest';
import { ApiHttpError, fetchProducts, isLaravelValidationBody } from './catalogApi';

describe('catalogApi', () => {
    afterEach(() => {
        vi.unstubAllGlobals();
    });

    it('throws ApiHttpError with validation payload on 422', async () => {
        const body = {
            message: 'The given data was invalid.',
            errors: { per_page: ['The per page field must be at least 1.'] },
        };

        vi.stubGlobal(
            'fetch',
            vi.fn().mockResolvedValue({
                ok: false,
                status: 422,
                json: () => Promise.resolve(body),
            }),
        );

        const error = await fetchProducts({ filters: {}, page: 1 }).catch((e: unknown) => e);
        expect(error).toBeInstanceOf(ApiHttpError);
        expect((error as ApiHttpError).status).toBe(422);
        expect((error as ApiHttpError).body).toEqual(body);
    });

    it('isLaravelValidationBody returns true for Laravel error shape', () => {
        expect(
            isLaravelValidationBody({ errors: { q: ['too short'] } }),
        ).toBe(true);
        expect(isLaravelValidationBody({ errors: { q: 'bad' } })).toBe(false);
        expect(isLaravelValidationBody(null)).toBe(false);
    });

    it('ApiHttpError is instanceof Error', () => {
        const err = new ApiHttpError(500, {});
        expect(err).toBeInstanceOf(Error);
        expect(err).toBeInstanceOf(ApiHttpError);
    });
});
