<div class="modal fade" id="upgradesite" role="dialog" aria-labelledby="upgradesiteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="upgradesiteLabel">Upgrade Site</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['route' => 'sites.upgrade', 'method' => 'post', 'class'=>'form']) !!}
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
		      		<div class="form-group">
			      		{!! Form::label('version_id', 'Version', ['class'=>'sr-only']); !!}
			      		<select class="form-control{{ $errors->has('version') ? ' form-control-danger' : '' }}  select2-version" name="version_id">
						<option></option>
						@foreach($versions as $version)
							@if(old('version_id') == $version->id)
								<option value='{{ $version->id }}' selected>{{ $version->name }}</option>
							@elseif($version->patch == true)
								<option value='{{ $version->id }}'>{{ $version->name }}</option>
							@endif
						@endforeach
					</select>
					{!! Form::hidden('site_id', "", ['id' => "site_id"]) !!}
		      		</div>
		      		<div class="form-group">
			      		{!! Form::label('user', 'HHK Username', ['class'=>'sr-only']) !!}
			      		{!! Form::text('user', "",['class'=> "form-control", 'placeholder'=> "HHK Username", 'id' => "user"]) !!}
		      		</div>
		      		<div class="form-group">
			      		{!! Form::label('pw', 'HHK Password', ['class'=>'sr-only']) !!}
			      		{!! Form::password('pw', ['class'=> "form-control", 'placeholder'=> "HHK Password", 'id' => "pw"]) !!}
		      		</div>
		      	</div>
	      	</div>
	      </div>
      </div>
      <div class="modal-footer">
	      {!! Form::submit('Upgrade', ['class'=>'btn btn-success']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2-version").select2({
			placeholder: "Select Version"
		});
		
		$(document).on('click', '#upgrade', function(e){

        	e.preventDefault();
			$("#upgradesite #site_id").val($(this).data("site_id"));
			
		});
	});
</script>
@append