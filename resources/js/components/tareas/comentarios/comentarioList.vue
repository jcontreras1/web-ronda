<template>
    <div v-for="(comentario, index) in comentarios" :key="index">
        <comentario 
        :comentario="comentario"
        :tarea="tarea"
        v-on:comentarioEditado="$emit('recargarComentarios')"
        />
    </div>
</template>
<script>
import comentario from './comentario.vue';
export default {
    props : ['comentarios', 'tarea'],
    emits : ['recargarComentarios'],
    methods : {

    },
    components : {
        comentario
    },
    created (){
    }
}
</script>