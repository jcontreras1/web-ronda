<script setup>
import { onBeforeUnmount, ref } from 'vue';

const dialog = ref(null);
const image = ref(null);
const loading = ref(false);
const failed = ref(false);
let opener;
let position;
let overflow;

function open(photo, trigger = document.activeElement) {
    opener = trigger;
    position = { x: window.scrollX, y: window.scrollY };
    overflow = document.body.style.overflow;
    image.value = photo;
    loading.value = true;
    failed.value = false;
    dialog.value.showModal();
    document.body.style.overflow = 'hidden';
    window.scrollTo({ left: position.x, top: position.y, behavior: 'instant' });
}

function restore(restorePosition = true) {
    if (!position) return;
    document.body.style.overflow = overflow;
    if (restorePosition) {
        if (opener?.isConnected) opener.focus({ preventScroll: true });
        window.scrollTo({ left: position.x, top: position.y, behavior: 'instant' });
    }
    position = null;
    image.value = null;
}

onBeforeUnmount(() => restore(false));
defineExpose({ open });
</script>

<template>
    <Teleport to="body">
        <dialog ref="dialog" class="ronda-lightbox" aria-label="Foto de la ronda" @click.self="dialog.close()" @close="restore">
            <button type="button" class="ronda-lightbox-close btn btn-light" aria-label="Cerrar foto" autofocus @click="dialog.close()">✕</button>
            <span v-if="loading" class="ronda-lightbox-status" role="status">Cargando foto...</span>
            <span v-if="failed" class="ronda-lightbox-status" role="alert">No se pudo cargar la foto. Cerrá e intentá nuevamente.</span>
            <img v-if="image" :src="image.url" :alt="image.alt" :class="{ 'opacity-0': loading || failed }" @load="loading = false" @error="loading = false; failed = true">
        </dialog>
    </Teleport>
</template>

<style>
.ronda-lightbox {
    border: 0;
    margin: 0;
    padding: 3.5rem 1rem 1rem;
    width: 100vw;
    height: 100vh;
    height: 100dvh;
    max-width: 100vw;
    max-height: 100vh;
    max-height: 100dvh;
    background: rgb(0 0 0 / 90%);
    color: white;
    overflow: hidden;
}
.ronda-lightbox[open] {
    display: flex;
    justify-content: center;
    align-items: center;
}
.ronda-lightbox::backdrop { background: rgb(0 0 0 / 80%); }
.ronda-lightbox img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.ronda-lightbox-close { position: absolute; top: .75rem; right: 1rem; }
.ronda-lightbox-status { position: absolute; text-align: center; padding: 1rem; }
</style>
