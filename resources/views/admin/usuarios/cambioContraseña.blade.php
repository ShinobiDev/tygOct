@extends('admin.layout')
@section('header')
	<h1>
	    Usuario
	    <small> En esta sección podra actualizar su contraseña de acceso</small>
  	</h1>
  	<ol class="breadcrumb">
	    <li class="active">Cambio Contraseña</li>
  	</ol>
@stop

@section('contenido')

	<div class="row">
		<div class="col-md-6">
			<div class="box box-warning">
			    <div class="box-header bg-warning">
			      <h3 class="box-title">Ingrese los datos para el cambio de contraseña</h3>
			    </div>
			    <!-- /.box-header -->
			    <form method="POST" action="{{ route('usuarios.actualizarContraseña') }}">
			    	{{ csrf_field() }}
			    	<div class="box-body">
			    		<div class="form-group">
			    			<label>Nueva contraseña</label>
			    			<input type="password" minlength="8" maxlength="40" name="password" class="form-control" placeholder="Nueva Contraseña" required></input>
			    		</div>
			    		<div class="form-group">
			    			<label>Confirme la contraseña</label>
			    			<input type="password" name="passwordA" class="form-control" placeholder="Confirma Contraseña" required></input>
			    		</div>
			    		<div class="form-group">
			    			<button type="submit" class="btn btn-success">Cambiar la Contraseña</button>
			    		</div>

			    	</div>
			    </form>
			    
			</div>
			
		</div>
	</div>
	
@stop