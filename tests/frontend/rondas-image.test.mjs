import assert from 'node:assert/strict';
import { test } from 'node:test';
import { imageSize } from '../../resources/js/components/rondas/image.js';

test('landscape uploads fit 1280 x 720 and preserve their aspect ratio', () => {
    assert.deepEqual(imageSize(4000, 3000), { width: 960, height: 720 });
    assert.deepEqual(imageSize(3840, 2160), { width: 1280, height: 720 });
});

test('portrait uploads fit 720 x 1280 and preserve their aspect ratio', () => {
    assert.deepEqual(imageSize(3000, 4000), { width: 720, height: 960 });
    assert.deepEqual(imageSize(1080, 1920), { width: 720, height: 1280 });
});

test('small and square uploads are not enlarged', () => {
    assert.deepEqual(imageSize(320, 200), { width: 320, height: 200 });
    assert.deepEqual(imageSize(1600, 1600), { width: 720, height: 720 });
});
