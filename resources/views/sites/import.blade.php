<div class="modal fade" id="importsite" role="dialog" aria-labelledby="importsiteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importsiteLabel">Import Existing Site</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['route' => 'sites.import', 'method' => 'post', 'class'=>'form']) !!}
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
		      		<div class="form-group">
			      		{!! Form::label('path', 'URL', ['class'=>'sr-only']); !!}
			      		<div class="input-group">
				      		<div class="input-group-prepend">
					      		<div class="input-group-text">hhk.net/</div>
				      		</div>
					  		{!! Form::text('path', null, ['class'=>'form-control', 'placeholder'=>'URL']); !!}
			      		</div>
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