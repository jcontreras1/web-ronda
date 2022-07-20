<div class="modal" id="mdl_salida_edit" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{route('salida.update', $salida)}}">
			@csrf
			@method('PATCH')
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Editar Salida</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
						<label>Fecha</label>
							<input autocomplete="off" type="date" class="primerCampo form-control @if($errors->has('fecha')) is-invalid @endif" name="fecha" required value="{{$salida->fecha}}">
						</div>
					</div>
					<div class="row">
						<div class="col-12">
						<label>Hora</label>
							<input autocomplete="off" type="time" class="form-control @if($errors->has('hora')) is-invalid @endif" name="hora" required value="{{$salida->hora}}">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-lg btn-success">Aceptar</button>
					<button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</form>
	</div>
</div>