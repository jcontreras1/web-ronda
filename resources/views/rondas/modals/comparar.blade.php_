<div class="modal" id="mdl_ronda_comparar" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        @csrf
        @method('PATCH')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comparar Rondín</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ronda_id">
                <select id="select_circuito" onchange="build_url(this.value)" class="form-select form-select-lg">
                    @foreach($circuitos as $circuito)
                    <option value="{{$circuito->id}}">#{{$circuito->id}} - {{$circuito->titulo}}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <a class="btn btn-success" id="btn_url_comparar">Aceptar</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
