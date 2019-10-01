@extends('admin.layout')
@section('contenido')
	<div class="box">
	    <div class="box-header">
	      @php
	       		
	    		if($detalleOrden[0]->estado_id == 2 || $detalleOrden[0]->estado_id == 3){
	    			$nombreEstado = "Por Cotizar";	
	    		}
	    		if($detalleOrden[0]->estado_id == 4){
	    			$nombreEstado = "Cotizado";	
	    		}
	    		if($detalleOrden[0]->estado_id == 8){
	    			$nombreEstado = "Orden";	
	    		}
	    		if($detalleOrden[0]->estado_id == 15){
	    			$nombreEstado = "En Negociación";	
	    		}	    		
	    	@endphp
	    	<h3 class="box-title">Detalles de la Orden <b>{{$detalleOrden[0]->orden_id}}</b> - Estado <b>{{$nombreEstado}}</b>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	      <table id="sedes-table" class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	        		<th>Sede</th>
	        		<th>Marca</th>
	        		<th>Referencia</th>
	        		<th>Descripción</th>
	        		<th>Cantidad</th>
	        		<th>Comentarios</th>
	        		
	        	</tr>
	        </thead>
	        
	        <tbody>
	        	@foreach($detalleOrden as $detalle)	
	        		<tr>
	        			<td>{{ $detalle->nombre }}</td>
	        			<td>{{ $detalle->marca }}</td>
	        			<td>{{ $detalle->referencia }}</td>
	        			<td>{{ $detalle->descripcion }}</td>
	        			<td>{{ $detalle->cantidad }}</td>
	        			<td>{{ $detalle->comentarios }}</td>
	        			
		        		
	        		</tr>
	        	@endforeach
	        	
	        </tbody>
	      </table>
	      @if(auth()->user()->rol_id == 1)
	      <!--Seccion solo para el Administrador-->
	      <!--Asignar Usuario para gestionar la orden-->
	      	<form method="POST" action="{{ route('ordenes.asignarUsuarioOrden') }}" class="form">
		    	{{ csrf_field() }}
		    	<input type="hidden" name="ordenId" value="{{ $detalleOrden[0]->orden_id }}">
		    	<div class="form-group col-md-4">
	    			<label>Seleccione el usuario para gestionar la orden</label>
	    		</div>
		      	<div class="form-group col-md-4">
		      		<select class="form-control" name="usuarioAsignado" id="usuarioAsignado" required>
		      			<option value="">Selecciona un Usuario</option>
						
						@foreach($user as $user)
							@if($user->rol_id == 2 || $user->rol_id == 8)
								<option value="{{ $user->id }}"> {{ $user->name }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-3">
	    			<input type="submit" class="btn btn-danger" value="Asignar Usuario">
	    		</div>				
			</form>
			<!---->
			@endif
	    </div>
	    <!-- /.box-body -->
	  </div>
@stop