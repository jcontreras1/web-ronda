<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    abiertas: Array,
    cerradas: Object,
    circuitos: Array,
    filters: Object,
    urls: Object,
});
const page = usePage();
const search = ref(props.filters.q);
const sort = ref(props.filters.sort ?? 'created_at');
const direction = ref(props.filters.direction ?? 'desc');
const createDialog = ref(null);
const pending = ref(null);
const form = useForm({ circuito_id: props.circuitos[0]?.id ?? null });
watch(() => props.filters, (value) => {
    search.value = value.q;
    sort.value = value.sort ?? 'created_at';
    direction.value = value.direction ?? 'desc';
});
watch(() => props.circuitos, (value) => { form.circuito_id = value[0]?.id ?? null; });

function create() {
    form.post(props.urls.store, {
        preserveScroll: true,
        onSuccess: () => createDialog.value.close(),
    });
}

function startCreate() {
    form.clearErrors();
    if (props.circuitos.length > 1) {
        createDialog.value.showModal();
    } else {
        create();
    }
}

function manage(ronda, action) {
    const close = action === 'close';
    if (!window.confirm(close ? '¿Cerrar ronda?' : '¿Eliminar ronda?')) return;
    pending.value = ronda.id;
    const options = { preserveScroll: true, onFinish: () => { pending.value = null; } };
    if (close) {
        router.patch(ronda.urls.close, {}, options);
    } else {
        router.delete(ronda.urls.destroy, options);
    }
}

