@extends('layouts.app')
@section('content')
@include('circuito.modals.create')
@section('titulo', "Circuitos - ")
<div class="container">
	<x-misc-title title="Circuitos">
		@if(count($areas_mias) > 0)			
		<button data-toggle="tooltip" id="btn_circuito_create" title="Agregar Circuito" class="btn btn-success"><i class="bi bi-plus"></i></button>
		@endif
	</x-misc-title>

	<div class="table-responsive">		
		<table class="table table-hover" id="tabla">
			<thead>
				<tr>
					<th>Nombre</th>
					{{-- <th>Descripción</th> --}}
					<th>Creado Por</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($circuitos as $circuito)
				<tr>
					<td>{{$circuito->titulo}}</td>
					{{-- <td>{{$circuito->descripcion}}</td> --}}
					<td>{{$circuito->creador->nombre}} - {{$circuito->created_at->diffForHumans()}}</td>
					<td>
						<a href="{{route('circuito.show', $circuito)}}" class="btn btn-primary"><i class="bi bi-list-task"></i></a>
						@can('administrar')
						<button class="btn btn-danger" onclick="delete_circuito('{{route('circuito.destroy', $circuito)}}');"><i class="bi bi-trash"></i></button>
						@endcan
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
<form id="form_delete_circuito" method="POST">
	@csrf @method('DELETE')
</form>
@endsection
@push('scripts')
<script type="text/javascript">

	var btn_circuito_create = document.getElementById('btn_circuito_create');
	btn_circuito_create.addEventListener('click', crear_circuito);

	function crear_circuito(){
		let opciones = document.getElementById('select_area').length;
		if(opciones == 1){
			document.getElementById('form_circuito_store').submit();
		}else{
			mdl = new bootstrap.Modal(document.getElementById('mdl_circuito_create'));
			mdl.show();
		}
	}


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
