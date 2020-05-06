<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Bootstrap core CSS-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <!-- Custom fonts for this template-->
  <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <!-- Custom styles for this template-->
  <link href="/css/sb-admin.css" rel="stylesheet">
  <link href="/css/app.css" rel="stylesheet">
  <link href="/css/select2-bootstrap4.min.css" rel="stylesheet">
  <link href="/css/dataTables.bootstrap4.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark sidenav-toggled" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="/">{{ config('app.name', 'Laravel') }}</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Sites">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseSites" data-parent="#sitesAccordion" aria-expanded="false">
            <i class="fa fa-fw fa-globe"></i>
            <span class="nav-link-text">Sites</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseSites" style="">
            <li class="{{ active([route('sites.live'), route('home')]) }}">
              <a href="{{ route('sites.live') }}">
	              <i class="fa fa-fw fa-globe"></i>
	              <span class="nav-link-text">Live</span>
	          </a>
            </li>
            <li class="{{ active(route('sites.demo')) }}">
              <a href="{{ route('sites.demo') }}">
	              <i class="fa fa-wrench"></i>
	              <span class="nav-link-text">Demo</span>
	          </a>
            </li>
            <li class="{{ active(route('sites.other')) }}">
              <a href="{{ route('sites.other') }}">
	              <i class="fa fa-exclamation-triangle"></i>
	              <span class="nav-link-text">Other</span>
	          </a>
            </li>
          </ul>
        </li>
        @if(Auth::user()->hasAnyRole("Admin"))
        <li class="nav-item {{ active(route('versions.index')) }}" data-toggle="tooltip" data-placement="right" title="Versions">
          <a class="nav-link" href="{{ route('versions.index') }}">
            <i class="fa fa-fw fa-code-fork"></i>
            <span class="nav-link-text">HHK Versions</span>
          </a>
        </li>
        <li class="nav-item {{ active(route('users.index')) }}" data-toggle="tooltip" data-placement="right" title="Users">
          <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fa fa-fw fa-users"></i>
            <span class="nav-link-text">Users</span>
          </a>
        </li>
        @endif
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <div class="navbar-text mr-auto">
      </div>
      <div class="navbar-nav m-auto">
	      @yield('site-title')
      </div>
      <ul class="navbar-nav ml-auto">
        				<!-- Authentication Links -->
                        @guest
                        @else
                        	
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa fa-user mr-1"></i>
                                    {{ Auth::user()->first_name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest

      </ul>
    </div>
  </nav>
  <div class="content-wrapper">
    <div class="container-fluid">

@yield('content')


      </div>

    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © {{ date('Y') }} Nonprofit Software Corporation</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>
        
<!-- Bootstrap core JavaScript-->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>    <!-- Page level plugin JavaScript-->
    <script src="/js/datatables/jquery.dataTables.js"></script>
    <script src="/js/datatables/dataTables.bootstrap4.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.16/sorting/datetime-moment.js"></script>
    <script src="/js/sb-admin-datatables.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script type="text/javascript">
	    $.fn.select2.defaults.set( "theme", "bootstrap4" );
	    $.fn.dataTable.moment( 'MMM D, YYYY h:mm a' );	    
    </script>
    
    @yield('scripts')
    
  </div>
</body>

</html>