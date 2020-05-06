<div class="modal fade" id="deletesite" role="dialog" aria-labelledby="deletesiteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="upgradesiteLabel">Delete Site</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['route' => 'sites.index', 'method' => 'delete', 'class'=>'form']) !!}
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
		      		
		      	</div>
	      	</div>
	      </div>
      </div>
      <div class="modal-footer">
	      {!! Form::submit('Remove', ['class'=>'btn btn-danger']) !!}
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
        	$("#upgradesite #upgradesiteLabel").text("Upgrade " + $(this).data("site_name"));
			$("#upgradesite #site_id").val($(this).data("site_id"));
			
		});
	});
</script>
@append