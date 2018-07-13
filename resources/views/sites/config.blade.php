<div class="modal fade" id="siteconfig" role="dialog" aria-labelledby="siteconfigLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="siteconfigLabel">Site Configuration</h5>
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

    $(document).on('click', '#getConfig', function(e){

        e.preventDefault();

        var url = $(this).data('url');

        $('#siteconfig #modal-content').html(''); // leave it blank before ajax call
        $('#siteconfig #modal-loader').show();      // load ajax loader

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json'
        })
        .done(function(data){
	        var contenthtml = '<div class="accordion" id="config">';
	        $.each(data, function(index, element){
		        contenthtml += '<div class="card"><h4 class="card-header"><a href="#" data-toggle="collapse" data-target="#' + index + '" aria-controls="' + index + '" style="text-transform:capitalize">' + index + '</a></h4>';
				contenthtml += '<div class="collapse" id="' + index + '" data-parent="#config"><table class="card-body table"><tbody>';
		        $.each(element, function(index, element){
			        contenthtml += '<tr><td>' + index + '</td><td>' + element + '</td></tr>';
		        });
		        contenthtml += '</tbody></table></div></div>';
	        });
	        contenthtml += '</div>';
            $('#siteconfig #modal-content').html('');  
            $('#siteconfig #modal-content').html(contenthtml); // load response 
            $('#siteconfig #modal-loader').hide();        // hide ajax loader   
        })
        .fail(function(){
	        $('#siteconfig #modal-loader').hide();        // hide ajax loader
            $('#siteconfig #modal-content').html('<i class="fa fa-exclamation-triangle"></i> Something went wrong, Please try again...');
        });

    });

});
	
</script>
@append