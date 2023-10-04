<template>
    <div class="card shadow mb-2">
    <div class="card-body">
        <em class="text-muted" style="text-decoration: line-through;">{{ tarea.titulo}}</em>
        <span class="float-right">
            <button 
            class="btn btn-warning btn-sm mx-1"
            @click="reactivarTarea()"
            >
            <i class="bi bi-arrow-repeat"></i>
            </button>
            <button 
            class="btn btn-danger btn-sm mx-1"
            @click="eliminarTarea()"
            >
            <i class="bi bi-x"></i>
            </button>
        </span>
    </div>
    </div>
</template>

<script>
export default ({
    props: ['tarea'],
    methods : {
        reactivarTarea(){
            this.tarea.finalizada = false;
            axios.put('api/tarea/' + this.tarea.id, this.tarea)
            .then( response => {
                if(response.status == 201){
                    this.$emit('tareaReactivada');
                    var audio = new Audio('/assets/sounds/restore.mp3');
                    audio.play();
                }
            })
            .catch( error => {
                console.log( error );
            })
        },
        eliminarTarea(){
            axios.delete('api/tarea/' + this.tarea.id)
            .then( response => {
                if( response.status == 200 ){
                    this.$emit('tareaEliminada');
                    var audio = new Audio('/assets/sounds/restore.mp3');
                    audio.play();
                }
            })
        }
    }
})
</script>
