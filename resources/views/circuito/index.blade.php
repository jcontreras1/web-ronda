@extends('layouts.app')
@section('content')

@section('titulo', "Circuitos")
<div class="container">
	<h3>
		Circuitos
		<span class="float-end">
			<form action="{{route('circuito.store')}}" method="POST">
				@csrf				
				<button data-toggle="tooltip" title="Agregar Circuito" class="btn btn-success"><i class="bi bi-plus"></i></button>
				@include('components.misc.backbutton', ['url' => url('home')])
			</form>
		</span>
	</h3>
	<hr>
	<table class="table table-striped" id="tabla">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Descripción</th>
				<th>Creado Por</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($circuitos as $circuito)
			<tr>
				<td>{{$circuito->titulo}}</td>
				<td>{{$circuito->descripcion}}</td>
				<td>{{$circuito->creador->nombre}} - {{$circuito->created_at->diffForHumans()}}</td>
				<td>
					<a href="{{route('circuito.show', $circuito)}}" class="btn btn-primary"><i class="bi bi-list-task"></i></a>
					@if($circuito->creador->id == Auth::user()->id)
					<button class="btn btn-danger" onclick="delete_circuito('{{route('circuito.destroy', $circuito)}}');"><i class="bi bi-trash"></i></button>
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<form id="form_delete_circuito" method="POST">
	@csrf @method('DELETE')
</form>
@endsection
@push('scripts')
<script type="text/javascript">
	function delete_circuito(url){
		Swal.fire({
			icon: 'question',
			title: '¿Eliminar Circuito?',
			showCancelButton: true,
			confirmButtonText: 'Si',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.isConfirmed) {
				$('#form_delete_circuito').attr('action', url);
				$('#form_delete_circuito').submit();
			}
		})

	}
	$(document).ready(function(){
		$('#tabla').DataTable({
			language : {
				url : '{{asset('assets/dt.spanish.json')}}'
			}
		});
	});
</script>
@endpush
