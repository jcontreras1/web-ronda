<div class="modal" id="mdl_area_modif" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" id="form_modif_area">
            @csrf @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Área</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>Nombre</label>
                    <input type="text" id="nombre_area_modif" class="form-control primerCampo" autocomplete="off" name="nombre">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="btn_url_comparar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
