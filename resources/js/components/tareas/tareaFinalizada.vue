<template>
    <div class="card shadow mb-2">
        <div class="card-body">
            <em class="text-muted" style="text-decoration: line-through;">{{ tarea.titulo}}</em>
            <span class="float-right">
                <button 
                class="btn btn-warning btn-sm mx-1"
                @click="reactivarTarea()"
                >
                <i class="bi bi-arrow-repeat"></i>
                </button>
                <button 
                class="btn btn-danger btn-sm mx-1"
                @click="eliminarTarea()"
                >
                <i class="bi bi-x"></i>
                </button>
            </span>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';

// 1. Definimos las props y las guardamos en una constante
const props = defineProps(['tarea']);

// 2. Definimos los eventos que emitirá el componente
const emit = defineEmits(['tareaReactivada', 'tareaEliminada']);

// 3. Convertimos los métodos en funciones normales de JavaScript
const reactivarTarea = () => {
    // Usamos props.tarea en lugar de this.tarea
    props.tarea.finalizada = false;
    
    axios.put('api/tarea/' + props.tarea.id, props.tarea)
    .then(response => {
        if (response.status == 201 || response.status == 200) {
            // Reemplazamos this.$emit por emit()
            emit('tareaReactivada');
            const audio = new Audio('/assets/sounds/restore.mp3');
            audio.play();
        }
    })
    .catch(error => {
        console.log(error);
    });
};

const eliminarTarea = () => {
    axios.delete('api/tarea/' + props.tarea.id)
    .then(response => {
        if (response.status == 200) {
            emit('tareaEliminada');
            const audio = new Audio('/assets/sounds/restore.mp3');
            audio.play();
        }
    })
    .catch(error => {
        console.log(error);
    });
};
</script>