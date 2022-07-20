<div class="modal" id="mdl_agencia_create" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{route('agencia.store')}}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Crear Agencia</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<label>Razón Social @include('components.misc.required')</label>
							<input autocomplete="off" type="text" class="primerCampo form-control @if($errors->has('razon_social')) is-invalid @endif" name="razon_social" required>
						</div>
						<div class="col-12">
							<label>Cuit</label>
							<input autocomplete="off" type="text" class="form-control @if($errors->has('cuit')) is-invalid @endif" name="cuit">
						</div>
						<div class="col-12">
							<label>Email</label>
							<input autocomplete="off" type="email" class="form-control @if($errors->has('email')) is-invalid @endif" name="email">
						</div>
						<div class="col-12">
							<label>Teléfono</label>
							<input autocomplete="off" type="text" class="form-control @if($errors->has('telefono')) is-invalid @endif" name="telefono">
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