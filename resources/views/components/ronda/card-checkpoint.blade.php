<style type="text/css">
	.crop {
    width: 200px; /* You can set the dimensions to whatever you want */
    height: 200px;
    object-fit: cover;
}
</style>
<div class="col-12">
	<div class="card card-primary">
		<div class="card-header @if($checkpoint->novedad || count($checkpoint->images) > 0) bg-dark @else bg-success @endif text-white">
			{{$checkpoint->user->nombre}} {{$checkpoint->user->apellido}} [{{ date('d/m/Y H:i', strtotime($checkpoint->created_at))}}]
			{{-- <span class="float-end">
				<form method="POST" action="{{route('checkpoint.destroy', ['ronda' => $ronda, 'checkpoint' => $checkpoint])}}">
					@method('DELETE') @csrf
					<button data-toggle="tooltip" title="Eliminar ronda" class="btn btn-danger"><i class="bi bi-trash"></i></button>
				</form>
			</span> --}}
		</div>
		<div class="card-body">									
			<strong>{!!$checkpoint->novedad ?? '<span class="text-muted">Sin novedades</span>'!!}</strong>
			@if(count($checkpoint->images))
			<hr>
			<div class="row">
				@foreach($checkpoint->images as $img)
				@include('components.ronda.novedad-image', ['img' => asset('storage/ronda/' . $checkpoint->id . '/' . $img->filename)])
				@endforeach
			</div>
			@endif
		</div>
	</div>
</div>