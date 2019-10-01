@extends('admin.layout')


@section('contenido')
	<div class="box box-success">
	    <div class="box-header">
	      <h3 class="box-title">Detalle de las Variables</h3>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	      <table id="sedes-table" class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	        		<th>Id</th>
	        		<th>Nombre</th>
	        		<th>Valor</th>
	        	</tr>
	        </thead>
	        
	        <tbody>
	        	@foreach($variables as $variable)	
	        		<tr>

	        			<td>{{ $variable->id }}</td>
	        			<td>{{ $variable->nombre }}</td>
	        			<td>{{ $variable->valor }}</td>
	        			<td>
		        			<button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#exampleModal{{$variable->id}}">Editar</button>
		        		</td>
	        		
	        		<div class="modal fade" id="exampleModal{{$variable->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<form method="POST" action="{{ route('trabajos.variables.update',$variable->id) }}">
							    	{{ csrf_field() }}
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <h5 class="modal-title" id="exampleModalLabel">{{$variable->nombre}}</h5>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						      <div class="modal-body">
						        <div class="form-group">
					    			<label>Nuevo Valor</label>
					    			<input name="nuevoValor" class="form-control" placeholder="{{ $variable->valor }}" required></input>
					    		</div>
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						        <button type="submit" class="btn btn-success">Editar Valor</button>
						      </div>
						    </div>
						</form>
					  </div>
					</tr>  
	        	@endforeach
	        	
	        </tbody>
	      </table>
	    </div>
	    <!-- /.box-body -->
	  </div>

	  <!-- Modal -->
	
	</div>	
@stop