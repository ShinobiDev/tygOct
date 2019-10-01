@extends('admin.layout')
@section('header')
	<h1>
	    Ordenes
	    <small> En esta sección se puede ver a detalle las ordenes</small>
  	</h1>
  	<ol class="breadcrumb">
	    <li class="active">Ordenes</li>
  	</ol>
@stop

@section('contenido')
	<div class="box box-success">
	    <div class="box-header">
	      <h3 class="box-title">Detalle de las ordenes</h3>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	      <table id="sedes-table" class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	        		<th>id</th>
	        		<th>Cliente</th>
	        		<th>Estado</th>
	        		<th>Convención</th>
	        		<th>TRM de la fecha</th>
	        		<th>Fecha Creación</th>
	        	</tr>
	        </thead>
	        
	        <tbody>
	        	@foreach($ordenes as $orden)	
	        		<tr>
	        			<td>{{ $orden->id }}</td>
	        			<td>{{ $orden->name }}</td>
	        			<td>{{ $orden->nombre }}</td>
	        			<td>{{ $orden->nombreConvencion }}</td>
	        			<td>{{ $orden->Trm }}</td>
	        			<td>{{ $orden->created_at }}</td>
	        			<td>
	        				
	        				<a href="{{ route('facturas.detalleFactura', $orden->id) }}" class="btn btn-xs btn-success"><i class="fa fa-eye"> Ver Detalle</i></a>
	        			</td>
	        		</tr>
	        	@endforeach
	        	
	        </tbody>
	      </table>
	    </div>
	    <!-- /.box-body -->
	  </div>	
@stop