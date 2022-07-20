<div class="modal" id="get_location" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <form id="form_edit_pass" method="POST" >
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn_edit_pass" id="btn_edit_pass">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </form>
</div>
</div>
