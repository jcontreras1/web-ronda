<div class="py-2"></div>
<div class="h4">Retiros del día</div>
<div class="table-responsive">    
	<table class="table table-striped table-sm">
		<thead>
			<tr>
				<th>Hora</th>
				<th>Monto</th>
				<th>Observaciones</th>
				<th>Medio de pago</th>
				@canany(['administrar', 'ventas'])
				<th>Opciones</th>
				@endcan
			</tr>
		</thead>
		<tbody>
			@foreach($retiros as $retiro)
			<tr>
				<td>{{date('H:i', strtotime($retiro->created_at))}}</td>
				<td>{{$retiro->moneda->simbolo}}{{pesosargentinos($retiro->monto)}}</td>
				<td>{{ucfirst($retiro->observaciones)}}</td>
				<td>{{($retiro->medio_pago->nombre)}}</td>
				@can('ventas')
				<td>
					<button class="btn btn-sm btn-warning btn_retiro_edit" data-id="{{$retiro->id}}" data-toggle="tooltip" title="Editar"><i class="bi bi-pencil-fill"></i></button>
					@can('administrar')
					<button class="btn btn-sm btn-danger btn_retiro_destroy" data-url="{{route('retiro.destroy', $retiro)}}" data-toggle="tooltip" title="Eliminar"><i class="bi bi-trash"></i></button>
					@endcan
				</td>
				@endcan
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@canany(['administrar', 'ventas'])
<form method="POST" id="form_retiro_destroy">@csrf @method('DELETE')</form>
@push('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('.btn_retiro_destroy').click(function(){
			let url = $(this).data('url');
			Swal.fire({
				icon: 'question',
				title: '¿Eliminar Retiro?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
			}).then((result) => {
				if (result.isConfirmed) {
					$('#form_retiro_destroy').attr('action', url);
					$('#form_retiro_destroy').submit();
				}
			});
		});
		
		$('.btn_retiro_edit').click(function(){
			$('#mdl_retiro_edit').modal('show');
			let id = $(this).data('id');
			let route = "{{route('api.retiro.show', '__retiro')}}".replace('__retiro', id);
			axios.get(route)
			.then(response => {
				let retiro = response.data;
				console.log(retiro);
				$('#form_retiro_edit_hora').val(retiro.hora);
				$('#form_retiro_edit_monto').val(retiro.monto);
				$('#form_retiro_edit_observaciones').val(retiro.observaciones);
				$('#form_retiro_edit_medio_pago').val(retiro.medio_pago).change();
				$('#form_retiro_edit_moneda').val(retiro.moneda);
				let action = "{{route('retiro.update', '__retiro')}}".replace('__retiro', id);
				$('#form_retiro_edit').attr('action', action);
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