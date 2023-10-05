<template>
    <div class="list-group mb-1">
        <div class="list-group-item" :class="comentario.fijado ? 'danger' : 'indigo'">
            <div class="text-muted">
                <i class="bi bi-chat-left mr-2" role="button" @click="fijarComentario"></i>
                {{comentario.user.name}}&nbsp;
                <span class="float-end">
                <span v-if="comentario.fijado">
                    <i title="fijado" class="bi bi-pin-angle text-danger"></i>
                </span>
                <span v-else-if="comentario.user_id == this.user" role="button" @click="eliminarComentario">
                    <i class="bi bi-trash text-danger"></i>
                </span>
            </span>
            </div>
            <span v-if="comentario.user_id == this.user" contenteditable="true" @keydown.prevent.enter="setComentario($event)" @blur="setComentario($event)">
                {{comentario.comentario}}
            </span>
            <span v-else>
                {{comentario.comentario}}
            </span>
        </div>
    </div>
</template>
<script>
export default {
    data: function(){
        return {
            user : null
        }  
    },  
    props: ['tarea', 'comentario'],
    emits: ['comentarioEditado'],
    methods : {
        eliminarComentario(){
            axios.delete('api/tarea/' + this.tarea.id + '/comentario/' + this.comentario.id)
            .then(response => {
                if(response.status == 200){
                    this.$emit('comentarioEditado');
                }
            })
            .catch(error => {
                console.log(error);
            })
        },
        setComentario(event){
            if(!event.target.innerText){
                event.target.innerText = this.comentario.comentario;
            }
            if(event.target.innerText === this.comentario.comentario){
                return;
            }
            axios.put('api/tarea/' + this.tarea.id + '/comentario/' + this.comentario.id + '/', {'comentario' : event.target.innerText})
            .then( response => {
                if(response.status == 201){
                    this.$emit('comentarioEditado');
                }
            })
            .catch( error => {
                console.log( error );
            })
        },
        fijarComentario(){
            axios.put('api/tarea/' + this.tarea.id + '/comentario/' + this.comentario.id + '/', {'fijado' : !this.comentario.fijado})
            .then( response => {
                if(response.status == 201){
                    this.$emit('comentarioEditado');
                }
            })
            .catch( error => {
                console.log( error );
            })
        }
    },
    created(){
        this.user = user_id;
    }
}
</script>