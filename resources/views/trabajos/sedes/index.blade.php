@extends('admin.layout')
@section('header')
	<h1>
	    Sedes
	    <small> Detalle de las sedes</small>
  	</h1>
@stop

@section('contenido')
	<div class="box box-success">
	    <div class="box-header">
	      <h3 class="box-title">Detalle de las sedes</h3>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	      <table id="sedes-table" class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	        		<th>Nombre</th>
	        		<th>Pais</th>
	        		<th>Estado / Departamento</th>
	        		<th>Ciudad</th>
	        		<th>Dirección</th>
	        		<th>Telefono</th>
	        		<th>Contacto</th>
	        		<th>Acciones</th>
	        	</tr>
	        </thead>
	        
	        <tbody>
	        	@foreach($sedes as $sede)	
	        		<tr>
	        			<td>{{ $sede->nombre }}</td>
	        			<td>{{ $sede->pais }}</td>
	        			<td>{{ $sede->estado }}</td>
	        			<td>{{ $sede->ciudad }}</td>
	        			<td>{{ $sede->direccion }}</td>
	        			<td>{{ $sede->telefono }}</td>
	        			<td>{{ $sede->contactoSede }}</td>
	        			<td>
		        			<button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#exampleModal{{$sede->id}}">Editar</button>
		        			
		        			<div class="modal fade" id="exampleModal{{$sede->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

								<form method="POST" action="{{ route('trabajos.sedes.update',[$sede->id]) }}">
						    	{{ csrf_field() }}
							    	<div class="modal-dialog" role="document">

									    <div class="modal-content">

									      <div class="modal-header">
									        <h5 class="modal-title" id="exampleModalLabel">{{$sede->nombre}}</h5>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>

									      <div class="modal-body">

								        	<div class="form-group">
								    			<label>Nombre</label>
								    			<input name="nombre" class="form-control" value="{{ $sede->nombre }}" required></input>
								    		</div>
								    		<div class="form-group">
								    			<label>Dirección</label>
								    			<input name="direccion" class="form-control" value="{{ $sede->direccion }}" required></input>
								    		</div>
								    		<div class="form-group">
								    			<label>Telefono</label>
								    			<input name="telefono" class="form-control" value="{{ $sede->telefono }}" required></input>
								    		</div>
								    		<div class="form-group">
								    			<label>Nombre Contacto</label>
								    			<input name="contacto" class="form-control" value="{{ $sede->contactoSede }}" required></input>
								    		</div>

									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
									        <button type="submit" class="btn btn-success">Editar Sede</button>
									      </div>
									    </div>
									</div>								
								</form>
					  		</div>
		        		</td>

		        		
	        		</tr>
	        	@endforeach
	        	
	        </tbody>
	      </table>
	    </div>
	    <!-- /.box-body -->
	  </div>	
@stop