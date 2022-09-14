<div class="col-12">
	<div class="card card-primary">
		<div class="card-header">
			{{$checkpoint->user->nombre}} {{$checkpoint->user->apellido}} [{{ date('d/m/Y H:i', strtotime($checkpoint->created_at))}}]
			{{-- <span class="float-end">
				<form method="POST" action="{{route('checkpoint.destroy', ['ronda' => $ronda, 'checkpoint' => $checkpoint])}}">
					@method('DELETE') @csrf
					<button data-toggle="tooltip" title="Eliminar ronda" class="btn btn-danger"><i class="bi bi-trash"></i></button>
				</form>
			</span> --}}
		</div>
		<div class="card-body">									
			<strong>{{$checkpoint->novedad}}</strong>
		</div>
	</div>
</div>