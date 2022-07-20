<div class="modal" id="mdl_color_create" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{route('color.store')}}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Crear color de salida</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
						<label>Color @include('components.misc.required')</label>
							<input type="color" class="form-control form-control-color" name="codigo" required style="width: 100% !important">
						</div>
					</div>
					<div class="row">
						<div class="col-12">
						<label>Nombre</label>
							<input type="text" placeholder="Nombre Rápido" class="form-control" name="nombre" autocomplete="off">
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