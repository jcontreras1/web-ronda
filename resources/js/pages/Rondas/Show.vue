<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Lightbox from '../../components/rondas/Lightbox.vue';
import RondaMap from '../../components/rondas/RondaMap.vue';
import { compressImage } from '../../components/rondas/image';
import { createLocationTracker } from '../../components/rondas/location-tracker.mjs';

const props = defineProps({ ronda: Object, map: Object, urls: Object });
const page = usePage();
const form = useForm({ latitud: '', longitud: '', novedad: '', image64: '' });
const lightbox = ref(null);
const fileInput = ref(null);
const location = ref(null);
const locating = ref(false);
const positionSaved = ref(false);
const gpsError = ref('');
const processingImage = ref(false);
const localError = ref('');
let tracker;
let disposed = false;

function locationError(error) {
    console.error('No se pudo obtener la ubicación', error);
    if (error.code === 1) return 'Permití el acceso a la ubicación para guardar la posición.';
    if (error.code === 2) return 'No se pudo determinar la ubicación. Revisá el GPS y volvé a intentar.';
    if (error.code === 3) return 'El GPS tardó demasiado. Revisá la señal y volvé a intentar.';
    return error.message || 'No se pudo obtener la ubicación. Revisá el GPS y los permisos.';
}

function startTracking() {
    tracker?.stop();
    positionSaved.value = false;
    location.value = null;
    gpsError.value = '';
    form.latitud = '';
    form.longitud = '';
    if (!window.isSecureContext) {
        gpsError.value = 'Para utilizar la ubicación, abrí la aplicación con HTTPS.';
        return;
    }
    try {
        tracker = createLocationTracker(navigator.geolocation, {
            onPosition: (point) => {
                gpsError.value = '';
                location.value = { ...point, tracking: true };
            },
            onError: (error) => {
                location.value = null;
                gpsError.value = locationError(error);
            },
        });
        tracker.start();
    } catch (error) {
        gpsError.value = locationError(error);
    }
}

onMounted(() => {
    if (props.ronda.abierta) startTracking();
});
watch([() => props.ronda.id, () => props.ronda.abierta], () => {
    tracker?.stop();
    location.value = null;
    positionSaved.value = false;
    if (props.ronda.abierta) startTracking();
});
onBeforeUnmount(() => {
    disposed = true;
    tracker?.stop();
});

function openPhoto(image, trigger) {
    lightbox.value.open(image, trigger);
}

async function selectImage(event) {
    form.image64 = '';
    const file = event.target.files[0];
    if (!file) return;
    processingImage.value = true;
    localError.value = '';
    try {
        form.image64 = await compressImage(file);
    } catch (error) {
        console.error('No se pudo procesar la foto', error);
        localError.value = 'No se pudo procesar la foto. Seleccioná una imagen JPEG o PNG válida.';
        if (fileInput.value) fileInput.value.value = '';
    } finally {
        processingImage.value = false;
    }
}

async function saveLocation() {
    locating.value = true;
    gpsError.value = '';
    try {
        if (!tracker) throw new Error('No se inició la ubicación. Revisá los permisos y volvé a intentar.');
        const point = await tracker.save();
        if (disposed) return false;
        form.latitud = point.latitud;
        form.longitud = point.longitud;
        location.value = { ...point, tracking: false };
        positionSaved.value = true;
        return true;
    } catch (error) {
        if (disposed || error.name === 'AbortError') return false;
        location.value = null;
        gpsError.value = locationError(error);
        return false;
    } finally {
        if (!disposed) locating.value = false;
    }
}

function positionAction() {
    if (positionSaved.value || gpsError.value) startTracking();
    else saveLocation();
}

async function submit() {
    localError.value = '';
    if (!positionSaved.value && !await saveLocation()) return;
    form.post(props.urls.checkpoint, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            if (fileInput.value) fileInput.value.value = '';
            if (props.ronda.abierta) startTracking();
        },
    });
}

</script>

