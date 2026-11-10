import assert from 'node:assert/strict';
import { test } from 'node:test';
import { createLocationTracker } from '../../resources/js/components/rondas/location-tracker.mjs';

function setup() {
    const watches = [];
    const captures = [];
    const cleared = [];
    const positions = [];
    const errors = [];
    const geolocation = {
        watchPosition(success, error, options) {
            watches.push({ success, error, options });
            return watches.length - 1;
        },
        clearWatch(id) { cleared.push(id); },
        getCurrentPosition(success, error, options) { captures.push({ success, error, options }); },
    };
    const tracker = createLocationTracker(geolocation, {
        onPosition: (point) => positions.push(point),
        onError: (error) => errors.push(error),
    });
    return { tracker, watches, captures, cleared, positions, errors };
}

function position(lat = -42.7, accuracy = 12, timestamp = Date.now()) {
    return { coords: { latitude: lat, longitude: -65, accuracy }, timestamp };
}

test('live tracking requests high accuracy with no cached fixes and accepts updated accuracy', () => {
    const { tracker, watches, positions } = setup();
    tracker.start();
    assert.deepEqual(watches[0].options, { enableHighAccuracy: true, maximumAge: 0, timeout: 15000 });
    watches[0].success(position(-42.7, 500));
    watches[0].success(position(-42.701, 8));
    assert.equal(positions.length, 2);
    assert.equal(positions[1].latitud, -42.701);
    assert.equal(positions[1].accuracy, 8);
    tracker.stop();
});

test('saving freezes the latest coordinates and ignores queued position and error callbacks', async () => {
    const { tracker, watches, positions, cleared, errors, captures } = setup();
    tracker.start();
    watches[0].success(position());
    const saved = await tracker.save();
    assert.deepEqual(cleared, [0]);
    assert.equal(captures.length, 0);
    watches[0].success(position(-41));
    watches[0].error({ code: 2 });
    assert.equal(saved.latitud, -42.7);
    assert.equal(positions.length, 1);
    assert.equal(errors.length, 0);
});

test('a fix exactly 10 seconds old is accepted, but older fixes are refreshed before saving', async (context) => {
    const now = Date.now();
    context.mock.method(Date, 'now', () => now);
    const { tracker, watches, captures } = setup();
    tracker.start();
    watches[0].success(position(-42.7, 20, now - 10000));
    await tracker.save();
    assert.equal(captures.length, 0);
    tracker.start();
    watches[1].success(position(-42.7, 20, now - 10001));
    const saved = tracker.save();
    assert.equal(captures.length, 1);
    assert.deepEqual(captures[0].options, watches[1].options);
    captures[0].success(position(-42.71, 8, now));
    assert.equal((await saved).latitud, -42.71);
});

test('a stale response to a fresh capture is rejected instead of storing old coordinates', async () => {
    const { tracker, captures } = setup();
    const saving = tracker.save();
    captures[0].success(position(-42.7, 20, Date.now() - 11000));
    await assert.rejects(saving, /demasiado antigua/);
});

test('restarting tracking ignores fixes from the previous subscription', async () => {
    const { tracker, watches, positions } = setup();
    tracker.start();
    watches[0].success(position());
    await tracker.save();
    tracker.start();
    watches[0].success(position(-41));
    watches[1].success(position(-42.72));
    assert.equal(positions.length, 2);
    assert.equal((await tracker.save()).latitud, -42.72);
});

test('permission errors stop tracking and are surfaced to the screen', () => {
    const { tracker, watches, errors, positions, cleared } = setup();
    tracker.start();
    watches[0].error({ code: 1 });
    assert.equal(errors[0].code, 1);
    assert.deepEqual(cleared, [0]);
    watches[0].success(position());
    assert.equal(positions.length, 0);
});

test('stopping during a fresh capture cancels it and ignores the eventual browser callback', async () => {
    const { tracker, captures, positions } = setup();
    const saving = tracker.save();
    tracker.stop();
    await assert.rejects(saving, { name: 'AbortError' });
    captures[0].success(position());
    assert.equal(positions.length, 0);
});

test('invalid coordinates or accuracy never become a valid live fix', () => {
    const { tracker, watches, positions, errors } = setup();
    tracker.start();
    watches[0].success(position(91));
    watches[0].success(position(-42.7, Infinity));
    watches[0].success({ coords: null });
    assert.equal(positions.length, 0);
    assert.equal(errors.length, 3);
    tracker.stop();
});
