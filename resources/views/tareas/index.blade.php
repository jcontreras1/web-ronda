@extends('layouts.webapp')
@section('content')
<div id="main">
	<tarea-index/>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
	var user_id = {{\Auth::user()->id}};
</script>
@endpush