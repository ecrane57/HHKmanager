@extends('layouts.app')

@section('content')
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
	                <i class="fa fa-exclamation-triangle fa-2x mr-5" aria-hidden="true"></i>
	                    @if( $exception->getMessage() )
		                    {{ $exception->getMessage() }}
	                    @else
		                    You do not have the correct permissions to view this page.
	                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
