<template>
    <div class="col-12 col-sm-6">
        <div class="media mb-2 bg-light h-100"
        >
            <div class="row">
            <div class="col-12 mb-2 text-center">
                
            <img class="img-fluid m-2" v-if="documento.formato == 'img'" 
            :src="'/storage/tareas/' + tarea.id + '/' + documento.nombre_archivo"
            />
            
            <img class="img-fluid m-2" v-else 
            :src="'/assets/img/icons/file_' + documento.formato + '.png'"
            style="max-height: 80px;" 
            />
            </div>
            <div class="text-center col-12">
                <p v-if="documento.formato !== 'img'" class="text-break">{{documento.nombre_real_archivo}}</p>
                <p class="">
                    <i class="fas fa-trash fa-lg fa-fw text-danger p-2" @click="eliminarDocumento" style="cursor: pointer;" title="Eliminar"></i>
                    <a :href="'/storage/tareas/' + tarea.id + '/' + documento.nombre_archivo" :download="documento.nombre_real_archivo">
                        <i class="fas fa-download fa-lg fa-fw text-primary p-2"></i>
                    </a>
                </p>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
export default {
    props : [
    'documento', 
    'tarea'
    ],
    methods : {
        eliminarDocumento(){
            axios.delete('api/tarea/' + this.tarea.id + '/documento/' + this.documento.id)
            .then(response => {
                this.$emit('documentoEliminado');
            })
            .catch(error => {
                console.log(error);
            });
        }
    },
}
</script>