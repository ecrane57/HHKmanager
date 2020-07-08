<div class="modal fade" id="deletesite" role="dialog" aria-labelledby="deletesiteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletesiteLabel">Delete Site</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post" class="form">
      <input name="_method" type="hidden" value="DELETE">
      	@csrf
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
		      		Are you sure you want to remove <strong><span id="siteName"></span></strong> from the NPSeCure dashboard? No files or databases will be deleted.
		      	</div>
	      	</div>
	      </div>
      </div>
      <div class="modal-footer">
	      {!! Form::submit('Remove', ['class'=>'btn btn-danger']) !!}
      </div>
      </form>
    </div>
  </div>
</div>

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		var siteIndexRoute = '/sites';
		$(document).on('click', '#delete', function(e){

        	e.preventDefault();
        	$("#deletesite #deletesiteLabel").text("Remove " + $(this).data("site_name"));
        	$("#deletesite #siteName").text($(this).data("site_name"));
			$("#deletesite form").attr('action', siteIndexRoute + '/' + $(this).data("site_id"));
			
		});
	});
</script>
@append