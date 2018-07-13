@extends('layouts.app')

@section('content')
@include("partials/messages")
	<h1 class="mb-4">Sites 
		<button class="ml-2 mr-2 mr-sm-2 btn btn-success" data-toggle="modal" data-target="#createsite"><i class="fa fa-plus"></i> New Site</button>
		<button class="ml-2 mr-2 mr-sm-2 btn btn-success" data-toggle="modal" data-target="#importsite"><i class="fa fa-plus"></i> Import Existing Site</button>
	</h1>
	<div class="row">
	    <h3 class="col-12 text-center">Live</h3>
	    @foreach($sites as $site)
		    @if($site->config['site']['Mode'] == 'live')
	        <div class="col-md-4 mb-4">
	            <div class="card">
	                <div class="card-body">
	                    <h2>{{ $site->name }}</h2>
	                    <p>Version: {{ $site->config['code']['Version'] }}</p>
	                    <p>Reservations: {{ ($site->sysconfig->get('Reservation')->Value == 'true' ? "enabled" : "disabled") }}</p>
	                </div>
	                <div class="card-footer text-right">
		                @if($site->url)
		                	<a href="https://hospitalityhousekeeper.net/{{ $site->url }}" target="_blank" class="btn btn-primary">Visit Site</a>
		                @endif
		                <button data-toggle="modal" data-target="#siteconfig" class="btn btn-secondary ml-2" id="getConfig" data-url="{{ route('sites.getConfig',['id'=>$site->id])}}">Config</button>
		                <button data-toggle="modal" data-target="#sitecomments" class="btn btn-secondary ml-2" id="getComments" data-url="{{ route('sites.getComments',['id'=>$site->id])}}">Comments</button>
		                <button disabled data-toggle="modal" data-target="#upgradesite" class="btn btn-secondary ml-2" id="upgrade" data-site_id="{{ $site->id }}">Upgrade</button>
	                </div>
	            </div>
	        </div>
	        @endif
        @endforeach
    </div>
    <div class="row">
	    <h3 class="col-12 text-center">Demos</h3>
	    @foreach($sites as $site)
		    @if($site->config['site']['Mode'] == 'demo')
	        <div class="col-md-4 mb-4">
	            <div class="card">
	                <div class="card-body">
	                    <h2>{{ $site->name }}</h2>
	                    <p>Version: {{ $site->config['code']['Version'] }}</p>
	                    <p>Reservations: {{ ($site->sysconfig->get('Reservation')->Value == 'true' ? "enabled" : "disabled") }}</p>
	                </div>
	                <div class="card-footer text-right">
		                @if($site->url)
		                	<a href="https://hospitalityhousekeeper.net/{{ $site->url }}" target="_blank" class="btn btn-primary">Visit Site</a>
		                @endif
		                <button data-toggle="modal" data-target="#siteconfig" class="btn btn-secondary ml-2" id="getConfig" data-url="{{ route('sites.getConfig',['id'=>$site->id])}}">Config</button>
		                <button data-toggle="modal" data-target="#sitecomments" class="btn btn-secondary ml-2" id="getComments" data-url="{{ route('sites.getComments',['id'=>$site->id])}}">Comments</button>
		                <button disabled data-toggle="modal" data-target="#upgradesite" class="btn btn-secondary ml-2" id="upgrade" data-site_id="{{ $site->id }}">Upgrade</button>
	                </div>
	            </div>
	        </div>
	        @endif
        @endforeach
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
	    });
	</script>
@append