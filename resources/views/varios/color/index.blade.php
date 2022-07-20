@extends('layouts.app')
@section('content')
@include('varios.color.modals.create')
@section('titulo', 'Colores - ')
<div class="container">
	<h3>
		Colores
		<span class="float-end">
			<button class="btn btn-success" id="btn_agregar_color" data-toggle="tooltip" title="Agregar Color"><i class="bi bi-plus"></i></button>
			@include('components.misc.backbutton', ['url' => route('config.index')])
		</span>
	</h3>
	<hr>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Color</th>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($colores as $color)
				<tr>
					<td><h3><i class="bi bi-circle-fill" style="color: {{$color->codigo}};"></i></h3></td>
					<td>{{$color->codigo}}</td>
					<td>{{$color->nombre}}</td>
					<td>
						<button class="btn btn-danger btn-sm btn_eliminar_color" data-url="{{route('color.destroy', $color)}}"><i class="bi bi-trash"></i></button>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
<form id="form_eliminar_color" method="POST">@csrf @method('DELETE')</form>
@endsection

@push('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('#btn_agregar_color').click(function(){
			console.log('etc');
			$('#mdl_color_create').modal('show');
		});
		$('.btn_eliminar_color').click(function(){
			let url = $(this).data('url');
			Swal.fire({
				icon: 'question',
				title: 'Eliminar Color?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
			}).then((result) => {
				if (result.isConfirmed) {
					console.log(url);
					$('#form_eliminar_color').attr('action', url);
					$('#form_eliminar_color').submit();
				}
			});
		});
	});
</script>
@endpush