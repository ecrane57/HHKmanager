<div class="modal fade" id="sitecomments" role="dialog" aria-labelledby="sitecommentsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sitecommentsLabel">Comments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	      <div class="container-fluid">
	      	<div class="row">
		      	<div class="col-12">
			      	<div class="alert alert-danger" id="error"></div>
			      	{!! Form::open(['route' => 'comments.store', 'method' => 'post', 'class'=>'form mb-4', 'id'=>'comment-form']) !!}
			      		<div class="form-group">
			      			{!! Form::label('body', 'Comment', ['class'=>'sr-only']); !!}
			      			{!! Form::textarea('body', null, ['class'=>'form-control', 'placeholder'=>'Add Comment', 'rows'=>'2', 'id'=>'body']); !!}
			      			{!! Form::hidden('site_id', null, ['id'=>'site_id']); !!}
		      			</div>
		      			{!! Form::submit('Add', ['class'=>'btn btn-success']) !!}
			      	{!! Form::close() !!}
			      	<div id="modal-loader" class="text-center mt-3 mb-3">
				      	<i class="fa fa-circle-o-notch fa-spin fa-2x" aria-hidden="true"></i>
			      	</div>
			      	<div id="modal-content">
				      	
			      	</div>
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
		
		$.ajaxSetup({
			headers: {
            	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		
	$('#sitecomments #modal-content').hide();
	$('#sitecomments #comment-form').hide();
	$('#sitecomments #error').hide();
    $(document).on('click', '#getComments', function(e){

        e.preventDefault();

        var url = $(this).data('url');

        $('#showdetails #modal-content').html(''); // leave it blank before ajax call
        
        $('#modal-loader').show();      // load ajax loader

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json'
        })
        .done(function(data){
	        var contenthtml = '';
			$.each(data, function(index, element){
			        contenthtml += '<div class="media">';
			        if(element.author.avatar){
				        contenthtml += '<img class="mr-3" src="/placeholder.jpg">';
			        }else{
				        contenthtml += '<i class="fa fa-user-circle fa-3x mr-3 text-secondary"></i>';
			        }
			        contenthtml += '<div class="media-body"><h5 class="mt-0">' + element.author.first_name + '</h5>' + element.body + '<p class="text-muted">' + element.timeago + '</p></div></div>';
		    });
		    $('#sitecomments #site_id').val(data[0].site_id);
            $('#sitecomments #modal-content').html(contenthtml); // load response 
            $('#sitecomments #modal-loader').hide();        // hide ajax loader
            $('#sitecomments #comment-form').show();
            $('#sitecomments #modal-content').show();
        })
        .fail(function(){
	        $('#sitecomments #modal-loader').hide();        // hide ajax loader
            $('#sitecomments #modal-content').html('<i class="fa fa-exclamation-triangle"></i> Something went wrong, Please try again...');
            $('#sitecomments #modal-content').show();
        });

    });
    
    
    $(document).on('submit', '#comment-form', function(e){

        e.preventDefault();

        var url = $(this).attr('action');
		var body = $('#comment-form #body').val();
		var site_id = $('#comment-form #site_id').val();
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data:{
	            body:body,
	            site_id:site_id
            }
        })
        .done(function(data){
	        if(data !== null && typeof data === 'object'){
		        var newcomment = '<div class="media" style="display:none" id="' + data.id +'">';
		        if(data.author.avatar){
			        newcomment += '<img class="mr-3" src="/placeholder.jpg">';
		        }else{
			        newcomment += '<i class="fa fa-user-circle fa-3x mr-3 text-secondary"></i>';
		        }
		        newcomment += '<div class="media-body"><h5 class="mt-0">' + data.author.first_name + '</h5>' + data.body + '<p class="text-muted">' + data.timeago + '</p></div></div>';
		        $('#sitecomments #error').html("").hide();
		    	$(newcomment).prependTo('#sitecomments #modal-content').hide().slideDown();
		    	$('#sitecomments #modal-content #' + data.id).fadeIn(1000);
		    	$('#comment-form #body').val("");
		    }
        })
        .fail(function(){
	        $('#sitecomments #error').html('<i class="fa fa-exclamation-triangle"></i> Something went wrong, Please try again...');
	        $('#sitecomments #error').show();
        });

    });

});
	
</script>
@append