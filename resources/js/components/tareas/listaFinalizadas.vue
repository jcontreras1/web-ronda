<template>
    <div class="accordion" id="accordionExample">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Historial
        </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <div class="row mb-2">
            <div class="col-12">
                <button @click="renovar_tareas" class="btn btn-primary mb-2 mx-1" title="Renovar tareas">
                    <i class="bi bi-arrow-repeat"></i>&nbsp;Renovar tareas periódicas
                </button>
            </div>
        </div>
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
</div>
</div>
</div>

</template>

<script>
    import tareaFinalizada from './tareaFinalizada.vue';
    export default ({
        methods : {
            renovar_tareas(){            
                Swal.fire({
                    title: '¿Renovar todas las tareas periódicas?',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: `Cancelar`,
                    icon : `question`
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post('/api/tareas/reactivar')
                        .then( response => {
                            this.$emit('aplicarFiltros', []);
                        })
                        .catch(error => {
                            console.log(error);
                        });
                    }
                })
            }
        },
        props : ['tareas'],
        emits : ['tareaEliminada', 'tareaReactivada'],
        components : {
            tareaFinalizada
        }
    })
</script>
