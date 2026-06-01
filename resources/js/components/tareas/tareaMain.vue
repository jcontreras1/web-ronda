<template>
    <div class="container">
        <div class="row">
<div class="col-12">
            <h3>Lista de tareas</h3>
            <tarea-create
            v-on:tareaCreada="getTareas()"
            />
            <hr>
            <lista 
            :tareas="tareas" 
            :administrar="administrar"
            v-on:tareaCumplida="getTareas(true)"
            class="mb-2"
            />

            <lista-finalizadas 
            :tareas="finalizadas"
            v-on:tareaReactivada="getTareas(true)"
            v-on:tareaEliminada="getTareas(true)"
            />
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios'; // Asegúrate de tener axios importado si no es global
import tareaCreate from "./tareaCreate.vue";
import lista from './lista.vue';
import listaFinalizadas from './listaFinalizadas.vue';
import containerFiltros from './filtros/containerFiltros.vue';

// Estado Reactivo (reemplaza a 'data')
const tareas = ref([]);
const finalizadas = ref([]);
const administrar = ref(false);
const filtros = ref([]);

// Métodos (reemplaza a 'methods')
const aplicarFiltros = (personas) => {
    getTareas(false, personas);
};

const cmpPersonasOcurrencias = (a, b) => {
    if (a.ocurrencias <= b.ocurrencias) {
        return 1;
    } else {
        return -1;
    }
};

const getTareas = (finalizadas = false, responsables = null) => {
    axios.get('/api/tarea', { params: { responsables: responsables } })
        .then(response => {
            tareas.value = response.data;
            let personas = [];
            for (let i = 0; i < response.data.length; i++) {
                //Tiene responsable
                if (response.data[i].responsable_id) {
                    let index = personas.findIndex(e => e.id === response.data[i].responsable_id);
                    if (index !== -1) {
                        personas[index].ocurrencias++;
                    } else {
                        // Es mejor usar push en lugar de personas[personas.length]
                        personas.push({
                            'id': response.data[i].responsable.id,
                            'name': response.data[i].responsable.name,
                            'ocurrencias': 1
                        });
                    }
                }
            }
            filtros.value = personas.sort(cmpPersonasOcurrencias);
        })
        .catch(error => {
            console.log(error);
        });

    if (traerFinalizadas) {
        axios.get('/api/tareas/finalizadas')
            .then(response => {
                finalizadas.value = response.data;
            })
            .catch(error => {
                console.log(error);
            });
    }
};

// Ciclo de vida (reemplaza a 'created')
onMounted(() => {
    getTareas(true);
    // FIXME: ver que onda
    // administrar.value = can_administrar_modulo_tareas;
    administrar.value = true;
});
</script>