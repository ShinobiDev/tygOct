@extends('admin.layout')

@section('contenido')
	<div class="box box-warning">
	    <div class="box-header">
	      <h3 class="box-title">Detalle de los Usuarios</h3>
	    </div>
	    <!-- /.box-header -->
	    <div class="box-body">
	      <table id="example1" class="table table-bordered table-striped">
	        <thead>
	        	<tr>

	        		<th>Nombre</th>
	        		<th>Email</th>
	        		<th>Telefono</th>
	        		<th>Contacto</th>
	        		<th>Rol</th>
	        		<th>Acciones</th>
	        	</tr>
	        </thead>

	        <tbody>
	        	@foreach($usuarios as $usuario)
	        		<tr>

	        			<td>{{ $usuario->name }}</td>
	        			<td>{{ $usuario->email }}</td>
	        			<td>{{ $usuario->telefono }}</td>
	        			<td>{{ $usuario->direccion }}</td>
	        			<td>{{ $usuario->nombre }}</td>
	        			<td>
	        				@if($usuario->estado_id == 2 || $usuario->estado_id == 3)
	        					<button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#exampleModal{{$usuario->id}}">Activar</button>
	        					<div class="modal fade" id="exampleModal{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

									<form method="POST" action="{{route('usuarios.actualizarActivar',$usuario->id)}}">
						    			{{ csrf_field() }}
										<div class="modal-dialog" role="document">
											<input type="hidden" name="userId" value="{{$usuario->id}}">
										    <div class="modal-content">

										      <div class="modal-header">
										        <h5 class="modal-title" id="exampleModalLabel">Activar {{$usuario->name}}</h5>
										        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
										          <span aria-hidden="true">&times;</span>
										        </button>
										      </div>

										      <div class="modal-body">
								        		<div class="form-check">
												    <input type="checkbox" name="Dolar" class="form-check-input" id="exampleCheck1">
												    <label class="form-check-label" for="exampleCheck1">Dolar</label>
											  	</div>
											  	<div class="form-check">
												    <input type="checkbox" name="DolarColombia" class="form-check-input" id="exampleCheck2">
												    <label class="form-check-label" for="exampleCheck2">Dolar Colombia</label>
											  	</div>	
											  	<div class="form-check">
												    <input type="checkbox" name="clienteVIP" class="form-check-input" id="exampleCheck3">
												    <label class="form-check-label" for="exampleCheck3">CLiente VIP</label>
											  	</div>								      	
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
										        <button type="submit" class="btn btn-success">Activar Usuario</button>
										      </div>
										    </div>
										</div>
									</form>
					  			</div>
	        				@endif
	        				@if($usuario->estado_id ==1)
	        					<a class="btn btn-xs" ><i class="fa fa-time"></i>Eliminar</a>
	        				@endif
		        			<button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#exampleModal{{$usuario->id}}">Editar</button>
		        			{{--<a href="" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>--}}
		        			<div class="modal fade" id="exampleModal{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

								<form method="POST" action="{{route('usuarios.actualizar',$usuario->id)}}">
					    			{{ csrf_field() }}
									<div class="modal-dialog" role="document">

									    <div class="modal-content">

									      <div class="modal-header">
									        <h5 class="modal-title" id="exampleModalLabel">Editar información de {{$usuario->name}}</h5>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>

									      <div class="modal-body">

								        	<div class="form-group">
								    			<label>Nombre</label>
								    			<input name="nombre" class="form-control" value="{{ $usuario->name }}" required></input>
								    			<input type="hidden" name="id" class="form-control" value="{{ $usuario->id }}"></input>
								    		</div>
								    		<div class="form-group">
								    			<label>Dirección</label>
								    			<input name="direccion" class="form-control" value="{{ $usuario->direccion }}" required></input>
								    		</div>
								    		<div class="form-group">
								    			<label>Teléfono</label>
								    			<input name="telefono" class="form-control" value="{{ $usuario->telefono }}" required></input>
								    		</div>
								    		<div class="form-group">
								    			<label>Email</label>
								    			<input name="email" class="form-control" value="{{ $usuario->email }}" required></input>
								    		</div>

												<div class="form-group">
													<label for="rol" class="col-form-label text-md-right">Rol</label>
														 <select name="rol" required>
															 <option value="0">Selecciona un rol</option>

															@foreach($roles as $r)

																@if($usuario->rol_id == $r->id)
																	<option value="{{$r->id}}" selected>{{$r->nombre}}</option>
																	@break
																@else
																	<option value="{{$r->id}}">{{$r->nombre}}</option>
																@endif
															@endforeach
														 </select>
												</div>

									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
									        <button type="submit" class="btn btn-success">Editar Usuario</button>
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
