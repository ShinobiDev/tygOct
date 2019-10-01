@extends('admin.layout')

@section('contenido')
	<div class="box box-warning">
	    <div class="box-header col-md-12">
	     @php
	       		
	    		if($detalleOrden[0]->estado_id == 2 || $detalleOrden[0]->estado_id == 3){
	    			$nombreEstado = "Por Cotizar";	
	    		}
	    		if($detalleOrden[0]->estado_id == 4){
	    			$nombreEstado = "Cotizado";	
	    		}
	    		if($detalleOrden[0]->estado_id == 8 || $detalleOrden[0]->estado_id == 9){
	    			$nombreEstado = "Orden";	
	    		}	    		
	    	@endphp
	    	<h3 class="box-title">Detalles de la Orden <b>{{$orden_id}}</b> - Estado <b>{{ $nombreEstado }}</b>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	    	<form class="form" method="POST" action="{{ route('ordenes.actualizarOrden', $orden_id) }}">
			{{ csrf_field() }}
		    <div class="box-body table-responsive col-md-12 bg-warning">
		    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
		      	<table 	id="example1" class="table table-bordered table-striped table-hover">
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
			        		<th>Peso Lbs</th>
			        		<th>Peso Promedio</th>
			        		<th>Total Peso Libra</th>
			        		<th>Costo Flete Unidad</th>
			        		<th>Costo Total Flete</th>
			        		<th>Precio Unitario USD</th>
			        		<th>Precio Total USD</th>
			        		<th>Fecha Solicitud al Proveedor</th>
			        		<th>Días de entrega proveedor</th>
			        		<th>Días festivos y no habiles del Proveedor</th>
			        		<th>Fecha estimada de llegada</th>
			        		<th>En Bodega</th>
			        		<th>Fecha cuando se completo la orden</th>
			        		<th>Dias Reales Entrega</th>
			        		<th>Guia Internacional</th>
			        		<th>Invoice</th>
			        		<th>Fecha Invoice</th>
			        		<th>Dias en transito al cliente</th>
			        		<th>Días festivos y no habiles cliente</th>
			        		<th>Días prometidos</th>
			        		<th>Fecha estimada de llegada</th>
			        		<th>Guia Interna destino</th>
			        		<th>Factura Cop</th>
			        		<th>Fecha real Entrega</th>
			        		<th>Fecha Factura</th>
			        		<th>Días reales Entrega</th>
			        		<th>Dias Planeador Vs Factura</th>
			        		
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
	        				$itemFecha = 0;
	        			@endphp
			        	@foreach($detalleOrden as $detalle)
			        		@php			        			
			        			$item++;
			        		@endphp
			        		@php
		        				$costoFleteUnidad = $variables[1]->valor * $detalle->pesoPromedio;
		        				$totalPeso = $detalle->cantidad * $detalle->pesoLb;
		        				$costoTotalFlete = $costoFleteUnidad * $totalPeso;	        				
		        			@endphp
		        			@php
	        					$a = $detalle->costoUnitario;
	        					$b = $detalle->margenUsa;
	        					$prom = $a * $b / 100;

	        					$c = $costoTotalFlete;
	        					$d = $detalle->cantidad;

	        					$e = $c / $d;

	        					$precioVenta = $a+$prom+$e;
	        					$precioTotal = $precioVenta * $detalle->cantidad
	        				@endphp

			        		<tr>
			        			<td>{{ $item }}</td>
			        			<td>{{ $detalle->id }}</td>
			        			<td>
			        				{{ $detalle->nombre }}
			        				<input type="hidden" name="sede[]" value="{{ $detalle->nombre }}">
			        				<input type="hidden" name="sedeId[]" value="{{ $detalle->sede_id }}">
			        			</td>
			        			<td>
			        				{{ $detalle->marca }}
			        				<input type="hidden" name="marca[]" value="{{ $detalle->marca }}">
			        			</td>
			        			<td>
			        				{{ $detalle->referencia }}
			        				<input type="hidden" name="referencia[]" value="{{ $detalle->referencia }}">
			        			</td>
			        			<td>
			        				{{ $detalle->descripcion }}
			        				<input type="hidden" name="descripcion[]" value="{{ $detalle->descripcion }}">
			        			</td>
			        			<td>
			        				{{ $detalle->cantidad }}
			       	 				<input type="hidden" id="cantidad{{ $detalle->id }}" name="cantidad[]" value="{{ $detalle->cantidad }}">
			        			</td>
			        			<td>
			        				{{ $detalle->comentarios }}
			        				<input type="hidden" name="comentarios[]" value="{{ $detalle->comentarios }}">
			        			</td>
			        			<td>
			        				{{$detalle->pesoLb}}
			        				<input type="hidden" name="pesoLb[]" value="{{$detalle->pesoLb}}">
			        				<input type="hidden" id="valorPesoLibra{{$detalle->id}}" value="{{$variables[1]->valor}}">
			        			</td>
			        			<td>
			        				@foreach($detallePeso as $detalleP)
			        					@if($detalle->sede_id == $detalleP->sede_id)
			        						@if($detalleP->PesoSede < 9)
			        							@php
			        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
			        							@endphp
			        							<input type="hidden" name="pesoPromedio" value="{{ $promedio }}">
			        							<label>{{ $promedio }}</label>
		        							@else
		        								<label>{{ $detalle->pesoLb }}</label>
	        								@endif
			        					@endif	
			        				@endforeach
			        				
			        			</td>
			        			<td class="bg-success">
			        				@foreach($detallePeso as $detalleP)
			        					@if($detalle->sede_id == $detalleP->sede_id)
			        						@if($detalleP->PesoSede < 9)
			        							@php
			        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
			        							$pesoTotal = $promedio;
			        							@endphp
			        							<label>{{ $pesoTotal }}</label>
		        							@else
		        								@php
		        									$pesoTotal = $detalle->pesoLb * $detalle->cantidad;
	        									@endphp
	    										<label>{{ $pesoTotal }}</label>
	        								@endif
			        					@endif	
			        				@endforeach
			        			</td>			        			        			
			        			<td class="bg-danger">
			        				<label id="costoFlete{{$detalle->id}}">{{$costoFleteUnidad}}</label>
			        			</td>
			        			<td class="bg-danger">
			        				@foreach($detallePeso as $detalleP)
			        					@if($detalle->sede_id == $detalleP->sede_id)
			        						@if($detalleP->PesoSede < 9)
			        							@php
				        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
				        							$costoTotalFlete = $promedio * $variables[1]->valor;
				        							$totalFlete = $costoTotalFlete * $detalle->cantidad;
				        							if($detalle->convencion_id == 1)
				        							{
				        								$precioMoneda = $totalFlete;
				        							}else {
				        								$precioMoneda = $totalFlete * $detalle->Trm;
				        							}
			        							@endphp
			        							<label>{{ $precioMoneda }}</label>
		        							@else
		        								@php
		        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;
		        									$totalFlete = $costoTotalFlete * $detalle->cantidad;
		        									if($detalle->convencion_id == 1)
				        							{
				        								$precioMoneda = $totalFlete;
				        							}else {
				        								$precioMoneda = $totalFlete * $detalle->Trm;
				        							}	
		        								@endphp
		        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioMoneda}}</label>
	        								@endif
			        					@endif	
			        				@endforeach
			        			</td>		        				
		        				<td>
		        					@foreach($detallePeso as $detalleP)
			        					@if($detalle->sede_id == $detalleP->sede_id)
			        						@if($detalleP->PesoSede < 9)
			        							@php
				        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
				        							$costoTotalFlete = $promedio * $variables[1]->valor;

				        							$a = $detalle->costoUnitario;
						        					$b = $detalle->margenUsa;
						        					$prom = $a * $b / 100;

						        					$precioUnidad = $a + $prom + $costoTotalFlete;
						        					if($detalle->convencion_id == 1)
				        							{
				        								$precioMonedaUnidad = $precioUnidad;
				        							}else {
				        								$precioMonedaUnidad = $precioUnidad * $detalle->Trm;
				        							}

			        							@endphp
			        							
			        							<label>{{ $precioMonedaUnidad }}</label>
		        							@else
		        								@php
		        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

		        									$a = $detalle->costoUnitario;
						        					$b = $detalle->margenUsa;
						        					$prom = $a * $b / 100;

						        					$precioUnidad = $a + $prom + $costoTotalFlete;	
						        					if($detalle->convencion_id == 1)
				        							{
				        								$precioMonedaUnidad = $precioUnidad;
				        							}else {
				        								$precioMonedaUnidad = $precioUnidad * $detalle->Trm;
				        							}
		        								@endphp
		        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioMonedaUnidad}}</label>
	        								@endif
			        					@endif	
			        				@endforeach
		        				</td>
		        				<td class="bg-primary">
		        					@foreach($detallePeso as $detalleP)
			        					@if($detalle->sede_id == $detalleP->sede_id)
			        						@if($detalleP->PesoSede < 9)
			        							@php
				        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
				        							$costoTotalFlete = $promedio * $variables[1]->valor;

				        							$a = $detalle->costoUnitario;
						        					$b = $detalle->margenUsa;
						        					$prom = $a * $b / 100;

						        					$precioUnidad = $a + $prom + $totalFlete;
						        					$precioTotal = $precioUnidad * $detalle->cantidad;

						        					if($detalle->convencion_id == 1)
				        							{
				        								$precioMonedaTotal = $precioTotal;
				        							}else {
				        								$precioMonedaTotal = $precioTotal * $detalle->Trm;
				        							}

			        							@endphp
			        							<label>{{ $precioMonedaTotal }}</label>
		        							@else
		        								@php
		        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

		        									$a = $detalle->costoUnitario;
						        					$b = $detalle->margenUsa;
						        					$prom = $a * $b / 100;

						        					$precioUnidad = $a + $prom + $totalFlete;
						        					$precioTotal = $precioUnidad * $detalle->cantidad;	
						        					if($detalle->convencion_id == 1)
				        							{
				        								$precioMonedaTotal = $precioTotal;
				        							}else {
				        								$precioMonedaTotal = $precioTotal * $detalle->Trm;
				        							}
		        								@endphp
		        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioMonedaTotal}}</label>
	        								@endif
			        					@endif	
			        				@endforeach
		        				</td>
		        				<td>
		        					<input type="date" name="fechaSolicitudProveedor[]" value="{{$detalle->fechaSolicitudProveedor}}">
		        				</td>
		        				<td>		        					
	        						<input type="text" name="diasEntregaProveedor[]" value="{{$detalle->diasEntregaProveedor}}">
		        				</td>
		        				<td>		        					
	        						<input type="text" name="diasfestivosNoHabilesProveedor[]" value="{{$detalle->diasfestivosNoHabilesProveedor}}">
		        				</td>		        				
		        				<td>
		        					{{$fechaEstimadaLlegada[$itemFecha]}}
		        					
		        				</td>
		        				<td>
		        					@if($detalle->cantidad <= $detalle->bodega)
		        						<input type="text" name="bodega[]" value="{{$detalle->bodega}}" class="bg-danger">
	        						@else
	        							<input type="text" name="bodega[]" value="{{$detalle->bodega}}">   
		        					@endif
	        						     					
		        				</td>
		        				<td>
		        					
		        					{{$detalle->fechaCantidadCompleta}}
		        					
		        				</td>
		        				<td>
		        					{{ $diasRealesEntregaProveedor[$itemFecha] }}		        					
		        				</td>
		        				<td>
		        					<input type="text" name="guiaInternacional[]" value="{{$detalle->guiaInternacional}}">
		        				</td>
		        				<td>
		        					<input type="text" name="invoice[]" value="{{$detalle->invoice}}">
		        				</td>
		        				<td>
		        					<input type="date" name="fechaInvoice[]" value="{{$detalle->fechaInvoice}}" min="{{date("Y-m-d")}}">		        					
		        				</td>
		        				<td>
		        					<input type="text" name="diasTransitoCliente[]" value="{{$detalle->diasTransitoCliente}}">
		        				</td>
		        				<td>
		        					<input type="text" name="diasFestivoNoHabilesCliente[]" value="{{$detalle->diasFestivoNoHabilesCliente}}">
		        				</td>
		        				<td>
		        					{{$diasPrometidosCliente[$itemFecha]}}
		        				</td>
		        				<td>
		        					{{$fechaPrometidaCliente[$itemFecha]}}
		        				</td>
		        				<td>
		        					<input type="text" name="guiaInternaDestino[]" value="{{$detalle->guiaInternaDestino}}">
		        				</td>
		        				<td>
		        					<input type="text" name="facturaCop[]" value="{{$detalle->facturaCop}}">
		        				</td>
		        				<td>
		        					<input type="date" name="fechaRealEntrega[]" value="{{$detalle->fechaRealEntrega}}">
	        					</td>
		        				<td>
		   							<input type="date" name="fechaFactura[]" value="{{$detalle->fechaFactura}}">
		        				</td>
		        				<td>
		        					{{$diasRealesEntregaCliente[$itemFecha]}}
		        				</td>
		        				<td>
		        					{{$vs[$itemFecha]}}
		        				</td>
			        			@php
	        						$itemFecha++;
	        					@endphp
			        			<td>
			        				@if($detalle->cantidad > 1)
		        					<button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#exampleModalCenter{{ $detalle->id }}">Dividir</button>	
					    			@endif	
					    			<div class="modal fade" id="exampleModalCenter{{ $detalle->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
									  	<div class="modal-dialog modal-dialog-centered" role="document">
										    <div class="modal-content">
										      <div class="modal-header">
										        <h5 class="modal-title" id="exampleModalCenterTitle">División del Item {{ $detalle->id }}</h5>
										        <h5>Cantidad del Item {{ $detalle->cantidad }}</h5>
										        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
										          <span aria-hidden="true">&times;</span>
										        </button>
										      </div>
										      <div class="modal-body">
										        <div class="form-group">
									    			<label>¿En cuantos Items, desea dividirlo?</label>
									    			<input type="number" name="itemDiv" id="itemDiv{{ $detalle->id }}" class="form-control" placeholder="Ingrese la cantidad"/>

									    		</div>
									    		
									    		<div class="form-group">
									    			<input class="btn btn-warning" type="button" value="Dividir Item" onclick="dividirItem('itemDiv{{ $detalle->id }}', '{{ $detalle->id }}')">
									    		</div>

									    		<table id="body_table{{ $detalle->id }}">
									    			
									    		</table>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
										        <button type="button" data-dismiss="modal" class="btn btn-primary" onclick="crearDivisiones()">Aceptar</button>
										      </div>
										    </div>
							  			</div>
									</div>
			        			</td>	
									
			        			
			        			<td><input type="hidden" name="detalle_id[]" value="{{$detalle->id}}"></td>
			        		</tr>
			        	@endforeach
			        	
			        </tbody>
		      	</table>
		      <!--Seccion solo para el Administrador-->
		      <!--Asignar Usuario para gestionar la orden-->
	      	</div>
	      	<div class="form-group col-md-offset-3">
	    		<button type="submit" class="btn btn-primary col-md-3">Actualizar</button>
	    	</div>    	
		</form>
	    </div>
	    
    	<!--Cambiar el estado de la orden a Facturable-->
    	<!--Actualizar la orden a cotizado y enviar los datos al cliente-->
    </div>
