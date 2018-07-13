<div class="modal fade" id="releasenotes" role="dialog" aria-labelledby="releasenotesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="releasenotesLabel">Release Notes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
			      	<div id="modal-loader" class="text-center mt-3 mb-3">
				      	<i class="fa fa-circle-o-notch fa-spin fa-2x" aria-hidden="true"></i>
			      	</div>
			      	<div id="modal-content"></div>
		      	</div>
	      	</div>
	      </div>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script type="text/javascript">
	
	$(document).ready(function(){

    $(document).on('click', '#getNotes', function(e){

        e.preventDefault();

        var url = $(this).data('url');

        $('#modalcontent').html(''); // leave it blank before ajax call
        $('#modal-loader').show();      // load ajax loader

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json'
        })
        .done(function(data){
            $('#modal-content').html('');  
            $('#modal-content').html(data['release_notes']); // load response 
            $('#modal-loader').hide();        // hide ajax loader   
        })
        .fail(function(){
	        $('#modal-loader').hide();        // hide ajax loader
            $('#modal-content').html('<i class="fa fa-exclamation-triangle"></i> Something went wrong, Please try again...');
        });

    });

});
	
</script>
@append