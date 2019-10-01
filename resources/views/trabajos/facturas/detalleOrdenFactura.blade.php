@extends('admin.layout')

@section('contenido')
	<div class="box box-warning">
	    <div class="box-header col-md-12">
	      <h3 class="box-title">Detalles de la Orden - <b>{{$orden_id}}</b></h3>
	    </div>
	    <!-- /.box-header -->

	    <form class="form" method="POST" action="{{ route('facturas.almacenar.facturaOrden') }}">
		{{ csrf_field() }}
		    <div class="box-body table-responsive col-md-12 bg-warning">
		    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
			      <table class="table table-bordered table-striped table-hover">
			        <thead style="overflow-y: hidden;">
			        	<tr class="bg-warning">
			        		<th>Item</th>
			        		<th>Id Item</th>
			        		<th>Sede</th>
			        		<th>Marca</th>
			        		<th>Referencia</th>
			        		<th>Cantidad</th>
			        		<th>Peso Lbs</th>
			        		<th>Peso Promedio</th>
			        		<th>Total Peso Libra</th>
			        		<th>Costo Flete Unidad</th>
			        		<th>Costo Total Flete</th>
			        		<th>Costo Unitario</th>
			        		<th>Margen USA</th>
			        		<th>Precio Unitario USD</th>
			        		<th>Precio Total USD</th>
			        		<th>%-Arancel</th>
			        		<th>Valor Arancel</th>
			        		<th>Empaque</th>
			        		<th>Cinta</th>
			        		<th>Costo 3</th>
			        		<th>Costo USD Colombia</th>
			        		<th>Margen Cop</th>
			        		<th>TE</th>
			        		<th>Precio USD Colombia</th>
			        		
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

	        				$cantidadTotalGlobal = 0;
	        				$pesoTotalGlobal = 0;
	        				$pesoPromedioTotal = 0;
	        				$totalFleteUnidad = 0;
	        				$costoTotalFleteGlobal = 0;
	        				$totalPrecioUnitario = 0;
	        				$precioTotalGlobal = 0;
	        				$totalValorArancel = 0;
	        				$totalEmpaque = 0;
	        				$totalCinta = 0;
	        				$totalCosto3 = 0;
	        				$totalCostoUsdCol = 0;
	        				$precioTotalUsdCol = 0;
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
			        				@php
			        					$cantidadTotalGlobal = $cantidadTotalGlobal + $detalle->cantidad;
			        				@endphp
			        				{{ $detalle->cantidad }}
			       	 				<input type="hidden" id="cantidad{{ $detalle->id }}" name="cantidad[]" value="{{ $detalle->cantidad }}">
			        			</td>
			        			<td>
			        				{{$detalle->pesoLb}}
			        				<input type="hidden" id="valorPesoLibra{{$detalle->id}}" value="{{$variables[1]->valor}}">
			        			</td>
			        			<td class="bg-danger">
			        				@foreach($detallePeso as $detalleP)
			        					@if($detalle->sede_id == $detalleP->sede_id)
			        						@if($detalleP->PesoSede < 9)
			        							@php
				        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
				        							$pesoTotalGlobal = $pesoTotalGlobal + $promedio;
			        							@endphp
			        							<input type="hidden" name="pesoPromedio" value="{{ $promedio }}">
			        							<label>{{ $promedio }}</label>
		        							@else
		        								@php
		        									$pesoTotalGlobal = $pesoTotalGlobal + $detalle->pesoLb;
		        								@endphp
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
			        				@php
			        					$totalFleteUnidad = $totalFleteUnidad + $costoFleteUnidad;
			        				@endphp
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
				        							$costoTotalFleteGlobal = $costoTotalFleteGlobal + $totalFlete;
			        							@endphp
			        							<label>{{ $totalFlete }}</label>
		        							@else
		        								@php
		        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;
		        									$totalFlete = $costoTotalFlete * $detalle->cantidad;
		        									$costoTotalFleteGlobal = $costoTotalFleteGlobal + $totalFlete;
		        								@endphp
		        								<label id="costoTotalFlete{{$detalle->id}}">{{$totalFlete}}</label>
	        								@endif
			        					@endif	
			        				@endforeach
			        			</td>
		        				<td>
		        					@php
		        						$totalPrecioUnitario = $totalPrecioUnitario + $detalle->costoUnitario;
		        					@endphp
		        					<label>{{$detalle->costoUnitario}}</label>	        					
		        				</td>  			
			        			<td>
			        				<label>{{$detalle->margenUsa}}</label>
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


			        							@endphp
			        							<input type="hidden" name="pesoPromedio" value="{{ $promedio }}">
			        							<label>{{ $precioUnidad }}</label>
		        							@else
		        								@php
		        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

		        									$a = $detalle->costoUnitario;
						        					$b = $detalle->margenUsa;
						        					$prom = $a * $b / 100;

						        					$precioUnidad = $a + $prom + $costoTotalFlete;

		        								@endphp
		        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioUnidad}}</label>
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
						        					$precioTotalGlobal = $precioTotalGlobal + $precioTotal;
						        					$precioTotalGlobalCal = $precioTotalGlobal;
			        							@endphp
			        							<label>{{ $precioTotal }}</label>

		        							@else
		        								@php
		        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

		        									$a = $detalle->costoUnitario;
						        					$b = $detalle->margenUsa;
						        					$prom = $a * $b / 100;

						        					$precioUnidad = $a + $prom + $totalFlete;
						        					$precioTotal = $precioUnidad * $detalle->cantidad;
						        					$precioTotalGlobal = $precioTotalGlobal + $precioTotal;	
						        					$precioTotalGlobalCal = $precioTotalGlobal;
		        								@endphp
		        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioTotal}}</label>
	        								@endif
			        					@endif	
			        				@endforeach
			        				
		        				</td>
		        				<td>
		        					<input type="text" name="porcentajeArancel[]" value="{{$detalle->porcentajeArancel}}">
		        				</td>
		        				<td>
		        					@php
		        						$valorArancel = $precioUnidad * $detalle->porcentajeArancel / 100;
		        						$valorArancelCal = $valorArancel;
		        						$totalValorArancel = $totalValorArancel + $valorArancelCal;
		        						$totalValorArancelA = number_format($totalValorArancel, 2, ",", ".");
		        						$valorArancel = number_format($valorArancel, 2, ",", ".");

		        					@endphp
		        					{{$valorArancel}}
		        				</td>
		        				<td>
		        					@php
		        						if($detalleOrden[0]->precioTotalGlobal == 0)
		        						{	
		        							$empaque = 0;
		        							$empaqueCal = 0;
		        							$totalEmpaqueA = 0;
                                            $detalleOrden[0]->precioTotalGlobal = 0;
                                        }else{
                                        $empaque = ($precioTotal / $detalleOrden[0]->precioTotalGlobal) * $variables[3]->valor;
		        						$empaque = $empaque / $detalle->cantidad;
		        						$empaqueCal = $empaque;
		        						$totalEmpaque = $totalEmpaque + $empaqueCal;
		        						$totalEmpaqueA = number_format($totalEmpaque, 2, ",", ".");
		        						$empaque = number_format($empaque, 2, ",", ".");
                                        }
		        						
		        					@endphp
		        					
		        					{{ $empaque }}
		        				</td>
		        				<td>	
		        					@php
		        						if($detalleOrden[0]->precioTotalGlobal == 0)
		        						{	
		        							$cinta = 0;
		        							$cintaCal = 0;
		        							$totalCintaA = 0;
                                            $detalleOrden[0]->precioTotalGlobal = 0;
                                        }else{
                                        $cinta = ($precioTotal / $detalleOrden[0]->precioTotalGlobal) * $variables[3]->valor;
		        						$cinta = $cinta / $detalle->cantidad;
		        						$cintaCal = $cinta;
		        						$totalCinta = $totalCinta + $cintaCal;
		        						$totalCintaA = number_format($totalCinta, 2, ",", ".");
		        						$cinta = number_format($cinta, 2, ",", ".");
                                        }
		        					@endphp
		        					{{ $cinta }}
		        					
		        				</td>
		        				<td>
		        					@php
		        						
		        						if($detalleOrden[0]->precioTotalGlobal == 0)
		        						{	
		        							$costo3 = 0;
		        							$costo3Cal = 0;
		        							$totalCosto3A = 0;
                                            $detalleOrden[0]->precioTotalGlobal = 0;
                                        }else{
                                        $costo3 = ($precioTotal / $detalleOrden[0]->precioTotalGlobal) * $variables[3]->valor;
		        						$costo3 = $costo3 / $detalle->cantidad;
		        						$costo3Cal = $costo3;
		        						$totalcosto3 = $totalCosto3 + $costo3Cal;
		        						$totalCosto3A = number_format($totalCosto3, 2, ",", ".");
		        						$costo3 = number_format($costo3, 2, ",", ".");
                                        }
		        					@endphp
		        						
		        					{{ $costo3 }}
		        				</td>
		        				<td>
		        					
		        					@php
		        						$costoUsdCop = $precioUnidad + $valorArancelCal + $empaqueCal + $cintaCal + $costo3Cal;
		        						$costoUsdCopCal = $costoUsdCop;
		        						$totalCostoUsdCol = $totalCostoUsdCol +  $costoUsdCop;
		        						$totalCostoUsdColA = number_format($totalCostoUsdCol, 2, ",", ".");
		        						$costoUsdCop = number_format($costoUsdCop, 2, ",", ".");
		        					@endphp
		        					{{  $costoUsdCop }}
		        				</td>
		        				<td>
		        					<input type="text" name="margenCop[]" value="{{$detalle->margenCop}}">
		        				</td>
		        				<td>
		        					<input type="text" name="TE[]" value="{{$detalle->TE}}">
		        				</td>	
		        				<td>
		        					@php
				        				$precioTotalUsdCol = $costoUsdCopCal / (100 - $detalle->margenCop)*100;
				        				$precioTotalUsdColA = number_format($precioTotalUsdCol, 2, ",", ".");  
				        			@endphp
				        			{{ $precioTotalUsdColA }}
		        				</td>				        			
			        			<td><input type="hidden" name="detalle_id[]" value="{{$detalle->id}}"></td>
			        		</tr>
			        	@endforeach
			        		<tr>
			        			<td><label class="text-danger">Totales</label></td>
				        		<td></td>
				        		<td></td>
				        		<td></td>
				        		<td></td>
				        		<td></td>
				        		<td><label class="text-primary">{{ $cantidadTotalGlobal }}</label></td>
				        		<td>
				        			<label class="text-primary">{{ $pesoTotalGlobal }}</label>
				        		</td>
				        		<td></td>
				        		<td><label class="text-primary">{{ $totalFleteUnidad }}</label></td>
				        		<td>
				        			@php
				      					$costoTotalFleteGlobal = number_format($costoTotalFleteGlobal, 2, ",", ".");
				      				@endphp
				      				<label class="text-primary"> {{ $costoTotalFleteGlobal }}</label>
				        		</td>
				        		<td></td>
				        		<td></td>
				        		<td><label class="text-primary">{{ $totalPrecioUnitario }}</label></td>
				        		
				        		<td>
				        			@php
				      					$precioTotalGlobal = number_format($precioTotalGlobal, 2, ",", ".");
				      				@endphp
				      				<input type="hidden" name="totalPrecioTotalUsd" value="{{ $precioTotalGlobalCal }}">
				      				<label class="text-primary">{{ $precioTotalGlobal }}</label>
				        		</td>
				        		<td></td>
				        		<td>
				        			
				        			<label class="text-primary">{{ $totalValorArancelA }}</label>
				        		</td>
				        		<td>
				        			
				        			<label class="text-primary">{{ $totalEmpaqueA }}</label>
				        		</td>
				        		<td>
				        			
				        			<label class="text-primary">{{ $totalCintaA }}</label>
				        		</td>
				        		<td>
				        			
				        			<label class="text-primary">{{ $totalCosto3A }}</label>
				        		</td>
				        		<td>
				        			
				        			<label class="text-primary">{{ $totalCostoUsdColA }}</label></td>
				        		<td></td>
				        		<td></td>
				        		<td>

				        		</td>
			        		</tr>

			        </tbody>
			      	</table>
		      <!--Seccion solo para el Administrador-->
		      <!--Asignar Usuario para gestionar la orden-->
	      	</div>
			<div class="form-group col-md-12">
	    		<button type="submit" class="btn btn-primary col-md-offset-5">Actualizar la Factura</button>
	    	</div>
    	</form>
    	<form class="form" method="POST" action="{{ route('facturas.generar.facturaOrden') }}">
    		{{ csrf_field() }}
    		<input type="hidden" name="ordenId" value="{{$orden_id}}">
			@foreach($detalleOrden as $detalle)
				{{--<input type="hidden" name="detalle[]" value="{{$detalle}}">--}}
				<input type="hidden" name="detalleId[]" value="{{$detalle->id}}">
				<input type="hidden" name="empaque" value="{{ $variables[3]->valor }}">
				<input type="hidden" name="cinta" value="{{ $variables[4]->valor }}">
				<input type="hidden" name="costo3" value="{{ $variables[5]->valor }}">
			@endforeach
    		<div class="form-group col-md-6">
	    		<button type="submit" class="btn btn-success">Generar la Factura</button>
	    	</div>
    	</form>
    	<!--Actualizar la orden a cotizado y enviar los datos al cliente-->
    </div>
    
    
@stop

@section('totalSede')
	<div class="box col-md-6">
	    <!-- /.box-header -->
	    <div class="box-body table-responsive col-md-4 col-md-offset-4 bg-success">
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
      	</div>
  	</div>
@stop