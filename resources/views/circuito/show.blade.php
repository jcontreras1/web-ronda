@extends('layouts.app')
@section('content')
@section('titulo', "Circuitos")
@include('circuito.modals.mdl_titulo')
<div class="container">
	<h3>
		{{$circuito->titulo}} | <small><a href="#" data-bs-toggle="modal" data-bs-target="#mdl_titulo" class="text-info"><i class="bi bi-pencil-fill"></i></a></small>
		<span class="float-end">
			{{-- <a href="{{route('geofence.create', ['circuito' => $circuito])}}" data-toggle="tooltip" title="Agregar punto" class="btn btn-success"><i class="bi bi-plus"></i></a> --}}
			@include('components.misc.backbutton', ['url' => route('circuito.index')])
		</span>
	</h3>
	<hr>

	<p>
		<small class="text-muted mb-3">
			Circuito creado por {{ucfirst($circuito->creador->nombre)}} {{ucfirst($circuito->creador->apellido)}} - 
			{{$circuito->created_at->diffForHumans()}} @if(count($circuito->geofences)) - Con {{ count($circuito->geofences) }} puntos @endif
		</small>
	</p>
	<div class="row">
		<div class="col-12 col-md-3">
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
		<table class="table table-striped">
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

	let myMap = L.map('myMap').setView([-42.7372, -65.03948],15);
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

</script>
@endsection