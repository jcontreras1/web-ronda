<template>
    <div class="input-group mb-3">
      <input 
      ref="input_tarea" 
      v-model="tarea.titulo"
      v-on:keyup.enter="agregarTarea"
      @input="updateProgressBar"
      type="text" autofocus="" class="form-control" placeholder="Descripción de la tarea"
      />
      <button 
      :disabled="tarea.titulo ? false : true" 
      @click="agregarTarea()"
      class="btn btn-outline-success" type="button" id="button-addon2"
      >Agregar</button>
  </div>

  <div class="progress" style="height: 3px;">
    <div 
    class="progress-bar"
    :class="{ 
        'bg-primary': tarea.titulo.length < 125,
        'bg-danger': tarea.titulo.length >= 125 
    }"
    role="progressbar" 
    :style="{width: progress + '%'}" 
    :aria-valuenow="progress" 
    aria-valuemin="0" 
    aria-valuemax="100">
</div>
</div>
<span v-if="tarea.titulo.length">
    <span v-if="tarea.titulo.length === 140 - 1">
        1 caracter restante
    </span>
    <span v-else>            
        {{140 - tarea.titulo.length}} caracteres restantes
    </span>
</span>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

// 1. Declaramos los eventos que emite el componente
const emit = defineEmits(['tareaCreada']);

// 2. Estado reactivo (reemplaza a 'data')
const tarea = ref({
    titulo: ""
});
const progress = ref(0);

// 3. Referencia al elemento del DOM (reemplaza a this.$refs.input_tarea)
// El nombre de la variable DEBE coincidir con el ref="..." en el template
const input_tarea = ref(null);

// 4. Métodos (reemplaza a 'methods')
const updateProgressBar = () => {
    if (tarea.value.titulo.length > 140) {
        tarea.value.titulo = tarea.value.titulo.slice(0, 140); // Limita el texto a 140 caracteres
    }
    // Calcula el porcentaje de progreso basado en la longitud del texto
    progress.value = (tarea.value.titulo.length / 140) * 100;
};

const agregarTarea = () => {
    if (!tarea.value.titulo) {
        return;
    }
    
    axios.post('/api/tarea', {
        titulo: tarea.value.titulo
    }).then(response => {
        if (response.status == 201) {
            const audio = new Audio('/assets/sounds/create.mp3');
            audio.play();
            
            tarea.value.titulo = "";
            
            // Accedemos al input del DOM y le damos focus
            input_tarea.value.focus();
            
            emit('tareaCreada');
            updateProgressBar();
        }
    }).catch(error => {
        console.log(error);
    });
};
</script>