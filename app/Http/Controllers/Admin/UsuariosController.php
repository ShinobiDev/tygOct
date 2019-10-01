<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\UsuarioCreado;
use App\User;
use App\Sede;
use App\Rol;
use App\Convencion;
use App\Cliente;
use App\DetalleConvencion;
use DB;
class UsuariosController extends Controller
{
	public function panel()
	{
		return view('admin.panel');
	}
    public function index()
    {
        $user = auth()->user()->id;

        //dd($user);
        $usuarios = User::select('users.id',
								 'name',
								 'email',
								 'telefono',
								 'direccion',
								 'rol_id',
                                 'estado_id',
								 'rols.nombre')
	        ->join('rols','users.rol_id','=','rols.id')
					->get();
				$roles=Rol::all();
        return view('auth.index', compact('usuarios','user','roles'));
    }

    public function indexCliente()
    {
        $user = auth()->user()->id;
        $cliente = auth()->user()->cliente_id;
        //dd($user);
        $usuarios = User::select('users.id',
                                 'name',
                                 'email',
                                 'telefono',
                                 'direccion',
                                 'rol_id',
                                 'estado_id',
                                 'rols.nombre')
        ->join('rols','users.rol_id','=','rols.id')
        ->where('cliente_id',$cliente)
        ->get();
        $roles=Rol::all();
        return view('auth.indexCliente', compact('usuarios','user','roles'));
    }

    public function crear()
    {
        $roles=Rol::all();
				//dd($roles);
        return view('admin.usuarios.crear',compact('roles'));
    }

    public function crearCliente()
    {
        $cliente = auth()->user()->cliente_id;
        $roles=Rol::select('nombre')
        ->whereIn('id',[6,7])
        ->get();
        //dd($roles);
        $sedes = Sede::select('id','nombre','telefono','direccion','contactoSede')
        ->where('cliente_id','=',$cliente)
        ->get();
        return view('admin.usuarios.crearUsuarioCliente',compact('roles','sedes'));
    }

