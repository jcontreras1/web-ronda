@extends('layouts.app')
@section('content')
@section('titulo', 'Rondas - ')
<div class="container">
	<h3 class="">Rondas abiertas
		<span class="float-end">
			<button class="btn btn-success text-white" id="btn_ronda_create" data-toggle="tooltip" title="Agregar ronda"><i class="bi bi-plus"></i></button>
			@include('components.misc.backbutton', ['url' => url('home')])
		</span>
	</h3>
	<hr>
	
	{{-- Rondas históricas --}}
	<h4>Histórico</h4>
	<div class="table-responsive">
		<table class="table table-striped" id="tabla">
			<thead>
				<tr>
					<th>Recorre</th>
					<th>Fecha</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($cerradas as $ronda)
				<tr onclick="ver_circuito('{{ route('export.show', $ronda) }}')" style="cursor: pointer;">
					<td data-order="{{ $ronda->id }}">{{ucwords($ronda->creador->nombre)}}
						<span class="lead text-primary d-block d-md-inline float-md-end ">
							@if(count($ronda->novedades) > 0)
							<i data-toggle="tooltip" title="Tiene novedades" class="bi bi-card-text"></i>
							@endif
							@if(count($ronda->images) > 0)
							<i data-toggle="tooltip" title="Hay imágenes" class="bi bi-card-image"></i>
							@endif
							@if(count($ronda->checkpoints) < 10 && count($ronda->checkpoints) > 0)
							<i data-toggle="tooltip" title="Puntos visitados" class="bi bi-{{ count($ronda->checkpoints) }}-circle"></i>
							@endif
						</span>
					</td>
					<td data-order="{{ $ronda->id }}">{{date('d/m/Y H:i', strtotime($ronda->created_at))}}</td>
					<td>
						<a href="{{route('export.show', $ronda)}}" data-toggle="tooltip" title="Ver" class="btn btn-primary mb-1"><i class="bi bi-list-task"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	{{-- @can('administrar_sistema') --}}
	<form id="form_delete_ronda" method="POST"> @csrf @method('DELETE') </form>
	<form id="form_cerrar_ronda" method="POST"> @csrf @method('PATCH') </form>
	{{-- @endcan --}}
	@endsection

	@section('scripts')
	<script type="text/javascript">

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
		});
	</script>
	@endsection