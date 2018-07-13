@extends('layouts.app')

@section('content')
    <div class="row">
	    <div class="col-12">
		    @include("partials/messages")
	        <div class="card">
		        <div class="card-header">
			        Users <a href="#" class="ml-2 mr-2 mr-sm-2 btn btn-success" data-toggle="modal" data-target="#createuser"><i class="fa fa-plus"></i> New User</a>
		        </div>
	            <div class="card-body">
		            <div class="table-responsive">
			            <table class="table table-bordered" width="100%" cellspacing="0">
				            <thead>
					            <tr>
						            <th>Name</th>
						            <th>Email</th>
						            <th>Roles</th>
						            <th>Actions</th>
					            </tr>
				            </thead>
				            <tbody>
					        @if(count($users) > 0)
							@foreach($users as $user)
							<tr>
								<td>{{ $user->name }}</td>
								<td>{{ $user->email }}</td>
								<td>@foreach($user->roles as $role)
									<p class="m-0">{{ $role->name }}</p>
									@endforeach
								</td>
								<td></td>
							</tr>
							@endforeach
							@else
							<tr>
								<td colspan="5" class="text-center">No Users found</td>
							</tr>
							@endif
				            </tbody>
			            </table>
		            </div>
	            </div>
	        </div>
	    </div>
    </div>
    @include('users.create')
@endsection