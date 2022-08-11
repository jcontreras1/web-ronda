@extends('layouts.app')
@section('content')

@section('titulo', "Definir circuito")
<div class="container">
	<h3>
		Ronda
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => url('home')])
		</span>
	</h3>
	<hr>
	<button class="btn btn-primary btn-lg" onclick="agregar_marcador();">Agregar + </button>
	<div class="row">
		<div id="myMap" style="height: 450px;"></div>
	</div>
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



	function getLocation() {
		if("navigator.geoLocation"){
				// getCurrentPosition() se utiliza para devolver la posición del usuario.
				navigator.geolocation.getCurrentPosition(showPosition);
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
			myMap.panTo(new L.LatLng(position.coords.latitude, position.coords.longitude));
			let marker = L.marker(
				[position.coords.latitude, position.coords.longitude],
				{
					draggable : true
				}
				).addTo(myMap);

			marker.on('dragend', function(e){
				let newPos = e.target.getLatLng;
				
				console.log(e.target.getLatLng());
			});
		}

	</script>
	@endsection