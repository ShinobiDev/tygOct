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
	        				
	        				<a href="{{ route('facturas.crearFacturaOrden', $orden->id) }}" class="btn btn-xs btn-success"><i class="fa fa-pencil"> Crear Factura</i></a>
	        			</td>
	        			<!---<td>
		        			<button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#exampleModal{{--$orden->id}}">Editar</button>
		        			<a href="" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
		        			<div class="modal fade" id="exampleModal{{$orden->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

								<form method="POST" action="{{-- route('trabajos.sedes.update',[$sede->id]) --}}">
						    	{{-- csrf_field() }}
							    	<div class="modal-dialog" role="document">

									    <div class="modal-content">

									      <div class="modal-header">
									        <h5 class="modal-title" id="exampleModalLabel">{{$orden->id--}}</h5>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>

									      <div class="modal-body">

								        	

									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
									        <button type="submit" class="btn btn-success">Editar Orden</button>
									      </div>
									    </div>
									</div>								
								</form>
					  		</div>
		        		</td>-->

		        		
	        		</tr>
	        	@endforeach
	        	
	        </tbody>
	      </table>
	    </div>
	    <!-- /.box-body -->
	  </div>	
@stop