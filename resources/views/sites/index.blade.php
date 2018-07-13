@extends('layouts.app')
@section('site-title')
	<div class="navbar-brand text-center">{{ $mode }} Sites</div>
	<div class="nav-link dropdown">
		<button class="btn btn-sm btn-success dropdown-toggle" type="button" id="addMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-plus"></i></button>
		<div class="dropdown-menu" aria-labelledby="addMenuButton">
			<button class="dropdown-item" data-toggle="modal" data-target="#createsite">New Site</button>
    		<button class="dropdown-item" data-toggle="modal" data-target="#importsite">Add Existing Site</button>
  		</div>
	</div>
@endsection
@section('content')
@include("partials/messages")
<!--
	<h3 class="mb-4">{{ $mode }} Sites 
		<button class="ml-2 mr-2 mr-sm-2 btn btn-success" data-toggle="modal" data-target="#createsite"><i class="fa fa-plus"></i> New Site</button>
		<button class="ml-2 mr-2 mr-sm-2 btn btn-success" data-toggle="modal" data-target="#importsite"><i class="fa fa-plus"></i> Import Existing Site</button>
	</h3>
-->
    <div class="row" id="sites-content">
	    <div class="col-12">
		    <table class="table table-striped" id="sitesTable">
			    <thead>
				    <th>Site Name</th>
				    <th>City</th>
				    <th>Version</th>
				    <th>Price Model</th>
				    <th>Rooms</th>
				    <th>Accessed</th>
				    <th>User</th>
				    <th>Actions</th>
			    </thead>
			    <tbody>
			    @foreach($sites as $site)
			    	<tr>
				    	<td>
					    	@if($mode == "Live")
					    		<a href="https://{{ $site->url }}.hospitalityhousekeeper.net" target="_blank">{!! $site->config['site']['Site_Name'] !!}</a>
					    	@else
					    		<a href="https://hospitalityhousekeeper.net/{{ $site->url }}" target="_blank">{!! $site->config['site']['Site_Name'] !!}</a>
					    	@endif
					    </td>
				    	<td>
					    	@if($site->city)
								{{ $site->city->City }}, {{ $site->city->State }}
							@else
								Zip Codes not loaded
							@endif
				    	</td>
				    	<td>{{ $site->config['code']['Version'] }} build {{ $site->config['code']['Build'] }}</td>
				    	<td>{{ $site->priceModel }}</td>
				    	<td>{{ $site->roomCount }}</td>
				    	<td>
					    	@if($site->lastAccessed)
					    		{{ $site->lastAccessed->Access_Date->setTimezone('America/Chicago')->format('M j, Y g:i a') }}
					    	@endif
				    	</td>
				    	<td>
					    	@if($site->lastAccessed)
					    		{{ $site->lastAccessed->Username }}
					    	@endif
				    	</td>
				    	<td>
					    	<div class="btn-group ml-2">
					            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionsButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>
					            <div class="dropdown-menu" aria-labelledby="actionsButton">
						            <button data-toggle="modal" data-target="#siteconfig" class="dropdown-item" id="getConfig" data-url="{{ route('sites.getConfig',['id'=>$site->id])}}">Config</button>
						            <button data-toggle="modal" data-target="#sitecomments" class="dropdown-item" id="getComments" data-url="{{ route('sites.getComments',['id'=>$site->id])}}">Comments</button>   
						        	<button data-toggle="modal" data-target="#upgradesite" class="dropdown-item" id="upgrade" data-site_id="{{ $site->id }}" data-site_name="{!! $site->config['site']['Site_Name'] !!}">Upgrade</button>
						        	@if($site->config['site']['Mode'] == "demo")
						        		<a href="{{ route('sites.setLive', $site) }}" class="dropdown-item">Set Live</a>
						        	@endif
					            </div>
				            </div>
				    	</td>
			    	</tr>
		        @endforeach
			    </tbody>
		    </table>
	    </div>
    </div>
    @include('sites.create')
    @include('sites.import')
    @include('sites.upgrade')
    @include('sites.config')
    @include('users.setpw')
    @include('sites.comments')

@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
		    var resetpw = "<?php echo Auth::user()->temp_password ?>";
		    if(resetpw){
			    $('#setpw').modal('show');
		    }
		    
			$('#sitesTable').DataTable({
				"pageLength": 25
			});

	    });
	</script>
@append