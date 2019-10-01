<?php

namespace App\Http\Controllers\Trabajos;

use App\Orden;
use App\User;
use App\ItemOrden;
use App\FacturaOrden;
use App\VariableEditable;
use App\Events\FacturaCreada;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class FacturasController extends Controller
{
    public function facturasOrden()
    {
    	$ordenes = Orden::where('ordens.estado_id','=',13)
    	->get();

    	//dd($ordenes);
    	return view('trabajos.facturas.ordenIndex', compact('ordenes'));
    }

    public function crearFacturaOrden($orden_id)
    {
    	//Seleccionamos los items correspondientes al id que traemos como parametro.
        $detalleOrden = itemOrden::select('ordens.id','ordens.Trm','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','pesoPromedio','costoUnitario','margenUsa','porcentajeArancel','empaque','cinta','costo3','margenCop','TE')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->where('item_ordens.estadoItem_id','=',4)
        ->get();
        //dd($detalleOrden);

        $variables = VariableEditable::all();

        $detallePeso = DB::table('item_ordens')
        ->join('ordens','item_ordens.orden_id','ordens.id')
        ->join('sedes','item_ordens.sede_id','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->select('ordens.id','sede_id','sedes.nombre',
                DB::raw('sum(pesoLb * cantidad) as PesoSede'),
                DB::raw('sum(cantidad) as cantidadProductos'),
                DB::raw('count(cantidad) as cantidadSede'))
        ->groupBy('sedes.id')
        ->get();
        //dd($detallePeso);

        return view('trabajos.facturas.detalleOrdenFactura', compact('detalleOrden','variables','detallePeso'))->with('orden_id',$orden_id);
    }

    public function almacenarFacturaOrden(Request $request)
    {   
        //dd($request);
    	$Orden = Orden::where('id', (int)$request->get('ordenId'))->first();
        $Orden->precioTotalGlobal = $request->totalPrecioTotalUsd;
        $Orden->update(); 

        $arr=['porcentajeArancel' =>'','empaque' =>'','cinta' =>'','costo3' =>'','margenCop' =>'','TE' =>''];
        foreach ($request['porcentajeArancel'] as $key => $value)
        {
            $detalle_id = $request['detalle_id'][$key];

            if($value!=null)
            {
                $arr['porcentajeArancel'] = $value;

            }else
            {
                unset($arr['porcentajeArancel']);
            }

            if($value!=null)
            {
                //dd($request['empaque'][$key]);
                 $arr['empaque'] = $request['empaque'][$key];
            }else
            {
                unset($arr['empaque']);
            }

            if($value!=null)
            {
                 $arr['cinta'] = $request['cinta'][$key];
            }else
            {
                unset($arr['cinta']);
            }

            if($value!=null)
            {
                 $arr['costo3'] = $request['costo3'][$key];
            }else
            {
                unset($arr['costo3']);
            }

            if($value!=null)
            {
                 $arr['margenCop'] = $request['margenCop'][$key];
            }else
            {
                unset($arr['margenCop']);
            }

            if($value!=null)
            {
                 $arr['TE'] = $request['TE'][$key];
            }else
            {
                unset($arr['TE']);
            }

            if(count($arr)>0)
            {
                //dd($arr);
                DB::table('item_ordens')
                ->where('id', $detalle_id)
                ->update($arr);
                $arr=['porcentajeArancel' =>'','empaque' =>'','cinta' =>'','costo3' =>'','margenCop' =>'','TE' =>''];
                //dd('almaceno');
            }            
        }

        //Seleccionamos los items correspondientes al id que traemos como parametro.
        $detalleOrden = itemOrden::select('ordens.id','ordens.Trm','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','pesoPromedio','costoUnitario','margenUsa','porcentajeArancel','empaque','cinta','costo3','margenCop','TE','ordens.precioTotalGlobal')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$request->ordenId)
        ->where('item_ordens.estadoItem_id','=',4)
        ->get();
        //dd($detalleOrden[0]->precioTotalGlobal);

        $variables = VariableEditable::all();
        //dd($variables[4]->valor);
        $detallePeso = DB::table('item_ordens')
        ->join('ordens','item_ordens.orden_id','ordens.id')
        ->join('sedes','item_ordens.sede_id','sedes.id')
        ->where('ordens.id','=',$request->ordenId)
        ->select('ordens.id','sede_id','sedes.nombre',
                DB::raw('sum(pesoLb * cantidad) as PesoSede'),
                DB::raw('sum(cantidad) as cantidadProductos'),
                DB::raw('count(cantidad) as cantidadSede'))
        ->groupBy('sedes.id')
        ->get();
        //dd($detallePeso);

        $orden_id = $request->ordenId;

            return view('trabajos.facturas.detalleOrdenFactura', compact('detalleOrden','variables','detallePeso'))->with('orden_id',$orden_id);
    }

    public function generarFacturaOrden(Request $request)
    {
         //dd((int)$request->ordenId);
         $factura = new FacturaOrden;
         $factura->orden_id = $request->ordenId;
         $factura->empaque = $request->empaque;
         $factura->cinta = $request->cinta;
         $factura->costo3 = $request->costo3;
         $factura->save();

         $orden = Orden::where('ordens.id','=',$request->get('ordenId'))->first();
         $orden->estado_id = 17;
         $orden->update();

        foreach ($request['detalleId'] as $key => $value)
        {
            //dd((int)$value);
            $item = ItemOrden::where('id',(int)$value)->first();
            $item->estadoItem_id = 5;
            $item->update();
        }

        $ordenes = Orden::where('ordens.estado_id','=',13)
        ->get();

        $ordenFac = Orden::where('ordens.id','=',$request->get('ordenId'))->first();


        $user = User::select('email','name')
        ->where('users.id','=',$ordenFac->user_id)
        ->get();
        $orden = (int)$request->ordenId;
        //dd($user);
        FacturaCreada::dispatch($user, $orden);
        //dd($orden);
        return view('trabajos.facturas.ordenIndex', compact('ordenes'));
    }

    public function index()
    {
        $ordenes = Orden::where('ordens.estado_id','=',17)
        ->get();

        //dd($orden);
        return view('trabajos.facturas.index', compact('ordenes'));
    }

    public function detalleFactura($orden_id)
    {
        $detalleOrden = itemOrden::select('ordens.id','ordens.Trm','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','pesoPromedio','costoUnitario','margenUsa','porcentajeArancel','empaque','cinta','costo3','margenCop','TE','ordens.precioTotalGlobal')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->where('item_ordens.estadoItem_id','=',5)
        ->get();

        $variables = VariableEditable::all();

        $detallePeso = DB::table('item_ordens')
        ->join('ordens','item_ordens.orden_id','ordens.id')
        ->join('sedes','item_ordens.sede_id','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->select('ordens.id','sede_id','sedes.nombre',
                DB::raw('sum(pesoLb * cantidad) as PesoSede'),
                DB::raw('sum(cantidad) as cantidadProductos'),
                DB::raw('count(cantidad) as cantidadSede'))
        ->groupBy('sedes.id')
        ->get();

        return view('trabajos.facturas.detalleFactura', compact('detalleOrden','variables','detallePeso'))->with('orden_id',$orden_id);
    }

    public function misfacturas()
    {
        $ordenes = Orden::where('ordens.estado_id','=',17)
        ->where('ordens.user_id','=',auth()->user()->id)
        ->get();

        //dd($orden);
        return view('trabajos.facturas.misFacturas', compact('ordenes'));
    }

}
