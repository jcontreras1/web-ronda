<div class="modal" id="mdl_ingreso_edit" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" id="form_ingreso_edit">
			@method('PATCH')
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Modificar Ingreso</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<label>Hora @include('components.misc.required')</label>
							<input type="time" class="form-control" name="hora" required id="form_ingreso_edit_hora">
						</div>
						<div class="col-12">
							<label>Monto @include('components.misc.required')</label>
							<input type="number" step="0.01" class="primerCampo form-control" name="monto" required id="form_ingreso_edit_monto">
						</div>						
						<div class="col-12">
							<label>Observaciones @include('components.misc.required')</label>
							<input type="text" placeholder="Ej: Venta de souvenir" autocomplete="off" class="form-control" name="observaciones" required id="form_ingreso_edit_observaciones">
						</div>
						<div class="col-12">
							<label>Medio de Pago @include('components.misc.required')</label>
							<select class="form-select" name="medio_pago_id" required id="form_ingreso_edit_medio_pago">
								@foreach($medios_de_pago as $medio_de_pago)
								<option @if(strtolower($medio_de_pago->nombre) == 'efectivo') selected @endif value="{{$medio_de_pago->id}}">{{ucfirst($medio_de_pago->nombre)}}</option>
								@endforeach
							</select>							
						</div>						
						<div class="col-12">
							<label>Moneda @include('components.misc.required')</label>
							<select class="form-select" name="moneda_id" required id="form_ingreso_edit_moneda">
								@foreach($monedas as $moneda)
								<option value="{{$moneda->id}}">{{ucfirst($moneda->simbolo)}}</option>
								@endforeach
							</select>							
						</div>
					</div>
					<div class="row" id="post_error" style="display: none;">
					<div class="col-12">
						<div class="alert alert-danger" role="alert">
							<span id="post_message"></span>
						</div>
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