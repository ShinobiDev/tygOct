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
	    		if($detalleOrden[0]->estado_id == 13){
	    			$nombreEstado = "Cerrada";	
	    		}	    		
	    	@endphp
	      <h3 class="box-title">Detalles de la Orden <b>{{$orden_id}}</b class="primary"> - Estado <b>{{$nombreEstado}}</b></h3>
	    </div>
	    <!-- /.box-header -->

	    <form class="form" method="POST" action="{{ route('ordenes.cotizarOrden', $orden_id) }}">
		{{ csrf_field() }}
	    <div class="box-body table-responsive col-md-12 bg-warning">
	    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
		        <table id="example1" class="table table-bordered table-striped table-hover">
			        <thead style="overflow-y: hidden;">
			        	@php
	        				$convencionOrden = $orden->convencion_id;
	    				@endphp
			        	<tr class="bg-warning">
			        		<th>Item</th>
			        		<th>Id Item</th>
			        		<th>Sede</th>
			        		<th>Marca</th>
			        		<th>Referencia</th>
			        		<th>Descripción</th>
			        		<th>Cantidad</th>
			        		<th>Comentarios</th>
			        		@if($detalleOrden[0]->estado_id == 4 || $detalleOrden[0]->estado_id == 8 || $detalleOrden[0]->estado_id == 9)

				        		@if($convencionOrden == 1 )
				        			<th class="bg-primary">Precio Venta Unidad USD</th>
				        			<th class="bg-primary">Precio Total USD</th>
				        		@endif
				        		<th>Día de Entrega</th>

				        		@if($convencionOrden == 2 || $convencionOrden == 3)
				        		<th>Guia Interna Destino</th>
				        		<th>Factura Cop</th>
				        		<th>Fecha Real de Entrega</th>
				        		<th>Fecha Factura</th>
				        		
				        		@endif

				        		<th>TE</th>

				        		@if($convencionOrden == 2 )		        			
					        		<th>Precio USD COL</th>
					        		<th>Precio Total USD COL</th>
				        		@endif
				        		@if($convencionOrden == 3 )
				        			<th>Precio Cop</th>
				        			<th>Precio Total Cop</th>
				        		@endif
				        		@if(auth()->user()->clienteVIP == 1)
				        			<th>Valor Negociación</th>
				        		@endif
				        	@endif	
			        		@if($detalleOrden[0]->estado_id == 4 || $detalleOrden[0]->estado_id == 2 || $detalleOrden[0]->estado_id == 3)
			        		<th>Acción</th>
			        		@endif

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
	        				$convencionOrden = $orden->convencion_id;
	        				
	        				$item = 0;

	        				$cantidadTotalGlobal = 0;
	        				$pesoTotalGlobal = 0;
	        				$pesoPromedioTotal = 0;
	        				$totalFleteUnidad = 0;
	        				$costoTotalFleteGlobal = 0;
	        				$totalCostoUnitario = 0;
	        				$totalPrecioUnitario = 0;
	        				$precioTotalGlobal = 0;
	        				$totalValorArancel = 0;
	        				$totalEmpaque = 0;
	        				$totalCinta = 0;
	        				$totalCosto3 = 0;
	        				$totalCostoUsdCol = 0;
	        				$precioTotalUsdCol = 0;
	        				$or = $orden->estado_id;
	        				$TotalGlobalPrecioTotalUsdCol = 0;
	        				$totalGlobalPrecioCop = 0;
	        				$totalConv1 = 0;
	        				$totalGlobalConv1 = 0;
	        				$totalNegociacion = 0;
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
			        				@php
			        					$cantidadTotalGlobal = $cantidadTotalGlobal + $detalle->cantidad;
			        				@endphp
			        				{{ $detalle->cantidad }}
			       	 				<input type="hidden" id="cantidad{{ $detalle->id }}" name="cantidad[]" value="{{ $detalle->cantidad }}">
			        			</td>
			        			<td>
			        				{{ $detalle->comentarios }}
			        				<input type="hidden" name="comentarios[]" value="{{ $detalle->comentarios }}">
			        			</td>

			        			@if($detalleOrden[0]->estado_id == 4 || $detalleOrden[0]->estado_id == 8 || $detalleOrden[0]->estado_id == 9)
			        			<!--Peso Libra-->
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
							        					
							        					if($convencionOrden == 1)
					        							{
					        								
					        								$totalPrecioUnitario = $precioUnidad;
					        								$totalConv1 = $totalConv1 + $totalPrecioUnitario;
					        								$totalPrecioUnitarioM = number_format($totalPrecioUnitario, 2, ",", ".");
					        							}else {
					        								
					        								$precioMonedaUnidad = $precioUnidad;
					        								$totalPrecioUnitario = $totalPrecioUnitario * $detalle->cantidad;
					        								$totalPrecioUnitarioM = number_format($totalPrecioUnitario, 2, ",", ".");
					        							}

				        							@endphp
				        							<input type="hidden" name="pesoPromedio" value="{{ $promedio }}">
				        							
			        							@else
			        								@php
			        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

			        									$a = $detalle->costoUnitario;
							        					$b = $detalle->margenUsa;
							        					$prom = $a * $b / 100;

							        					$precioUnidad = $a + $prom + $costoTotalFlete;	
							        					if($detalle->convencion_id == 1)
					        							{
					        								dd('Estoy aca');
					        								$precioMonedaUnidad = $precioUnidad;
					        								$totalPrecioUnitario = $precioUnidad * $detalle->cantidad;
					        								$totalPrecioUnitarioM = number_format($totalPrecioUnitario, 2, ",", ".");
					        							}else {
					        								
					        								$totalPrecioUnitario = $precioUnidad;
					        								$totalConv1 = $totalConv1 + $totalPrecioUnitario;
					        								$totalPrecioUnitarioM = number_format($totalPrecioUnitario, 2, ",", ".");
					        							}
			        								@endphp
			        								
		        								@endif
				        					@endif	
			        				@endforeach
				        			<input type="hidden" id="valorPesoLibra{{$detalle->id}}" value="{{$variables[1]->valor}}">

				        			@if($convencionOrden == 1)
				        				<td><b class="text-primary">U$</b>{{$totalPrecioUnitarioM}}</td>
				        				@php
				        					$totalGlobalPrecioUnitario = $totalPrecioUnitario * $detalle->cantidad;
				        					$totalGlobalConv1 = $totalGlobalConv1 + $totalGlobalPrecioUnitario;
				        					$totalGlobalPrecioUnitarioM = number_format($totalGlobalPrecioUnitario, 2, ",", ".");
				        				@endphp
				        				<td><b class="text-primary">U$</b>{{ $totalGlobalPrecioUnitarioM }}</td>
				        			@endif
				        			<!--Peso Promedio-->
				        				@foreach($detallePeso as $detalleP)
				        					@if($detalle->sede_id == $detalleP->sede_id)
				        						@if($detalleP->PesoSede < 9)
				        							@php
				        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
				        							@endphp
				        							<input type="hidden" name="pesoPromedio" value="{{ $promedio }}">
												@endif
				        					@endif	
				        				@endforeach
				        			<!--Peso Total Libra-->
				        				@foreach($detallePeso as $detalleP)
				        					@if($detalle->sede_id == $detalleP->sede_id)
				        						@if($detalleP->PesoSede < 9)
				        							@php
				        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
				        							$pesoTotal = $promedio;
				        							$pesoTotalGlobal = $pesoTotalGlobal + $pesoTotal;
				        							@endphp
				        							
			        							@else
			        								@php
			        									$pesoTotal = $detalle->pesoLb * (int)$detalle->cantidad;
			        									$pesoTotalGlobal = $pesoTotalGlobal + $pesoTotal;
		        									@endphp
		    										
		        								@endif
				        					@endif	
				        				@endforeach

				        				<!--Costo Flete Unidad-->
				        					@foreach($detallePeso as $detalleP)
				        					@if($detalle->sede_id == $detalleP->sede_id)
				        						@if($detalleP->PesoSede < 9)
				        							@php
					        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
					        							$costoFleteUnidad = $variables[1]->valor * $detalle->pesoPromedio;
								        				$totalPeso = $detalle->cantidad * $detalle->pesoLb;
								        				$costoTotalFlete = $costoFleteUnidad * $totalPeso;
				        							@endphp
		        								@endif
				        					@endif	
				        				@endforeach	

				        				<!--Costo Total Flete-->
				        				@foreach($detallePeso as $detalleP)
				        					@if($detalle->sede_id == $detalleP->sede_id)
				        						@if($detalleP->PesoSede < 9)
				        							@php
					        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
					        							$costoTotalFlete = $promedio * $variables[1]->valor;
					        							$totalFlete = $costoTotalFlete * $detalle->cantidad;
					        							if($detalle->convencion_id == 1)
					        							{	
					        								$costoTotalFleteGlobal = $costoTotalFleteGlobal + $totalFlete;
					        								$precioMoneda = $totalFlete;

					        							}else {
					        								
					        								$precioMoneda = $totalFlete * $detalle->Trm;
					        								$costoTotalFleteGlobal = $costoTotalFleteGlobal + $precioMoneda;
					        							}
				        							@endphp
				        							
			        							@else
			        								@php
			        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;
			        									$totalFlete = $costoTotalFlete * $detalle->cantidad;
			        									if($detalle->convencion_id == 1)
					        							{	
					        								$costoTotalFleteGlobal = $costoTotalFleteGlobal + $totalFlete;
					        								$precioMoneda = $totalFlete;
					        							}else {
					        								
					        								$precioMoneda = $totalFlete * $detalle->Trm;
					        								$costoTotalFleteGlobal = $costoTotalFleteGlobal + $precioMoneda;
					        							}	
			        								@endphp
			        								
		        								@endif
				        					@endif	
				        				@endforeach

				        				<!--Costo Unidad-->
					        				@php
					        					$a = $detalle->costoUnitario;
					        					$b = $detalle->margenUsa;
					        					$prom = $a * $b / 100;

					        					$precioParteUnidad = $detalle->costoUnitario + $prom;
					        				@endphp

				        			<!--Dias de Entrega-->
				        			<td>
				        				{{$fechaPrometidaCliente[$itemFecha]}}
				        			</td>


				        			<!--Guia Interna Destino-->
				        			@if($convencionOrden == 2 || $convencionOrden == 3)
				        			<td>		        				
				        				<label>{{--$detalle->guiaInterna--}}Guia Interna</label>
				        			</td>
				        			
				        			<!--Factura Cop-->
				        			<td>
				        				{{$detalle->facturaCop}}
				        			</td>

				        			<!--Fecha Real Entrega-->		        			        			
				        			<td class="bg-primary">
				        				{{$detalle->fechaRealEntrega}}
				        			</td>

				        			<!--Fecha Factura-->
				        			<td class="bg-primary">
				        				{{$detalle->fechaFactura}}
				        			</td>

				        			<!--Dias Planeados entrega Vs fecha Factura -->
				        			@endif
				        			<!--Precio Unitario-->

				        			<!--TE-->	        				
			        				<td>

			        					
				        				<label>{{$detalle->TE}}</label>

			        				</td>

			        				{{--Valor del Arancel--}}
			        					@php
			        						$valorArancel = $precioUnidad * $detalle->porcentajeArancel / 100;
			        						$valorArancelCal = $valorArancel;
			        						$totalValorArancel = $totalValorArancel + $valorArancelCal;
			        						$totalValorArancelA = number_format($totalValorArancel, 2, ",", ".");
			        						$valorArancel = number_format($valorArancel, 2, ",", ".");

			        					@endphp	        					
			        				
				        			{{--Valor Empaque o variable 1--}}	
			        					@php		        						
			        						if($detalleOrden[0]->precioTotalGlobal == 0)
			        						{	
			        							$empaque = 0;
			        							$empaqueCal = 0;
			        							$totalEmpaqueA = 0;
		                                        $detalleOrden[0]->precioTotalGlobal = 0;                                    
		                                    }else{
		                                    $empaque = ($precioTotal / $detalleOrden[0]->precioTotalGlobal) * $detalleOrden[0]->valorVar1;
			        						$empaque = $empaque / $detalle->cantidad;
			        						$empaqueCal = $empaque;
			        						$totalEmpaque = $totalEmpaque + $empaqueCal;
			        						$totalEmpaqueA = number_format($totalEmpaque, 2, ",", ".");
			        						$empaque = number_format($empaque, 2, ",", ".");
			        						
		                                    }
			        						
			        					@endphp        					
			        				
			        				{{--Valor Cinta o Variable 2--}}
			        					@php
			        						if($detalleOrden[0]->precioTotalGlobal == 0)
			        						{	
			        							$cinta = 0;
			        							$cintaCal = 0;
			        							$totalCintaA = 0;
		                                        $detalleOrden[0]->precioTotalGlobal = 0;
		                                    }else{
		                                    $cinta = ($precioTotal / $detalleOrden[0]->precioTotalGlobal) * $detalleOrden[0]->valorVar2;
			        						$cinta = $cinta / $detalle->cantidad;
			        						$cintaCal = $cinta;
			        						$totalCinta = $totalCinta + $cintaCal;
			        						$totalCintaA = number_format($totalCinta, 2, ",", ".");
			        						$cinta = number_format($cinta, 2, ",", ".");
		                                    }
			        					@endphp     					
			        				
			        				{{--Valor Costo 3 o Variable 3--}}
			        				
			        					@php
			        						
			        						if($detalleOrden[0]->precioTotalGlobal == 0)
			        						{	
			        							$costo3 = 0;
			        							$costo3Cal = 0;
			        							$totalCosto3A = 0;
		                                        $detalleOrden[0]->precioTotalGlobal = 0;
		                                    }else{
		                                    $costo3 = ($precioTotal / $detalleOrden[0]->precioTotalGlobal) * $detalleOrden[0]->valorVar3;
			        						$costo3 = $costo3 / $detalle->cantidad;
			        						$costo3Cal = $costo3;
			        						$totalcosto3 = $totalCosto3 + $costo3Cal;
			        						$totalCosto3A = number_format($totalCosto3, 2, ",", ".");
			        						$costo3 = number_format($costo3, 2, ",", ".");
		                                    }
			        					@endphp	        					
			        				
			        				{{--Costo USA Colombia--}}	        				
			        					
			        					@php
			        						$costoUsdCop = $precioUnidad + $valorArancelCal + $empaqueCal + $cintaCal + $costo3Cal;
			        						$costoUsdCopCal = $costoUsdCop;
			        						$totalCostoUsdCol = $totalCostoUsdCol +  $costoUsdCop;
			        						$totalCostoUsdColA = number_format($totalCostoUsdCol, 2, ",", ".");
			        						$costoUsdCop = number_format($costoUsdCop, 2, ",", ".");
			        					@endphp
			        				
			        				{{--Precio USA Colombia--}}	
			        				
			        					@php
					        				$precioTotalUsdCol = $costoUsdCopCal / (100 - $detalle->margenCop)*100;
					        				$precioTotalUsdColA = number_format($precioTotalUsdCol, 2, ",", ".");  
					        			@endphp	
					        		@if($convencionOrden == 2)			        			
			        				<td class="bg-success">
			        					<b class="text-success">$_</b>{{$precioTotalUsdColA}}
			        				</td>
			        				{{--Precio Total USA Colombia--}}

			        				{{--Precio --}}
			        				<td class="bg-success">
			        					@php
					        				$TotalprecioTotalUsdCol = $precioTotalUsdCol * $detalle->cantidad;
					        				$TotalGlobalPrecioTotalUsdCol = $TotalGlobalPrecioTotalUsdCol + $TotalprecioTotalUsdCol;

					        				$TotalprecioTotalUsdColA = number_format($TotalprecioTotalUsdCol, 2, ",", "."); 
					        				$TotalGlobalPrecioTotalUsdColA = number_format($TotalGlobalPrecioTotalUsdCol, 2, ",", "."); 
					        			@endphp
					        			<b class="text-success">$_</b>{{$TotalprecioTotalUsdColA}}
			        				</td>
			        				@endif
			        				<!--Precio Total USD-->
			        				
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
					        								$precioTotalGlobal = $precioTotalGlobal + $precioTotal;
					        								
					        								$precioMonedaTotal = $precioTotal;
					        								$precioMonedaTotal = number_format($precioMonedaTotal, 2, ",", ".");
					        							}else {
					        								$precioMonedaTotal = $precioTotal * $detalle->Trm;
					        								$precioTotalGlobal = $precioTotalGlobal + $precioMonedaTotal;
					        								$precioMonedaTotal = number_format($precioMonedaTotal, 2, ",", ".");
					        								
					        							}

				        							@endphp
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
					        								$precioTotalGlobal = $precioTotalGlobal + $precioTotal;
					        								
					        								$precioMonedaTotal = $precioTotal;
					        								$precioMonedaTotal = number_format($precioMonedaTotal, 2, ",", ".");
					        							}else 
					        							{
					        								$precioMonedaTotal = $precioTotal * $detalle->Trm;
					        								
					        								$precioTotalGlobal = $precioTotalGlobal + $precioMonedaTotal;
					        								$precioMonedaTotal = number_format($precioMonedaTotal, 2, ",", ".");
					        							}
			        								@endphp
			        								
		        								@endif
				        					@endif
				        					@php
				        						$orden = $detalle->orden_id;
				        					@endphp	
			        				@endforeach
				        			<!--Precio COP-->
				        			@if($convencionOrden == 3)
			        				<td class="bg-danger">
			        					@php
				        					$precioCop = $precioTotalUsdCol * $detalle->Trm;
				        					$precioCopA = number_format($precioCop, 2, ",", ".");
				        				@endphp
				        				<b class="text-danger">$_</b>{{ $precioCopA }}
			        				</td>

			        				<!--Precio Total COP-->
			        				@php
			        					$totalPrecioCop = $precioCop * $detalle->cantidad;
			        					$totalGlobalPrecioCop = $totalGlobalPrecioCop + $totalPrecioCop;

			        					$totalPrecioCopA = number_format($totalPrecioCop, 2, ",", ".");
			        					$totalGlobalPrecioCopA = number_format($totalGlobalPrecioCop, 2, ",", ".");
			        				@endphp
			        				<td class="bg-danger">
				        				<b class="text-danger">$_</b>{{ $totalPrecioCopA }}
			        				</td>        						
			        				@endif
			        				@if(auth()->user()->clienteVIP == 1)
			        					<td><input type="text" name="negociacion[]" onchange="valorNegociacion(this)"></td>

			        				@endif
		        				@endif
		        				@if($detalleOrden[0]->estado_id == 4 || $detalleOrden[0]->estado_id == 2 || $detalleOrden[0]->estado_id == 3)
			        			<td>
			        					
			        				<a href="{{ route('ordenes.eliminarItem', $detalle->id) }}" class="btn btn-xs btn-danger
			        					"></i>Quitar</a>
			        				
			        			</td>
			        			@endif
			        			<td><input type="hidden" name="detalle_id[]" value="{{$detalle->id}}"></td>
			        		</tr>
			        		@php
			        			$itemFecha ++;
			        		@endphp
			        	@endforeach
			        	<tr>
		        			<td><label class="text-danger">Totales</label></td>
			        		<td></td>
			        		<td></td>
			        		<td></td>
			        		<td></td>
			        		<td></td>

			        		<td><label class="text-primary">{{ $cantidadTotalGlobal }}</label></td>
			        		<td></td>
			        		@if($detalleOrden[0]->estado_id == 4 || $detalleOrden[0]->estado_id == 8 || $detalleOrden[0]->estado_id == 9)
				        		<td>
				        			@php
			        					$totalConv1M = number_format($totalConv1, 2, ",", ".");
		        						$totalGlobalConv1M = number_format($totalGlobalConv1, 2, ",", ".");
			        				@endphp
				        			@if($convencionOrden == 1)
				        				<b class="text-primary">U$_</b>{{$totalConv1M}}

				        			@endif
				        			
				        		</td>
				        		<td>
				        			@if($convencionOrden == 1)
				        				<b class="text-primary">U$_</b>{{$totalGlobalConv1M}}       				
				        			@endif
				        		</td>
				        		<td></td>
				        		
				        		<td>
				        			@if($convencionOrden == 1)
					        			<input type="hidden" id="hdTotalNegociacion" value="0">
					        			$<span value="0" id="totalNegociacion"></span>
				        			@endif
				        		</td>
				        		<td></td>
				        		
				        		<td>
				        		<td>
				        		</td>
				        		@if($convencionOrden == 2)
									<td class="bg-success"><b class="text-danger">$</b>{{$TotalGlobalPrecioTotalUsdColA}}</td>
				        		@endif
				        		@if($convencionOrden == 3)
				        			<td class="bg-danger"><b class="text-danger">$</b>{{$totalGlobalPrecioCopA}}</td>
				        		@endif
				        		<td>
				        			@if($convencionOrden == 2 || $convencionOrden == 3)
					        			<input type="hidden" id="hdTotalNegociacion" value="0">
					        			$<span class="text-danger" value="0" id="totalNegociacion"></span>
				        			@endif
				        		</td>
				        	@endif
				        	<td></td>
				        	<td></td>	
		        		</tr>
			        </tbody>
		      	</table>

		      	@if($or == 4)
		      	
		    		<button type="submit" class="btn btn-primary col-md-3  col-md-offset-1">Aceptar</button>

		    		<button type="submit" class="btn btn-success col-md-3 col-md-offset-1">Solicitar Negociación</button>
			    	
		    	@endif
	      <!--Seccion solo para el Administrador-->
	      <!--Asignar Usuario para gestionar la orden-->
      	</div>

    	</form>

    	<!--Actualizar la orden a cotizado y enviar los datos al cliente-->
    </div>