function filter() {
    router.get(props.urls.index, { q: search.value, sort: sort.value, direction: direction.value }, {
        only: ['cerradas', 'filters'],
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function sortBy(column) {
    direction.value = sort.value === column && direction.value === 'asc' ? 'desc' : 'asc';
    sort.value = column;
    filter();
}

function date(value) {
    return new Intl.DateTimeFormat('es-AR', { dateStyle: 'short', timeStyle: 'short' }).format(new Date(value));
}
</script>

<template>
    <Head :title="`Rondas - ${page.props.appName}`" />
    <div class="d-flex align-items-center justify-content-between my-3">
        <h1 class="h3 mb-0">Rondas abiertas</h1>
        <button class="btn btn-success" :disabled="form.processing" @click="startCreate">
            {{ form.processing ? 'Creando...' : 'Nueva ronda' }}
        </button>
    </div>
    <div class="row mb-3">
        <p v-if="!abiertas.length" class="text-muted">No hay rondas abiertas.</p>
        <div v-for="ronda in abiertas" :key="ronda.id" class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card mb-3 shadow-sm">
                <Link :href="ronda.urls.show" class="card-body text-decoration-none text-body">
                    <h2 class="h5">Ronda #{{ ronda.id }} <small v-if="ronda.circuito" class="text-muted">- {{ ronda.circuito }}</small></h2>
                    <hr>
                    <div>Fecha: <strong>{{ date(ronda.created_at) }}</strong></div>
                    <div>Por: <strong>{{ ronda.creador }}</strong></div>
                    <small>{{ ronda.checkpoints_count ? `${ronda.checkpoints_count} puntos de control` : 'Sin datos' }}</small>
                </Link>
                <div v-if="ronda.canManage" class="card-footer text-end">
                    <button v-if="ronda.checkpoints_count" class="btn btn-warning me-1" :disabled="pending !== null" aria-label="Cerrar ronda" @click="manage(ronda, 'close')">
                        <i class="bi bi-lock-fill"></i>
                    </button>
                    <button class="btn btn-danger" :disabled="pending !== null" aria-label="Eliminar ronda" @click="manage(ronda, 'destroy')">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <h2 class="h4">Histórico</h2>
    <form class="d-flex gap-2 my-3" role="search" @submit.prevent="filter">
        <label for="ronda-search" class="visually-hidden">Buscar por persona o circuito</label>
        <input id="ronda-search" v-model="search" type="search" maxlength="100" class="form-control" placeholder="Buscar por persona o circuito">
        <button class="btn btn-outline-primary">Buscar</button>
    </form>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th :aria-sort="sort === 'creador' ? (direction === 'asc' ? 'ascending' : 'descending') : 'none'">
                        <button class="btn btn-link text-body fw-bold p-0" @click="sortBy('creador')">Recorre <i v-if="sort === 'creador'" :class="direction === 'asc' ? 'bi bi-arrow-up' : 'bi bi-arrow-down'"></i></button>
                    </th>
                    <th :aria-sort="sort === 'created_at' ? (direction === 'asc' ? 'ascending' : 'descending') : 'none'">
                        <button class="btn btn-link text-body fw-bold p-0" @click="sortBy('created_at')">Fecha <i v-if="sort === 'created_at'" :class="direction === 'asc' ? 'bi bi-arrow-up' : 'bi bi-arrow-down'"></i></button>
                    </th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="ronda in cerradas.data" :key="ronda.id">
                    <td>
                        <Link :href="ronda.urls.show">{{ ronda.creador }}</Link>
                        <span class="float-end text-primary-emphasis">
                            <i v-if="ronda.novedades_count" class="bi bi-list me-2" title="Tiene novedades"></i>
                            <i v-if="ronda.images_count" class="bi bi-image-alt me-2" title="Hay imágenes"></i>
                            <span v-if="ronda.checkpoints_count" class="border rounded px-1" title="Puntos visitados">{{ ronda.checkpoints_count }}</span>
                        </span>
                    </td>
                    <td>{{ date(ronda.created_at) }}</td>
                    <td><Link :href="ronda.urls.show" class="btn btn-primary" :aria-label="`Ver ronda ${ronda.id}`"><i class="bi bi-list-task"></i></Link></td>
                </tr>
                <tr v-if="!cerradas.data.length"><td colspan="3" class="text-muted">No hay rondas que coincidan.</td></tr>
            </tbody>
        </table>
    </div>
    <nav aria-label="Páginas del histórico" class="d-flex align-items-center justify-content-between gap-2">
        <Link v-if="cerradas.prev_page_url" :href="cerradas.prev_page_url" :only="['cerradas', 'filters']" preserve-state preserve-scroll class="btn btn-outline-primary">Anterior</Link>
        <span class="ms-auto">Página {{ cerradas.current_page }} de {{ cerradas.last_page }} · {{ cerradas.total }} rondas</span>
        <Link v-if="cerradas.next_page_url" :href="cerradas.next_page_url" :only="['cerradas', 'filters']" preserve-state preserve-scroll class="btn btn-outline-primary">Siguiente</Link>
    </nav>

    <dialog ref="createDialog" class="ronda-create-dialog rounded border-0 p-0" aria-labelledby="create-title" @click.self="!form.processing && createDialog.close()" @cancel="form.processing && $event.preventDefault()">
        <form @submit.prevent="create">
            <div class="p-3 border-bottom"><h2 id="create-title" class="h5 mb-0">Crear ronda</h2></div>
            <div class="p-3">
                <div v-for="(error, field) in form.errors" :key="field" class="alert alert-danger" role="alert">{{ error }}</div>
                <label for="circuito" class="form-label">Circuito</label>
                <select id="circuito" v-model="form.circuito_id" class="form-select" :disabled="form.processing">
                    <option v-for="circuito in circuitos" :key="circuito.id" :value="circuito.id">#{{ circuito.id }} - {{ circuito.titulo }}</option>
                </select>
            </div>
            <div class="p-3 border-top d-flex gap-2">
                <button class="btn btn-success" :disabled="form.processing">{{ form.processing ? 'Creando...' : 'Aceptar' }}</button>
                <button type="button" class="btn btn-secondary" :disabled="form.processing" @click="createDialog.close()">Cancelar</button>
            </div>
        </form>
    </dialog>
</template>

<style scoped>
.ronda-create-dialog {
    width: min(95vw, 500px);
    color: var(--bs-body-color);
    background: var(--bs-body-bg);
}
.ronda-create-dialog::backdrop { background: rgb(0 0 0 / 65%); }
</style>
