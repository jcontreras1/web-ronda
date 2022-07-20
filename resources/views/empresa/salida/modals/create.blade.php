<div class="modal" id="mdl_salida_create" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{route('salida.store')}}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Crear Salida</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
						<label>Fecha</label>
							<input autocomplete="off" type="date" class="primerCampo form-control @if($errors->has('fecha')) is-invalid @endif" name="fecha" required value="{{date('Y-m-d')}}">
						</div>
					</div>
					<div class="row">
						<div class="col-12">
						<label>Hora</label>
							<input autocomplete="off" type="time" class="form-control @if($errors->has('hora')) is-invalid @endif" name="hora" required value="10:00">
						</div>
					</div>
					<div class="row">
						<div class="col-12">
						<label>Embarcación</label>
							<select class="form-select @if($errors->has('embarcacion')) is-invalid @endif" name="embarcacion" required>
								@foreach($embarcaciones as $embarcacion)
								<option @if(variable_global('EMBARCACION_POR_DEFECTO') == $embarcacion->id) selected @endif value="{{$embarcacion->id}}">{{ucfirst($embarcacion->nombre)}}</option>
								@endforeach
							</select>
						</div>
					</div>
					@if(variable_global('USAR_COLORES_SALIDA') == 1)
					<div class="row">
						<div class="col-12">
						<label>Color</label>
							<select id="colorselect" class="form-select @if($errors->has('embarcacion')) is-invalid @endif" name="color" required>
								@foreach($colores as $color)
								<option value="{{$color->codigo}}" style="background-color: {{$color->codigo}};">{{$color->nombre}}</option>
								@endforeach
							</select>
						</div>
					</div>
					@endif
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-lg btn-success">Aceptar</button>
					<button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</form>
	</div>
</div>

@push('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		let color = $('#colorselect').val();
		$('#colorselect').css('background-color', color);
		$('#colorselect').change(function(){
			let color = $(this).val();
			$('#colorselect').css('background-color', color);
		});
	});
</script>
@endpush