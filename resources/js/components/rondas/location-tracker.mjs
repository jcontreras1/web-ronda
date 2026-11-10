const options = { enableHighAccuracy: true, maximumAge: 0, timeout: 15000 };
const maxPositionAge = 10000;

function readPosition(position) {
    const { latitude, longitude, accuracy } = position?.coords ?? {};
    if (!Number.isFinite(latitude) || Math.abs(latitude) > 90
        || !Number.isFinite(longitude) || Math.abs(longitude) > 180
        || !Number.isFinite(accuracy) || accuracy < 0
        || !Number.isFinite(position?.timestamp)) {
        throw new Error('El navegador devolvió una ubicación inválida.');
    }
    return { latitud: latitude, longitud: longitude, accuracy, timestamp: position.timestamp };
}

function isFresh(position) {
    if (!position) return false;
    const age = Date.now() - position.timestamp;
    return age >= 0 && age <= maxPositionAge;
}

export function createLocationTracker(geolocation, { onPosition, onError }) {
    let watchId = null;
    let generation = 0;
    let latest = null;
    let cancelCapture = null;

    function stop() {
        generation++;
        if (watchId !== null) geolocation.clearWatch(watchId);
        watchId = null;
        cancelCapture?.();
        cancelCapture = null;
    }

    function start() {
        stop();
        latest = null;
        if (!geolocation) throw new Error('Tu navegador no permite obtener la ubicación.');
        const current = generation;
        const fail = (error) => {
            if (current !== generation) return;
            latest = null;
            if (error.code === 1) stop();
            onError(error);
        };
        watchId = geolocation.watchPosition((position) => {
            if (current !== generation) return;
            let point;
            try {
                point = readPosition(position);
            } catch (error) {
                fail(error);
                return;
            }
            latest = point;
            onPosition({ ...point });
        }, fail, options);
    }

    async function save() {
        const point = latest;
        stop();
        if (isFresh(point)) return { ...point };
        if (!geolocation) throw new Error('Tu navegador no permite obtener la ubicación.');
        const current = generation;
        return new Promise((resolve, reject) => {
            cancelCapture = () => {
                const error = new Error('Captura de ubicación cancelada.');
                error.name = 'AbortError';
                reject(error);
            };
            geolocation.getCurrentPosition((position) => {
                if (current !== generation) return;
                try {
                    const fresh = readPosition(position);
                    if (!isFresh(fresh)) throw new Error('La ubicación recibida es demasiado antigua. Volvé a intentar.');
                    latest = fresh;
                    resolve({ ...fresh });
                } catch (error) {
                    reject(error);
                }
            }, (error) => {
                if (current === generation) reject(error);
            }, options);
        }).finally(() => {
            if (current === generation) cancelCapture = null;
        });
    }

    return { start, save, stop };
}
