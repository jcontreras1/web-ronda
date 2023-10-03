require('./bootstrap');
import { createApp } from 'vue';
import 'datatables.net-bs5';
import tareaMain from './components/tareas/tareaMain';

//require( 'jszip' );
// require( 'pdfmake' );
// require( 'datatables.net-buttons-bs5' )();
// require( 'datatables.net-buttons/js/buttons.html5.js' )();
// require( 'datatables.net-buttons/js/buttons.print.js' )();
const app = createApp({})
app.component('tarea-index', tareaMain);
app.mount('#main')
