<?php

namespace App\Http\Controllers\Trabajos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VariableEditable;

class VariableController extends Controller
{
    public function index()
    {
    	$variables = VariableEditable::all();

    	return view('trabajos.variables.index', compact('variables'));
    }

    public function update(Request $request, $variable_id)
    {
    	$variableEditable = VariableEditable::where('id', $variable_id)->first();
    	$variableEditable->valor = $request->get('nuevoValor');
    	$variableEditable->update();
		return redirect()->back()->with('flash','La variable se actualizo exitosamente');
        //return view('admin.variables.index')->with('success','Se actualizo exitosamente');
    }
}
