@extends('layouts.app')
@section('content')
@section('titulo', "Ronda #$ronda->id - ")
<div class="container">
	<h3>
		<i class="fas fa-users"></i> Ronda <strong>#{{$ronda->id}}</strong>
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('ronda.index')])
		</span>
	</h3>
	<hr>
	<form method="post" action="{{route('checkpoint.store', $ronda)}}">
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
				<textarea class="form-control" id="novedad" name="novedad" rows="6"></textarea>
				<div class="py-1"></div>
				<div class="d-grid gap-2">
					<button class="btn btn-lg btn-success">Aceptar</button>
					<button type="button" class="btn btn-lg btn-warning" onClick="vaciar_novedad()">Borrar Campo</button>
				</div>
			</div>
			@endif
		</div>
	</form>
	<hr>

	<div class="row">
		@foreach($ronda->checkpoints as $checkpoint)
		<div class="col-12">
			<div class="card card-primary">
				<div class="card-header">
					<strong>#{{$checkpoint->id}}</strong> - <em>{{$checkpoint->user->nombre}} {{$checkpoint->user->apellido}} ({{$ronda->created_at->diffForHumans()}})</em>
					<span class="float-end">
						<form method="POST" action="{{route('checkpoint.destroy', ['ronda' => $ronda, 'checkpoint' => $checkpoint])}}">
							@method('DELETE') @csrf
							<button data-toggle="tooltip" title="Eliminar ronda" class="btn btn-danger"><i class="bi bi-trash"></i></button>
						</form>
					</span>
				</div>
				<div class="card-body">
					Latitud: <code>{{$checkpoint->latitud}}</code>
					Longitud: <code>{{$checkpoint->longitud}}</code>
					<div>
						
						Novedad: <strong>{{$checkpoint->novedad}}</strong>
					</div>
				</div>
			</div>
		</div>
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
	var x = document.getElementById("geo");
	var marker = null;
	let myMap = L.map('myMap').setView([-42.7372, -65.03948],15);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		dragging: false,
		attribution: '© OpenStreetMap'
	}).addTo(myMap);

	function showError(error) {
		console.log(error);
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