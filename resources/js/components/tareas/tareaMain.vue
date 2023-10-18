<template>
    <div class="container">
        <div class="row">
<!--          <div class="col-12 col-md-3">
            <container-filtros
            :filtros="filtros"
            :administrar="administrar"
            v-on:aplicarFiltros="aplicarFiltros"
            />
        </div>   -->          
        <div class="col-12">
            <h3>Lista de tareas</h3>
            <tarea-create
            v-on:tareaCreada="getTareas()"
            />
            <hr>
            <lista 
            :tareas="tareas" 
            :administrar="administrar"
            v-on:tareaCumplida="getTareas(true)"
            class="mb-2"
            />

            <lista-finalizadas 
            :tareas="finalizadas"
            v-on:tareaReactivada="getTareas(true)"
            v-on:tareaEliminada="getTareas(true)"
            />
        </div>
    </div>
</div>
</template>
<script>

    import tareaCreate from "./tareaCreate.vue";
    import lista from './lista.vue';
    import listaFinalizadas from './listaFinalizadas.vue';
    import containerFiltros from './filtros/containerFiltros.vue';

    export default ({
        components: {
            tareaCreate,
            listaFinalizadas,
            lista,
            containerFiltros,
        },
        data : function(){
            return {
                tareas : [],
                finalizadas : [],
                administrar : false,
                filtros : [],
            // users : [],
            }
        },
        methods : {
            aplicarFiltros(personas){
                this.getTareas (false, personas);
            },
            cmpPersonasOcurrencias(a,b){
                if(a.ocurrencias <= b.ocurrencias){
                    return 1;
                }else{
                    return -1;
                }
            },
            getTareas (finalizadas = false, responsables = null) {
                axios.get('/api/tarea', {params: {responsables : responsables}})
                .then( response => {
                    this.tareas = response.data;
                    let personas = [];
                    for(let i = 0; i < response.data.length; i++){
                    //Tiene responsable
                        if(response.data[i].responsable_id){
                            let index = personas.findIndex(e => e.id === response.data[i].responsable_id);
                            if(index !== -1){
                                personas[index].ocurrencias++;
                            }else{
                                personas[personas.length] = {
                                    'id' : response.data[i].responsable.id,
                                    'name' : response.data[i].responsable.name,
                                    'ocurrencias' : 1
                                };
                            }
                        }
                    }
                    this.filtros = personas.sort(this.cmpPersonasOcurrencias);
                })
                .catch(error => {
                    console.log(error);
                });
                if(finalizadas){
                    axios.get('/api/tareas/finalizadas')
                    .then( response => {
                        this.finalizadas = response.data
                    })
                    .catch(error => {
                        console.log(error);
                    }); 
                }
            }
        },
        created(){
            this.getTareas(true);
        //FIXME: ver que onda
        // this.administrar = can_administrar_modulo_tareas;
            this.administrar = true;
        }

    })
</script>
