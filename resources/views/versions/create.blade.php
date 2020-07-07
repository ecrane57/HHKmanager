<div class="modal fade" id="createversion" role="dialog" aria-labelledby="createversionLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createversionLabel">New Version</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['route' => 'versions.store', 'method' => 'post', 'class'=>'form']) !!}
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
			      		<div class="form-group">
				      		{!! Form::label('name', 'Version'); !!}
				      		{!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Version']); !!}
			      		</div>
			      		<div class="form-group">
				      		{!! Form::label('Select Zip', 'Select Zip', ['class'=>'sr-only']); !!}
				      		<select class="form-control{{ $errors->has('version') ? ' form-control-danger' : '' }}  select2-zip" name="path">
								<option></option>
								@foreach($zips as $zip)
									@if(old('path') == $zip)
										<option value='{{ $zip }}' selected>{{ $zip }}</option>
									@else
										<option value='{{ $zip }}'>{{ $zip }}</option>
									@endif
								@endforeach
							</select>
			      		</div>
<!--
			      		<div class="form-group">
				      		{!! Form::label('path', 'Path to directory'); !!}
				      		<div class="input-group">
					      		<div class="input-group-prepend">
						      		<div class="input-group-text">storage/app/hhk/</div>
					      		</div>
						  		{!! Form::text('path', null, ['class'=>'form-control', 'placeholder'=>'Path']); !!}
				      		</div>
			      		</div>
-->
			      		<div class="form-group">
				      		<label for="patch" class="d-block">Is this a patch?</label>
				      		<label class="switch">
				      			<input type="checkbox" name="patch">
				      			<span class="slider round"></span>
				      		</label>
			      		</div>
			      		<div class="form-group">
				      		{!! Form::label('release_notes', 'Release Notes'); !!}
				      		{!! Form::textarea('release_notes', '', ['class'=>'form-control', 'placeholder'=>'Add any included features or manual update steps here...']); !!}
			      		</div>
			      		<div class="form-group">
				      		{!! Form::label('releaseDate', 'Release Date'); !!}
				      		{!! Form::date('releaseDate', \Carbon\Carbon::now(), ['class'=>'form-control']); !!}
			      		</div>
		      	</div>
	      	</div>
	      </div>
      </div>
      <div class="modal-footer">
	      {!! Form::submit('Add', ['class'=>'btn btn-success']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2-zip").select2({
			placeholder: "Select Zip File"
		});		
	});
</script>
@append