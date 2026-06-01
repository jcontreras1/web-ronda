<template>
    <div class="input-group mb-3">
      <input 
      ref="input_comentario" 
      v-model="comentario.comentario"
      v-on:keyup.enter="agregarComentario"
      type="text" autofocus="" class="form-control" placeholder="Agregar comentario"
      />
      <button 
      :disabled="comentario.comentario ? false : true" 
      @click="agregarComentario"
      class="btn btn-outline-success" type="button" id="button-addon2"
      >Agregar</button>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

// 1. Props y Emits
const props = defineProps(['tarea']);
const emit = defineEmits(['comentarioCreado']);

// 2. Estado Reactivo
const comentario = ref({
    comentario: ""
});

// 3. Referencia al elemento del DOM
const input_comentario = ref(null);

// 4. Métodos
const agregarComentario = () => {
    if (!comentario.value.comentario) {
        return;
    }
    
    // Usamos props.tarea.id
    axios.post('/api/tarea/' + props.tarea.id + '/comentario', {
        comentario: comentario.value.comentario
    }).then(response => {
        if (response.status == 201) {
            const audio = new Audio('/assets/sounds/create.mp3');
            audio.play();
            
            comentario.value.comentario = "";
            
            // Accedemos al input del DOM y le damos focus
            input_comentario.value.focus();
            
            // Emitimos el evento hacia el padre
            emit('comentarioCreado');
        }
    }).catch(error => {
        console.log(error);
    });
};
</script>