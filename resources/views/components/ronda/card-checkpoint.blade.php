<style type="text/css">
	.crop {
    width: 200px; /* You can set the dimensions to whatever you want */
    height: 200px;
    object-fit: cover;
}
</style>
<div class="col-12">
	<div class="card shadow-sm">
		<div class="card-header">
			@if(!$checkpoint->novedad && count($checkpoint->images) == 0)
			<i class="bi bi-check-circle-fill text-success pr-2"></i>
			<span class="mx-2">
			{{$checkpoint->user->nombre}} {{$checkpoint->user->apellido}} [{{ date('d/m/y H:i', strtotime($checkpoint->created_at))}}]
			</span>
			<strong><em>(Sin novedades)</em></strong>
			@else
			<i class="bi bi-chat-square-dots-fill text-primary"></i>
			<span class="mx-2">
			{{$checkpoint->user->nombre}} {{$checkpoint->user->apellido}} [{{ date('d/m/y H:i', strtotime($checkpoint->created_at))}}]
			</span>
			@endif
			<span class="float-end">#{{$checkpoint->id}}</span>
			{{-- <span class="float-end">
				<form method="POST" action="{{route('checkpoint.destroy', ['ronda' => $ronda, 'checkpoint' => $checkpoint])}}">
					@method('DELETE') @csrf
					<button data-toggle="tooltip" title="Eliminar ronda" class="btn btn-danger"><i class="bi bi-trash"></i></button>
				</form>
			</span> --}}
		</div>
		@if($checkpoint->novedad || count($checkpoint->images) > 0)
		<div class="card-body">		

			@if($checkpoint->novedad)
				<strong>{!!nl2br($checkpoint->novedad)!!}</strong>
			@endif
			@if($checkpoint->novedad && count($checkpoint->images) > 0)
			<hr>
			@endif
			@if(count($checkpoint->images))
			<div class="row">
				@foreach($checkpoint->images as $img)
				@include('components.ronda.novedad-image', ['img' => asset('storage/ronda/' . $checkpoint->id . '/' . $img->filename)])
				@endforeach
			</div>
			@endif
		</div>
		@endif
	</div>
</div>