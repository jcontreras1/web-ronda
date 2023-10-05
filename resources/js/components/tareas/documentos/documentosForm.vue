<template>
    <form @submit.prevent="upload" enctype="multipart/form-data">
        <label for="formFile">Subir documentos</label>
        <input class="form-control mb-4" type="file" id="formFile"  style="border: 2px dashed #495057; height: 65px;" @change="change">       <i v-if="loading" class="bi bi-arrow-repeat text-primary"></i>
    </form>
</template>
<script>
    export default {
        data: function(){
            return {
                documento : null,
                loading : false
            }
        },
        props : [
            'tarea'
            ],
        methods : {
            change(e){
                if(!e.target.files.length){
                    return;
                }
                this.loading = true;
                this.documento = e.target.files[0];
                let fd = new FormData();
                fd.append('doc', this.documento);
                axios.post('api/tarea/' + this.tarea.id + '/documento', fd)
                .then(response => {
                    this.$emit('recargarDocumentos');
                    this.documento = null;
                    e.target.value = null;
                })
                .catch(error => {
                    console.log(error);
                });
                this.loading = false;  
            }
        }
    }
</script>