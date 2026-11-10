<script setup>
import { onBeforeUnmount, ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const page = usePage();
const connectionError = ref('');
const successMessage = ref('');
let successTimer;

function clearSuccess() {
    clearTimeout(successTimer);
    successMessage.value = '';
}

watch(() => page.props.flash, (flash) => {
    clearSuccess();
    successMessage.value = flash?.success ?? '';
    if (successMessage.value) {
        successTimer = setTimeout(clearSuccess, successMessage.value === 'Novedad agregada' ? 1000 : 3000);
    }
}, { immediate: true });

const stopException = router.on('exception', (event) => {
    console.error('No se pudo completar la solicitud', event.detail.exception);
    connectionError.value = 'No se pudo completar la solicitud. Revisá la conexión e intentá nuevamente.';
    event.preventDefault();
});
const stopStart = router.on('start', () => {
    connectionError.value = '';
    clearSuccess();
});
onBeforeUnmount(() => {
    clearSuccess();
    stopException();
    stopStart();
});
</script>

<template>
    <div class="container pb-3">
        <div v-if="connectionError" class="alert alert-danger" role="alert">{{ connectionError }}</div>
        <Transition name="ronda-toast">
            <div v-if="successMessage" class="ronda-success-toast alert alert-success py-2 px-3 mb-0 shadow-sm" role="status">
                <i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>{{ successMessage }}
            </div>
        </Transition>
        <div v-for="(error, field) in page.props.errors" :key="field" class="alert alert-danger" role="alert">
            {{ error }}
        </div>
        <Transition name="ronda-page" mode="out-in">
            <div :key="page.component">
                <slot />
            </div>
        </Transition>
    </div>
</template>

<style>
.ronda-success-toast {
    position: fixed;
    right: 1rem;
    bottom: 1rem;
    z-index: 1055;
    max-width: calc(100vw - 2rem);
    pointer-events: none;
}
.ronda-toast-enter-active,
.ronda-toast-leave-active { transition: opacity 150ms ease; }
.ronda-toast-enter-from,
.ronda-toast-leave-to { opacity: 0; }
.ronda-page-enter-active,
.ronda-page-leave-active,
.ronda-toast-enter-active,
.ronda-toast-leave-active {
    transition: opacity 80ms ease;
}
.ronda-page-enter-from,
.ronda-page-leave-to {
    opacity: 0;
}
@media (prefers-reduced-motion: reduce) {
    .ronda-page-enter-active,
    .ronda-page-leave-active {
        transition: none;
    }
}
</style>
