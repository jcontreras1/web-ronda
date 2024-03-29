@extends('layouts.app')
@section('content')
@section('titulo', "Circuitos")
@include('circuito.modals.mdl_titulo')
<div class="container">	
	<x-misc-title :title="$circuito->titulo" back="{{ route('circuito.index') }}">
		<a href="#" data-bs-toggle="modal" data-bs-target="#mdl_titulo" class="btn btn-warning"><i class="bi bi-pencil-fill"></i></a>
	</x-misc-title>
	
	<div class="row">
		<div class="col-12 col-md-3">
			<p>
				<small class="text-muted mb-3">
					Creado por <strong>{{ucfirst($circuito->creador->nombre)}} {{ucfirst($circuito->creador->apellido)}}</strong> <br>
					Área: <strong>{{ $circuito->area->nombre ?? 'Sin Área' }}</strong> <br>
					Fecha: <strong>{{ date('d/m/Y H:i', strtotime($circuito->created_at)) }}</strong> <br>
					@if(count($circuito->geofences))
					<strong>{{ count($circuito->geofences) }} </strong> punto(s)
					@endif
				</small>
			</p>
			<form method="POST" action="{{route('geofence.store', ['circuito' => $circuito])}}">
				@csrf
				<div class="row mb-3">
					<div class="col-12 d-grid">
						<button class="btn btn-primary btn-lg mb-1" type="button" onclick="agregar_marcador();">Agregar marcador </button>
						<button class="btn btn-success btn-lg mb-1" type="submit" id="btn-save" disabled>Guardar</button>
					</div>
					
					<div class="col-12">
						<div class="input-group">
							<span class="input-group-text">Radio (Mts.)</span>
							<input type="number" name="radio" id="radio" value="25" class="form-control form-control-lg">
						</div>
						
					</div>
					<input type="hidden" id="latitud" name="latitud" class="form-control" >
					<input type="hidden" id="longitud" name="longitud" class="form-control" >
				</div>
			</form>
		</div>
		<div class="col-12 col-md-9">
			<div id="myMap" style="height: 450px;"></div>
		</div>
	</div>
	<hr>
	@if(count($circuito->geofences))
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>#Punto</th>
					<th>Radio</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($circuito->geofences as $geofence)
				<tr>
					<td>#{{$geofence->id}}</td>
					<td>{{$geofence->radio}}mts.</td>
					<td>
						@if($geofence->created_by == Auth::user()->id)
						<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar Punto" onclick="delete_geofence('{{route('geofence.destroy', ['circuito' => $circuito, 'geofence' => $geofence])}}')">
							<i class="bi bi-trash"></i>
						</button>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@endif
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
	
	var marker = null;
	var x = document.getElementById("geo");
	
	function agregar_marcador(){
		navigator.geolocation.getCurrentPosition(showPosition, showError, {enableHighAccuracy: true});
		document.getElementById('btn-save').disabled = false;
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
	
	
	
	function showError(error){
		console.log(error);
	}
	
	
	function getLocation() {
		if("navigator.geoLocation"){
			navigator.geolocation.getCurrentPosition(showPosition, showError, {enableHighAccuracy: true});
		}
		else{
			x.innerHTML = "no es compatible tu navegador";
		}
	}
	
	polygon = [];
	@foreach($circuito->geofences as $geo)
	polygon.push([{{$geo->latitud}}, {{$geo->longitud}}]);
	@endforeach
	let center = null;
	if(polygon.length) center = L.polyline(polygon).getBounds().getCenter();
	let mapLat = -42.7372;
	let mapLng = -65.03948;
	if(center){
		mapLat = center.lat;
		mapLng = center.lng;
	}
	
	let myMap = L.map('myMap').setView([mapLat, mapLng],15);
	L.tileLayer('{{ variable_global("URL_TILES") }}?access_token={{ variable_global("API_TOKEN_MAPS") }}', {
		maxZoom: 19,
		dragging: false,
		attribution: '© OpenStreetMap'
	}).addTo(myMap);
	
	@foreach($circuito->geofences as $geo)
	var circle = L.circle([{{$geo->latitud}}, {{$geo->longitud}}], {
		color: 'red',
		fillColor: '#f03',
		fillOpacity: 0.5,
		radius: {{$geo->radio}}
	}).addTo(myMap);
	circle.bindPopup('Punto #{{$geo->id}}');
	@endforeach
	
	// marker = L.marker(
	// 		[position.coords.latitude, position.coords.longitude],
	// 		{
		// 			draggable : true
		// 		}
		// 		).addTo(myMap);
		
		
	</script>
	@endsection