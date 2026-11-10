import { createApp } from 'vue';
import tareaMain from './components/tareas/tareaMain.vue';

export function mountTareas() {
    createApp(tareaMain).mount('#main');
}
