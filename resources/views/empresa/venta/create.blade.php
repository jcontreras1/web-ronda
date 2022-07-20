@extends('layouts.app')
@section('titulo', 'Venta - ')
@section('content')
@include('empresa.venta.modals.agencia_create')
<div class="container">
	<h3>
		Pasavante
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('salida.index')])
		</span>
	</h3>
	<hr>
	{{-- Sección de detalles de la salida --}}
	@if(variable_global('USAR_COLORES_SALIDA') == 1)
	<div class="card" style="border-left: 10px solid {{$salida->color}};">
		@else
		<div class="card info">
			@endif
			<div class="card-body">
				<h5 class="card-title">Salida de las <strong>{{date('H:i', strtotime($salida->hora))}}</strong></h5>
				<div class="row h5">
					<div class="col-12 col-md-4">
						Disponibilidad: <strong class="text-primary">{{$salida->disponibilidad}}</strong> lugares
					</div>
					<div class="col-12 col-md-4">
						Pasajeros a bordo: <strong>{{count($salida->pasajeros)}}</strong>
					</div>
					<div class="col-12 col-md-4">
						Embarcación: <strong>{{$salida->embarcacion->nombre}}</strong>
					</div>
				</div>
			</div>
		</div>
		<div class="py-1"></div>

		{{-- Sección de Carga de pasajeros --}}
		<div class="row">
			<div class="col-12 col-md-4">
				<label>Cantidad de pasajeros | <small class="text-muted"><em>Máximo: {{$salida->disponibilidad}}</em></small></label>
				<input step="1" type="number" class="form-control form-control-sm" id="cantidad" autofocus autocomplete="off" placeholder="Ingresar cantidad de pasajeros" max="{{$salida->disponibilidad}}">
			</div>
		</div>
		<div class="py-1"></div>

		{{-- Comienzo del formulario real de venta --}}
		<form method="POST" id="formulario" action="{{route('venta.store', $salida)}}">
			@csrf

			{{-- Cantidad real de pasajeros --}}
			<input type="hidden" name="cantidad_pasajeros" id="real_cantidad_pasajeros">

			{{-- Tabla de pasajeros --}}
			<div id="tab_pasajeros"></div>
			<div class="py-1"></div>

			{{-- Datos de Facturación --}}
			<div class="row">
				<div class="col-12 col-md-2">
					<span class="badge bg-primary">Razón Social / Cliente</span>
					<input type="text" class="form-control form-control-sm" name="razon_social" id="razon_social" autocomplete="off">
				</div>
				<div class="col-12 col-md-2">
					<span class="badge bg-primary">CUIT / CUIL / DNI</span>
					<input type="text" class="form-control form-control-sm" name="cuit" id="cuit" autocomplete="off">
				</div>
				<div class="col-12 col-md-6">
					<span class="badge bg-primary">Detalle</span>
					<input type="text" class="form-control form-control-sm" name="detalle" id="detalle" autocomplete="off">
				</div>
				<div class="col-12 col-md-2">
					<span class="badge bg-danger">Total</span>
					<div class="input-group input-group-sm">
						<span class="input-group-text">$</span>
						<input type="text" class="form-control form-control-sm subtotal" name="subtotal" id="subtotal" readonly autocomplete="off" value="0">
					</div>
				</div>
			</div>
			<div class="py-2"></div>
			<hr>

			{{-- Tipo de venta: Voucher/Mostrador --}}

			<div class="py-1"></div>
			<div class="row">
				<div class="col-12">					
					<label>Seleccione el tipo de venta</label>
					<select id="select_tipo_venta" name="tipo_venta_id" class="form-select">
						@foreach($tipos_venta as $tipo_venta)
						<option value="{{$tipo_venta->id}}">{{$tipo_venta->nombre}}</option>
						@endforeach
					</select>
				</div>
			</div>

			{{-- Si era Voucher se despliega la sección de agencia + Nº Voucher --}}

			<div style="display: none;" id="seccion_agencia">
				<div class="row">
					<div class="col-12 col-md-6">
						<label>Seleccionar Agencia</label>
						<div class="input-group">
							<select name="agencia_id" id="select_agencia" class="form-select">
								@foreach($agencias as $agencia)
								<option value="{{$agencia->id}}">{{ucwords($agencia->razon_social)}}</option>
								@endforeach
							</select>
							<button class="btn btn-outline-primary" id="btn_agregar_agencia" type="button"><i class="bi bi-plus-lg"></i> Nueva Agencia</button>	
						</div>
					</div>
					<div class="col-12 col-md-6">
						<label>Numero Voucher</label>
						<input type="text" name="numero_voucher" class="form-control" id="numero_voucher">
					</div>
				</div>
			</div>
			<hr>

			{{-- Total, descuentos y anticipos --}}
			
			<div class="py-1"></div>
			<div class="accordion">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seccion_descuentos_anticipos" aria-expanded="false" aria-controls="collapseOne">
							<span class="lead">Totales, descuentos y anticipos</span>
						</button>
					</h2>
					<div id="seccion_descuentos_anticipos" class="accordion-collapse collapse" aria-labelledby="headingOne">
						<div class="accordion-body">
							<div class="row">
								<div>Descuentos</div>
								<div class="col-12 col-md-4">
									<span class="badge bg-dark">Descuento</span>
									<div class="input-group input-group-sm">
										<span class="input-group-text">$</span>
										<input type="number" class="form-control form-control-sm modificador_subtotal" name="descuento" id="descuento" autocomplete="off">
									</div>
								</div>
								<div class="col-12 col-md-8">
									<span class="badge bg-dark">Observaciones</span>
									<div class="input-group input-group-sm">
										<span class="input-group-text"><i class="bi bi-pencil"></i></span>
										<input type="text" placeholder="Motivo de descuento" class="form-control form-control-sm" name="motivo_descuento" id="motivo_descuento" autocomplete="off">
									</div>
								</div>
							</div>
							<div class="py-1"></div>
							<div class="row">
								<div>Anticipos</div>
								<div class="col-12 col-md-2">
									<span class="badge bg-dark">Anticipo</span>
									<div class="input-group input-group-sm">
										<span class="input-group-text">$</span>
										<input type="number" class="form-control form-control-sm modificador_subtotal" name="anticipo" id="anticipo">
									</div>
								</div>
								<div class="col-12 col-md-2">
									<span class="badge bg-dark">Medio de Pago </span>
									<select class="form-select form-select-sm" name="medio_de_pago_anticipo">
										@foreach($medios_de_pago as $medio_de_pago)
										<option value="{{$medio_de_pago->id}}">{{$medio_de_pago->nombre}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-12 col-md-8">
									<span class="badge bg-dark">Observaciones</span>
									<div class="input-group input-group-sm">
										<span class="input-group-text"><i class="bi bi-pencil"></i></span>
										<input type="text" placeholder="Nº Comprobante u otros" class="form-control form-control-sm" name="observaciones_anticipo" autocomplete="off">
									</div>
								</div>
							</div>
							<div class="py-1"></div>
							<div class="row">
								<div class="col-12 col-md-3">
									<span class="badge bg-danger">Total en Venta</span>
									<div class="input-group input-group-sm">
										<span class="input-group-text">$</span>
										<input type="text" class="form-control form-control-sm subtotal" readonly autocomplete="off" value="0">
									</div>
								</div>
								<div class="col-12 col-md-3">
									<span class="badge bg-danger">Total a pagar</span>
									<div class="input-group input-group-sm">
										<span class="input-group-text">$</span>
										<input type="text" class="form-control form-control-sm subtotal" id="total_a_pagar" name="total_a_pagar" readonly autocomplete="off" value="0">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="py-2"></div>

			{{-- Medios de pago --}}

			<div class="accordion">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seccion_medios_de_pago" aria-expanded="true" aria-controls="collapseOne">
							<span class="lead">Medios de Pago</span>
						</button>
					</h2>
					<div id="seccion_medios_de_pago" class="accordion-collapse collapse" aria-labelledby="headingOne">
						<div class="accordion-body">
							<button type="button" class="btn btn-success btn-sm" id="btn_agregar_medio_de_pago" data-toggle="tooltip" title="Agregar medios de pago">Agregar</button>
							<div class="py-1"></div>
							<div id="panel_medios_de_pago"></div>
							<hr>
							<div class="row">
								<div class="col-12 col-md-4">
									<span>Total abonado</span>
									<div class="input-group input-group-sm">
										<span class="input-group-text">$</span>
										<input type="text" class="form-control form-control-sm" id="total_medios_de_pago" readonly aria-describedby="feedback_total_medios_de_pago">
										<div id="feedback_total_medios_de_pago" class="invalid-feedback">as</div>
									</div>
								</div>							
								<div class="col-12 col-md-4">
									<span>Falta Pagar</span>
									<div class="input-group input-group-sm">
										<span class="input-group-text">$</span>
										<input type="text" class="form-control form-control-sm" id="faltante_medios_de_pago" readonly >
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
			<hr>
			<button class="btn btn-success btn-sm" id="btn_submit" disabled>Guardar</button>
		</form>
	</div>

	@endsection

	@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			var cantidad_pasajeros = 0;
			var cantidad_medios_de_pago = 0;
			/*
			Si la venta es con voucher, debería desbloquear el boton de guardar. 
			Porque pueden no haber pagado nada, o pagan una parte o pagan todo
			*/
			var bloquea_guardar = true;

			{{-- Sección de codigos agregados dinámicos --}} 
			var tipos_pasajero = `
			@foreach($tipos_pasajeros as $tipo)
			<option data-tarifa="{{$tipo->importe}}" value="{{$tipo->id}}">{{ucfirst($tipo->nombre)}}</option>
			@endforeach
			`;		
			var paises = `
			@foreach($paises as $pais)
			<option @if($pais->id == $default_pais) selected @endif value="{{$pais->id}}">{{ucwords($pais->nombre)}}</option>
			@endforeach
			`;
			var medios_de_pago = `
			@foreach($medios_de_pago as $medio_de_pago)
			<option value="{{$medio_de_pago->id}}">{{$medio_de_pago->nombre}}</option>
			@endforeach
			`;
			var monedas = `
			@foreach($monedas as $moneda)
			<option data-cotizacion="{{$moneda->cotizacion}}" data-editable="{{$moneda->editable}}" value="{{$moneda->id}}">{{$moneda->simbolo}}</option>
			@endforeach
			`;

			{{-- Btn Crear agencia rápida --}}
			$('#btn_agregar_agencia').click(function(){
				$('#mdl_agencia_create').modal('show');
			});
			
			{{-- Formulario al submitearlo bloquearlo --}}
			$('#formulario').submit(function(){
				$('#formulario').submit(function(){
					return false;
				});
			});

			{{-- Modulo para agregar o retirar pasajeros --}}
			$('#cantidad').on('blur keyup', function(e) {
				/*Si es tab o enter*/
				if (e.type === 'blur' || e.keyCode === 13){
					let temp_cantidad_pasajeros = $(this).val();
					temp_cantidad_pasajeros = parseInt(temp_cantidad_pasajeros);
					if(temp_cantidad_pasajeros == "" || isNaN(temp_cantidad_pasajeros) || cantidad_pasajeros == temp_cantidad_pasajeros){
						console.log('No era un numero, o no hay cambios');
						return;
					}
					/*Calculo la diferencia para saber si hay que agregar lugares o restarlos*/
					if(temp_cantidad_pasajeros > cantidad_pasajeros){
						/*Debo agregar pasajeros*/
						let tab_pasajeros = "";
						for(var i = cantidad_pasajeros; i < temp_cantidad_pasajeros; i++){
							tab_pasajeros += pax_code(i);
						}
						$('#tab_pasajeros').append(tab_pasajeros);				
						$('#dni_0').focus();
					}else{
						for(var i = cantidad_pasajeros - 1; i >= temp_cantidad_pasajeros; i--){
							let selector = '#fila_pasajero_' + (i);
							$(selector).remove();
						}
					}
					cantidad_pasajeros = temp_cantidad_pasajeros;
					$('#real_cantidad_pasajeros').val(cantidad_pasajeros);
				}
			});

			{{-- Sección auxiliar para cuando ocurre un cambio en alguno de los pasajeros para que recalcule el total --}}
			$(document).on('change blur', '.pax', function(){
				let subtotal = 0;
				let tipos = [];
				$('.tipo_pasajero').each(function(){
					let tipo = $(this).find(':selected').text();
					if(tipos[tipo] === undefined){
						tipos[tipo] = 1;
					}else{
						tipos[tipo] += 1;
					}
					subtotal += parseFloat($(this).find(':selected').data('tarifa'));
				});
				let tipos_aux = [];
				for(tipo in tipos){
					tipos_aux.push(tipo + ' x ' + tipos[tipo]);
				}
				$('#detalle').val(tipos_aux.join(', '));
				$('.subtotal').val(Math.round(subtotal * 100) / 100);
				recalcular_total();
			});

			$(document).on('change', '.dni_pax', function(){
				analizar_input($(this).data('id'));
			});

			{{-- Modificar el subtotal a pagar luego de modificar el descuento --}}
			$(document).on('keyup blur', '.modificador_subtotal', function(){
				recalcular_total();
			});

			{{-- Cada vez que se modifique un campo de la primer fila, se actualizan los datos de la factura --}}
			$(document).on('change blur', '#nombre_0, #apellido_0, #dni_0', function(){
				let razon_social = $('#nombre_0').val() + ' ' + $('#apellido_0').val();
				let cuit = $('#dni_0').val();
				$('#razon_social').val(razon_social);
				$('#cuit').val(cuit);
			});

			{{-- Agregar un medio de pago --}}
			$('#btn_agregar_medio_de_pago').click(function(){
				let code = medio_de_pago_code(++cantidad_medios_de_pago, medios_de_pago, monedas);
				$('#panel_medios_de_pago').append(code);
			});

			{{-- Eliminar un medio de pago --}}
			$(document).on('click', '.btn_eliminar_fila_medio_pago', function(){
				let id = $(this).data('id');
				$('#fila_medio_de_pago_' + id).remove();
				recalcular_total();
			});

			{{-- Cambio en la moneda --}}
			$(document).on('change', '.moneda_medio_de_pago', function(){
				let cotizacion = $(this).find(':selected').data('cotizacion');
				let editable = $(this).find(':selected').data('editable');
				let id = $(this).data('id');
				$('#cotizacion_medio_de_pago_' + id).val(cotizacion);
				$('#cotizacion_medio_de_pago_' + id).prop('readonly', !editable);
				recalcular_total_fila(id);
			});

			{{-- Cambio en lo que abona de alguno de los medios de pago --}}
			$(document).on('keyup change', '.cantidad_medio_de_pago', function(){
				let id = $(this).data('id');
				recalcular_total_fila(id);
			});

			{{-- Cambio en una cotizacion --}}
			$(document).on('keyup change', '.cotizacion_medio_de_pago', function(){
				let id = $(this).data('id');
				recalcular_total_fila(id);
			});		

			{{-- Calcula el total abonado por cada fila de medio de pago --}}
			function recalcular_total_fila(fila){
				let cotizacion = $('#cotizacion_medio_de_pago_' + fila).val();
				let importe = $('#cantidad_medio_de_pago_' + fila).val();
				let total = cotizacion * importe;
				$('#total_medio_de_pago_' + fila).val(total);
				recalcular_total();
			}

			{{-- Calcula el total sumando todas las filas de medios de pago --}}
			function recalcular_total(){
				/*Descuentos*/

				let descuento = $('#descuento').val();
				let anticipo = $('#anticipo').val();
				let subtotal = $('#subtotal').val();
				let total = parseFloat(subtotal || 0) - parseFloat(anticipo || 0) - parseFloat(descuento || 0);
				total = (total * 100) / 100;
				$('#total_a_pagar').val(total);

				total = 0;
				$('.total_medio_de_pago').each(function(){
					total += parseFloat($(this).val());
				});
				total_pagado = (total * 100) / 100;
				let total_a_pagar = $('#total_a_pagar').val();
				total_a_pagar = parseFloat(total_a_pagar || 0);
				$('#total_medios_de_pago').val(total_pagado);
				let faltante = total_a_pagar - total_pagado;
				$('#faltante_medios_de_pago').val(faltante);
				if(total_pagado < total_a_pagar){
					$('#total_medios_de_pago').removeClass('is-valid');
					$('#total_medios_de_pago').addClass('is-invalid');
					$('#feedback_total_medios_de_pago').html('Fondos insuficientes');
					$('#btn_submit').prop('disabled', true);
				}else{
					$('#total_medios_de_pago').removeClass('is-invalid');
					$('#total_medios_de_pago').addClass('is-valid');
					$('#feedback_total_medios_de_pago').html('');
					$('#btn_submit').prop('disabled', false);
				}
				if(!bloquea_guardar){
					$('#btn_submit').prop('disabled', false);					
				}
			}

			function analizar_input(id){				
				var lectura = $('#dni_' + id).val();
				var tipos = {
					'Mayor' : 1,
					'Menor' : 2,
					'Infoa' : 3
				};
				if(lectura.includes('"')){
					var count = (lectura.match(/"/g) || []).length;
					if(count > 3){			
						let arr = lectura.split('"');
						let edad = _calculateAge(new Date(arr[6].split('-').reverse().join('-')));
						let tipo = "Mayor";
						if(edad <= 3){
							tipo = "Infoa";
						}
						if(edad >= 4 && edad <= 12){
							tipo = "Menor";
						}
						/*Cargar los datos*/
						$('#dni_' + id).val(arr[4]);
						$('#apellido_' + id).val(arr[1]);
						$('#nombre_' + id).val(arr[2]);
						$('#tipo_pasajero_' + id).val(tipos[tipo]).change();
						let next_id = parseInt(id) + 1;
						if($('#dni_' + next_id) !== null){
							$("#dni_" + next_id).focus();
						}
					}
				}	
			}

			{{-- Calular edad --}}
			function _calculateAge(birthday) { 
				var ageDifMs = Date.now() - birthday.getTime();
				var ageDate = new Date(ageDifMs);
				return Math.abs(ageDate.getUTCFullYear() - 1970);
			}

			{{-- Codigo de pasajeros --}}
			function pax_code(id){
				let pax = `
				<div class="row" id="fila_pasajero_${id}">
				<div class="col-12 col-md-2"><input type="text" id="dni_${id}" data-id="${id}" class="form-control form-control-sm pax dni_pax" name="dni[]" placeholder="DNI pax ${id+1}" /></div>
				<div class="col-12 col-md-3"><input type="text" id="nombre_${id}" class="form-control form-control-sm pax" name="nombre[]" placeholder="Nombre" /></div>
				<div class="col-12 col-md-3"><input type="text" id="apellido_${id}" class="form-control form-control-sm pax" name="apellido[]" placeholder="Apellido" /></div>
				<div class="col-12 col-md-1">
				<select class="form-select form-select-sm tipo_pasajero pax" id="tipo_pasajero_${id}" name="tipo_pasajero[]">${tipos_pasajero}</select>
				</div>
				<div class="col-12 col-md-3">
				<select class="form-select form-select-sm pax" name="nacionalidad[]">${paises}</select>
				</div>
				<div class="py-1"></div>
				</div>
				`;
				return pax;
			}

			function medio_de_pago_code(id, medios_de_pago, monedas){
				let panel = `
				<div class="row" id="fila_medio_de_pago_${id}">
				<div class="col-12 col-md-2">
				<span class="badge bg-success">Medio de Pago</span>
				<select class="form-select form-select-sm" name="medio_pago[]">
				${medios_de_pago}
				</select>
				</div>

				<div class="col-12 col-md-1">
				<span class="badge bg-success">Moneda</span>
				<select class="form-select form-select-sm moneda_medio_de_pago" data-id="${id}" name="moneda_medio_de_pago[]">
				${monedas}
				</select>
				</div>					

				<div class="col-12 col-md-1">
				<span class="badge bg-success">Cotización</span>
				<div class="input-group input-group-sm">
				<span class="input-group-text">$</span>
				<input type="number" name="cotizacion_medio_de_pago[]" data-id="${id}" readonly class="form-control form-control-sm cotizacion_medio_de_pago" id="cotizacion_medio_de_pago_${id}" value="1">
				</div>
				</div>

				<div class="col-12 col-md-2">
				<span class="badge bg-success">Abona</span>
				<div class="input-group input-group-sm">
				<span class="input-group-text">$</span>
				<input autocomplete="off" type="number" data-id="${id}" id="cantidad_medio_de_pago_${id}" name="cantidad_medio_de_pago[]" class="form-control form-control-sm cantidad_medio_de_pago" value="0">
				</div>
				</div>

				<div class="col-12 col-md-2">
				<span class="badge bg-success">Total</span>
				<div class="input-group input-group-sm">
				<span class="input-group-text">$</span>
				<input readonly type="number" name="total_medio_de_pago[]" id="total_medio_de_pago_${id}" class="form-control form-control-sm total_medio_de_pago" value="0">
				</div>
				</div>

				<div class="col-12 col-md-3">
				<span class="badge bg-success">Observaciones</span>
				<input type="text" name="observaciones_medio_de_pago[]" class="form-control form-control-sm" placeholder="Nº Comprobante, aclaraciones, titular, etc">
				</div>

				<div class="col-12 col-md-1 d-flex align-items-end">

				<button type="button" class="btn btn-danger btn-sm btn_eliminar_fila_medio_pago" data-id="${id}" data-toggle="tooltip" title="Eliminar esta fila"><i class="bi bi-x-lg"></i></button>
				</div>
				</div>
				`;
				return panel;
			} 
		});


	</script>
	@endsection