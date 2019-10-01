@extends('admin.layout')

@section('header')
	<h1>
	    Ordenes asignadas
	    <small> En esta sección estan las ordenes de Santiago y Tita.</small>
  	</h1>
  	<ol class="breadcrumb">
	    <li class="active">Ordenes</li>
  	</ol>
@stop

@section('contenido')
	<div class="box box-primary">
	    <div class="box-header">
	      <h3 class="box-title">Detalle de las Ordenes</h3>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	      	<table id="sedes-table" class="table table-bordered table-striped">
		        <thead>
		        	<tr>
		        		<th>Id</th>
		        		<th>Estado</th>
		        		<th>Cliente</th>
		        		<th>fecha creación</th>
		        	</tr>
		        </thead>
		        
		        <tbody>
		        	@foreach($ordenAsignadas as $orden)	
		        		<tr>
		        			<td>{{ $orden->id }}</td>
		        			@if($orden->nombreEstado == 'Por Cotizar Asignado' || $orden->nombreEstado == 'Por Cotizar Sin Asignar')
	        				<td>Por Cotizar</td>
	        			@elseif($orden->nombreEstado == 'Cotizado Sin Asignar' || $orden->nombreEstado == 'Cotizado Asignado')
        				 	<td>Cotizado</td>
    				 	@elseif($orden->estado_id == 9)
        				 	<td>Orden</td>
	        			@endif
		        			<td>{{ $orden->name }}</td>
		        			<td>{{ $orden->created_at }}</td>
		        			<td>
		        				@if($orden->estado_id == 3)
		        					<a href="{{ route('detalle.asignadas', $orden->id) }}" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Ver Detalle</a>
		        				@elseif($orden->estado_id == 9)
		        					<a href="{{ route('detalle.asignadasOrden', $orden->id) }}" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Ver Detalle</a>
		        				@endif
			        			
			        		</td>
		        		</tr>
		        	@endforeach		        	
		        </tbody>
	      	</table>
	    </div>
	    <!-- /.box-body -->
  	</div>	
@stop