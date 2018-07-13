<div class="modal fade" id="createuser" role="dialog" aria-labelledby="createuserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createuserLabel">New User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['route' => 'users.store', 'method' => 'post', 'class'=>'form']) !!}
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
			      	<div class="form-row">
			      		<div class="form-group col-md-6">
				      		{!! Form::label('first_name', 'First Name', ['class'=>'sr-only']); !!}
				      		{!! Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>'First Name']); !!}
			      		</div>
			      		<div class="form-group col-md-6">
				      		{!! Form::label('last_name', 'Last Name', ['class'=>'sr-only']); !!}
				      		{!! Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>'Last Name']); !!}
			      		</div>
			      	</div>
			      		<div class="form-group">
				      		{!! Form::label('email', 'Email', ['class'=>'sr-only']); !!}
				      		<div class="input-group">
					      		{!! Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'Email']); !!}
					      		<div class="input-group-append">
						      		<div class="input-group-text">@nonprofitsoftwarecorp.org</div>
					      		</div>
						  		
				      		</div>
			      		</div>
			      		<div class="form-group">
				      		{!! Form::label('roles', 'Roles', ['class'=>'sr-only']); !!}
				      		<select class="select2 form-control{{ $errors->has('roles') ? ' form-control-danger' : '' }}  select2-roles" name="roles">
							<option></option>
							@foreach($roles as $role)
								@if(old('roles') == $role->id)
									<option value='{{ $role->id }}' selected>{{ $role->name }}</option>
								@else
									<option value='{{ $role->id }}'>{{ $role->name }}</option>
								@endif
							@endforeach
						</select>
		      		</div>
			      	<div class="form-row">
			      		<div class="form-group col-md-6">
				      		{!! Form::label('password', 'Password', ['class'=>'sr-only']); !!}
				      		{!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Password']); !!}
			      		</div>
			      		<div class="form-group col-md-6">
				      		{!! Form::label('password_confirmation', 'Confirm password', ['class'=>'sr-only']); !!}
				      		{!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>'Confirm password']); !!}
			      		</div>
			      		<div class="form-group">
			      			<label class="switch">
			      				<input type="checkbox" name="reset">
				  				<span class="slider round"></span>
				  			</label>
				  			<span class="align-top ml-2 mt-1 d-inline-block">Require new user to reset password on first login</span>
		      			</div>
			      	</div>
			      		
		      	</div>
	      	</div>
	      </div>
      </div>
      <div class="modal-footer">
	      {!! Form::submit('Create', ['class'=>'btn btn-success']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2-roles").select2({
			placeholder: "Select Role",
		});		
	});
</script>
@append