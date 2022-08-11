@extends('layouts.app')
@section('content')

@section('titulo', "Circuitos")
<div class="container">
	<h3>
		{{$circuito->titulo}}
		<span class="float-end">
			<a href="{{route('geofence.create', ['circuito' => $circuito])}}" data-toggle="tooltip" title="Agregar punto" class="btn btn-success"><i class="bi bi-plus"></i></a>
			@include('components.misc.backbutton', ['url' => route('circuito.index')])
		</span>
	</h3>
	<div class="py-1"></div>
	<p>
		<small class="text-muted">Circuito creado por {{ucfirst($circuito->creador->nombre)}} {{ucfirst($circuito->creador->apellido)}} - {{$circuito->created_at->diffForHumans()}}</small>
	</p>
	<p>
		Puntos definidos: <strong>{{count($circuito->geofences)}}</strong>
	</p>
	<hr>

	<div class="row">
		<div class="col-12">
			<div id="myMap" style="height: 450px;"></div>
		</div>
	</div>
	<hr>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#Punto</th>
				<th>Lat</th>
				<th>Lon</th>
				<th>Radio</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($circuito->geofences as $geofence)
			<tr>
				<td>#{{$geofence->id}}</td>
				<td><code>{{$geofence->latitud}}</code></td>
				<td><code>{{$geofence->longitud}}</code></td>
				<td>{{$geofence->radio}}mts.</td>
				<td>
					@if($geofence->created_by == Auth::user()->id)
					<button 
					class="btn btn-danger" 
					data-toggle="tooltip" 
					title="Eliminar Punto" 
					onclick="delete_geofence('{{route('geofence.destroy', ['circuito' => $circuito, 'geofence' => $geofence])}}')">
					<i class="bi bi-trash"></i>
				</button>
				@endif
			</td>

		</tr>
		@endforeach
	</tbody>
</table>
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