@stop

@section('totalSede')
<div class="box col-md-12">
	<div class="box-body col-md-6">
	    <!-- /.box-header -->
	    
	    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
	    	<table class="table table-bordered table-striped table-hover">
	    		<thead>
    				<tr class="text-primary">
    					<td><label>Sede</label></td>
    					<td><label>Articulos por Sede</label></td>
    					<td><label>Peso Lb por sede</label></td>
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
      		<table class="table table-bordered table-striped table-hover">
      			<tr>
      				<td><label class="text-primary">Cantidad total</label></td>
      				<td><label class="text-primary">{{ $cantidadTotalGlobal }} Productos </label></td>
      			</tr>
      			<tr>
      				<td><label class="text-primary">Peso total en libras</label></td>
      				<td><label class="text-primary">{{ $pesoTotalGlobal }} Lbs </label></td>
      			</tr>
      			<tr>
      				<td><label class="text-success">Costo total del Flete</label></td>
      				<td><label class="text-success">
      					@php
	      					$costoTotalFleteGlobal = number_format($costoTotalFleteGlobal, 2, ",", ".");
	      				@endphp
      					<strong class="text-danger">$</strong>  {{ $costoTotalFleteGlobal }}</label></td>
      			</tr>
      			<tr>
      				<td><label class="text-success">Total precio Usd</label></td>
      				@php
      					$precioTotalGlobal = number_format($precioTotalGlobal, 2, ",", ".");
      				@endphp
      				<td><label class="text-success"><strong class="text-danger">$</strong>  {{ $precioTotalGlobal }}</label></td>
      			</tr>
      		</table>
      	
  	</div>
  </div>
