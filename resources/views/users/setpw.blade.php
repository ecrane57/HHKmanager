<div class="modal fade" id="setpw" role="dialog" aria-labelledby="setpwLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="setpwLabel">Set Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::model(Auth::user(),['route' => ['users.resetpassword', Auth::user()->id], 'method'=>'PUT']) !!}
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
			      	<div class="form-row">
			      		<div class="form-group col-md-6">
				      		{!! Form::label('password', 'Password', ['class'=>'sr-only']); !!}
				      		{!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Password']); !!}
			      		</div>
			      		<div class="form-group col-md-6">
				      		{!! Form::label('password_confirmation', 'Confirm password', ['class'=>'sr-only']); !!}
				      		{!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>'Confirm password']); !!}
			      		</div>
			      	</div>
		      	</div>
	      	</div>
	      </div>
      </div>
      <div class="modal-footer">
	      {!! Form::submit('Save', ['class'=>'btn btn-success']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

@section('scripts')
<script type="text/javascript">
$(".select2").select2({
	placeholder: "Select Roles",
});
</script>
@append