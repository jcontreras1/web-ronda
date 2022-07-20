<div class="modal" id="mdl_voucher_rendir" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" id="form_voucher_rendir">
			@csrf @method('PATCH')
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Rendir Voucher</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<input type="hidden" name="rendido" value="1">
						<div class="col-12">
							<label>Número de Voucher</label>
							<input type="text" id="form_voucher_rendir_numero" class="form-control" readonly>
						</div>
						<div class="col-12 col-md-6">
							<label>Total en venta</label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" id="form_voucher_rendir_total" class="form-control" readonly>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<label>Abonado por Pasajero</label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" id="form_voucher_rendir_recibido" class="form-control" readonly>
							</div>
						</div>
						<div class="col-12">
							<label>Recibido por Agencia</label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" name="total_recibido" class="form-control primerCampo">
							</div>
						</div>
						<div class="col-12">
							<label>Comisión Agencia</label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" name="comision" id="form_voucher_rendir_comision" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" id="btn_rendir_sin_especificar">Rendir, sin especificar</button>
					<button type="submit" class="btn btn-success">Aceptar</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</form>
	</div>
</div>