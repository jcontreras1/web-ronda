<template>
    <div class="input-group mb-3">
        <input 
        ref="input_subtarea" 
        v-model="subtarea.titulo"
        v-on:keyup.enter="agregarSubtarea"
        type="text" autofocus="" class="form-control" placeholder="Agregar pasos"
        />
        <button 
        :disabled="subtarea.titulo ? false : true" 
        @click="agregarSubtarea()"
        class="btn btn-outline-success" type="button" id="button-addon2"
        >Agregar</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

// 1. Props y Emits
const props = defineProps(['tarea']);
const emit = defineEmits(['subtareaCreada']);

// 2. Estado Reactivo
const subtarea = ref({
    titulo: ""
});

// 3. Referencia al elemento del DOM (Reemplaza a this.$refs.input_subtarea)
const input_subtarea = ref(null);

// 4. Métodos
const agregarSubtarea = () => {
    if (!subtarea.value.titulo) {
        return;
    }
    
    // Usamos props.tarea.id en lugar de this.tarea.id
    axios.post('/api/tarea/' + props.tarea.id + '/subtarea', {
        titulo: subtarea.value.titulo
    }).then(response => {
        if (response.status == 201) {
            const audio = new Audio('/assets/sounds/create.mp3');
            audio.play();
            
            subtarea.value.titulo = "";
            
            // Accedemos al input del DOM y le damos focus
            input_subtarea.value.focus();
            
            // Emitimos el evento hacia el padre
            emit('subtareaCreada');
        }
    }).catch(error => {
        console.log(error);
    });
};
</script>