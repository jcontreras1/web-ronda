@extends('layouts.app')
@section('content')
@include('rondas.modals.new_checkpoint')
@section('titulo', "Ronda #$ronda->id - ")
<div class="container">
	<h3>
		<i class="fas fa-users"></i> Ronda <strong>#{{$ronda->id}}</strong>
		<span class="float-end">
			<a href="#" data-bs-toggle="modal" data-bs-target="#get_location" class="btn btn-success"><i class="bi bi-plus"></i></a>
			@include('components.misc.backbutton', ['url' => url('home')])
		</span>
	</h3>
	<hr>

	<button type="button" onclick="getLocation()">Obtener ubicación</button>
	<p id="geo"></p>
	<div id="myMap" style="height: 500px;"></div>
	<hr>


	<div class="row">
		@foreach($ronda->checkpoints as $checkpoint)
		<div class="col-12 col-md-4">
			<div class="card card-primary">
				<div class="card-header">
					<span class="float-end">
						<a href="#" data-toggle="tooltip" title="Cerrar ronda" class="btn btn-warning"><i class="bi bi-check2"></i></a>
						<a href="#" data-toggle="tooltip" title="Eliminar ronda" class="btn btn-danger"><i class="bi bi-trash"></i></a>
					</span>
				</div>
				<div class="card-body">
					<a class="text-dark" href="{{route('ronda.show', $ronda)}}" style="text-decoration: none;">
						<div class="card-title">
							<h5>Ronda #{{$ronda->id}}</h5>
							<hr>
							@if(count($ronda->checkpoints) == 0)
							<small><em>Sin datos</em></small>
							@else
							@endif
						</div>
					</a>
				</div>
				<div class="card-footer">
					Ronda creada {{$ronda->created_at->diffForHumans()}}
				</div>
			</div>
		</div>
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
	function getLocation() {
		if("navigator.geoLocation"){
				// getCurrentPosition() se utiliza para devolver la posición del usuario.
				navigator.geolocation.getCurrentPosition(showPosition);
			}
			else{
				x.innerHTML = "no es compatible tu navegador";
			}
		}
		let myMap = L.map('myMap').setView([-42.7425792, -65.0477568],13);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '© OpenStreetMap'
		}).addTo(myMap);

		function showPosition(position) {  
			console.log(position.coords.latitude);
			let marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(myMap);
		}

	</script>
	@endsection