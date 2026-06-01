<template>
    <div v-for="(subtarea, index) in subtareas" :key="index" class="row mb-1">
        <div class="col-12">
            <div class="list-group">
                <div class="list-group-item">
                    <button v-if="!subtarea.finalizada" class="btn btn-sm btn-outline-success" @click="finalizarTarea(subtarea.id)"><i class="bi bi-check"></i></button>
                    <button v-else class="btn btn-sm btn-outline-primary" @click="reactivarTarea(subtarea.id)"><i class="bi bi-arrow-repeat"></i></button>
                    &nbsp;
                    <span v-if="subtarea.finalizada" style="text-decoration: line-through;">{{subtarea.titulo}}</span>
                    <span v-else contenteditable="true" @keydown.prevent.enter="setTitulo($event, subtarea.id)" @blur="setTitulo($event, subtarea.id)">{{subtarea.titulo}}</span>
                    &nbsp;
                    <button class="btn btn-sm btn-outline-danger float-end" @click="eliminarTarea(subtarea.id)"><i class="bi bi-x"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';

// 1. Definimos las Props
const props = defineProps(['subtareas', 'tarea']);

// 2. Definimos los Eventos
const emit = defineEmits(['recargarSubtareas']);

// 3. Métodos
const eliminarTarea = (subtareaId) => {
    // Usamos props.tarea.id
    axios.delete('api/tarea/' + props.tarea.id + '/subtarea/' + subttareaId)
    .then(response => {
        if (response.status == 200) {
            // Usamos emit() en lugar de this.$emit
            emit('recargarSubtareas');
            const audio = new Audio('/assets/sounds/restore.mp3');
            audio.play();
        }
    })
    .catch(error => {
        console.log(error);
    });
};

const setTitulo = (event, subttareaId) => {
    event.target.blur();
    if (event.target.innerText == "") {
        return;
    }
    axios.put('api/tarea/' + props.tarea.id + '/subtarea/' + subttareaId, { 'titulo': event.target.innerText })
    .then(response => {
        if (response.status == 201) {
            emit('recargarSubtareas');
        }
    })
    .catch(error => {
        console.log(error);
    });
};

const finalizarTarea = (subtareaId) => {
    axios.put('api/tarea/' + props.tarea.id + '/subtarea/' + subttareaId, { 'finalizada': 1 })
    .then(response => {
        if (response.status == 201) {
            emit('recargarSubtareas');
            const audio = new Audio('/assets/sounds/done.mp3');
            audio.play();
        }
    })
    .catch(error => {
        console.log(error);
    });
};

const reactivarTarea = (subtareaId) => {
    axios.put('api/tarea/' + props.tarea.id + '/subtarea/' + subttareaId, { 'finalizada': 0 })
    .then(response => {
        if (response.status == 201) {
            emit('recargarSubtareas');
            const audio = new Audio('/assets/sounds/done.mp3');
            audio.play();
        }
    })
    .catch(error => {
        console.log(error);
    });
};
</script>