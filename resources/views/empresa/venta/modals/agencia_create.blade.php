<div class="modal" id="mdl_agencia_create" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Crear Agencia</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<label>Razón Social @include('components.misc.required')</label>
						<input autocomplete="off" id="mdl_ag_create_razon_social" type="text" class="campo_modal primerCampo form-control @if($errors->has('razon_social')) is-invalid @endif">
					</div>
					<small id="razon_social_feedback" class="text-danger" style="display: none;">
						Razón social obligatoria
					</small>
					<div class="col-12">
						<label>Cuit</label>
						<input autocomplete="off" id="mdl_ag_create_cuit" type="text" class="campo_modal form-control @if($errors->has('cuit')) is-invalid @endif">
					</div>
					<div class="col-12">
						<label>Email</label>
						<input autocomplete="off" id="mdl_ag_create_email" type="email" class="campo_modal form-control @if($errors->has('email')) is-invalid @endif">
					</div>
					<div class="col-12">
						<label>Teléfono</label>
						<input autocomplete="off" id="mdl_ag_create_telefono" type="text" class="campo_modal form-control @if($errors->has('telefono')) is-invalid @endif">
					</div>
				</div>
				<div class="py-1"></div>
				<div class="row" id="post_error" style="display: none;">
					<div class="col-12">
						<div class="alert alert-danger" role="alert">
							<span id="post_message"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="btn_aceptar_agregar_agencia" class="btn btn-success">Aceptar</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		{{-- Btn Submit Agregar Agencia rápida --}}
		$('#btn_aceptar_agregar_agencia').click(function(){
			let razon_social = $('#mdl_ag_create_razon_social').val();
			if(!razon_social || razon_social.trim() == ""){
				$('#mdl_ag_create_razon_social').addClass('is-invalid');
				$('#razon_social_feedback').show();
			}else{
				$('#mdl_ag_create_razon_social').removeClass('is-invalid');
				$('#razon_social_feedback').hide();
			}
			let cuit = $('#mdl_ag_create_cuit').val();
			let email = $('#mdl_ag_create_email').val();
			let telefono = $('#mdl_ag_create_telefono').val();

			$(this).html(`<div class="spinner-border spinner-border-sm text-light" role="status"><span class="visually-hidden">Agregando...</span></div> Agregando agencia`);
			$(this).attr('disabled', true);

			axios.post("{{route('api.agencia.store')}}", {
				razon_social: razon_social,
				cuit: cuit,
				email: email,
				telefono: telefono,
			})
			.then(response => {
				$('#post_error').hide();
				$(this).attr('disabled', false);
				$(this).html('Guardar');
				$('.campo_modal').val('');
				$('#mdl_agencia_create').modal('hide');
				let id = response.data.id;
				let nueva_razon_social = response.data.razon_social;
				if($("#select_agencia option[value='"+id+"']").length == 0){
					/*
					La agencia ya está en la base de datos, solo la agrego dinámicamente 
					a las opciones y la marco seleccionada
					*/
					$('#select_agencia').append($('<option>', {
						value: id,
						text: nueva_razon_social
					}));
				}
				$("#select_agencia").val(id).change();
			})
			.catch(error => {
				$(this).attr('disabled', false);
				$(this).html('Guardar');
				$('#post_error').show();
				$('#post_message').html(error.response.data.message);
			});
		});

		{{-- Selección de vouchers y acciones --}}
		$('#select_tipo_venta').on('change blur', function(){
			let option = $(this).find(':selected').val();
			if(option == 2){
				/*Es vocuher*/
				$('#seccion_agencia').show();
				bloquea_guardar = false;
				$('#btn_submit').prop('disabled', false);
				$('#numero_voucher').prop('required', true);
			}else{
				$('#seccion_agencia').hide();
				bloquea_guardar = true;
				$('#numero_voucher').prop('required', false);
				if($('#total_a_pagar').val() > 0){
					$('#btn_submit').prop('disabled', true);
				}
			}
		});
	});
</script>
@endpush