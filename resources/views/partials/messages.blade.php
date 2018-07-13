@if (Session::has('success'))

	<div class="alert alert-success" role="alert">
		<strong>Success:</strong> {!! Session::get('success') !!}
	</div>

@endif

@if (Session::has('info'))

	<div class="alert alert-primary" role="alert">
		<strong>Info:</strong> <br>{!! Session::get('info') !!}
	</div>

@endif

@if (Session::has('error'))

	<div class="alert alert-danger" role="alert">
		<strong>Error:</strong> {!! Session::get('error') !!}
	</div>

@endif
@if(isset($errors))
@if (count($errors) > 0)
<div class="row justify-content-center">
	<div class="alert alert-danger col-md-6 alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<strong>Error:</strong> Please correct the following errors:
		<ul>
			@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
</div>
@endif
@endif