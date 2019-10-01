
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
	    		if($detalleOrden[0]->estado_id == 15){
	    			$nombreEstado = "En Negociación";	
	    		}	    		
	    	@endphp
	      <h3 class="box-title">Detalles de la Orden <b>{{$orden_id}}</b> - Estado <b>{{$nombreEstado}}</b>
	    </div>
	    <!-- /.box-header -->

	    <form class="form" method="POST" action="{{ route('ordenes.update') }}">
		{{ csrf_field() }}
	    <div class="box-body table-responsive col-md-12 bg-warning">
	    	<input type="hidden" name="ordenId" value="{{$orden_id}}">
		      <table id="example1" class="table table-bordered table-striped">
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
		        		<th>Costo Unitario</th>
		        		<th>% Margen USA</th>
		        		<th>Precio Unitario USD</th>
		        		<th>Precio Total USD</th>
		        		
		        		@php
		        			
		        			$user = auth()->user()->rol_id;
		        			
		        		@endphp
		        		@if($user == 1 && $orden->estado_id == 15)
		        			<th>Negociación</th>
	        			@endif
		        		@if($user == 2)

			        		@if($orden->convencion_id == 2 || $orden->convencion_id == 3)
			        			<th>% Arancel</th>
				        		<th>Valor Arancel</th>
				        		<th>
				        			<input type="hidden" name="nombreVar1" value="{{ $detalleOrden[0]->nombreVar1 }}">
				        			<input type="hidden" name="valorVar1" value="{{ $detalleOrden[0]->valorVar1 }}">
				        			{{$detalleOrden[0]->nombreVar1}}
				        		</th>
				        		<th>
				        			<input type="hidden" name="nombreVar2" value="{{ $detalleOrden[0]->nombreVar2 }}">
				        			<input type="hidden" name="valorVar2" value="{{ $detalleOrden[0]->valorVar2 }}">
				        			{{$detalleOrden[0]->nombreVar2}}
				        		</th>
				        		<th>
				        			<input type="hidden" name="nombreVar3" value="{{ $detalleOrden[0]->nombreVar3 }}">
				        			<input type="hidden" name="valorVar3" value="{{ $detalleOrden[0]->valorVar3 }}">
				        			{{$detalleOrden[0]->nombreVar3}}
				        		</th>
				        		<th>USD Colombia</th>
				        		<th>% Margen COP</th>
				        		<th>TE</th>
				        		<th>Precio USD COL</th>
				        		<th>Precio Total USD COL</th>
			        		@endif
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
		        			<td>
		        				<input name="pesoLb[]" id="pesoLb{{$detalle->id}}" value="{{$detalle->pesoLb}}" onchange="calculalPesoLibras({{$detalle->id}},this,{{$detalle->cantidad}})">

		        				<input type="hidden" id="valorPesoLibra{{$detalle->id}}" value="{{$variables[1]->valor}}">
		        			</td>
		        			<td>
		        				@foreach($detallePeso as $detalleP)
		        					@if($detalle->sede_id == $detalleP->sede_id)
		        						@if($detalleP->PesoSede < 9)
		        							@php
			        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
			        							$pesoTotalGlobal = $pesoTotalGlobal + $promedio;
		        							@endphp
		        							<input type="hidden" name="pesoPromedio[]" value="{{ $promedio }}">
		        							<label>{{ $promedio }}</label>
	        							@else
	        								@php
	        									$pesoTotalGlobal = $pesoTotalGlobal + $detalle->pesoLb;
	        								@endphp
	        								<input type="hidden" name="pesoPromedio[]" value="0">
	        								<label>{{ $detalle->pesoLb }}</label>
        								@endif
		        					@endif	
		        				@endforeach
		        			
		        			</td>
		        			{{--Peso Total--}}	
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
		        			{{--Flete unidad--}}			        			        			
		        			<td class="bg-danger">
		        				@php
		        					$totalFleteUnidad = $totalFleteUnidad + $costoFleteUnidad;
		        				@endphp
		        				<label id="costoFlete{{$detalle->id}}">{{$costoFleteUnidad}}</label>
		        			</td>
		        			{{--Costo total Flete--}}
		        			<td class="bg-danger">
		        				@foreach($detallePeso as $detalleP)
		        					@if($detalle->sede_id == $detalleP->sede_id)
		        						@if($detalleP->PesoSede < 9)
		        							@php
			        							$promedio = (float) 9 / (float) $detalleP->cantidadSede;
			        							$costoTotalFlete = $promedio * $variables[1]->valor;
			        							$totalFlete = $costoTotalFlete * 1;
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
		        			{{--Costo Unitario--}}
	        				<td>
	        					@php
	        						$totalCostoUnitario = $totalCostoUnitario + $detalle->costoUnitario;
	        					@endphp
	        					<input type="text" name="costoUnitario[]" value="{{$detalle->costoUnitario}}">
	        				</td> 
	        				{{--Margen USA--}} 			
		        			<td>
		        				<input name="margenUsa[]" value="{{$detalle->margenUsa}}">
		        			</td>
		        			{{--Precio Venta Unitario USD--}}
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
					        					$totalPrecioUnitario = $totalPrecioUnitario + $precioUnidad;
	        								@endphp
		        							<input type="hidden" name="pesoPromedio[]" value="{{ $promedio }}">
		        							<label>{{ $precioUnidad }}</label>
	        							@else
	        								@php
	        									$costoTotalFlete = $detalle->pesoLb * $variables[1]->valor;

	        									$a = $detalle->costoUnitario;
					        					$b = $detalle->margenUsa;
					        					$prom = $a * $b / 100;

					        					$precioUnidad = $a + $prom + $costoTotalFlete;	
					        					$totalPrecioUnitario = $totalPrecioUnitario + $precioUnidad;
	        								@endphp
	        								
	        								<label id="costoTotalFlete{{$detalle->id}}">{{$precioUnidad}}</label>
        								@endif
		        					@endif	
		        				@endforeach
	        				</td>
	        				{{--Precio Total USD--}}
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

	        				@php
        						$valorArancel = $precioUnidad * $detalle->porcentajeArancel / 100;
        						$valorArancelCal = $valorArancel;
        						$totalValorArancel = $totalValorArancel + $valorArancelCal;
        						$totalValorArancelA = number_format($totalValorArancel, 2, ",", ".");
        						$valorArancel = number_format($valorArancel, 2, ",", ".");

        					@endphp

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

        					@php
        						$costoUsdCop = $precioUnidad + $valorArancelCal + $empaqueCal + $cintaCal + $costo3Cal;
        						$costoUsdCopCal = $costoUsdCop;
        						$totalCostoUsdCol = $totalCostoUsdCol +  $costoUsdCop;
        						$totalCostoUsdColA = number_format($totalCostoUsdCol, 2, ",", ".");
        						$costoUsdCop = number_format($costoUsdCop, 2, ",", ".");
        					@endphp

	        				@if($user == 2)
		        				@if($orden->convencion_id == 2 || $orden->convencion_id == 3)
	        						<td>
			        					<input type="text" name="porcentajeArancel[]" value="{{$detalle->porcentajeArancel}}">
			        				</td>
			        				{{--Valor del Arancel--}}
			        				<td>
			        					
			        					{{$valorArancel}}
			        				</td>
				        			{{--Valor Empaque o variable 1--}}	
			        				<td>
			        					
			        					
			        					{{ $empaque }}
			        				</td>
			        				{{--Valor Cinta o Variable 2--}}
			        				<td>	
			        					
			        					{{ $cinta }}
			        					
			        				</td>
			        				{{--Valor Costo 3 o Variable 3--}}
			        				<td>
			        					
			        						
			        					{{ $costo3 }}
			        				</td>
			        				{{--Costo USA Colombia--}}
			        				<td>
			        					
			        					
			        					{{  $costoUsdCop }}
			        				</td>
			        				{{--Margen COP--}}
			        				<td>
			        					<input type="text" name="margenCop[]" value="{{$detalle->margenCop}}">
			        				</td>
			        				{{--TE--}}
			        				<td>
			        					<input type="text" name="TE[]" value="{{$detalle->TE}}">
			        				</td>
			        				{{--Precio USA Colombia--}}	
			        				<td>
			        					@php
					        				$precioTotalUsdCol = $costoUsdCopCal / (100 - $detalle->margenCop)*100;
					        				$precioTotalUsdColA = number_format($precioTotalUsdCol, 2, ",", ".");  
					        			@endphp
					        			{{ $precioTotalUsdColA }}
			        				</td>
			        				{{--Precio Total USA Colombia--}}
			        				<td>
			        					@php
					        				$TotalprecioTotalUsdCol = $precioTotalUsdCol * $detalle->cantidad;

					        				$TotalprecioTotalUsdColA = number_format($TotalprecioTotalUsdCol, 2, ",", ".");  
					        			@endphp
					        			{{ $TotalprecioTotalUsdColA }}
			        				</td>

		        				@endif
	        				@endif
	        				{{--Procentaje Arancel--}}
	        				@if($user == 1 && $orden->estado_id == 15)
			        			<td><label>{{$detalle->negociacion}}</label></td>
		        			@endif
		        			<td>
		        				<input type="hidden" name="detalle_id[]" value="{{$detalle->id}}">
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
									        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="crearDivisiones()">Aceptar</button>
									      </div>
									    </div>
						  			</div>
								</div>		        				
		        			</td>
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
		        		<td></td>
		        		<td></td>
		        		<td></td>
		        		<td class="bg-success">
		        			<label class="text-primary">{{ $pesoTotalGlobal }}</label>
		        		</td>
		        		
		        		<td class="bg-danger"><label class="text-primary">{{ $totalFleteUnidad }}</label></td>
		        		<td class="bg-danger">
		        			@php
		      					$costoTotalFleteGlobal = number_format($costoTotalFleteGlobal, 2, ",", ".");
		      				@endphp
		      				<label class="text-primary"> {{ $costoTotalFleteGlobal }}</label>
		        		</td>
		        		<td>
		        			@php
		        				$totalCostoUnitarioVis = number_format($totalCostoUnitario, 2, ",", ".");
		      				@endphp
		        			<label>{{ $totalCostoUnitarioVis }}</label>
		        		</td>
		        		<td></td>
		        		<td><label class="text-primary">{{ $totalPrecioUnitario }}</label></td>
		        		
		        		<td>
		        			@php
		      					$precioTotalGlobal = number_format($precioTotalGlobal, 2, ",", ".");
		      				@endphp
		      				<input type="hidden" name="totalPrecioTotalUsd" value="{{ $precioTotalGlobalCal }}">
		      				<label class="text-primary">{{ $precioTotalGlobal }}</label>
		        		</td>
		        		@if($user == 1 && $orden->estado_id == 15)
		        			<td>T</td>
	        			@endif
		        		@if($user == 2)
		        		@if($orden->convencion_id == 2 || $orden->convencion_id == 3)
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
			        		
			        		<td></td>
			        		<td></td>
		        		@endif
		        		@endif
		        		
		        		<td></td>
	        		</tr>
		        	
		        </tbody>
		      	</table>
	      <!--Seccion solo para el Administrador-->
	      <!--Asignar Usuario para gestionar la orden-->
      	</div>
      	
		<div class="form-group col-md-12">
    		<button type="submit" class="btn btn-primary col-md-offset-5">Actualizar la Orden</button>
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
        		alert('El numero tiene que ser mayor a 1 y menor o igual a '+Number(document.getElementById('cantidad'+detalle_id).value));
        	}   		
    	}

    	function crearDivisiones()
    	{

    	}
    </script>
    
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
      	@if($user == 2)
      	@if($orden->convencion_id == 2 || $orden->convencion_id == 3)
      		<div class="col-md-6 bg-success">
	      		<form class="form" method="POST" action="{{ route('ordenes.actualizarVariableOrden') }}">
	      			{{ csrf_field() }}
	      			<table class="table table-bordered table-striped table-hover">
			  			<thead>
			  				<tr>
				  				<td>
				  					<input type="hidden" name="ordenId" value="{{$orden_id}}">
				  					Concepto
				  				</td>
				  				<td>Valor</td>
				  			</tr>
			  			</thead>
			  			<tbody>
			  				<tr>
			  					<td><input type="text" class="form-control" name="nombreVariable1" value="{{ $detalleOrden[0]->nombreVar1 }}"></td>
			  					<td><input type="text" class="form-control" name="valorVariable1" value="{{ $detalleOrden[0]->valorVar1 }} "></td>
			  				</tr>
			  				<tr>
			  					<td><input type="text" class="form-control" name="nombreVariable2" value="{{ $detalleOrden[0]->nombreVar2 }}"></td>
			  					<td><input type="text" class="form-control" name="valorVariable2" value="{{ $detalleOrden[0]->valorVar2 }}"></td>
			  				</tr>
			  				<tr>
			  					<td><input type="text" class="form-control" name="nombreVariable3" value="{{ $detalleOrden[0]->nombreVar3 }}"></td>
			  					<td><input type="text" class="form-control" name="valorVariable3" value="{{ $detalleOrden[0]->valorVar3 }}"></td>
			  				</tr>
			  			</tbody>
			  		</table>
			  		<button type="submit" class="btn btn-success ">Actualizar Variables</button>
	      		</form>	  		
		  	</div>
      	@endif
      	@endif
      	      	
  	</div>
@stop
@section('tres')
	<form class="form" method="POST" action="{{ route('ordenes.cotizarOrden',$orden_id) }}">
		{{ csrf_field() }}
		@if($user == 1 && $orden->estado_id == 15)
			<div class="form-group col-md-12">
				<input type="hidden" name="ordenId" value="{{$orden_id}}">
	    		<button type="submit" class="btn btn-success col-md-offset-5">Aceptar Negociación</button>
	    	</div>
		@endif
		@if($user == 2 && $orden->estado_id == 3)
			<div class="form-group">
			<input type="hidden" name="ordenId" value="{{$orden_id}}">
			<button type="submit" class="btn btn-danger">Enviar al Cliente</button>
		</div>
		@endif
		
	</form>
@stop