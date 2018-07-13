<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Bootstrap core CSS-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="/css/sb-admin.css" rel="stylesheet">
</head>

<body class="bg-dark">
  <div class="container">
	  <div class="text-center login-title mt-5">
		  <h1>NPSeCure</h1>
	  </div>
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Two Factor Verification</div>
      <div class="card-body">
	      @include('partials/messages')
                    <form role="form" method="POST" action="/2fa">
{{ csrf_field() }}
<div class="form-group">
	<div class="text-center">Check your email for a Two Factor PIN code</div>
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
<input id="2fa" type="text" class="form-control" name="2fa" placeholder="Two Factor PIN" required autofocus>
@if ($errors->has('2fa'))
<span class="help-block">
<strong>{{ $errors->first('2fa') }}</strong>
</span>
@endif
</div>
<div class="form-group">
<button class="btn btn-primary btn-block" type="submit">Login</button>
</div>
</form>


        <div class="text-center">
          <a class="d-block small" href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
