@extends('admin.layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-warning">
                <div class="box-body">
                    <form method="POST" action="{{ route('almacenarUsuario') }}">
                        {{ csrf_field() }}

                        <div class="form-group col-md-6">
                            <label for="name" class="col-form-label text-md-right">Nombre Completo</label>
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-6">
                            <label for="email" class="col-form-label text-md-right">E-mail</label>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="telefono" class="col-form-label text-md-right">Teléfono</label>
                            <input id="telefono" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="telefono" value="{{ old('telefono') }}" required>
                            @if ($errors->has('telefono'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="direccion" class="col-form-label text-md-right">Dirección</label>
                            <input id="direccion" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="direccion" value="{{ old('direccion') }}" required>
                            @if ($errors->has('direccion'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-4 col-md-offset-4">
                            <label for="rol" class="col-form-label text-md-right">Rol</label>
                               <select name="rol" class="form-control" required>
                                <option value="0">Selecciona un rol</option>
                                <option value="8">Usuario USA</option>
                                <option value="2">Usuario COL</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-warning" >Registrar</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
