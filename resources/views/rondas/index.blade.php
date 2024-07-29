@extends('layouts.app')
@section('content')
@include('rondas.modals.create')
@section('titulo', 'Rondas - ')
<div class="container">

	<x-misc-title title="Rondas Abiertas">
		<button class="btn btn-success" id="btn_ronda_create" data-toggle="tooltip" title="Agregar ronda"><i class="bi bi-plus"></i></button>
	</x-misc-title>

	{{-- Rondas abiertas --}}
	<div class="row mb-3">
		@foreach($abiertas as $ronda)		
		@include('components.ronda.card-ronda-abierta', ['ronda' => $ronda])
		@endforeach
	</div>

	{{-- Rondas históricas --}}
	<h4 class="mb-3">Histórico</h4>
	<div class="table-responsive">
		<table class="table table-hover" id="tabla">
			<thead>
				<tr>
					<th>Recorre</th>
					<th>Fecha</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($cerradas as $ronda)
				<tr onclick="ver_circuito('{{ route('ronda.show', $ronda) }}')" style="cursor: pointer;">
					<td>{{ucwords($ronda->creador->nombre)}}
						<span class="lead text-primary-emphasis d-block d-md-inline float-md-end">
							@if(count($ronda->novedades) > 0)
							{{-- <i data-toggle="tooltip" title="Tiene novedades" class="bi bi-card-text"></i> --}}
							<i class="bi bi-list border px-1 rounded" data-toggle="tooltip" title="Tiene novedades"></i>
							@endif
							@if(count($ronda->images) > 0)
							{{-- <i  class="bi bi-card-image"></i> --}}
							<i class="bi bi-image-alt border px-1 rounded" data-toggle="tooltip" title="Hay imágenes"></i>
							@endif
							@if(count($ronda->checkpoints) > 0)
							@if(count($ronda->checkpoints) >= 10 )
							<span class="text-primary-emphasis border px-1 rounded" data-toggle="tooltip" title="Puntos visitados">
								<small>9+</small>
							</span>
							@else
							<span class="text-primary-emphasis border px-2 rounded" data-toggle="tooltip" title="Puntos visitados">
								{{ count($ronda->checkpoints) }}
							</span>
							@endif
							@endif
						</span>
					</td>
					<td data-order="{{ $ronda->created_at }}">{{date('d/m/Y H:i', strtotime($ronda->created_at))}}</td>
					<td>
						<a href="{{route('ronda.show', $ronda)}}" data-toggle="tooltip" title="Ver" class="btn btn-primary mb-1"><i class="bi bi-list-task"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
{{-- @can('administrar_sistema') --}}
<form id="form_delete_ronda" method="POST"> @csrf @method('DELETE') </form>
<form id="form_cerrar_ronda" method="POST"> @csrf @method('PATCH') </form>
{{-- @endcan --}}
@endsection

@section('scripts')
<script type="text/javascript">
	var btn_ronda_create = document.getElementById('btn_ronda_create');
	btn_ronda_create.addEventListener('click', crear_rondin);

	function crear_rondin(){
		let opciones = document.getElementById('select_circuito').length;
		if(opciones > 1){
			var modal = new bootstrap.Modal(document.getElementById('mdl_ronda_create'));
			modal.show();
		}else{
			//Bloquear el boton de crear ronda
			btn_ronda_create.disabled = true;
			btn_ronda_create.innerHTML = '<i class="bi bi-spinner fa-spin"></i> Creando ronda...';
			var form = document.getElementById('form_ronda_create');
			form.submit();
		}
	}

	function ver_circuito(url){
		location.href = url;
	}

	$(document).ready(function(){
		$('#tabla').DataTable({
			language : {
				url : '{{asset('assets/dt.spanish.json')}}'
			},
			order: [[1, 'desc']],
		});
		$('.btn_delete_ronda').click(function(){
			let url = $(this).data('url');
			Swal.fire({
				icon: 'question',
				title: '¿Eliminar Ronda?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'Cancelar',
			}).then((result) => {
				if (result.isConfirmed) {
					$('#form_delete_ronda').attr('action', url);
					$('#form_delete_ronda').submit();
				}
			})
		});
		$('.btn_cerrar_ronda').click(function(){
			let url = $(this).data('url');
			Swal.fire({
				icon: 'question',
				title: '¿Cerrar Ronda?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'Cancelar',
			}).then((result) => {
				if (result.isConfirmed) {
					$('#form_cerrar_ronda').attr('action', url);
					$('#form_cerrar_ronda').submit();
				}
			})
		});
	});
</script>
@endsection