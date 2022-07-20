<h4>Vouchers sin Rendir</h4>
	<div class="table-responsive">
		<table class="table table-hover table-sm">
			<thead>
				<tr>
					<th>Nº Voucher</th>
					<th>Salida</th>
					<th>Detalle</th>
					<th>Total Real</th>
					<th>Desc. Antic.</th>
					<th>Recibido</th>
					@canany(['administrar', 'ventas'])
					<th>Opciones</th>
					@endcan
				</tr>
			</thead>
			<tbody>
				@foreach($vouchers_sin_rendir as $voucher)
				<tr>
					<td>{{$voucher->numero}}</td>
					@if($voucher->venta->salida)
					<td>{{date('d/m/Y', strtotime($voucher->venta->salida->fecha))}} - {{date('H:i', strtotime($voucher->venta->salida->hora))}}</td>
					@else
					<td>Sin datos de la Salida</td>
					@endif
					<td>{{$voucher->venta->detalle}}</td>
					<td>${{pesosargentinos($voucher->venta->total_real)}}</td>
					<td>${{pesosargentinos($voucher->venta->descuento)}} / ${{pesosargentinos($voucher->venta->anticipo)}}</td>
					<td><strong>${{pesosargentinos($voucher->venta->total)}}</strong></td>
					@canany(['administrar', 'ventas'])
					<td>
						<button class="btn btn-success btn-sm btn_rendir" data-id="{{$voucher->id}}" data-toggle="tooltip" title="Rendir"><i class="bi bi-currency-dollar"></i></button>
					</td>
					@endcan
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

@push('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('.btn_rendir').click(function(){
			$('#mdl_voucher_rendir').modal('show');
			let id = $(this).data('id');
			let route = "{{route('api.voucher.show', '__voucher')}}".replace('__voucher', id);
			axios.get(route)
			.then(response => {
				let voucher = response.data;
				console.log(voucher);
				$('#form_voucher_rendir_numero').val(voucher.numero);
				$('#form_voucher_rendir_total').val(voucher.venta.total_real);
				$('#form_voucher_rendir_recibido').val(voucher.venta.total);
				//$('#form_voucher_rendir_monto').val(voucher.monto);
				//$('#form_voucher_rendir_observaciones').val(voucher.observaciones);
				//$('#form_voucher_rendir_medio_pago').val(voucher.medio_pago).change();
				//$('#form_voucher_rendir_moneda').val(voucher.moneda);
				let action = "{{route('voucher.update', '__voucher')}}".replace('__voucher', id);
				$('#form_voucher_rendir').attr('action', action);
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