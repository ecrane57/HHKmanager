<div class="modal fade" id="editsite" role="dialog" aria-labelledby="editsiteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editsiteLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['route' => 'sites.store', 'method' => 'put', 'class'=>'form']) !!}
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
			      	<div id="modal-loader" class="text-center mt-3 mb-3">
				      	<i class="fa fa-circle-o-notch fa-spin fa-2x" aria-hidden="true"></i>
			      	</div>
			      	<div id="modal-content" style="display: none;">
				      	<div class="form-group">
				      		{!! Form::label('name', 'Site Name', ['class'=>'sr-only']); !!}
				      		{!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Site Name']); !!}
			      		</div>
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
	$(document).ready(function(){
		$(document).on("show.bs.modal", "#editsite", function(e){
			var btn = $(e.relatedTarget);
			console.log("edit button clicked");
			var url = btn.data('url');
			$('#editsite #modal-content').hide();
	         $('#editsite #modal-loader').show();        // hide ajax loader   
			$.ajax({
	            url: url,
	            type: 'GET',
	            dataType: 'json'
	        })
	        .done(function(data){
		        $('#editsite #editsiteLabel').text("Edit " + data.siteName);
		        $('#editsite form').prop('action', url);
		        $('#editsite input[name=name]').val(data.siteName);
		        $('#editsite input[name=path]').val(data.url);
		        $('#editsite #modal-content').show();
	            $('#editsite #modal-loader').hide();        // hide ajax loader   
	        })
	        .fail(function(){
		        $('#editsite #modal-loader').hide();        // hide ajax loader
	            $('#editsite #modal-content').html('<i class="fa fa-exclamation-triangle"></i> Something went wrong, Please try again...');
	        });
		});
	});
</script>
@append