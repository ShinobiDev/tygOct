<?php

namespace App\Http\Controllers\Trabajos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sede;

class SedesController extends Controller
{
	public function index()
	{
		$cliente = auth()->user()->cliente_id;

		//dd(auth()->user());
		$sedes = Sede::select('id','nombre','pais','estado','ciudad','telefono','direccion','contactoSede','cliente_id')
		->where('cliente_id','=',$cliente)
		->get();
        //dd($sedes);
		return view('trabajos.sedes.index', compact('sedes'));

	}

    public function crear()
    {
    	return view('trabajos.sedes.crear');
    }

    public function almacenar(Request $request)
    {
    	//return Sede::create($request->all());
        $cliente = auth()->user()->cliente_id;
    	$sede = new Sede;

    	$sede->nombre = $request->get('nombre');
        $sede->pais = $request->get('pais');
        $sede->estado = $request->get('estado');
        $sede->ciudad = $request->get('ciudad');
    	$sede->direccion = $request->get('direccion');
    	$sede->telefono = $request->get('telefono');
    	$sede->contactoSede = $request->get('contacto');
        $sede->cliente_id = $cliente;
    	$sede->save();

    	return back()->with('flash','La sede ha sido creada');
    }

    public function actualizar(Request $request, $sede_id)
    {	
    	//dd($sede_id);
    	$sede = Sede::where('id', $sede_id)->first();
    	$sede->nombre = $request->get('nombre');
    	$sede->direccion = $request->get('direccion');
    	$sede->telefono = $request->get('telefono');
    	$sede->contactoSede = $request->get('contacto');
    	$sede->update();

        $cliente = auth()->user()->cliente_id;

        $sedes = Sede::select('id','nombre','telefono','direccion','contactoSede')
        ->where('cliente_id','=',$cliente)
        ->get();
        //dd($sedes);
        return view('trabajos.sedes.index', compact('sedes'))->with('success','Se actualizo exitosamente');
        //return view('admin.variables.index')->with('success','Se actualizo exitosamente');
    }
}
