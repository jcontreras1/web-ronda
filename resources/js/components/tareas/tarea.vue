<template>
    <div class="card shadow">
        <a data-bs-toggle="collapse" :href="'#collapseTarea' + tarea.id" role="button" aria-expanded="false" aria-controls="collapseExample" style="text-decoration: none;" class="text-dark">
            <div class="card-body">
                <span >{{ tarea.titulo }}</span>
                <span class="float-right">
                    <button 
                    class="btn btn-success btn-sm"
                    @click.prevent.stop="completarTarea()"
                    >
                    <i class="fas fa-check"></i>
                </button>
            </span>
            <div>
                <!-- Responsable -->
                <span v-if="tarea.responsable_id" class="ml-1 badge badge-success bg-secondary" data-bs-toggle="tooltip" title="Responsable">

                    <i class="bi bi-user"></i> {{user_selected.name}}
                </span>
                <!-- Subtareas - Progress -->
                <span v-if="totales" class="ml-1 badge badge-dark" data-bs-toggle="tooltip" title="Tareas">
                    Tareas {{realizadas}}/{{totales}}
                </span>
                <!-- Comentarios -->
                <span v-if="comentarios.length" class="ml-1 badge badge-dark" data-bs-toggle="tooltip" title="Tiene comentarios">
                    <i class="far fa-comment-alt mr-2"></i>{{comentarios.length}}
                </span>
                <!-- Renovable -->
                <span v-if="tarea.renovable" class="ml-1 badge badge-primary" data-bs-toggle="tooltip" title="Renovable">
                    <i class="fas fa-sync-alt"></i>
                </span>
                <!-- Archivos -->
                <span v-if="documentos.length" class="ml-1 badge badge-secondary" data-bs-toggle="tooltip" title="Documentos">
                    {{this.documentos.length}} <i class="fas fa-file-alt"></i>
                </span>
            </div>
        </div>
    </a>
    <div class="collapse" :id="'collapseTarea' + tarea.id">
        <div class="card-footer bg-white">
            <div class="row">
                <div class="col-12">
                    <!-- Responsable -->
                    <div class="mb-3">
                        <label>Responsable</label>
                        <select class="form-control form-control-sm" :disabled="!(tarea.responsable_id == user_id || administrar || (tarea.creador.id == user_id))" @change="setResponsable($event)">
                            <option value=""></option>
                            <option v-for="user in users" :key="user.id" :value="user.id" :selected="tarea.responsable.id === user.id">
                                {{ user.name }}
                            </option>
                        </select>
                    </div>
                    <!-- Tarea Periódica -->
                    <div>
                        <div class="custom-control custom-switch">
                            <input 
                            :checked="tarea.renovable" 
                            type="checkbox" 
                            @change="setPeriodica($event)" 
                            class="custom-control-input" 
                            :id="'customSwitch' + tarea.id"
                            />
                            <label class="custom-control-label" :for="'customSwitch' + tarea.id">Tarea periódica</label>
                        </div>
                    </div>                    
                </div>
                
                <!-- Subtareas -->
                <div class="col-12">
                    <label>Subtareas</label>
                    <subtarea-create
                    :tarea="tarea"
                    v-on:subtareaCreada="recargarSubtareas"
                    />
                    <hr>
                    <subtarea-list 
                    :subtareas="subtareas"
                    :tarea="tarea"
                    v-on:recargarSubtareas="recargarSubtareas"
                    />
                </div>
                
                <!-- Comentarios -->
                <div class="col-12">
                    <label>Comentarios</label>
                    <comentario-create
                    :tarea="tarea"
                    v-on:comentarioCreado="recargarComentarios"
                    />
                    <hr>
                    <comentario-list 
                    :comentarios="comentarios"
                    :tarea="tarea"
                    v-on:recargarComentarios="recargarComentarios"
                    />
                </div>                
            </div>
            <hr>
            <div class="row">
                 <!-- Documentos -->
                 <div class="col-12">
                         <documento-create 
                         :tarea="tarea"
                         v-on:recargarDocumentos="recargarDocumentos"
                         />                        
                    </div>
                    
                    <div class="col-12">
                        <documento-list 
                            :documentos="documentos"
                            :tarea="tarea"
                            v-on:recargarDocumentos="recargarDocumentos"
                        />
                    </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="float-right">
                        <small class="text-muted">
                        Tarea #{{tarea.id}}
                        Creada por {{tarea.creador.name}} {{tarea.created_at_human}}
                    </small>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
