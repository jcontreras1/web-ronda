<template>
   
    <div class="input-group mb-3">
     <input 
     ref="input_subtarea" 
     v-model="subtarea.titulo"
     v-on:keyup.enter="agregarSubtarea"
     type="text" autofocus="" class="form-control" placeholder="Agregar pasos"
     />
     <button 
     :disabled="subtarea.titulo ? false : true" 
     @click="agregarSubtarea()"
     class="btn btn-outline-success" type="button" id="button-addon2"
     >Agregar</button>
 </div>
</template>
<script>

    export default ({
        props : ['tarea'],
        emits : ['subtareaCreada'],
        data: function(){
            return {
                subtarea : {
                    titulo : ""
                }
            }
        },
        methods : {
            agregarSubtarea(){
                if( !this.subtarea.titulo ){
                    return;
                }
                axios.post('/api/tarea/'+this.tarea.id+'/subtarea', {
                    titulo : this.subtarea.titulo
                }).then(response => {
                    if( response.status == 201 ){
                        var audio = new Audio('/assets/sounds/create.mp3');
                        audio.play();
                        this.subtarea.titulo = "";
                        this.$refs.input_subtarea.focus();
                        this.$emit('subtareaCreada')
                    }
                }).catch( error => {
                    console.log( error );
                } )
            }
        },
        created (){
        }
    })
</script>
