<template>
    <div class="row">

        <div v-for="(tarea) in tareas" :key="tarea.id" class="col-12 col-sm-6 col-md-4 mb-2">
            <tarea 
            :tarea="tarea"
            :administrar="administrar"
            v-on:tareaCumplida="$emit('tareaCumplida')"
            />
        </div>
    </div>

</template>

<script>
import tarea from './tarea.vue';
export default ({
    props : ['tareas', 'administrar'],
    components : {
        tarea
    }
})
</script>
