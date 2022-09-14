<div class="modal fade" id="mdl_titulo" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modificar título del Circuito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('circuito.update', $circuito) }}">
        @csrf @method('PATCH')
        <div class="modal-body">
          <label>Título</label>
          <input type="text" class="form-control primerCampo" autocomplete="off" name="titulo" value="{{ $circuito->titulo }}">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>