@stop
<script type="text/javascript">
	function valorNegociacion(elemento)
	{	
		var elementos = document.getElementsByName('negociacion[]');
		var suma = 0;
		for (e in elementos) {

			if(elementos[e].value != undefined){
				console.log(elementos[e].value);
				console.log(elementos[e]);
				suma += Number(elementos[e].value);
			}

		}
		document.getElementById('totalNegociacion').innerHTML = number_format(suma, 2, ",", ".");
	}

	/*Funcion tomada del sitio
         * http://www.antisacsor.com/articulo/10_98_dar-formato-a-numeros-en-javascript
         * Para dar formato a los numeros*/
        /**
         * Da formato a un número para su visualización
         *
         * @param {(number|string)} numero Número que se mostrará
         * @param {number} [decimales=null] Nº de decimales (por defecto, auto); admite valores negativos
         * @param {string} [separadorDecimal=","] Separador decimal
         * @param {string} [separadorMiles=""] Separador de miles
         * @returns {string} Número formateado o cadena vacía si no es un número
         *
         * @version 2014-07-18
         */
        function number_format(numero, decimales, separador_decimal, separador_miles){ // v2007-08-06
            numero=parseFloat(numero);
            if(isNaN(numero)){
                return "";
            }

            if(decimales!==undefined){
                // Redondeamos
                numero=numero.toFixed(decimales);
            }

            // Convertimos el punto en separador_decimal
            numero=numero.toString().replace(".", separador_decimal!==undefined ? separador_decimal : ",");

            if(separador_miles){
                // Añadimos los separadores de miles
                var miles=new RegExp("(-?[0-9]+)([0-9]{3})");
                while(miles.test(numero)) {
                    numero=numero.replace(miles, "$1" + separador_miles + "$2");
                }
            }

            return numero;
        }
</script>

