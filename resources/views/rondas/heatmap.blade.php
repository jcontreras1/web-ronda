@extends('layouts.app')
@section('content')
@section('titulo', "Heatmap")


<div class="container-fluid">
	<x-misc-title :title="'Heatmap'" back="{{ route('home') }}" />
	<div class="row">
		<div class="col-12">
			<div id="myMap" style="height:75vh;"></div>
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
<script src="{{ asset('assets/leaflet-heat.js') }}"></script>
<script type="text/javascript">

	const fileInput = document.querySelector('#input_image');
	const sizeLandscape = {'width' : 1280, 'height' : 720};
	const sizePortrait = {'width' : 720, 'height' : 1280};
	const btn_aceptar = document.getElementById('btn-aceptar');
	const btn_vaciar = document.getElementById('btn-vaciar');
	const btn_obtener_ubicacion = document.getElementById('btn_obtener_ubicacion');
	const latitud = document.querySelector('#latitud');
	const longitud = document.querySelector('#longitud');


	let activeImage, originalWidthToHeightRatio;




	function resize(width, height){
		let newWidth, newHeight;
		if(width > height){
				/*Landscape*/
			newWidth = sizeLandscape.width;
			newHeight = Math.floor(newWidth / originalWidthToHeightRatio);
		}else{
				/*Portrait*/
			newWidth = sizePortrait.width;
			newHeight = Math.floor(newWidth / originalWidthToHeightRatio);
		}			
		canvas.width = newWidth;
		canvas.height = newHeight;
		canvasCtx.drawImage(activeImage, 0, 0, newWidth, newHeight);
		document.getElementById('image64').value = canvas.toDataURL("image/jpeg",0.6);
	}

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

	/*Establecer centro*/
	polygon = [];
	@foreach($checkpoints as $geo)
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




	console.log(polygon)

	let myMap = L.map('myMap').setView([mapLat, mapLng],14);

	L.tileLayer('{{ variable_global("URL_TILES") }}?access_token={{ variable_global("API_TOKEN_MAPS") }}', {
		maxZoom: 19,
		dragging: false,
		attribution: '© OpenStreetMap'
	}).addTo(myMap);

	var heat = L.heatLayer(polygon).addTo(myMap);
		//Si hay geofences
	@foreach($circuitos as $circuito)

	@foreach($circuito->geofences as $geo)
	var circle = L.circle([{{$geo->latitud}}, {{$geo->longitud}}], {
		color: '#186116',
		fillColor: '#41f03e',
		fillOpacity: 0.9,
		radius: {{$geo->radio}}
	}).addTo(myMap);
	circle.bindPopup('Punto #{{$geo->id}}');
	@endforeach

	@endforeach




	function showError(error) {
		console.log(error);
	}


</script>
@endsection