</template>

<script>
import subtareaCreate from './subtareas/subtareaCreate.vue';
import subtareaList from './subtareas/subtareaList.vue';
import comentarioCreate from './comentarios/comentarioCreate.vue';
import comentarioList from './comentarios/comentarioList.vue';
import documentoCreate from './documentos/documentosForm.vue';
import documentoList from './documentos/documentosList.vue';
export default ({
    data: function(){
        return {
            documentos : [],
            subtareas : [],
            comentarios : [],
            totales : 0,
            realizadas : 0,
            users : [],
            user_selected : 
            {
                id : null,
                name : null,
            },
            user_id : null,
        }
    },
    components : {
        subtareaCreate,
        subtareaList,
        comentarioCreate,
        comentarioList,
        documentoCreate,
        documentoList,
    },
    props: ['tarea', 'administrar'],
    methods : {
        completarTarea(){
            this.tarea.finalizada = true;
            axios.put('api/tarea/' + this.tarea.id, this.tarea)
            .then( response => {
                if(response.status == 201){
                    this.$emit('tareaCumplida');
                    var audio = new Audio('/assets/sounds/done.mp3');
                    audio.play();
                }
            })
            .catch( error => {
                console.log( error );
            })
        },
        setPeriodica(event){
            this.tarea.renovable = event.target.checked;
            axios.put('api/tarea/' + this.tarea.id, this.tarea)
            .then( response => {
                if(response.status == 201){
                    console.log(response.data);
                }
            })
            .catch( error => {
                console.log( error );
            })
        },
        recargarSubtareas(){
            axios.get('api/tarea/' + this.tarea.id + '/subtarea')
            .then( response => {
                if(response.status == 200){
                    this.subtareas = response.data;
                    this.totales = response.data.length;
                    let temp = 0;
                    for (let i in response.data){
                        if(response.data[i].finalizada == true){
                            temp++;
                        }
                    }
                    this.realizadas = temp;
                }
            })
            .catch( error => {
                console.log( error );
            })
        },
        setResponsable(event){
            this.tarea.responsable_id = event.target.value ? event.target.value : null;
            axios.put('api/tarea/' + this.tarea.id, this.tarea)
            .then( response => {
                if(response.status == 201){
                    this.user_selected = response.data.responsable;
                    this.$emit('tareaCumplida');
                }
            })
            .catch( error => {
                console.log( error );
            })
        },
        /* Para obtener los usuarios a asignar a alguna tarea */
        getUsers(){
            axios.get('/api/users')
            .then( response => {
                this.users = response.data.data;
            })
            .catch(error => {
                console.log(error);
            });
        },
        recargarComentarios(){
            axios.get('api/tarea/' + this.tarea.id + '/comentario')
            .then( response => {
                if(response.status == 200){
                    this.comentarios = response.data;
                }
            })
            .catch( error => {
                console.log( error );
            })
        },
        recargarDocumentos(){
            axios.get('api/tarea/' + this.tarea.id + '/documento')
            .then( response => {
                if(response.status == 200){
                    this.documentos = response.data;
                }
            })
            .catch( error => {
                console.log( error );
            })
        }
    },
    created (){
        this.recargarSubtareas();
        this.recargarComentarios();
        this.getUsers();
        this.user_selected = this.tarea.responsable;
        this.recargarDocumentos();
        this.user_id = user_id;
    }
})
</script>