@stop

@section('totalSede')
	<div class="box box-warning col-md-6">
	    <!-- /.box-header -->
	    <div class="box-body table-responsive col-md-6 bg-warning">
	    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
	    	<table class="table table-bordered table-striped table-hover">
	    		<thead>
    				<tr>
    					<td>Sede</td>
    					<td>Articulos por Sede</td>
    					<td>Peso Lb por sede</td>
    				</tr>
				</thead>
    			<tbody>
    				@foreach($detallePeso as $detalle)
    					<tr>
    						<td>{{ $detalle->nombre }}</td>
    						<td>{{ $detalle->cantidadProductos }}</td>
    						<td>{{ $detalle->PesoSede }}</td>
    					</tr>
    				@endforeach
    			</tbody>	
      		</table>
      	</div>
      	<div class="box-body">
    	<form class="form" method="POST" action="{{ route('ordenes.actualizarParaFacturar') }}">
    		{{ csrf_field() }}
    		 <input type="hidden" name="orden_id" value="{{$orden_id}}">
    		    			
			<table>
				@foreach($detalleOrden as $detalle)
				<tr>
					<td><input type="hidden" name="item_id[]" value="{{$detalle->id}}">
						
					</td>
				</tr>
				@endforeach
			</table>
			<div class="form-group col-md-offset-3">				
	    		<button type="submit" class="btn btn-primary col-md-3">Cerrar Orden</button>
	    	</div>
    	</form>
    </div>
  	</div>

@stop

@push('styles')
	<link rel="stylesheet" href="{{asset('adminLte/plugins/datepicker/datepicker3.css')}}">
@endpush

@push('scripts')
	<script src="{{asset('adminLte/plugins/datepicker/bootstrap-datepicker.js')}}">
		//Date picker
	    $('#datepicker').datepicker({
	      autoclose: true
	    });
	</script>
@endpush

<script type="text/javascript">
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
        		alert('El numero tiene que ser mayor a 1 y menor o igual a '+Number(document.getElementById('cantidad'+detalle_id).value));
        	}   		
    	}

    	function crearDivisiones()
    	{

    	}
    </script>




