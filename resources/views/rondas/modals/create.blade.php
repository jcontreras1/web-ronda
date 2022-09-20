<div class="modal" id="mdl_ronda_create" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="post" id="form_ronda_create" action="{{ route('ronda.store') }}">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">Crear Rondín</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<select id="select_circuito" name="circuito_id" class="form-select">
						@foreach($circuitos_posibles as $circuito)
						<option value="{{$circuito->id}}">#{{$circuito->id}} - {{$circuito->titulo}}</option>
						@endforeach
					</select>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success">Aceptar</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</div>
			</form>
		</div>
	</div>
</div>
