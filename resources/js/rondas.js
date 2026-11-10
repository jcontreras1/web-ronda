import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import RondasLayout from './layouts/RondasLayout.vue';

const pages = import.meta.glob('./pages/Rondas/*.vue');

createInertiaApp({
    id: 'rondas-app',
    resolve: async (name) => {
        const load = pages[`./pages/${name}.vue`];
        if (!load) {
            throw new Error(`Pantalla no encontrada: ${name}`);
        }
        const page = await load();
        page.default.layout = RondasLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) }).use(plugin).mount(el);
    },
    progress: { color: '#2d89c8', delay: 150 },
});
