<template>
    <div class="row">
        <div class="col-12 m-2" v-if="documentos.length">Documentos</div>
        <documento v-for="documento in documentos" :key="documento.id" 
        :documento="documento"
        :tarea="tarea"
        v-on:documentoEliminado="$emit('recargarDocumentos')"
        />
    </div>
</template>
<script>
import documento from './documento.vue';
export default {
    components : {
        documento
    },
    emits : [
        'recargarDocumentos'
    ],
    props : [
        'documentos',
        'tarea'
    ],
    methods : {

    }
}
</script>