<template>
    <a class="btn btn-primary mb-2" data-toggle="collapse" href="#collapseFinalizadas" role="button" aria-expanded="false" aria-controls="collapseExample">
        Historial
    </a>
    <div class="collapse" id="collapseFinalizadas">
    <div class="row">
            <div v-for="(tarea, index) in tareas" :key="index" class="col-12">
                <tareaFinalizada 
                v-on:tareaReactivada="$emit('tareaReactivada')"
                v-on:tareaEliminada="$emit('tareaEliminada')"
                :tarea="tarea"
                />
            </div>
        </div>
    </div>
</template>

<script>
import tareaFinalizada from './tareaFinalizada.vue';
export default ({
    props : ['tareas'],
    emits : ['tareaEliminada', 'tareaReactivada'],
    components : {
        tareaFinalizada
    }
})
</script>
