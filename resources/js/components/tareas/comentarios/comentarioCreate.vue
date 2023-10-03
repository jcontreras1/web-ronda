<template>
    <div class="input-group mb-3 input-group-sm">
       <input 
       ref="input_comentario" 
       v-model="comentario.comentario"
       v-on:keyup.enter="agregarComentario"
       type="text" autofocus="" class="form-control" placeholder="Agregar comentario"
       />
       <div class="input-group-append">
           <button 
           :disabled="comentario.comentario ? false : true" 
           @click="agregarComentario"
           class="btn btn-outline-success" type="button" id="button-addon2"
           >Agregar</button>
       </div>
   </div>
</template>
<script>

export default ({
   props : ['tarea'],
   emits : ['comentarioCreado'],
   data: function(){
       return {
           comentario : {
               comentario : ""
           }
       }
   },
   methods : {
       agregarComentario(){
           if( !this.comentario.comentario ){
               return;
           }
           axios.post('/api/tarea/'+this.tarea.id+'/comentario', {
               comentario : this.comentario.comentario
           }).then(response => {
               if( response.status == 201 ){
                   var audio = new Audio('/assets/sounds/create.mp3');
                   audio.play();
                   this.comentario.comentario = "";
                   this.$refs.input_comentario.focus();
                   this.$emit('comentarioCreado');
               }
           }).catch( error => {
               console.log( error );
           })
       }
   },
   created (){
   }
})
</script>
