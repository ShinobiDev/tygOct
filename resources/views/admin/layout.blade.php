<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{asset('adminLte/bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

  @stack('styles')
  <link rel="stylesheet" href="{{asset('adminLte/plugins/datatables/dataTables.bootstrap.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('adminLte/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="{{asset('adminLte/css/skins/skin-blue.min.css')}}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!--<span class="logo-mini">{{-- config('app.name') --}}</span>-->
      <!-- logo for regular state and mobile devices -->
      <!--<span class="logo-lg">{{-- config('app.name') --}}</span>-->
      <img src="{{ asset('img/logo.png') }}">
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{asset('img/imgUsu.png')}}" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{asset('img/imgUsu.png')}}" class="img-circle" alt="User Image">

                <p>
                  {{ auth()->user()->name }}
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <form method="POST" action="{{route('logout')}}">
                   {{csrf_field()}}
                    <!--<a href="#" class="btn btn-default btn-flat">Cerrar Sesión</a>-->

                     <button>Cerrar sesión</button>
                  </form>


                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Datos del Usuario Logueado ....................................................-->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('img/imgUsu.png')}}" class="img-circle" alt="User Image" style="border-radius: 20px">
        </div>
        <div class="pull-left info">
          <p>{{ auth()->user()->name }}</p>
          <!-- Status -->
        </div>
      </div>



      <!-- Menu de Opciones ...............................................................-->
      <ul class="sidebar-menu">
        <!--<li class="header">Administración</li>-->
        <!-- Optionally, you can add icons to the links -->
        {{--<li class="active"><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>
        <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>--}}
        <li class="treeview">
          <a href="{{ route('usuarios.cambioContraseña') }}"><i class="fa fa-lock"></i> <span>Cambiar contraseña</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-clone"></i> <span>Ordenes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            </li>
            @if(auth()->user()->rol_id == 2 || auth()->user()->rol_id == 8)
            <li>
              <a href="{{ route('ordenes.misAsignadas') }}"><i class="fa fa-pencil"></i>Mis Asignadas</a></li>
            @endif
            @if(auth()->user()->rol_id == 3 || auth()->user()->rol_id == 4 || auth()->user()->rol_id == 5 || auth()->user()->rol_id == 6 || auth()->user()->rol_id == 7)
                
                @if(auth()->user()->estado_id == 1 || auth()->user()->estado_id == 3)
                {
                  <li><a href="{{ route('ordenes.misOrdenes') }}"><i class="fa fa-user"></i>Mis Ordenes</a>
                  <li><a href="{{ route('ordenes.crear') }}"><i class="fa fa-pencil"></i>Crear Orden</a></li>
                }@else{
                    <li><a href="{{ route('ordenes.misOrdenes') }}"><i class="fa fa-user"></i>Mis Ordenes</a>
                }
                @endif
                
            @endif
            @if(auth()->user()->rol_id == 1)
            <li><a href="{{ route('ordenes.index') }}"><i class="fa fa-pencil"></i>Ver Ordenes</a></li>
            <li><a href="{{ route('ordenes.sinAsignar') }}"><i class="fa fa-eye"></i>Sin Asignar</a></li>
            <li><a href="{{ route('ordenes.asignadas') }}"><i class="fa fa-eye"></i>Asignadas</a></li>
            <li><a href="{{ route('ordenes.negociadas') }}"><i class="fa fa-eye"></i>Negociadas</a></li>
            @endif


          </ul>
        </li>
        @if(auth()->user()->rol_id == 3 || auth()->user()->rol_id == 4 || auth()->user()->rol_id == 5)
        <li class="treeview">
          <a href="#"><i class="fa fa-building"></i> <span>Sedes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('sedes.crear') }}"><i class="fa fa-pencil"></i>Crear sede</a></li>
            <li><a href="{{ route('sedes.index') }}"><i class="fa fa-eye"></i>Ver Sedes</a></li>
          </ul>
        </li>
        @endif
        @if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 4 || auth()->user()->rol_id == 5 || auth()->user()->rol_id == 6)
        <li class="treeview">
          <a href="#"><i class="fa fa-user"></i> <span>Usuarios</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(auth()->user()->rol_id == 1)
              <li><a href="{{ route('usuarios.index') }}"><i class="fa fa-eye"></i> Ver Usuarios</a></li>
              <li><a href="{{ route('usuarios.crear') }}"><i class="fa fa-pencil"></i> Crear Usuario</a></li>
              <li><a href="{{ route('usuarios.activar') }}"><i class="fa fa-pencil"></i> Activar Usuario</a></li>
            @endif
            @if(auth()->user()->rol_id == 4 || auth()->user()->rol_id == 6)
              <li><a href="{{ route('usuarios.indexCliente') }}"><i class="fa fa-eye"></i> Ver Usuarios</a></li>
            @endif
            @if(auth()->user()->rol_id == 6 || auth()->user()->rol_id == 4)
              <li><a href="{{ route('usuarios.crearCliente') }}"><i class="fa fa-pencil"></i> Crear Usuario</a></li>
              <li><a href="{{ route('usuarios.activar') }}"><i class="fa fa-pencil"></i> Activar Usuario</a></li>
            @endif
          </ul>
        </li>
        @endif
        {{--<li class="treeview">
          <a href="#"><i class="fa fa-file"></i> <span>Factura</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('facturas.misFacturas') }}"><i class="fa fa-eye"></i>Mis Facturas</a></li>
            @if(auth()->user()->rol_id == 1)
             <li></li>
              <li><a href="{{ route('facturas.index') }}"><i class="fa fa-pencil"></i>Ver Facturas</a></li>
              <li><a href="{{ route('facturas.orden') }}"><i class="fa fa-pencil"></i>Crear Facturas por Orden</a></li>
            @endif
          </ul>
        </li>--}}
        @if(auth()->user()->rol_id == 1)
        <li class="treeview">
          <a href="#"><i class="fa fa-indent"></i> <span>Variables</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('variables.index') }}"><i class="fa fa-eye"></i>Ver variables</a></li>
          </ul>
        </li>
        @endif

      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      @yield('header')
    </section>

    <!-- Main content -->
    <section class="content">
      
      @if(session()->has('flash'))
        <div class="alert alert-success">{{ session('flash') }}</div>
      @endif
      @if(session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
      <!-- Your Page Content Here -->
      @yield('contenido')
      
      @yield('totalSede')
      @yield('tres')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
          <b>Version</b> 1.3
        </div>
        <center>
            <a href="http://prismaweb.co/diseno-a-la-medida/" target="_blank" >Desarrollado por: </a>
            <a href="http://prismaweb.co/diseno-a-la-medida/" target="_blank" ><img src="http://www.prismaweb.net/img/prismaweb-footer-webs-gris.png" alt="WWW.PRISMAWEB.CO - Skype: prismaweb22" /></a>
        </center>
  </footer>
  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
@stack('scripts')
<!-- jQuery 2.2.3 -->
<script src="{{asset('adminLte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('adminLte/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('adminLte/js/app.min.js')}}"></script>
<script src="{{asset('adminLte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
      $('.js-example-basic-single').select2();
    });

  $(function (){
    /*DATATABLE*/
    $('#example1').DataTable( {
                    stateSave: true,
                    responsive: true,
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                    language:
                      {
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "Ningún dato disponible en esta tabla",
                        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix":    "",
                        "sSearch":         "Buscar:",
                        "sUrl":            "",
                        "sInfoThousands":  ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sLast":     "Último",
                            "sNext":     "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                } );
    /* FIN DATATABLE*/                                
  });
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
