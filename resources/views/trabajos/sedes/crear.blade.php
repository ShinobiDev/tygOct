@extends('admin.layout')
@section('header')
	<h1>
	    Sedes
	    <small> En esta sección podra crear las sedes</small>
  	</h1>
  	<ol class="breadcrumb">
	    <li class="active">Sedes</li>
  	</ol>
@stop

@section('contenido')

	<div class="row">
		<div class="col-md-9">
			<div class="box box-success">
			    <div class="box-header bg-success">
			      <h3 class="box-title">Ingrese los datos para crear una nueva sede</h3>
			    </div>
			    <!-- /.box-header -->
			    <form method="POST" action="{{ route('trabajos.sedes.almacenar') }}">
			    	{{ csrf_field() }}
			    	<div class="box-body">
			    		<div class="form-group col-md-4">
			    			<label>Nombre de la Sede</label>
			    			<input name="nombre" class="form-control" placeholder="Ingrese el nombre de la Sede" required></input>
			    		</div>
			    		<div class="form-group col-md-4">
			    			<label>Pais de la Sede</label>
			    			<input name="pais" class="form-control" placeholder="Ingrese el pais de la Sede" required></input>
			    		</div>
			    		<div class="form-group col-md-4">
			    			<label>Estado o Departamento de la Sede</label>
			    			<input name="estado" class="form-control" placeholder="Ingrese el estado o departamento de la Sede" required></input>
			    		</div>
			    		<div class="form-group col-md-4">
			    			<label>Ciudad de la Sede</label>
			    			<input name="ciudad" class="form-control" placeholder="Ingrese la ciudad de la Sede" required></input>
			    		</div>
			    		<div class="form-group col-md-4">
			    			<label>Dirección de la Sede</label>
			    			<input name="direccion" class="form-control" placeholder="Ingrese la dirección de la Sede" required></input>
			    		</div>
			    		<div class="form-group col-md-4">
			    			<label>Telefono de la Sede</label>
			    			<input type="number" name="telefono" class="form-control" placeholder="Ingrese el telefono de la Sede" required></input>
			    		</div>
			    		<div class="form-group col-md-4">
			    			<label>Nombre del contacto de la Sede</label>
			    			<input name="contacto" class="form-control" placeholder="Ingrese el nombre del contacto" required></input>
			    		</div>
			    		<div class="form-group">
			    			<button type="submit" class="btn btn-success">Crear Sede</button>
			    		</div>

			    	</div>
			    </form>
			    
			</div>
			
		</div>
	</div>
	
@stop