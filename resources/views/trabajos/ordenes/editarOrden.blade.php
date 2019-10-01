@extends('admin.layout')

@section('contenido')
	<div class="box box-warning">
	    <div class="box-header col-md-12">
	      <h3 class="box-title">Detalles de la Orden - <b>{{$orden_id}}</b></h3>
	    </div>
	    <!-- /.box-header -->

	    <form class="form" method="POST" action="{{ route('ordenes.actualizarEdicion') }}">
		{{ csrf_field() }}
	    <div class="box-body table-responsive col-md-12 bg-warning">
	    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
		      <table id="example1" class="table table-bordered table-striped table-hover">
		        <thead style="overflow-y: hidden;">
		        	<tr class="bg-warning">
		        		<th>Item</th>
		        		<th>Id Item</th>
		        		<th>Sede</th>
		        		<th>Marca</th>
		        		<th>Referencia</th>
		        		<th>Descripción</th>
		        		<th>Cantidad</th>
		        		<th>Comentarios</th>
		        		<th></th>
		        		
		        		
		        		<!--<th>Venta Unitario</th>
		        		<th>Total USD</th>
		        		<th>Entrega Proveedor</th>
		        		<th>Bodega</th>
		        		<th>Recepción Bodega</th>
		        		<th>Diás Reales Entrega</th>-->
		        	</tr>
		        </thead>
		        
		        <tbody id="detalle">
		        			@php
		        				$item = 0;
		        			@endphp
			        	@foreach($detalleOrden as $detalle)
			        		@php			        			
			        			$item++;
			        		@endphp	
			        		<tr>
			        			<td>
			        				{{ $item }}
			        			</td>
			        			<td>{{ $detalle->id }}</td>
			        			<td>
			        				<select name="sede_id[]" class="form-group">
			        					@foreach($sede as $s)
			        						@if($s->id == $detalle->sede_id)
			        							<option value="{{ $s->id }}" selected>{{ $s->nombre }}</option>
		        							@else
		        								<option value="{{ $s->id }}">{{ $s->nombre }}</option>
			        						@endif
			        					@endforeach	
		        					</select>
			        				{{--<input name="sede[]" value="{{ $detalle->nombre }}">
			        				<input type="hidden" name="sedeId[]" value="{{ $detalle->sede_id }}">--}}
			        			</td>
			        			<td>
			        				<label>{{ $detalle->marca }}"</label>
			        			</td>
			        			<td>
			        				<label>{{ $detalle->referencia }}</label>
			        			</td>
			        			<td>
			        				<label> {{  $detalle->descripcion }}</label>
			        				
			        			</td>
			        			<td>
			       	 				<input id="cantidad{{ $detalle->id }}" name="cantidad[]" value="{{ $detalle->cantidad }}">
			        			</td>
			        			<td>
			        				<input name="comentarios[]" value="{{ $detalle->comentarios }}">
			        			</td>
			        			
			        			<td><input type="hidden" name="detalle_id[]" value="{{$detalle->id}}"></td>
			        		</tr>
			        	@endforeach
		        	
		        </tbody>
		      	</table>
	      <!--Seccion solo para el Administrador-->
	      <!--Asignar Usuario para gestionar la orden-->
	      	<hr>
      	</div>
		<div class="form-group col-md-offset-3">
    		<button type="submit" class="btn btn-primary col-md-3">Actualizar la Orden</button>
    	</div>
    	</form>
    	<!--Actualizar la orden a cotizado y enviar los datos al cliente-->
    </div>
    <script type="text/javascript">
    	function calculalPesoLibras(id,e,cant)
    	{
    		var costoFlete = e.value*document.getElementById('valorPesoLibra'+id).value;
    		document.getElementById('costoFlete'+id).innerHTML = number_format(costoFlete, 2, ',','.');

    		var totalPesoLibra = cant*document.getElementById('pesoLb'+id).value;
    		document.getElementById('totalPesoLibra'+id).innerHTML = number_format(totalPesoLibra, 2, ',','.');

    		var costoTotalFlete = costoFlete * totalPesoLibra;
    		document.getElementById('costoTotalFlete'+id).innerHTML = number_format(costoTotalFlete, 2, ',','.');
    	}

    	function dividirItem(itemDiv, detalle_id)
    	{
    		console.log(itemDiv);
    		console.log(detalle_id);
    		console.log('cantidad'+detalle_id);
    		console.log(Number(document.getElementById(itemDiv).value));

    		if( Number(document.getElementById(itemDiv).value) > 1 && Number(document.getElementById(itemDiv).value) <= Number(document.getElementById('cantidad'+detalle_id).value) )
        	{
        		console.log(itemDiv);
	    		console.log(document.getElementById(itemDiv));
	    		console.log(document.getElementById(itemDiv).value);
		        var t = document.getElementById('body_table'+detalle_id);
		        //limpio lo que tenia en la tabla
		        t.innerHTML="";
		        
		        for(i = 0; i < document.getElementById(itemDiv).value; i++)
		        {
		        	//creo un tr
		            var tr = document.createElement('tr');  
		            //creo un td
		            var td = document.createElement('td');
		            //creo el input
		            var hd = document.createElement('input');
		            hd.setAttribute('type','text');
		            hd.setAttribute('name','itemDividido'+detalle_id+'[]');
		            td.appendChild(hd);
		            tr.appendChild(td);
		            t.appendChild(tr);
		        }
        	}
        	else
        	{
        		alert('El numero tiene que ser mayor a 1 y menor o igual a '+Number(document.getElementById('cantidad'+detalle_id).value))
        	}   		
    	}

    	function crearDivisiones()
    	{

    	}
    </script>
    
@stop