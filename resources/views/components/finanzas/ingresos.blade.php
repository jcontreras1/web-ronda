<div class="py-2"></div>
<div class="h4">Ingresos extra</div>
<div class="table-responsive">    
	<table class="table table-striped table-sm">
		<thead>
			<tr>
				<th>Hora</th>
				<th>Monto</th>
				<th>Observaciones</th>
				<th>Medio de pago</th>
				@canany(['administrar', 'ventas'])
				<th></th>
				@endcan
			</tr>
		</thead>
		<tbody>
			@foreach($ingresos as $ingreso)
			<tr>
				<td>{{date('H:i', strtotime($ingreso->created_at))}}</td>
				<td>{{$ingreso->moneda->simbolo}}{{pesosargentinos($ingreso->monto)}}</td>
				<td>{{ucfirst($ingreso->observaciones)}}</td>
				<td>{{($ingreso->medio_pago->nombre)}}</td>
				@canany(['administrar', 'ventas'])
				<td>
					<button class="btn btn-sm btn-warning btn_ingreso_edit" data-id="{{$ingreso->id}}" data-toggle="tooltip" title="Editar"><i class="bi bi-pencil-fill"></i></button>
					@can('administrar')
					<button class="btn btn-sm btn-danger btn_ingreso_destroy" data-url="{{route('ingreso.destroy', $ingreso)}}" data-toggle="tooltip" title="Eliminar"><i class="bi bi-trash"></i></button>
				@endcan
				</td>
				@endcan
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@canany(['administrar', 'ventas'])
<form method="POST" id="form_ingreso_destroy">@csrf @method('DELETE')</form>
@push('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('.btn_ingreso_destroy').click(function(){
			let url = $(this).data('url');
			Swal.fire({
				icon: 'question',
				title: '¿Eliminar Ingreso?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
			}).then((result) => {
				if (result.isConfirmed) {
					$('#form_ingreso_destroy').attr('action', url);
					$('#form_ingreso_destroy').submit();
				}
			});
		});

	 $('.btn_ingreso_edit').click(function(){
		$('#mdl_ingreso_edit').modal('show');
		let id = $(this).data('id');
		let route = "{{route('api.ingreso.show', '__ingreso')}}".replace('__ingreso', id);
		axios.get(route)
		.then(response => {
			let ingreso = response.data;
			console.log(ingreso);
			$('#form_ingreso_edit_hora').val(ingreso.hora);
			$('#form_ingreso_edit_monto').val(ingreso.monto);
			$('#form_ingreso_edit_observaciones').val(ingreso.observaciones);
			$('#form_ingreso_edit_medio_pago').val(ingreso.medio_pago).change();
			$('#form_ingreso_edit_moneda').val(ingreso.moneda);
			let action = "{{route('ingreso.update', '__ingreso')}}".replace('__ingreso', id);
			$('#form_ingreso_edit').attr('action', action);
		})
		.catch(error => {
			$('#post_error').show();
			$('#post_message').html(error.response.data.message);
			console.error(error.response.data.message);      
		});
	});
 });
</script>
@endpush
@endcan