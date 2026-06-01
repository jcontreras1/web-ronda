<template>
    <div class="card shadow p-3">
        <div class="row d-flex align-items-center">
            <div class="col-2">
                <button class="btn btn-success btn-sm" @click.stop.prevent="completarTarea($event)"> <i class="bi bi-check"></i></button>
            </div>
            <div class="col-10" role="button">
                <div data-bs-toggle="offcanvas" :data-bs-target="'#offCanvasTarea'+tarea.id">                       
                    <span>{{ tarea.titulo }}</span>
                    <div>
                        <span v-if="tarea.responsable_id" class="mr-1 badge bg-primary" data-bs-toggle="tooltip" title="Responsable">

                            <i class="bi bi-person"></i> {{user_selected?.name}}
                        </span>
                        <span v-if="totales" class="mr-1 badge bg-success" data-bs-toggle="tooltip" title="Tareas">
                            Tareas {{realizadas}}/{{totales}}
                        </span>
                        <span v-if="comentarios.length" class="mr-1 badge bg-secondary" data-bs-toggle="tooltip" title="Tiene comentarios">
                            <i class="bi bi-chat mr-1"></i>&nbsp;{{comentarios.length}}
                        </span>
                        <span v-if="tarea.renovable" class="mr-1 badge bg-primary" data-bs-toggle="tooltip" title="Renovable">
                            <i class="bi bi-arrow-repeat"></i>
                        </span>
                        <span v-if="documentos.length" class="mr-1 badge bg-secondary" data-bs-toggle="tooltip" title="Documentos">
                            {{documentos.length}} <i class="bi bi-file-earmark-text"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" :id="'offCanvasTarea' + tarea.id" aria-labelledby="offcanvasWithBothOptionsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">{{tarea.titulo}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="mb-3">
                            <label>Responsable</label>
                            <select class="form-select" :disabled="!(tarea.responsable_id == current_user_id || administrar || (tarea.creador.id == current_user_id))" @change="setResponsable($event)">
                                <option value=""></option>
                                <option v-for="user in users" :key="user.id" :value="user.id" :selected="tarea.responsable?.id === user.id">
                                    {{ user.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <div class="form-check form-switch">
                                <input 
                                :checked="tarea.renovable" 
                                type="checkbox" 
                                @change="setPeriodica($event)" 
                                class="form-check-input" 
                                role="switch"
                                :id="'customSwitch' + tarea.id"
                                />
                                <label class="form-check-label" :for="'customSwitch' + tarea.id">Tarea periódica</label>
                            </div>
                        </div>                    
                    </div>

                    <hr>

                    <div class="col-12 mb-3">
                        <label>Subtareas</label>
                        <subtarea-create
                        :tarea="tarea"
                        v-on:subtareaCreada="recargarSubtareas"
                        />
                        <subtarea-list 
                        :subtareas="subtareas"
                        :tarea="tarea"
                        v-on:recargarSubtareas="recargarSubtareas"
                        />
                    </div>

                    <hr>

                    <div class="col-12 mb-3">
                        <label>Comentarios</label>
                        <comentario-create
                        :tarea="tarea"
                        v-on:comentarioCreado="recargarComentarios"
                        />
                        <comentario-list 
                        :comentarios="comentarios"
                        :tarea="tarea"
                        v-on:recargarComentarios="recargarComentarios"
                        />
                    </div>                

                    <hr>

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
                                Creada por {{tarea.creador?.name}} {{tarea.created_at_human}}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

// Componentes hijos (Vue los registra automáticamente)
import subtareaCreate from './subtareas/subtareaCreate.vue';
import subtareaList from './subtareas/subtareaList.vue';
import comentarioCreate from './comentarios/comentarioCreate.vue';
import comentarioList from './comentarios/comentarioList.vue';
import documentoCreate from './documentos/documentosForm.vue';
import documentoList from './documentos/documentosList.vue';

// 1. Props y Emits
// Guardamos las props en una constante porque necesitamos acceder a `props.tarea.id` dentro del script
const props = defineProps(['tarea', 'administrar']);

// Corregí un typo de tu código original: 'tareaCumplid' -> 'tareaCumplida'
const emit = defineEmits(['tareaCumplida']);

// 2. Estado (Refs)
const documentos = ref([]);
const subtareas = ref([]);
const comentarios = ref([]);
const totales = ref(0);
const realizadas = ref(0);
const users = ref([]);
const user_selected = ref({ id: null, name: null });
// Renombramos a current_user_id para no hacer conflicto con la variable global 'user_id' de Laravel/JS
const current_user_id = ref(null);

// 3. Métodos
const completarTarea = (e) => {
    // Al usar modificadores en el template (@click.stop.prevent) esto ya no es estrictamente necesario, pero no hace daño
    e.preventDefault();
    e.stopPropagation();
    
    props.tarea.finalizada = true;
    axios.put('api/tarea/' + props.tarea.id, props.tarea)
    .then( response => {
        if(response.status == 201 || response.status == 200){
            emit('tareaCumplida');
            const audio = new Audio('/assets/sounds/done.mp3');
            audio.play();
        }
    })
    .catch( error => console.log(error) );
};

const setPeriodica = (event) => {
    props.tarea.renovable = event.target.checked;
    axios.put('api/tarea/' + props.tarea.id, props.tarea)
    .then( response => {
        if(response.status == 201 || response.status == 200){
            console.log(response.data);
        }
    })
    .catch( error => console.log(error) );
};

const recargarSubtareas = () => {
    axios.get('api/tarea/' + props.tarea.id + '/subtarea')
    .then( response => {
        if(response.status == 200){
            subtareas.value = response.data;
            totales.value = response.data.length;
            
            let temp = 0;
            for (let i in response.data){
                if(response.data[i].finalizada == true){
                    temp++;
                }
            }
            realizadas.value = temp;
        }
    })
    .catch( error => console.log(error) );
};

const setResponsable = (event) => {
    props.tarea.responsable_id = event.target.value ? event.target.value : null;
    axios.put('api/tarea/' + props.tarea.id, props.tarea)
    .then( response => {
        if(response.status == 201 || response.status == 200){
            user_selected.value = response.data.responsable;
            emit('tareaCumplida');
        }
    })
    .catch( error => console.log(error) );
};

const getUsers = () => {
    axios.get('/api/users')
    .then( response => {
        users.value = response.data.data;
    })
    .catch( error => console.log(error) );
};

const recargarComentarios = () => {
    axios.get('api/tarea/' + props.tarea.id + '/comentario')
    .then( response => {
        if(response.status == 200){
            comentarios.value = response.data;
        }
    })
    .catch( error => console.log(error) );
};

const recargarDocumentos = () => {
    axios.get('api/tarea/' + props.tarea.id + '/documento')
    .then( response => {
        if(response.status == 200){
            documentos.value = response.data;
        }
    })
    .catch( error => console.log(error) );
};

// 4. Manejo del Event Listener del body (Mejorado)
const stopPropagationHandler = (e) => {
    e.stopPropagation();
};

// 5. Ciclo de Vida (Lifecycle Hooks)
onMounted(() => {
    recargarSubtareas();
    recargarComentarios();
    getUsers();
    
    // Asignaciones iniciales
    if (props.tarea.responsable) {
        user_selected.value = props.tarea.responsable;
    }
    recargarDocumentos();
    
    // Suponiendo que 'user_id' es una variable global inyectada en tu HTML/Blade
    if (typeof user_id !== 'undefined') {
        current_user_id.value = user_id;
    }

    // Registramos el evento SOLO cuando el componente se monta
    document.body.addEventListener('click', stopPropagationHandler);
});

onUnmounted(() => {
    // Limpiamos el evento cuando el componente se destruye para evitar problemas de memoria
    document.body.removeEventListener('click', stopPropagationHandler);
});
</script>