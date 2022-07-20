<div class="modal" id="mdl_personal_edit" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{route('rol.update', $rol)}}">
			@csrf
			@method('PATCH')
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Editar <span class="tipo_personal"></span></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<label><span class="tipo_personal"></span></label>
							<div id="select_personal"></div>
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