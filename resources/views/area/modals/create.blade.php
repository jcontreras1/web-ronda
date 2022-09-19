<div class="modal" id="mdl_area_create" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('area.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Área</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>Nombre</label>
                    <input type="text" class="form-control primerCampo" autocomplete="off" name="nombre">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="btn_url_comparar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
