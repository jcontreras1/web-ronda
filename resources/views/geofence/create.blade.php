@extends('layouts.app')
@section('content')

@section('titulo', "Circuitos")
<div class="container">
	<h3>
		{{$circuito->titulo}}
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('circuito.show', $circuito)])
		</span>
	</h3>
	<hr>
	<form method="POST" action="{{route('geofence.store', ['circuito' => $circuito])}}">
		@csrf
		<div class="row">
			<div class="col-12 col-md-4 d-grid">
				<button class="btn btn-primary btn-lg" type="button" onclick="agregar_marcador();">Agregar + </button>
			</div>
			<div class="col-12 col-md-4">
				<div class="input-group input-group-lg">
					<span class="input-group-text">Lat</span>
					<input type="text" readonly id="latitud" name="latitud" class="form-control" >
				</div>
			</div>
			<div class="col-12 col-md-4">
				<div class="input-group input-group-lg">
					<span class="input-group-text input-group-lg">Lng</span>
					<input type="text" readonly id="longitud" name="longitud" class="form-control" >
				</div>
			</div>
		</div>

		<div class="py-1"></div>

		<div class="row">
			<div class="col-12">
				<div id="myMap" style="height: 450px;"></div>
			</div>
		</div>

		<div class="py-1"></div>

		<div class="row">
			<div class="col-12 col-md-8">
				<div class="input-group">
					<span class="input-group-text">Radio (Mts.)</span>
					<input type="number" name="radio" id="radio" value="25" class="form-control form-control-lg">
				</div>
			</div>
			<div class="col-12 col-md-4 d-grid">
				<button class="btn btn-lg btn-success">Guardar punto</button>
			</div>
		</div>
	</form>
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


	function getLocation() {
		if("navigator.geoLocation"){
				// getCurrentPosition() se utiliza para devolver la posición del usuario.
				navigator.geolocation.getCurrentPosition(showPosition, null, {enableHighAccuracy: true});
			}
			else{
				x.innerHTML = "no es compatible tu navegador";
			}
		}

		let myMap = L.map('myMap').setView([-42.7372, -65.03948],15);

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			dragging: false,
			attribution: '© OpenStreetMap'
		}).addTo(myMap);



		function agregar_marcador(){
			navigator.geolocation.getCurrentPosition(showPosition);
		}

		function showPosition(position) {
			/*Si el marker ya estaba definido, me muevo hasta el marker*/
			if(marker){
				let coords = marker.getLatLng();
				myMap.panTo(new L.LatLng(coords.lat, coords.lng));
				return;
			}
			myMap.panTo(new L.LatLng(position.coords.latitude, position.coords.longitude));
			document.getElementById('latitud').value = position.coords.latitude;
			document.getElementById('longitud').value = position.coords.longitude;
			marker = L.marker(
				[position.coords.latitude, position.coords.longitude],
				{
					draggable : true
				}
				).addTo(myMap);

			marker.on('dragend', function(e){
				let newPos = e.target.getLatLng();
				document.getElementById('latitud').value = newPos.lat;
				document.getElementById('longitud').value = newPos.lng;
				console.log(e.target.getLatLng());
			});
		}

	</script>
	@endsection