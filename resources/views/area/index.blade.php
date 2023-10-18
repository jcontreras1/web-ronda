@extends('layouts.app')
@section('content')
@include('area.modals.create')
@include('area.modals.modif')
	@section('titulo', 'Areas - ')
	<div class="container">
	<x-misc-title title="Áreas">
		<button data-toggle="model" onclick="modal_create()" class="btn btn-success"><i class="bi bi-plus"></i></button>
	</x-misc-title>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Area</th>
					@can('administrar')
					<th>Opciones</th>
					@endcan
				</tr>
			</thead>
			<tbody>
				@foreach($areas as $area)
				<tr>
					<td>{{ strtoupper($area->nombre) }}</td>
					@can('administrar')
					<td>
						<button onclick="editar('{{ route('area.update', ['area' => $area]) }}', '{{ $area->nombre }}')" class="btn btn-warning" data-toggle="tooltip" title="Editar"><i class="bi bi-pencil-fill"></i></button>
						<button onclick="eliminar('{{ route('area.destroy', ['area' => $area]) }}')" class="btn btn-danger" data-toggle="tooltip" title="Eliminar"><i class="bi bi-trash"></i></button>
					</td>
					@endcan
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	</div>

	@can('administrar')
	<form method="POST" id="eliminar_area">@csrf @method('delete')</form>
	@endcan
@endsection

@section('scripts')
<script type="text/javascript">
	function modal_create() {
		var modal_create = new bootstrap.Modal(document.getElementById('mdl_area_create'));
		modal_create.show();
	}

	function editar(url, nombre){
		var input = document.getElementById('nombre_area_modif');
		var form = document.getElementById('form_modif_area');
		var modal = new bootstrap.Modal(document.getElementById('mdl_area_modif'));

		input.value = nombre;
		form.action = url;
		modal.show();
	}

	function eliminar(url){
		var form = document.getElementById('eliminar_area');
		Swal.fire({
			icon: 'question',
			title: '¿Eliminar Área?',
			showCancelButton: true,
			confirmButtonText: 'Si',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.isConfirmed) {
				form.action = url;
				form.submit();
			}
		})
	}
</script>
@endsection