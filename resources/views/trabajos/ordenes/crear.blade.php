@extends('admin.layout')
@section('header')
	<h1>
	    Ordenes
	    <small> En esta sección podra crear las ordenes</small>
  	</h1>
  	<ol class="breadcrumb">
	    <li class="active">Ordenes</li>
  	</ol>
@stop

@section('contenido')

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			    <div class="box-header">
			      <h3 class="box-title">Ingrese los datos para crear una nueva orden</h3>
			    </div>
			    <!-- /.box-header -->

		    	<div class="box-body">
		    		<div class="form-group col-md-3">
						<label>Sede</label>
						<select class="form-control" name="sede" id="sede" required>
							<option value="0">Selecciona una Sede</option>
							@foreach($sedes as $sede)
								<option value="{{ $sede->id }}">- {{ $sede->nombre }}</option>
							@endforeach
						</select>
					</div>
		    		<div class="form-group col-md-3">
		    			<label>Marca</label>
		    			<select class="js-example-basic-single form-control" name="marca" id="marca" required>
							<option value="0">Selecciona una marca</option>
							@foreach($marcas as $marca)
								<option value="{{ $marca->id }}">- {{ $marca->nombreMarca }}</option>
							@endforeach
						</select>
		    		</div>
		    		<div class="form-group col-md-3">
		    			<label>Refencia</label>
		    			<input name="direccion" id="referencia" class="form-control" placeholder="Ingrese la referencia" required></input>
		    		</div>
		    		<div class="form-group col-md-3">
		    			<label>Cantidad</label>
		    			<input type="number" min="1" value="1" name="cantidad" id="cantidad" class="form-control" placeholder="Ingrese la cantidad" required></input>
		    		</div>
		    		<div class="form-group col-md-3">
		    			<label>Descripción</label>
		    			<input name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese la descripcion" required></input>
		    		</div>
		    		<div class="form-group col-md-3">
		    			<label>VIN</label>
		    			<input name="vin" id="descripcion" class="form-control" placeholder="Ingrese la descripcion" ></input>
		    		</div>
		    		<div class="form-group col-md-3">
		    			<label>Placa</label>
		    			<input name="placa" id="descripcion" class="form-control" placeholder="Ingrese la descripcion" ></input>
		    		</div>
		    		<div class="form-group col-md-3">
		    			<label>Comentarios</label>
		    			<input name="comentarios" id="comentarios" class="form-control" placeholder="Ingrese los comentarios"></input>
		    		</div>
		    		<div class="form-group">
		    			<input type="button" class="btn btn-warning" value="agregar" onclick="agregar_a_list()">
		    		</div>
		    		<div class="form-group">
		    			<input type="button" class="btn btn-success" value="importar excel" data-toggle="modal" data-target="#exampleModal">
		    		</div>
		    	</div>
		    	<!--Incluye  modal para importar excel-->
		    	@include('trabajos.ordenes.modal_importar_excel')



				<form id="crearOrden" method="POST" action="{{ route('ordenes.almacenar') }}">
		    		{{ csrf_field() }}
		    		<table class="table ">
		    			<thead>
		    				<tr class="bg-primary">
		    					<th>Sede</th>
			    				<th>Marca</th>
			    				<th>Refencia</th>
			    				<th>Cantidad</th>
			    				<th>Descripción</th>
			    				<th>Comentarios</th>
		    				</tr>
		    			</thead>

		    			<tbody id="body_table">

		    			</tbody>
		    		</table>

		    		<div class="form-group col-md-3 col-md-offset-3">

						<select class="form-control" name="convencion" id="convencion" required>
							<option value="">Selecciona el tipo de Moneda</option>
							@foreach($convenciones as $conven)
								<option value="{{ $conven->id }}">- {{ $conven->nombreConvencion }}</option>
							@endforeach
						</select>
					</div>

		    		<div class="form-group col-md-4">
		    			<button type="submit" class="btn btn-primary">Crear Orden</button>
		    		</div>
		    	</form>
		    </div>
		</div>
	</div>
	<script type="text/javascript">

	    var arreglo=[];

	    function agregar_a_list(){



	        if(document.getElementById('sede').value=="0"){
	        	alert("Debes seleccionar una sede");
	        	return false;
	        }
	        if(document.getElementById('marca').value==""){
	        	alert("Debes ingresar una marca");
	        	return false;
	        }
	        if(document.getElementById('referencia').value==""){
	        	alert("Debes ingresar una referencia");
	        	return false;
	        }
	        if(document.getElementById('cantidad').value==""){
	        	alert("Debes ingresar la cantidad");
	        	return false;
	        }
	        if(document.getElementById('descripcion').value==""){
	        	alert("Debes ingresar una descripción de tu producto");
	        	return false;
	        }
	        var ob={
	        	sede:document.getElementById('sede').value,
	            marca:document.getElementById('marca').value,
	            referencia:document.getElementById('referencia').value,
	            cantidad:document.getElementById('cantidad').value,
	            descripcion:document.getElementById('descripcion').value,
	            comentarios:document.getElementById('comentarios').value
	        };

	        arreglo.push(ob);
	        draw_table();


	    }

	    function draw_table(){
	        console.log(arreglo);
	        var t = document.getElementById('body_table');
	        //limpio lo que tenia en la tabla
	        t.innerHTML="";
	        for(var f in arreglo){

	            //creo un tr
	            var tr = document.createElement('tr');
	            //creo un td

	            var td = document.createElement('td');
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','sede[]');
	            hd.value=arreglo[f].sede;
	            for(var i in document.getElementById("sede").options){
	            	if(document.getElementById("sede").options[i].value==arreglo[f].sede){
	            		label.innerHTML=document.getElementById("sede").options[i].text;
	            		break;
	            	}
	            }
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);

	            var td = document.createElement('td');
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','marca[]');
	            hd.value=arreglo[f].marca;
	            label.innerHTML=arreglo[f].marca;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);

	            var td = document.createElement('td');
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','referencia[]');
	            hd.value=arreglo[f].referencia;
	            label.innerHTML=arreglo[f].referencia;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);

	            var td = document.createElement('td');
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','cantidad[]');
	            hd.value=arreglo[f].cantidad;
	            label.innerHTML=arreglo[f].cantidad;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);

	            var td = document.createElement('td');
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','descripcion[]');
	            hd.value=arreglo[f].descripcion;
	            label.innerHTML=arreglo[f].descripcion;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);

	            var td = document.createElement('td');
	            //creo un label
	            var label = document.createElement('label');
	            var hd = document.createElement('input');
	            hd.setAttribute('type','hidden');
	            hd.setAttribute('name','comentarios[]');
	            hd.value=arreglo[f].comentarios;
	            label.innerHTML=arreglo[f].comentarios;
	            td.appendChild(hd);
	            td.appendChild(label);
	            //agrego el campo a la fila de la tabla
	            tr.appendChild(td);


	            //agrego la fila a el cuerpo de la tabla
	            t.appendChild(tr);
	        }


	        document.getElementById('sede').value="0";
	        document.getElementById('marca').value="";
	        document.getElementById('referencia').value="";
	        document.getElementById('cantidad').value=1;
	        document.getElementById('descripcion').value="";
	        document.getElementById('comentarios').value="";
	    }
	</script>
@stop
