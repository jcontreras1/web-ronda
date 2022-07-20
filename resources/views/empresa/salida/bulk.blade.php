@extends('layouts.app')
@section('content')
@section('titulo', 'Crear Salidas - ')
<div class="container">
	<h3>
		Crear varias salidas
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('salida.index')])
		</span>
	</h3>
	<hr>
	<form method="post" action="{{route('salidas.bulk')}}">
		@csrf
		<div class="row">
			<div class="col-12 col-md-6">
				<label>Salidas desde @include('components.misc.required')</label>
				<input type="date" min="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" name="desde" class="form-control">
			</div>
			<div class="col-12 col-md-6">
				<label>Salidas Hasta @include('components.misc.required')</label>
				<input type="date" min="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" name="hasta" class="form-control">
			</div>
		</div>
		<div class="py-2"></div>
		<button type="button"  id="btn_agregar_horario" class="btn btn-success">Agregar <i class="bi bi-chevron-down"></i></button>
		<div class="py-2"></div>
		<div id="panel_horarios"></div>
		<hr>
		<div class="float-end">
			<button class="btn btn-warning" id="btn_submit" disabled>Crear</button>
		</div>
	</form>
</div>
<form id="form_salida_cerrar" method="POST">@csrf @method('PATCH') <input type="hidden" name="cerrada" value="1"></form>
<form id="form_salida_abrir" method="POST">@csrf @method('PATCH') <input type="hidden" name="cerrada" value="0"></form>

@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		var horarios = 0;
		var colores = "";
		{{-- Sección de colores --}} 
		@if(variable_global('USAR_COLORES_SALIDA') == 1)
		var colores = `
		@foreach($colores as $color)
		<option value="{{$color->codigo}}" style="background-color: {{$color->codigo}};">{{ucfirst($color->nombre)}}</option>
		@endforeach
		`;
		@endif
		var embarcaciones = `
		@foreach($embarcaciones as $embarcacion)
		<option @if($embarcacion->id == variable_global('EMBARCACION_POR_DEFECTO')) selected="" @endif value="{{$embarcacion->id}}">{{ucfirst($embarcacion->nombre)}}</option>
		@endforeach
		`;
		
		{{-- Agregar un medio de pago --}}
		$(document).on('click', '.btn_eliminar_fila', function(){
			let id = $(this).data('id');
			$('#fila_horario_' + id).remove();
			var numItems = $('.fila_nueva').length;
			if(numItems == 0){
				$('#btn_submit').attr('disabled', true);
			}
		});
		$('#btn_agregar_horario').click(function(){
			$('#btn_submit').attr('disabled', false);
			horarios++;
			var codigo = code_horario(horarios, colores, embarcaciones);
			$('#panel_horarios').append(codigo);
		});


		let color = $('#colorselect').val();
		$('#colorselect').css('background-color', color);
		$('#colorselect').change(function(){
			let color = $(this).val();
			$('#colorselect').css('background-color', color);
		});


		function code_horario(id, colores, embarcaciones){
			var code = `
			<div class="row fila_nueva" id="fila_horario_${id}">
			<div class="col-12 col-md-4">
			<span class="badge bg-success">Horario</span>
			<input class="form-control" name="horario[]" type="time" value="10:00">
			</div>
			<div class="col-12 col-md-3">
			<span class="badge bg-success">Embarcación</span>
			<select class="form-select" name="embarcacion_id[]">
			${embarcaciones}
			</select>
			</div>
			<div class="col-12 col-md-4">
			<span class="badge bg-success">Color</span>
			<select class="form-select" name="color[]">
			${colores}
			</select>
			</div>
			<div class="col-12 col-md-1 d-flex align-items-end">

			<button type="button" class="btn btn-danger btn_eliminar_fila" data-id="${id}" data-toggle="tooltip" title="Eliminar esta fila"><i class="bi bi-x-lg"></i></button>
			</div>
			</div>
			<div class="py-1"></div>	
			`;

			return code;
		}
	});
</script>
@endsection