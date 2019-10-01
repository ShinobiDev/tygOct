<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name') }} | Ingreso</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="adminLte/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="adminLte/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="adminLte/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
    @if(session()->has('flash'))
        <div class="alert alert-success">{{ session('flash') }}</div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="box">
        <div class="login-logo">
            <img src="{{asset('img/imgUsu.png')}}">
        </div>
        <!--Formulario para Registrar persona natural-->
        <div class="box-body col-md-6 col-md-offset-3">
            
            <p class="login-box-msg">Registrar un nuevo usuario</p>
            <form method="post" action="{{ route('usuarios.almacenarNuevoUsuarioCliente')}}">
                {{ csrf_field() }}
                <div class="form-group col-md-4 col-md-offset-4">
                    <select class="form-control" name="tipoCLiente" onchange="tipoCliente(this)">
                        <option value="1">Persona Natural</option>
                        <option value="2">Persona Juridica</option>
                    </select>        
                </div>
                <div class="form-group natural col-md-6">
                    <input type="text" class="form-control natural" placeholder="Nombre" name="nombre">
                </div>
                <div class="form-group juridica col-md-6" style="display: none">
                    <input type="text" class="form-control" placeholder="Razón Social" name="nombreJuridico">
                </div>
                <div class="form-group natural col-md-6 natural">
                    <input type="text" class="form-control" name="documento" placeholder="Numero de documento">
                </div>
                <div class="form-group juridica col-md-6" style="display: none">
                    <input type="text" class="form-control" name="documentoJuridico" placeholder="Nit">
                </div>
                <div class="form-group col-md-6">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="pais" placeholder="Pais" required>
                </div>
                
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="ciudad" placeholder="Ciudad - Provincia" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="direccion" placeholder="Direccion" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="telefono" placeholder="Telefono" required>
                </div>
                <div class="form-group col-md-6 juridica" style="display: none">
                    <input type="text" class="form-control" name="nombreRepresentante" placeholder="Nombre Representante en T&G">
                </div>
                {{--<div class="col-md-5 col-md-offset-1">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Acepto los <a href="#">termino y condiciones</a>
                        </label>
                    </div>
                </div>--}}
                <!-- /.col -->
                <div class="form-group col-md-3 col-md-offset-5">
                    <button type="submit" class="btn btn-success btn-block btn-flat">Registrarse</button>
                </div>
                <!-- /.col -->
                <div class="form-group col-md-4 col-md-offset-5">
                    <a href="{{ route('login') }}" class="text-center">Ya tengo una cuenta</a>
                </div>
                
            </form>        
        </div>
    </div>
</body>
<script type="text/javascript">
  function tipoCliente(variableTipoCliente){
    if(variableTipoCliente.value == 1)
    {
      var variable = document.querySelectorAll('.natural');
      var variableA = document.querySelectorAll('.juridica');
      for(var e in variable)
      {
        if(variable[e].style != undefined)
        {
          variable[e].style.display = '';
        }        
      }
      for(var e in variableA)
      {
        if(variableA[e].style != undefined)
        {
          variableA[e].style.display = 'none';
        }
        
      }
      
    }
    if(variableTipoCliente.value == 2)
    {
      var variable = document.querySelectorAll('.juridica');
      var variableA = document.querySelectorAll('.natural');
      for(var e in variable)
      {
        if(variable[e].style != undefined)
        {
          variable[e].style.display = '';
          
        }
      }
      for(var e in variableA)
      {
        if(variableA[e].style != undefined)
        {
          
          variableA[e].style.display = 'none';
        }
      }
    }
  }    
</script>