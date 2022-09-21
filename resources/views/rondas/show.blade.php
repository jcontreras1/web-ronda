@extends('layouts.app')
@section('content')
@section('titulo', "Ronda #$ronda->id - ")

<div class="modal fade " id="lightbox">
	<div class="modal-dialog" id="dialog">
		<div class="modal-content">
			<img id="img_modal" class="img-fluid">
		</div>
	</div>
</div>

<div class="container">
	<h3>
		<i class="fas fa-users"></i> Ronda #{{$ronda->id}} @if($ronda->circuito) | <small class="text-muted">{{ $ronda->circuito->titulo }} </small> @endif
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('ronda.index')])
		</span>
	</h3>
	<hr>
	<form method="post" action="{{route('checkpoint.store', $ronda)}}" enctype="multipart/form-data" id="formulario">
		@if($ronda->abierta)
		<div class="row">
			<div class="col-12 col-md-4 d-grid mb-1">
				<button type="button" class="btn btn-lg btn-primary btn-block" id="btn_obtener_ubicacion" onclick="getLocation()">Obtener ubicación <i class="bi bi-geo-alt"></i></button>
			</div>
			<div class="col-12 col-md-4 d-none d-md-block mb-1">			
				<input type="text" readonly id="latitud" name="latitud" class="form-control form-control-lg" >
			</div>
			<div class="col-12 col-md-4 d-none d-md-block mb-1">
				<input type="text" readonly id="longitud" name="longitud" class="form-control form-control-lg" >
			</div>
		</div>
		@endif
		<p id="geo"></p>
		<div class="row">
			<div class="col-12 @if($ronda->abierta) col-md-8 @endif">
				<div id="myMap" style="height: 450px;"></div>
			</div>
			@if($ronda->abierta)
			<div class="col-12 col-md-4" id="s_novedad">
				@csrf 
				<label>Agregar descripción</label>
				<textarea class="form-control mb-2" id="novedad" name="novedad" rows="6"></textarea>				
				<input type="file" accept="image/png, image/gif, image/jpeg" class="form-control mb-2" name="imagen[]" multiple >
				<div class="d-grid gap-2">
					<button type="button" class="btn btn-lg btn-success" onClick="aceptar_formulario()" id="btn-aceptar">Aceptar</button>
					<button type="button" class="btn btn-lg btn-warning" onClick="vaciar_novedad()" id="btn-vaciar">Borrar Campo</button>
				</div>
			</div>
			@endif
		</div>
	</form>
	<hr>

	<div class="row">
		@foreach($ronda->checkpoints as $checkpoint)
		@include('components.ronda.card-checkpoint', ['checkpoint' => $checkpoint])
		<div class="py-1"></div>
		@endforeach
	</div>
</div>  


@endsection
@section('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
crossorigin=""/>
@endsection
@section('scripts')
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
crossorigin=""></script>
<script type="text/javascript">
	
	var icono_sin_novedades = L.icon({
		iconUrl: '{{ asset('assets/img/markers/marker-ok.png') }}',
		iconSize:     [35, 47]
	});	
	var icono_novedades = L.icon({
		iconUrl: '{{ asset('assets/img/markers/marker-warning.png') }}',
		iconSize:     [35, 47]
	});	
	var icono_imagenes = L.icon({
		iconUrl: '{{ asset('assets/img/markers/marker-photo.png') }}',
		iconSize:     [35, 47]
	});

	var x = document.getElementById("geo");
	var formulario = document.getElementById("formulario");
	var marker = null;
	let myMap = L.map('myMap').setView([-42.7372, -65.03948],15);

	var myModal = new bootstrap.Modal(document.getElementById('lightbox'));
	var dialog = document.getElementById('dialog');
	function launch_modal(img){
		const oimg = new Image();
		oimg.src = img;
		if(oimg.height > oimg.width){
			dialog.classList.remove('modal-xl');
		}else{
			dialog.classList.add('modal-xl');

		}
		
		document.getElementById('img_modal').src = img;
		myModal.show();
	}

	L.tileLayer('{{ variable_global("URL_TILES") }}?access_token={{ variable_global("API_TOKEN_MAPS") }}', {
		maxZoom: 19,
		dragging: false,
		attribution: '© OpenStreetMap'
	}).addTo(myMap);

	let punto_visitado = null;
	@foreach($ronda->checkpoints as $geo)
	punto_visitado = L.marker([{{$geo->latitud}}, {{$geo->longitud}}], {icon: @if(count($geo->images) > 0) icono_imagenes @elseif($geo->novedad) icono_novedades @else icono_sin_novedades @endif}).addTo(myMap);
	punto_visitado.bindPopup('<strong>{{ $geo->novedad }}</strong><br>Visitado a las {{ date('d/m/Y H:i', strtotime($geo->created_at)) }}');
	@endforeach

	//Si hay geofences
	@if($ronda->circuito)
	@foreach($ronda->circuito->geofences as $geo)
	var circle = L.circle([{{$geo->latitud}}, {{$geo->longitud}}], {
		color: '#eb4034',
		fillColor: '#eb4034',
		fillOpacity: 0.3,
		radius: {{$geo->radio}}
	}).addTo(myMap);
	circle.bindPopup('Punto #{{$geo->id}}');
	@endforeach
	@endif

	function showError(error) {
		console.log(error);
	}

	function aceptar_formulario(){
		let btn_aceptar = document.getElementById('btn-aceptar');
		let btn_vaciar = document.getElementById('btn-vaciar');
		btn_aceptar.disabled = true;
		btn_vaciar.disabled = true;
		btn_aceptar.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden"></span></div> Subiendo...';
		formulario.submit();
	}

	function getLocation() {
		console.log(marker);
		/*Si el marker ya estaba definido, me muevo hasta el marker*/
		if(marker){
			myMap.removeLayer(marker);
		}
		document.getElementById('btn_obtener_ubicacion').innerHTML = `
		<div class="spinner-border" role="status">
		<span class="visually-hidden">Loading...</span>
		</div>`;
		if("navigator.geoLocation"){
			navigator.geolocation.getCurrentPosition(showPosition, showError, {enableHighAccuracy: true});
		}else{
			x.innerHTML = "no es compatible tu navegador";
		}
	}
	
	function showPosition(position) {  
		document.getElementById('btn_obtener_ubicacion').innerHTML = `Obtener ubicación <i class="bi bi-geo-alt"></i>`;
		myMap.panTo(new L.LatLng(position.coords.latitude, position.coords.longitude));
		document.getElementById('latitud').value = position.coords.latitude;
		document.getElementById('longitud').value = position.coords.longitude;
		marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(myMap);
		//document.getElementById('novedad').focus();
		//document.getElementById("novedad").scrollIntoView();

	}

	function vaciar_novedad(){
		document.getElementById('novedad').value = "";
		document.getElementById('novedad').focus();
		document.getElementById("novedad").scrollIntoView();
	}

</script>
@endsection