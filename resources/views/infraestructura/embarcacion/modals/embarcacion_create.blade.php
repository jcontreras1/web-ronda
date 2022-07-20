<div class="modal" id="mdl_embarcacion_create" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{route('embarcacion.store')}}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Crear Embarcación</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<label>Nombre @include('components.misc.required')</label>
							<input autocomplete="off" type="text" class="primerCampo form-control @if($errors->has('nombre')) is-invalid @endif" name="nombre" required>
						</div>
						<div class="col-12">
							<label>Capacidad @include('components.misc.required')</label>
							<input autocomplete="off" type="number" value="30" class="form-control @if($errors->has('capacidad')) is-invalid @endif" name="capacidad" required>
						</div>
						<div class="col-12">
							<label>Eslora</label>
							<input autocomplete="off" type="number" step="0.01" class="form-control @if($errors->has('eslora')) is-invalid @endif" name="eslora">
						</div>
						<div class="col-12">
							<label>Manga</label>
							<input autocomplete="off" type="number" step="0.01" class="form-control @if($errors->has('manga')) is-invalid @endif" name="manga">
						</div>
						<div class="col-12">
							<label>Puntal</label>
							<input autocomplete="off" type="number" step="0.01" class="form-control @if($errors->has('puntal')) is-invalid @endif" name="puntal">
						</div>
						<div class="col-12">
							<label>Matrícula</label>
							<input autocomplete="off" type="text" class="form-control @if($errors->has('matricula')) is-invalid @endif" name="matricula">
						</div>
						<div class="col-12">
							<label>Horas</label>
							<input autocomplete="off" type="number" class="form-control @if($errors->has('horas')) is-invalid @endif" name="horas">
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