    public function almacenarUsuario(Request $request)
    {
        //dd('almacenarUsuario TyG');
		$data = $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'telefono'=> 'required|unique:users',
          'direccion'=>'required',
					'rol'=>'required'
        ]);
				//dd($data);
        //Instanciamos un objeto del modelo User, para guardar los datos en la BD
        $user = new User;

        $pass = str_random(8);
        $user->estado_id = 1;
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($pass);
        $user->telefono = $request->get('telefono');
        $user->direccion = $request->get('direccion');
        $user->rol_id = $request->get('rol');
        $user->save();

        $user = $user->email;
        //dd($user);
        //dd($pass);
        //Enviamos el email con las credenciales de acceso
        UsuarioCreado::dispatch($user, $pass);
        return back()->with('flash','El usuario fue creado exitosamente');
    }
    /*Usuario nuevo creado desde el formulario de registro*/
    public function almacenarNuevoUsuarioCliente(Request $request)
    {
        //dd($request);
        if((int)$request->tipoCLiente == 1)
        {   
            //dd('debe entrar aca');
            $email = DB::table('users')->where('email',$request->email)->get();
            $nit = DB::table('users')->where('documento',$request->documento)->get();
            //dd($dato);
            if(count($email)>0)
            {
                return back()->with('error','El email ya esta registrado');
            }
            if(count($nit)>0)
            {
                return back()->with('error','El documento no es valido');
            }



            $pass = str_random(8);
            $user = new User();
            $user->rol_id = 3;
            $user->estado_id = 3;
            $user->name = $request->nombre;
            
            $user->documento = $request->documento;
            $user->email = $request->email;
            $user->telefono = $request->telefono;
            $user->password = bcrypt($pass);
            $user->save();
            //dd($user);
            $u = $user->id;

            $cliente = new Cliente;
            $cliente->nombreCliente = $request->nombre;
            $cliente->documento = $request->documento;
            $cliente->tipoDocumento_id = 1;
            $cliente->direccion = $request->direccion;
            $cliente->direccion = $request->telefono;
            $cliente->nombreRepresentanteLegal = $request->nombre;
            $cliente->estado_id = 1;
            $cliente->pais = $request->pais;
            $cliente->ciudad = $request->ciudad;
            $cliente->save();

            $item = User::where('id',$u)->first();
            $item->cliente_id = $u;
            $item->update();

            $convencion3 = 3;
            $conv = new DetalleConvencion;
            $conv->usuario_id = $user->id;
            $conv->convencion_id = $convencion3;
            $conv->save();

            $user = $user->email;
            UsuarioCreado::dispatch($user, $pass);
            return back()->with('flash','El usuario fue creado exitosamente, ingresa a tu email, donde encontraras los datos de acceso.');
        }
        if((int)$request->tipoCLiente == 2)
        {
            $email = DB::table('users')->where('email',$request->email)->get();
            $nit = DB::table('users')->where('documento',$request->documentoJuridico)->get();
            //dd($dato);
            if(count($email)>0)
            {
                return back()->with('error','El email ya esta registrado');
            }
            if(count($nit)>0)
            {
                return back()->with('error','El nit no es valido');
            }
            //dd($request);

            $pass = str_random(8);
            $user = new User();
            $user->rol_id = 4;
            $user->estado_id = 3;
            $user->name = $request->nombreJuridico;
            $user->documento = $request->documentoJuridico;
            $user->email = $request->email;
            $user->pais = $request->pais;
            $user->estado = $request->estado;
            $user->ciudad = $request->ciudad;
            $user->direccion = $request->direccion;
            $user->telefono = $request->telefono;
            $user->password = bcrypt($pass);
            $user->tipoUsuario = $request->tipoCLiente;
            $user->nombreRepresentante = $request->nombreRepresentante;
            $user->save();


            //dd($user);

            $u = $user->id;

            $item = User::where('id',$u)->first();
            $item->cliente_id = $u;
            $item->update();
            
            $convencion3 = 3;
            $conv = new DetalleConvencion;
            $conv->usuario_id = $user->id;
            $conv->convencion_id = $convencion3;
            $conv->save();

            $user = $user->email;
             UsuarioCreado::dispatch($user, $pass);
        return back()->with('flash','El usuario fue creado exitosamente, ingresa a tu email, donde encontraras los datos de acceso.');
        }
    }
    /*Usuario creado desde el perfil administrativo de los clientes*/
    public function almacenarUsuarioCliente(Request $request)
    {
        $cliente = auth()->user()->id;
        //dd($request);
        $user = new User;

        $pass = str_random(8);
        $user->estado_id = 1;
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($pass);
        $user->telefono = $request->get('telefono');
        $user->direccion = $request->get('direccion');
        $user->documento = $request->get('documento');
        $user->rol_id = $request->get('rol');
        $user->cliente_id = $cliente;
        $user->save();

        $user = $user->email;
        $userAdmin = User::where('rol_id',1);
        //dd($user);
        //dd($pass);
        //Enviamos el email con las credenciales de acceso
        UsuarioCreado::dispatch($user, $pass);
        //UsuarioCreadoAdmin::dispatch($userAdmin, $user);

        return back()->with('flash','El usuario fue creado exitosamente');
    }

    public function actualizar_usuario(Request $request)
    {
     //dd([$request,$request->get('nombre')]);

     User::where('id',$request['id'])->update([
                                        "name"=>$request->get('nombre'),
                                        "email"=>$request->get('email'),
                                        "telefono"=>$request->get('telefono'),
                                        "direccion"=>$request->get('direccion'),
										"rol_id"=>$request->get('rol')
                                        ]);
     return back()->with('flash','El usuario fue actualizado exitosamente');
    }

    public function activar(Request $request)
    {
        $user = auth()->user()->id;
        $cliente = auth()->user()->cliente_id;
        //dd($user);
        if(auth()->user()->rol_id == 1)
        {
            $usuarios = User::select('users.id',
                                 'name',
                                 'email',
                                 'telefono',
                                 'direccion',
                                 'rol_id',
                                 'estado_id',
                                 'rols.nombre')
            ->join('rols','users.rol_id','=','rols.id')
            ->whereIn('rol_id',[3,4,5])
            ->where('estado_id','=',3)
            ->get();
        }

        if( auth()->user()->rol_id == 6 || auth()->user()->rol_id == 4 )
        {
            $usuarios = User::select('users.id',
                                 'name',
                                 'email',
                                 'telefono',
                                 'direccion',
                                 'rol_id',
                                 'estado_id',
                                 'cliente_id',
                                 'rols.nombre')
            ->join('rols','users.rol_id','=','rols.id')
            ->whereIn('rol_id',[6,7])
            ->where('cliente_id',$cliente)
            ->where('estado_id','=',2)
            ->get();
        }      

            
        $roles=Rol::all();
        $convencion = Convencion::all();
        //dd($convencion);
        return view('auth.index', compact('usuarios','user','roles','convencion'));
    }

    public function actualizarActivar(Request $request)
    {
        //dd($request);
        if($request->Dolar == 'on')
        {
            $convencion1 = 1;
            $conv = new DetalleConvencion;
            $conv->usuario_id = $request->userId;
            $conv->convencion_id = $convencion1;
            $conv->save();
        }else{
            $convencion1 = 'null';
        }

        if($request->DolarColombia == 'on')
        {
            $convencion2 = 2;
            $conv = new DetalleConvencion;
            $conv->usuario_id = $request->userId;
            $conv->convencion_id = $convencion2;
            $conv->save();
        }else{
            $convencion2 = 'null';
        }

        if($request->clienteVIP == 'on')
        {
            //dd($request->userId);
            $user = User::where('id',(int)$request->userId)->first();
            $user->clienteVIP = 1;
            $user->update();
        }
        else{
            $clienteVIP = 'null';
        }
        $u = (int)$request->get('userId');
        //dd($u);
        $item = User::where('id',$u)->first();
        $item->estado_id = 1;
        $item->update();
        
       return back()->with('flash','El usuario fue activado');
    }

    public function desactivar($usuario_id)
    {
        
        $user = User::where('id',(int)$usuario_id)->first();
        $user->estado_id = 2;
        $user->update();
        
        return back()->with('flash','El usuario ha sido desactivado');

    }
    public function cambioContraseña()
    {
        $user = User::where('id',auth()->user()->id)->first();

        //dd($user);
        return view('admin.usuarios.cambioContraseña',compact('usesr'));
    }
    public function actualizarContraseña(Request $request)
    {
        $user = User::where('id',auth()->user()->id)->first();
        //dd($request);
        //$pass = bcrypt($request->pass);
        //dd($user->password);
        /*if($request->pass == $user->password)
        {*/
            if($request->password == $request->passwordA)
            {
                //dd('Vamos a Cambiar la contraseña');
                $user->password = bcrypt($request->password);
                $user->update();
                //dd($user);
                $user = $user->email;
                $pass = $request->password;
                UsuarioCreado::dispatch($user, $pass);
                return back()->with('flash','Se ha realizado el cambio de contraseña');
            }
            else
            {
                dd('Los nuevos datos no son iguales');
                return back()->with('flash','Los nuevos datos estan errados');
            }
        /*}
        else
        {
            dd('La contraseña actual esta errada');
        }*/
    }

}
