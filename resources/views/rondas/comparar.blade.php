@extends('layouts.app')
@section('content')

@section('titulo', "Comparar")
<div class="container">
	<h3>
		Ronda <strong>#{{$ronda->id}}</strong> sobre <strong>{{$circuito->titulo}}</strong>
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('ronda.index')])
		</span>
	</h3>
	<div class="py-1"></div>
	<p>
		<small class="text-muted">
			Rondin realizado por {{ucfirst($ronda->creador->nombre)}} {{ucfirst($ronda->creador->apellido)}} el {{date('d/m/Y', strtotime($ronda->created_at)) }} a las {{date('H:i', strtotime($ronda->created_at)) }}. Puntos definidos <strong>{{count($circuito->geofences)}}</strong> - Puntos visitados: <strong>{{count($ronda->checkpoints)}}</strong>
		</small>
	</p>

	<div class="row">
		<div class="col-12">
			<div id="myMap" style="height: 80vh;"></div>
		</div>
	</div>
</div>
<form id="form_delete_geofence" method="POST">@csrf @method('DELETE')</form>
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

	function delete_geofence(url){
		Swal.fire({
			icon: 'question',
			title: '¿Eliminar Punto?',
			showCancelButton: true,
			confirmButtonText: 'Si',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.isConfirmed) {
				document.getElementById('form_delete_geofence').setAttribute('action', url);
				document.getElementById('form_delete_geofence').submit();
			}
		})
	}

	var x = document.getElementById("geo");
	var marker = null;

	function getLocation() {
		if("navigator.geoLocation"){
				navigator.geolocation.getCurrentPosition(showPosition);
			}else{
				x.innerHTML = "no es compatible tu navegador";
			}
		}

		let myMap = L.map('myMap').setView([-42.7372, -65.03948],15);

		L.tileLayer('https://{s}.tiles.mapbox.com/v4/mapbox.satellite/{z}/{x}/{y}.jpg?access_token={{ variable_global("API_TOKEN_MAPS") }}', {
			maxZoom: 19,
			dragging: false,
			attribution: '© OpenStreetMap'
		}).addTo(myMap);

		@foreach($circuito->geofences as $geo)
		var circle = L.circle([{{$geo->latitud}}, {{$geo->longitud}}], {
			color: '#eb4034',
			fillColor: '#eb4034',
			fillOpacity: 0.3,
			radius: {{$geo->radio}}
		}).addTo(myMap);
		circle.bindPopup('Punto #{{$geo->id}}');
		@endforeach

		@foreach($ronda->checkpoints as $point)
		marker = L.marker([{{$point->latitud}}, {{$point->longitud}}]).addTo(myMap);
		marker.bindPopup('<strong>{{ $point->novedad }}</strong><br>Visitado a las {{ date('d/m/Y H:i', strtotime($geo->created_at)) }}');
		@endforeach

	</script>
	@endsection