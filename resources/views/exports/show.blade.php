@extends('layouts.app')
@section('content')
@section('titulo', "Ronda #$ronda->id - ")

<div class="container">
	<h3>
		<i class="fas fa-users"></i> Ronda #{{$ronda->id}} @if($ronda->circuito) | <small class="text-muted">{{ $ronda->circuito->titulo }} </small> @endif
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('export.index')])
		</span>
	</h3>
	<hr>
	<div class="card indigo mb-3 ">
		<div class="card-body">
			<p class="">
				Desde esta pantalla, se pueden copiar las novedades para pegarlas en el módulo de Libro de Novedades de Sistegral. Para copiar el contenido del rondín #{{ $ronda->id }}, utilice el botón verde de <span class="text-success">Copiar Contenido</span> 
			</p>
		</div>
	</div>
	<p class="form-control mb-3 py-4" id="myInput">
		Rondín {{ $ronda->circuito->titulo }} comenzado por {{ $ronda->creador->nombre }} a las {{ date('H:i', strtotime($ronda->created_at)) }} del día {{ date('d/m/Y', strtotime($ronda->created_at)) }} <br>
		@foreach($ronda->checkpoints as $checkpoint)
		[{{ date('d/m/Y H:i', strtotime($checkpoint->created_at)) }}] [Lat: {{ substr($checkpoint->latitud, 0, 7) }}, Lng: {{ substr($checkpoint->longitud, 0, 7) }}] -
		{{ $checkpoint->novedad ?? 'Sin novedades' }}
		<br>
		@endforeach
	</p>
	<button class="btn btn-success" onclick="copiar()">Copiar Contenido</button>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	function copiar() {
		var copyText = document.getElementById("myInput");
		copyText = copyText.innerText;
		var input = document.createElement('textarea');
		input.value = copyText;

		input.select();
		input.setSelectionRange(0, 99999);
		navigator.clipboard.writeText(input.value);
		Swal.fire({
			showConfirmButton: false,
			title : 'Contenido copiado',
			icon: 'success',
			toast: true,
			position: 'top-right',
			timer: 1500,
		});
	}
</script>
@endsection