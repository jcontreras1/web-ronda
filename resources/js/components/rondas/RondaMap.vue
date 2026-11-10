<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({ checkpoints: Array, geofences: Array, config: Object, location: Object });
const emit = defineEmits(['photo']);
const container = ref(null);
const error = ref('');
let map;
let checkpointsLayer;
let currentMarker;
let accuracyCircle;
let followLocation = true;
let observer;

function coordinates(point) {
    if (point.latitud === null || point.longitud === null || point.latitud === '' || point.longitud === '') return null;
    const lat = Number(point.latitud);
    const lng = Number(point.longitud);
    return Number.isFinite(lat) && Number.isFinite(lng) && Math.abs(lat) <= 90 && Math.abs(lng) <= 180 ? [lat, lng] : null;
}

function popup(checkpoint) {
    const content = document.createElement('div');
    if (checkpoint.images.length) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'btn p-0 d-block';
        button.setAttribute('aria-label', 'Ampliar foto del punto ' + checkpoint.id);
        const photo = checkpoint.images[0];
        const image = document.createElement('img');
        image.src = photo.url;
        image.alt = 'Foto del punto ' + checkpoint.id;
        image.loading = 'lazy';
        image.style.maxWidth = '200px';
        image.style.maxHeight = '150px';
        image.style.objectFit = 'contain';
        button.append(image);
        button.addEventListener('click', () => emit('photo', { ...photo, alt: image.alt }, button));
        content.append(button);
    }
    if (checkpoint.novedad) {
        const description = document.createElement('strong');
        description.textContent = checkpoint.novedad;
        description.style.whiteSpace = 'pre-wrap';
        content.append(description, document.createElement('br'));
    }
    const date = document.createElement('small');
    date.textContent = 'Visitado a las ' + new Date(checkpoint.created_at).toLocaleString('es-AR');
    content.append(date);
    return content;
}

function updateCheckpoints() {
    if (!map) return;
    checkpointsLayer.clearLayers();
    for (const checkpoint of props.checkpoints) {
        const point = coordinates(checkpoint);
        if (!point) {
            error.value = 'Hay puntos con coordenadas inválidas que no se pueden mostrar en el mapa.';
            continue;
        }
        const type = checkpoint.images.length ? 'photo' : checkpoint.novedad ? 'warning' : 'ok';
        const icon = L.icon({ iconUrl: props.config.icons[type], iconSize: [35, 47], iconAnchor: [17, 47] });
        L.marker(point, { icon }).bindPopup(() => popup(checkpoint)).addTo(checkpointsLayer);
    }
}

function updateLocation() {
    if (!map) return;
    if (!props.location) {
        if (currentMarker) map.removeLayer(currentMarker);
        if (accuracyCircle) map.removeLayer(accuracyCircle);
        currentMarker = null;
        accuracyCircle = null;
        followLocation = true;
        return;
    }
    const point = coordinates(props.location);
    if (!point) return;
    const first = !currentMarker;
    const saved = !props.location.tracking;
    const icon = L.divIcon({
        className: 'ronda-position-icon',
        html: `<span class="ronda-position-dot${saved ? '' : ' ronda-position-live'}"></span>`,
        iconSize: [18, 18],
        iconAnchor: [9, 9],
    });
    if (currentMarker) {
        currentMarker.setLatLng(point);
        if (currentMarker.options.tracking !== props.location.tracking) currentMarker.setIcon(icon);
    } else {
        currentMarker = L.marker(point, { icon, zIndexOffset: 1000 }).addTo(map);
    }
    const justSaved = saved && currentMarker.options.tracking === true;
    currentMarker.options.tracking = props.location.tracking;
    currentMarker.getElement().title = `${saved ? 'Posición fijada' : 'Ubicación en vivo'} · Precisión estimada: ±${Math.round(props.location.accuracy)} m`;
    if (accuracyCircle) {
        accuracyCircle.setLatLng(point).setRadius(props.location.accuracy);
    } else {
        accuracyCircle = L.circle(point, { radius: props.location.accuracy, color: '#2d89c8', weight: 1, fillOpacity: .1, interactive: false }).addTo(map);
    }
    if (first || justSaved || (followLocation && !map.getBounds().pad(-.2).contains(point))) map.panTo(point);
}

onMounted(() => {
    const points = props.geofences.map(coordinates).filter(Boolean);
    const center = points.length ? L.latLngBounds(points).getCenter() : [-42.7372, -65.03948];
    map = L.map(container.value).setView(center, 15);
    map.on('dragstart', () => { followLocation = false; });
    if (props.config.tiles) {
        const separator = props.config.tiles.includes('?') ? '&' : '?';
        const tiles = props.config.tiles + (props.config.token ? separator + 'access_token=' + encodeURIComponent(props.config.token) : '');
        L.tileLayer(tiles, { maxZoom: 19, attribution: '© OpenStreetMap' })
            .on('tileerror', () => { error.value = 'No se pudo cargar el mapa base. Los puntos siguen disponibles.'; })
            .addTo(map);
    } else {
        error.value = 'No se configuró el mapa base. Los puntos siguen disponibles.';
    }
    for (const geofence of props.geofences) {
        const point = coordinates(geofence);
        if (point) L.circle(point, { color: '#eb4034', fillOpacity: .3, radius: Number(geofence.radio) })
            .bindPopup('Punto #' + geofence.id).addTo(map);
    }
    checkpointsLayer = L.layerGroup().addTo(map);
    updateCheckpoints();
    updateLocation();
    observer = new ResizeObserver(() => map.invalidateSize());
    observer.observe(container.value);
});

watch(() => props.checkpoints, updateCheckpoints);
watch(() => props.location, updateLocation);
onBeforeUnmount(() => {
    observer?.disconnect();
    map?.remove();
});
</script>

<template>
    <div>
        <div v-if="error" class="alert alert-warning" role="alert">{{ error }}</div>
        <div class="ronda-map-container">
            <div ref="container" class="ronda-map" aria-label="Mapa de puntos de control"></div>
            <span v-if="location" class="ronda-position-status rounded shadow-sm">
                {{ location.tracking ? 'En vivo' : 'Posición fijada' }} · Precisión ±{{ Math.round(location.accuracy) }} m
            </span>
        </div>
    </div>
</template>

<style scoped>
.ronda-map { height: 450px; max-height: 65vh; }
.ronda-map-container { position: relative; }
.ronda-position-status {
    position: absolute;
    left: .5rem;
    bottom: .5rem;
    z-index: 800;
    padding: .2rem .45rem;
    background: var(--bs-body-bg);
    color: var(--bs-body-color);
    font-size: .75rem;
    pointer-events: none;
}
</style>

<style>
.ronda-position-dot {
    display: block;
    width: 18px;
    height: 18px;
    border: 3px solid white;
    border-radius: 50%;
    background: #2d89c8;
    box-shadow: 0 0 0 2px #2d89c8;
}
.ronda-position-live { animation: ronda-position-pulse 1.8s ease-out infinite; }
@keyframes ronda-position-pulse {
    0% { box-shadow: 0 0 0 2px rgb(45 137 200 / 75%); }
    100% { box-shadow: 0 0 0 14px rgb(45 137 200 / 0%); }
}
@media (prefers-reduced-motion: reduce) {
    .ronda-position-live { animation: none; }
}
</style>
