<div class="modal" id="mdl_circuito_create" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="form_circuito_store" method="POST" action="{{ route('circuito.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Circuito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>Área del circuito</label>
                    <select class="form-select primerCampo" id="select_area" name="area_id">
                        @foreach($areas_mias as $area)
                        <option value="{{ $area->id }}">{{ strtoupper($area->nombre) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="btn_url_comparar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
