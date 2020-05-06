@extends('layouts.app')
@section('site-title')
	<div class="navbar-brand text-center">Reports</div>
@endsection
@section('content')
@include("partials/messages")



	<div class="row justify-content-center">
	    <div class="col-10">
		    <div class="card">
				<div class="card-body">
					<div class="row mb-0">
						<div class="col-xs-12 col-sm">
							 <h3>18</h3>
							 <h6 class="text-muted">Houses</h6>
						</div>
						<div class="col-xs-12 col-sm">
							<h3>1516</h3>
							<h6 class="text-muted">Visits</h6>
						</div>
						<div class="col-xs-12 col-sm">
							<h3>652</h3>
							<h6 class="text-muted">Stays</h6>
						</div>
						<div class="col-xs-12 col-sm">
							<h3>19504</h3>
							<h6 class="text-muted">Guests</h6>
						</div>
					</div>
					<div class="row mb-0">
						<div class="col-xs-12 col-sm">
							<h3>192590</h3>
							<h6 class="text-muted">Available Nights</h6>
						</div>
						<div class="col-xs-12 col-sm">
							<h3>192590</h3>
							<h6 class="text-muted">Unavailable Nights</h6>
						</div>
						<div class="col-xs-12 col-sm">
							<h3>192590</h3>
							<h6 class="text-muted">Confirmed Reservations</h6>
						</div>
						<div class="col-xs-12 col-sm">
							<h3>192590</h3>
							<h6 class="text-muted">Waitlisted Reservations</h6>
						</div>
					</div>
				</div>
		    </div>
	    </div>
    </div>
    
@endsection