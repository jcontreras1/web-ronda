<div class="modal" id="EditarPassUsuario" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form id="form_edit_pass" method="POST" action="{{ route('user.profile.password.update') }}">
			@csrf
			@method('PATCH')
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Cambiar Contraseña</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
						<label>{{ __('Password') }}</label>
							<input id="password" type="password" class="primerCampo form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
						<label>{{ __('Confirm Password') }}</label>
							<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn_edit_pass" id="btn_edit_pass">Aceptar</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</form>
	</div>
</div>