<template>
    <div class="input-group mb-3 input-group-lg">
        <input 
        ref="input_tarea" 
        v-model="tarea.titulo"
        v-on:keyup.enter="agregarTarea"
        @input="updateProgressBar"
        type="text" autofocus="" class="form-control" placeholder="Descripción de la tarea"
        />
        <div class="input-group-append">
            <button 
            :disabled="tarea.titulo ? false : true" 
            @click="agregarTarea()"
            class="btn btn-outline-success" type="button" id="button-addon2"
            >Agregar</button>
        </div>
    </div>
    <div class="progress" style="height: 3px;">
        <div 
        class="progress-bar"
        :class="{ 
            'bg-primary': tarea.titulo.length < 125,
            'bg-danger': tarea.titulo.length >= 125 
        }"
        role="progressbar" 
        :style="{width: progress + '%'}" 
        :aria-valuenow="progress" 
        aria-valuemin="0" 
        aria-valuemax="100">

    </div>
</div>
<span v-if="tarea.titulo.length">
    <span v-if="tarea.titulo.length === 140 - 1">
        1 caracter restante
    </span>
    <span v-else>            
        {{140 - tarea.titulo.length}} caracteres restantes
    </span>
</span>
</template>

<script>
    import axios from 'axios';

    export default ({
        data: function(){
            return {
                tarea : {
                    titulo : ""
                },
                progress : 0
            }
        },
        emits:[
            'tareaCreada'
            ],
        methods : {
            agregarTarea(){
                if( !this.tarea.titulo ){
                    return;
                }
                axios.post('/api/tarea', {
                    titulo : this.tarea.titulo
                }).then(response => {
                    if( response.status == 201 ){
                        var audio = new Audio('/assets/sounds/create.mp3');
                        audio.play();
                        this.tarea.titulo = "";
                        this.$refs.input_tarea.focus();
                        this.$emit('tareaCreada');
                        this.updateProgressBar();
                    }
                }).catch( error => {
                    console.log( error );
                } )
            },
            updateProgressBar(){
                if (this.tarea.titulo.length > 140) {
                        this.tarea.titulo = this.tarea.titulo.slice(0, 140); // Limita el texto a 140 caracteres
                    }
                    // Calcula el porcentaje de progreso basado en la longitud del texto
                    this.progress = (this.tarea.titulo.length / 140) * 100;
                }
            }
        })
    </script>
