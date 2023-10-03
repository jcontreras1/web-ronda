<template>
    <div class="jumbotron">
        <h3>Panel de Control</h3>
        <hr>
        <h5>Filtros por Responsable</h5>
        <div v-for="filtro in filtros" :key="filtro.id">            
            <div class="custom-control custom-switch mb-2">
                <input 
                type="checkbox" 
                class="custom-control-input filtro-persona" 
                :data-id="filtro.id"
                :id="'customSwitch' + filtro.id"
                @change="changeSwitch"
                />
                <label class="custom-control-label" :for="'customSwitch' + filtro.id">{{filtro.name}} <small class="text-muted">({{filtro.ocurrencias}})</small></label>
            </div>
        </div>
        <div class="py-2"></div>
        <button v-if="administrar" @click="renovar_tareas" class="btn btn-outline-primary mb-2">
            Renovar tareas periódicas
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
</template>
<script>
export default {
    props : ['filtros', 'administrar'],
    emits : ['aplicarFiltros'],
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
        },
        changeSwitch(){
            const personasSeleccionadas = document.getElementsByClassName('filtro-persona');
            let personas = [];
            for(let i = 0; i < personasSeleccionadas.length; i++){
                if(personasSeleccionadas[i].checked){
                    personas.push(personasSeleccionadas[i].dataset.id);
                }
            }
            this.$emit('aplicarFiltros', personas);
        }
    }
}
</script>