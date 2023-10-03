<template>
    <div v-for="(subtarea, index) in subtareas" :key="index" class="row">
        <div class="col-12">
            <div class="list-group">
                <div class="list-group-item">
                    <button v-if="!subtarea.finalizada" class="btn btn-sm btn-outline-success" @click="finalizarTarea(subtarea.id)"><i class="bi bi-check"></i></button>
                    <button v-else class="btn btn-sm btn-outline-primary" @click="reactivarTarea(subtarea.id)"><i class="bi bi-arrow-repeat"></i></button>
                    &nbsp;
                    <span v-if="subtarea.finalizada" style="text-decoration: line-through;">{{subtarea.titulo}}</span>
                    <span v-else contenteditable="true" @keydown.prevent.enter="setTitulo($event, subtarea.id)" @blur="setTitulo($event, subtarea.id)">{{subtarea.titulo}}</span>
                    &nbsp;
                    <button class="btn btn-sm btn-outline-danger float-right" @click="eliminarTarea(subtarea.id)"><i class="bi bi-x"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>/
<script>
export default ({
    props : ['subtareas', 'tarea'],
    emits : ['recargarSubtareas'],
    methods : {
        eliminarTarea(subtarea){
            axios.delete('api/tarea/' + this.tarea.id + '/subtarea/' + subtarea)
            .then( response => {
                if( response.status == 200 ){
                    this.$emit('recargarSubtareas');
                    var audio = new Audio('/assets/sounds/restore.mp3');
                    audio.play();
                }
            })
        },
        setTitulo(event, subtarea){
            event.target.blur();
            if(event.target.innerText == ""){
                return;
            }
            axios.put('api/tarea/' + this.tarea.id + '/subtarea/' + subtarea, {'titulo' : event.target.innerText})
            .then( response => {
                if( response.status == 201 ){
                    this.$emit('recargarSubtareas');
                }
            })
            .catch( error => {
                console.log( error );
            })
        },
        finalizarTarea(subtarea){
            axios.put('api/tarea/' + this.tarea.id + '/subtarea/' + subtarea, {'finalizada' : 1})
            .then( response => {
                if( response.status == 201 ){
                    this.$emit('recargarSubtareas');
                    var audio = new Audio('/assets/sounds/done.mp3');
                    audio.play();
                }
            })
            .catch( error => {
                console.log( error );
            })
        },
        reactivarTarea(subtarea){
            axios.put('api/tarea/' + this.tarea.id + '/subtarea/' + subtarea, {'finalizada' : 0})
            .then( response => {
                if( response.status == 201 ){
                    this.$emit('recargarSubtareas');
                    var audio = new Audio('/assets/sounds/done.mp3');
                    audio.play();
                }
            })
            .catch( error => {
                console.log( error );
            })
        }
    }
})
</script>