<template>
    <Head :title="`Ronda #${ronda.id} - ${page.props.appName}`" />
    <div class="d-flex align-items-center gap-3 my-3">
        <Link :href="urls.index" class="btn btn-outline-secondary" aria-label="Volver a rondas"><i class="bi bi-arrow-left"></i></Link>
        <h1 class="h3 mb-0">Ronda #{{ ronda.id }}</h1>
        <span v-if="!ronda.abierta" class="badge text-bg-secondary">Cerrada</span>
    </div>
    <p v-if="ronda.circuito" class="text-muted">Circuito: <strong>{{ ronda.circuito.titulo }}</strong></p>
    <div v-if="localError" class="alert alert-danger" role="alert">{{ localError }}</div>
    <div v-if="gpsError" class="alert alert-warning py-2" role="alert">{{ gpsError }}</div>
    <div v-if="ronda.abierta" class="row mb-2">
        <div class="col-12 col-md-4">
            <button class="btn btn-lg btn-primary w-100" :disabled="locating || form.processing || (!location && !gpsError)" @click="positionAction">
                <span v-if="locating || (!location && !gpsError)" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                {{ locating ? 'Guardando posición...' : positionSaved ? 'Actualizar posición' : gpsError ? 'Reintentar ubicación' : !location ? 'Buscando ubicación...' : 'Guardar posición' }}
                <i v-if="positionSaved" class="bi bi-check-circle" aria-hidden="true"></i>
                <i v-else-if="location" class="bi bi-geo-alt" aria-hidden="true"></i>
            </button>
        </div>
        <div v-if="location" class="col-md-8 d-none d-md-block align-self-center" aria-label="Coordenadas de la posición">
            Ubicación: {{ Number(location.latitud).toFixed(6) }}, {{ Number(location.longitud).toFixed(6) }}
        </div>
    </div>
    <div class="row g-3">
        <div :class="ronda.abierta ? 'col-12 col-md-8' : 'col-12'">
            <RondaMap :key="ronda.id" :checkpoints="ronda.checkpoints" :geofences="ronda.geofences" :config="map" :location="location" @photo="openPhoto" />
        </div>
        <form v-if="ronda.abierta" class="col-12 col-md-4" @submit.prevent="submit">
            <label for="novedad" class="form-label">Agregar descripción</label>
            <textarea id="novedad" v-model="form.novedad" class="form-control mb-2" rows="6" :disabled="form.processing"></textarea>
            <label for="ronda-photo" class="visually-hidden">Foto del punto de control</label>
            <input id="ronda-photo" ref="fileInput" type="file" capture="environment" accept="image/png,image/jpeg" class="form-control mb-2" :disabled="processingImage || form.processing || locating" @change="selectImage">
            <div class="d-grid gap-2">
                <button class="btn btn-lg btn-success" :disabled="processingImage || form.processing || locating">
                    {{ processingImage ? 'Procesando foto...' : form.processing ? 'Subiendo...' : locating ? 'Obteniendo ubicación...' : 'Aceptar' }}
                </button>
            </div>
        </form>
    </div>
    <hr>
    <p v-if="!ronda.checkpoints.length" class="text-muted">Todavía no hay puntos de control.</p>
    <article v-for="checkpoint in ronda.checkpoints" :key="checkpoint.id" class="card shadow-sm mb-2">
        <div class="card-header d-flex align-items-center flex-wrap gap-2">
            <i :class="checkpoint.novedad || checkpoint.images.length ? 'bi bi-chat-square-dots-fill text-primary' : 'bi bi-check-circle-fill text-success'"></i>
            <span>{{ checkpoint.usuario }} [{{ new Date(checkpoint.created_at).toLocaleString('es-AR') }}]</span>
            <strong v-if="!checkpoint.novedad && !checkpoint.images.length"><em>(Sin novedades)</em></strong>
            <span class="ms-auto">#{{ checkpoint.id }}</span>
        </div>
        <div v-if="checkpoint.novedad || checkpoint.images.length" class="card-body">
            <p v-if="checkpoint.novedad" class="ronda-description fw-bold">{{ checkpoint.novedad }}</p>
            <div v-if="checkpoint.images.length" class="d-flex flex-wrap gap-2">
                <button v-for="image in checkpoint.images" :key="image.id" type="button" class="btn p-0" :aria-label="`Ampliar foto del punto ${checkpoint.id}`" @click="openPhoto({ ...image, alt: `Foto del punto ${checkpoint.id}` }, $event.currentTarget)">
                    <img :src="image.url" :alt="`Foto del punto ${checkpoint.id}`" class="ronda-thumbnail rounded shadow-sm" width="200" height="200" loading="lazy" decoding="async">
                </button>
            </div>
        </div>
    </article>
    <Lightbox ref="lightbox" />
</template>

<style scoped>
.ronda-thumbnail { width: 200px; max-width: 100%; height: 200px; object-fit: cover; }
.ronda-description { white-space: pre-wrap; }
</style>
