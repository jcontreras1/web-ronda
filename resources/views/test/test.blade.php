@extends('layouts.app')
@section('content')

<div class="container">
	<!-- Modal -->
	<div class="modal fade" id="lightbox">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<img class="img-fluid" id="img_modal" src="{{ asset('assets/img/frames/1.jpg') }}">
			</div>
		</div>
	</div>

	<div class="row">
		@for($i = 1; $i < 7; $i++)
		@include('components.ronda.novedad-image', ['img' => $i])
		@endfor
	</div>



<div class="h3">Upload</div>
<form method="POST" action="{{ route('img.upload') }}" enctype="multipart/form-data">
	@csrf
	<input type="file" accept="image/png, image/gif, image/jpeg" class="form-control mb-3" name="imagen" required>
	<button class="btn btn-outline-success">Subir</button>

</form>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
	var myModal = new bootstrap.Modal(document.getElementById('lightbox'));
	function launch_modal(img){
		document.getElementById('img_modal').src = img;
		myModal.show();
	}
</script>
@endsection