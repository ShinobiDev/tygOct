<?php

namespace App\Http\Controllers\Trabajos;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrdensImport;
use App\Http\Controllers\Controller;
use App\Events\NotificationEvent;
use App\Events\OrdenCreada;
use App\Events\OrdenAsignada;
use App\Events\OrdenAceptada;
use App\Events\OrdenParaFacturar;
use App\Sede;
use App\Orden;
use App\User;
use App\ItemOrden;
use App\HistorialOrden;
use App\VariableEditable;
use App\Convencion;
use App\DetalleConvencion;
use DB;
use Carbon\Carbon;
use App\Marca;

class OrdenesController extends Controller
{

    public function index()
    {
        $ordenes =Orden::select('ordens.id','Trm','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name','convencions.nombreConvencion')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->join('convencions','ordens.convencion_id','=','convencions.id')
        ->get();
        //dd($ordenes);
        return view('trabajos.ordenes.index', compact('ordenes'));
    }

    //Listado de Ordenes por cada usuario
    public function porUsuario()
    {
        $user = auth()->user()->id;
        $ordenes = Orden::select('ordens.id','Trm','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name','convencions.nombreConvencion')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->join('convencions','ordens.convencion_id','=','convencions.id')
        ->where('user_id','=',$user)
        ->get();
        //dd($ordenes);
        return view('trabajos.ordenes.misOrdenes', compact('ordenes'));
    }

    //Listado de Ordenes Asignadas al usuario
    public function misAsignadas()
    {
        dd('Ya llego');
        $user = auth()->user()->id;

        $ordenes = Orden::select('ordens.id','users.name');
        
        if(count(DB::table('historial_ordens')->join('ordens','historial_ordens.orden_id','=','ordens.id')->where('userAsignado_id',$user)->whereIn('ordens.estado_id',[3,6,9,12])->get())>0){
            $ordenAsignadas = Orden::select('users.name',
                                            'ordens.id',
                                            'ordens.user_id',
                                            'ordens.estado_id',
                                            'ordens.created_at',
                                            'historial_ordens.userAsignado_id')
            ->join('historial_ordens','historial_ordens.orden_id','=','ordens.id')
            ->join('users','ordens.user_id','=','users.id')
            ->where('historial_ordens.userAsignado_id','=',$user)
            ->where('ordens.gestion',1)
            ->get();
            //dd($ordenAsignadas);
            return view('trabajos.ordenes.asignadas_a_mi',compact('ordenAsignadas'));
        }else
        {
                $ordenAsignadas=[];
                return view('trabajos.ordenes.asignadas_a_mi',compact('ordenAsignadas'));
        }
    }

    //Detalle de la Orden de usuario en estado Precotizado o Cotizado ..................................
    public function detalleUsuario($orden_id)
    {
        $useRol = auth()->user()->rol_id;

        //dd($useRol);
        $detalleOrden = itemOrden::select('ordens.id','ordens.estado_id','ordens.Trm','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','pesoPromedio','costoUnitario','margenUsa','porcentajeArancel','empaque','cinta','costo3','margenCop','TE','ordens.precioTotalGlobal','nombreVar1','valorVar1','nombreVar2','valorVar2','nombreVar3','valorVar3','fechaFactura','fechaRealEntrega','facturaCop')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->where('item_ordens.estadoItem_id','=',1)
        ->get();
        //dd($detalleOrden);

        $variables = VariableEditable::all();
        //dd($variables);
        $detallePeso = DB::table('item_ordens')
        ->join('ordens','item_ordens.orden_id','ordens.id')
        ->join('sedes','item_ordens.sede_id','sedes.id')
        ->where('item_ordens.estadoItem_id','=',1)
        ->where('ordens.id','=',$orden_id)
        ->select('ordens.id','sede_id','sedes.nombre',
                DB::raw('sum(pesoLb * cantidad) as PesoSede'),
                DB::raw('sum(cantidad) as cantidadProductos'),
                DB::raw('count(cantidad) as cantidadSede'))
        ->groupBy('sedes.id')
        ->get();

        foreach ($detalleOrden as $key) 
        {
            $itemC = 0;

            $add = Carbon::parse($key->fechaSolicitudProveedor)->addDays($key->diasEntregaProveedor);
            $add->addDays($key->diasfestivosNoHabilesProveedor);
            $fechaEstimadaLlegada[] = $add->format('d-m-Y');

            $diasRealesEntregaProveedor[] = Carbon::parse($key->fechaCantidadCompleta)->diffInDays($key->fechaSolicitudProveedor);

            $add->addDays($key->diasTransitoCliente);
            $add->addDays($key->diasFestivoNoHabilesCliente);

            //dd($key->fechaAceptacionCliente);
            $diasPrometidosCliente[] = Carbon::parse($key->fechaAceptacionCliente)->diffInDays($add);
            
            $diasP = $diasPrometidosCliente[$itemC];
            $fechaPrometidaCliente[] = Carbon::parse($key->fechaAceptacionCliente)->addDays($diasP);


            $diasRealesEntregaCliente[] = Carbon::parse($key->fechaAceptacionCliente)->diffInDays($key->fechaRealEntrega);
            //dd($diasRealesEntregaCliente[0]);
            $vs[] = Carbon::parse($key->fechaFactura)->diffInDays($key->fechaRealEntrega);
            $itemC++;
        }

        $orden = Orden::where('id',$orden_id)->first();
        //dd($orden);
        //dd($detallePeso);
        return view('trabajos.ordenes.detalleUsuario',compact('detalleOrden','variables','detallePeso','useRol','orden','fechaPrometidaCliente'))->with('orden_id',$orden_id);
    }

    //Crear Retornar el formualrio para crear nuevas ordenes ....................................
    public function crear()
    {
        $user = auth()->user()->id;
        $useRol = auth()->user()->rol_id;
        $cliente = auth()->user()->cliente_id;
        $sede = auth()->user()->sede_id;
        $marcas = Marca::all();


        //dd($us);
        //dd($user);
        $sedes = Sede::select('id','nombre','telefono','direccion','contactoSede')
        ->where('cliente_id','=',$cliente)
        ->get();

        $se = count($sedes);
        //dd($sedes);
        if($se<1){
            return back()->with('error','No hay SEDES creadas, sin crear las SEDES, no se pueden crear ordenes de pedido');
        }

        /*if($useRol == 7){
            $sedes = Sede::select('id','nombre','telefono','direccion','contactoSede')
            ->where('cliente_id','=',$cliente)
            ->where('sede.id',$sede)
            ->get();
        }*/
        $convenciones = DetalleConvencion::select('usuario_id','convencion_id','convencions.id','convencions.nombreConvencion')
        ->join('convencions','convencions.id','=','convencion_id')
        ->where('usuario_id','=',$cliente)
        ->get();
        //dd($convenciones);
        return view('trabajos.ordenes.crear', compact('sedes','convenciones','marcas'));
    }

    //metodo para almacenar las nuevas ordenes ...................................................
    public function almacenar(Request $request)
    {
        //dd($request);
        //Almacenamos los datos de la orden
        $trm = VariableEditable::all();
        //dd($trm);
        $cliente = auth()->user()->cliente_id;
        //dd($trm);
        $orden = new Orden;
        $orden->cliente_id = $cliente;
        $orden->estado_id = 2;
        $orden->user_id = auth()->user()->id;
        $orden->convencion_id = $request->get('convencion');
        $orden->trm = $trm[0]->valor;
        $orden->nombreVar1 = $trm[3]->nombre;
        $orden->valorVar1 = $trm[3]->valor;
        $orden->nombreVar2 = $trm[4]->nombre;
        $orden->valorVar2 = $trm[4]->valor;
        $orden->nombreVar3 = $trm[5]->nombre;
        $orden->valorVar3 = $trm[5]->valor;
        $orden->save();

        //Se crea el historial
        $historialOrden = new historialOrden;
        $historialOrden->orden_id = $orden->id;
        $historialOrden->estadoActual_id = 2;
        $historialOrden->save();

        //Recorremos cada detalle que trae los productos relacionados a la Orden y los almacenamos
        foreach ($request['sede'] as $key => $value)
        {
            $item = new ItemOrden;
            $item->estadoItem_id = 1;
            $item->sede_id = $value;
            $item->orden_id = $orden->id;
            $item->marca = $request['marca'][$key];
            $item->referencia = $request['referencia'][$key];
            $item->descripcion = $request['descripcion'][$key];
            $item->cantidad = $request['cantidad'][$key] ;
            $item->comentarios = $request['comentarios'][$key];
            $item->vin = $request['vin'][$key];
            $item->placa = $request['placa'][$key];
            $item->save();
        }

        $user = User::all();
        $orden = $orden->id;

        //Activamos el evento para el envio del Correo
        OrdenCreada::dispatch($user, $orden);

        //retornamos a la vista
        return back()->with('flash','La orden ha sido creada');
    }

    //consulta de Ordenes sin Asignar .........................................................//
    public function sinAsignar()
    {
        $sinUsuario = Orden::select('ordens.id','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->whereIn('ordens.estado_id',[8,2])
        //->where('ordens.estado_id','=',8)
        //->orwhere('ordens.estado_id','=',2)
        ->get();
        //dd($sinUsuario);
        return view('trabajos.ordenes.sinAsignar', compact('sinUsuario'));
    }

    public function negociadas()
    {
        $sinUsuario = Orden::select('ordens.id','ordens.convencion_id','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->whereIn('ordens.estado_id',[15])
        //->where('ordens.estado_id','=',8)
        //->orwhere('ordens.estado_id','=',2)
        ->get();
        //dd($sinUsuario);
        return view('trabajos.ordenes.negociadas', compact('sinUsuario'));
    }

    //Detalle de las ordenes en estado PreCotizada sin Asignar ...........................................
    public function detalleCotizadas($orden_id)
    {
        //dd($orden_id);

        $detalleOrden = itemOrden::select('sedes.nombre','ordens.estado_id','item_ordens.marca','item_ordens.referencia','item_ordens.descripcion','item_ordens.cantidad','item_ordens.comentarios','item_ordens.orden_id')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('item_ordens.orden_id','=',(int)$orden_id)
        ->where('item_ordens.estadoItem_id',1)
        ->get();

        //dd($detalleOrden);
        $user = User::all();
        //dd($detalleOrden);
        return view('trabajos.ordenes.detalleCotizadas', compact('detalleOrden','user'));
    }

    //Asignar usuario a la orden ..................................................................
    public function asignarUsuarioOrden(Request $request)
    {
        $UserGestiona = auth()->user()->id;

        $historialOrden = new historialOrden;
        $historialOrden->orden_id = $request->get('ordenId');

        $Orden = Orden::where('id', $request->get('ordenId'))->first();
        //dd($Orden->estado_id);
        if($Orden->estado_id == 2)
        {
            $historialOrden->estadoActual_id = 3;
        }
        if($Orden->estado_id == 8)
        {
            $historialOrden->estadoActual_id = 9;
        }

        //dd($historialOrden->estadoActual_id);

        $historialOrden->userAsignado_id = $request->get('usuarioAsignado');
        $historialOrden->save();

        if($Orden->estado_id == 2)
        {
            $Orden->estado_id = 3;
            $Orden->userAsignado_id = $request->get('usuarioAsignado');
        }
        if($Orden->estado_id == 8)
        {
            $Orden->estado_id = 9;
            $Orden->userAsignado_id = $request->get('usuarioAsignado');
        }

        $Orden->update();

        $sinUsuario = Orden::select('ordens.id','ordens.created_at','estado_ordens.nombreEstado','users.name')
        ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->whereIn('ordens.estado_id',[8,2])
        //->where('ordens.estado_id','=',8)
        //->orwhere('ordens.estado_id','=',2)
        ->get();
        //dd($request);
        $user = User::select('email','name')
        ->where('users.id','=',$request->usuarioAsignado)
        ->get();
        $ordenAsignada = (int)$request->ordenId;
        //dd($ordenAsignada);
        OrdenAsignada::dispatch($user, $ordenAsignada);

        //dd($UserGestiona);
        return view('trabajos.ordenes.cotizadaSinAsignar',compact('sinUsuario'))->with('flash','El Usuario fue asignado');
    }

    //Consulta de ordenes asignada...............................................................
    public function asignadas()
    {

        $ordenAsignadas = Orden::select('ordens.id','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name')
            ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
            ->join('users','ordens.user_id','=','users.id')
            ->whereIn('ordens.estado_id',[3,6,9])
            ->get();
        //dd($ordenAsignadas);
        return view('trabajos.ordenes.asignadas', compact('ordenAsignadas'));
    }

    //Detalle ordenes asignadas en estado PreCotizar .............................................
    public function detalleAsignada($orden_id)
    {
        //dd($orden_id);
        //Seleccionamos los items correspondientes al id que traemos como parametro.
        $detalleOrden = itemOrden::select('ordens.id','ordens.estado_id','ordens.Trm','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','pesoPromedio','costoUnitario','margenUsa','porcentajeArancel','empaque','cinta','costo3','margenCop','TE','ordens.precioTotalGlobal','nombreVar1','valorVar1','nombreVar2','valorVar2','nombreVar3','valorVar3','negociacion')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->where('item_ordens.estadoItem_id','=',1)
        ->get();
        //dd($detalleOrden);

        $orden = Orden::where('id',$orden_id)->first();

        $variables = VariableEditable::all();
        //dd($variables);
        $detallePeso = DB::table('item_ordens')
        ->join('ordens','item_ordens.orden_id','ordens.id')
        ->join('sedes','item_ordens.sede_id','sedes.id')
        ->where('item_ordens.estadoItem_id','=',1)
        ->where('ordens.id','=',$orden_id)
        ->select('ordens.id','sede_id','sedes.nombre',
                DB::raw('sum(pesoLb * cantidad) as PesoSede'),
                DB::raw('sum(cantidad) as cantidadProductos'),
                DB::raw('count(cantidad) as cantidadSede'))
        ->groupBy('sedes.id')
        ->get();
        //dd($variables);
        
        return view('trabajos.ordenes.detalleAsignada',compact('orden','detalleOrden','variables','detallePeso','orden'))->with('orden_id',$orden_id);
    }

    //Actualización de los items de las ordenes en estado PreCotizar ......................................
    public function update(Request $request)
    {
        //dd($request);
        //Recorremos si hay algun item dividido *********************************
        foreach ($request['detalle_id'] as $key => $value)
        {
            //dd($request);

            //Validamos cual item de la orden esta dividido
            if(isset($request['itemDividido'.$value]))
            {
                //dd($request['itemDividido'].$value);

                //Hallamos el tamaño o cantidad de item en los que se dividio
                $tamaño = count($request['itemDividido'.$value]);
                echo "El tamaño del arreglo es ".$tamaño;

                //dd('detener');
                //Vamos a crear los item segun la cantidad necesaria
                foreach ($request['itemDividido'.$value] as $k => $values) {
                    $cantidadItem = $values;

                    //dd($request['porcentajeArancel'][$key]);

                    $item = new ItemOrden;

                    $item->orden_id = $request->get('ordenId');
                    $item->item_id = $value;
                    $item->estadoItem_id = 1;
                    $item->sede_id = $request['sedeId'][$key];
                    $item->marca = $request['marca'][$key];
                    $item->referencia = $request['referencia'][$key];
                    $item->descripcion = $request['descripcion'][$key];
                    $item->cantidad = $cantidadItem;
                    $item->pesoLb = $request['pesoLb'][$key];
                    $item->pesoPromedio = $request['pesoPromedio'][$key];
                    $item->comentarios = $request['comentarios'][$key];
                    $item->costoUnitario = $request['costoUnitario'][$key];
                    $item->margenUsa = $request['margenUsa'][$key];
                    $item->porcentajeArancel = $request['porcentajeArancel'][$key];
                    $item->empaque = $request['empaque'][$key];
                    $item->cinta = $request['cinta'][$key];
                    $item->costo3 = $request['costo3'][$key];
                    $item->margenCop = $request['margenCop'][$key];
                    $item->TE = $request['TE'][$key];
                    $item->save();
                }


                $item = ItemOrden::where('id',$value)->first();
                $item->estadoItem_id = 2;
                $item->update();
            }
        }

        $Orden = Orden::where('id', (int)$request->get('ordenId'))->first();
        $Orden->precioTotalGlobal = $request->totalPrecioTotalUsd;
        $Orden->nombreVar1 = $request->nombreVar1;
        $Orden->valorVar1 = $request->valorVar1;
        $Orden->nombreVar2 = $request->nombreVar2;
        $Orden->valorVar2 = $request->valorVar2;
        $Orden->nombreVar3 = $request->nombreVar3;
        $Orden->valorVar3 = $request->valorVar3 ;
        $Orden->update(); 

        $arr=['pesoLb' =>'','costoUnitario' =>'','margenUsa' =>'','porcentajeArancel' =>'','empaque' =>'','cinta' =>'','costo3' =>'','margenCop' =>'','TE' =>''];
        foreach ($request['pesoLb'] as $key => $value)
        {
            $detalle_id = $request['detalle_id'][$key];

            if($value!=null)
            {
                $arr['pesoLb'] = $value;

            }else
            {
                unset($arr['pesoLb']);
            }

            if($value!=null)
            {
                $arr['pesoPromedio'] = $value;

            }else
            {
                unset($arr['pesoPromedio']);
            }

            if($value!=null)
            {
                //dd($request['costoUnitario'][$key]);
                 $arr['costoUnitario'] = $request['costoUnitario'][$key];
            }else
            {
                unset($arr['costoUnitario']);
            }

            if($value!=null)
            {
                 $arr['margenUsa'] = $request['margenUsa'][$key];
            }else
            {
                unset($arr['margenUsa']);
            }

            if($value!=null)
            {
                $arr['porcentajeArancel'] = $request['porcentajeArancel'][$key];

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
                $arr=['pesoLb' =>'','costoUnitario' =>'','margenUsa' =>'','porcentajeArancel' =>'','empaque' =>'','cinta' =>'','costo3' =>'','margenCop' =>'','TE' =>''];
            }

        }

        return back()->with('flash','La orden ha sido actualizada');
    }

    /*Eliminar item de la Orden*/
    public function eliminarItem($detalle_id)
    {
        //dd((int)$detalle_id);
        $u = (int)$detalle_id;
        $item = ItemOrden::where('id',$u)->first();
        $item->estadoItem_id = 5;
        $item->update();

        //dd($item);
        //dd($detallePeso);
        return back()->with('flash','La orden ha sido actualizada');

    }
    /*Solicitar Negociación de la orden*/
    public function solicitarNegociacion(Request $request)
    {
        if(auth()->user()->clienteVIP == 1 && $request->negociacion[0] == null)
        {
            return back()->with('error','Ud no ingreso datos a la Negociación');
        }
        //dd('Viene con Datos');
        if($request->negociacion[0] != null)
        {
            //dd('Viene con datos y entro');
            
            $orden = Orden::where('id', (int)$request->ordenId)->first();
            $orden->estado_id = 15;
            $orden->update();

            $arr=['negociacion' =>''];
            foreach ($request['negociacion'] as $key => $value)
            {
                $detalle_id = $request['detalle_id'][$key];

                if($value!=null)
                {
                    $arr['negociacion'] = $value;

                }

                if(count($arr)>0)
                {
                    //dd($arr);
                    DB::table('item_ordens')
                    ->where('id', $detalle_id)
                    ->update($arr);
                    $arr=['negociacion' =>''];
                }

            }
            $user = auth()->user()->id;
            $ordenes = Orden::select('ordens.id','Trm','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name','convencions.nombreConvencion')
            ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
            ->join('users','ordens.user_id','=','users.id')
            ->join('convencions','ordens.convencion_id','=','convencions.id')
            ->where('user_id','=',$user)
            ->get();
            //dd($ordenes);

            $user = User::where('rol_id','=',1)
            ->get();
            //dd($user);

            OrdenAceptada::dispatch($user,(int)$request->ordenId);
            return view('trabajos.ordenes.misOrdenes',compact('ordenes'))->with('flash','La orden fue aceptada');
        }
    }

    //Actulizar la orden a estado Cotizado y envio de datos al cliente..................................
    public function cotizarOrden(Request $request)
    {
        //dd($request);

        $orden = Orden::where('id', (int)$request->ordenId)->first();
        //dd($orden);
        //Validamos si el estado es 3 Por cotizar Asignado y lo cambiamos a estado 4 Cotizado
        if($orden->estado_id == 3)
        {
            //Cambiamos el estado de la orden
            $orden->estado_id = 4;
            $orden->gestion = 2;
            $orden->update();

            //Creamos el historial con el cambio de estado de la orden
            $historialOrden = new historialOrden;
            $historialOrden->orden_id = (int)$request->ordenId;
            $historialOrden->estadoActual_id = 4;
            $historialOrden->userAsignado_id = auth()->user()->id;
            $historialOrden->save();

            //Activamos el evento para el envio del Correo
            NotificationEvent::dispatch(User::where('id',$orden->user_id)->first(),[(int)$request->ordenId],"CambioEstadoCotizado");

            //Retornamos a la vista anterior
            //dd('Lego Aqui');

            $user = auth()->user()->id;

            $ordenAsignadas = Orden::select('ordens.id','ordens.estado_id','ordens.created_at','historial_ordens.userAsignado_id')
            ->join('historial_ordens','historial_ordens.orden_id','=','ordens.id')
            ->where('historial_ordens.userAsignado_id','=',$user)->first()
            ->whereIn('ordens.estado_id',[3,6,12])
            ->get();
            //dd($ordenAsignadas);

            //dd('La consulta trae valores');
            return view('trabajos.ordenes.asignadas_a_mi',compact('ordenAsignadas'));

        }

        //Validamos si el estado es 4 Cotizado y lo cambiamos a estado 8 Orden Sin Asignar
        if($orden->estado_id == 4)
        {
            $orden->estado_id = 8;
            $orden->fechaAceptacionCliente = Carbon::now();
            $orden->update();

            $historialOrden = new historialOrden;
            $historialOrden->orden_id = (int)$request->ordenId;
            $historialOrden->estadoActual_id = 8;
            $historialOrden->userAsignado_id = auth()->user()->id;
            $historialOrden->save();

            $user = auth()->user()->id;
            $ordenes = Orden::select('ordens.id','Trm','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name','convencions.nombreConvencion')
            ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
            ->join('users','ordens.user_id','=','users.id')
            ->join('convencions','ordens.convencion_id','=','convencions.id')
            ->where('user_id','=',$user)
            ->get();
            //dd($ordenes);

            $user = User::where('rol_id','=',1)
            ->get();
            //dd($user);

            OrdenAceptada::dispatch($user,(int)$request->ordenId);
            return view('trabajos.ordenes.misOrdenes',compact('ordenes'))->with('flash','La orden fue aceptada');
        }
        if($orden->estado_id == 15)
        {
            $orden->estado_id = 8;
            $orden->update();

            $historialOrden = new historialOrden;
            $historialOrden->orden_id = (int)$request->ordenId;
            $historialOrden->estadoActual_id = 8;
            $historialOrden->userAsignado_id = auth()->user()->id;
            $historialOrden->save();

            $user = auth()->user()->id;
            $ordenes = Orden::select('ordens.id','Trm','ordens.estado_id','ordens.created_at','estado_ordens.nombreEstado','users.name','convencions.nombreConvencion')
            ->join('estado_ordens','ordens.estado_id','=','estado_ordens.id')
            ->join('users','ordens.user_id','=','users.id')
            ->join('convencions','ordens.convencion_id','=','convencions.id')
            ->where('user_id','=',$user)
            ->get();
            //dd($ordenes);

            $user = User::where('rol_id','=',1)
            ->get();
            //dd($user);

            OrdenAceptada::dispatch($user,(int)$request->ordenId);
            return view('trabajos.ordenes.misOrdenes',compact('ordenes'))->with('flash','La orden fue aceptada');
        }
    }

    //Vista con el formulario para editar la orden..................................................
    public function editar($orden_id)
    {
        $detalleOrden = itemOrden::select('ordens.id','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','costoUnitario','margenUsa')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->whereIn('item_ordens.estadoItem_id',[1])
        //->where('item_ordens.estadoItem_id','=',1)
        ->get();
        //dd($detalleOrden);
        $user = auth()->user()->cliente_id;
        //dd($user);
        $sede = Sede::where('sedes.cliente_id','=',$user)
        ->get();
        //dd($sede);
        $variables = VariableEditable::all();
        //dd($detalleOrden);
        $orden_id = $orden_id;
        return view('trabajos.ordenes.editarOrden', compact('detalleOrden','variables','sede'))->with('orden_id',$orden_id);
    }

    public function editarDetalle($orden)
    {
        dd('Llego');
        $detalleOrden = itemOrden::select('ordens.id','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','costoUnitario','margenUsa')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden)
        //->where('item_ordens.estadoItem_id','=',1)
        ->get();
        //dd($detalleOrden);
        $user = auth()->user()->id;
        //dd($user);
        $sede = Sede::where('sedes.user_id','=',$user)
        ->get();
        //dd($sede);
        $variables = VariableEditable::all();
        //dd($detalleOrden);
        $orden_id = $orden_id;
        return view('trabajos.ordenes.editarOrden', compact('detalleOrden','variables','sede'))->with('orden_id',$orden_id);
    }

    //Actualizar o editar la orden por parte del cliente.............................................
    public function actualizarEdicion(Request $request)
    {
        //dd($request);
        $arr=['sede_id' =>'','descripcion' =>'','cantidad' =>'','comentarios' =>''];
        foreach ($request['sede_id'] as $key => $value)
        {
            $detalle_id = $request['detalle_id'][$key];

            if($value!=null)
            {
                //dd($request['costoUnitario'][$key]);
                 $arr['sede_id'] = $request['sede_id'][$key];
            }else
            {
                unset($arr['sede_id']);
            }

            if($value!=null)
            {
                 $arr['descripcion'] = $request['descripcion'][$key];
            }else
            {
                unset($arr['descripcion']);
            }
            if($value!=null)
            {
                 $arr['cantidad'] = $request['cantidad'][$key];
            }else
            {
                unset($arr['cantidad']);
            }
            if($value!=null)
            {
                 $arr['comentarios'] = $request['comentarios'][$key];
            }else
            {
                unset($arr['comentarios']);
            }


            if(count($arr)>0)
            {
                //dd($arr);
                DB::table('item_ordens')
                ->where('id', $detalle_id)
                ->update($arr);
                $arr=['sede_id' =>'','descripcion' =>'','cantidad' =>'','comentarios' =>''];
            }
        }
        return back()->with('flash','La orden ha sido actualizada');
    }

    public function detalleAsignadasOrden($orden_id)
    {
        //dd($orden_id);
        $detalleOrden = itemOrden::select('ordens.id','ordens.estado_id','ordens.Trm','ordens.convencion_id','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','costoUnitario','margenUsa','fechaAceptacionCliente','fechaSolicitudProveedor','diasEntregaProveedor','diasfestivosNoHabilesProveedor','bodega','guiaInternacional','invoice','fechaInvoice','diasTransitoCliente','diasFestivoNoHabilesCliente','guiaInternaDestino','facturaCop','fechaRealEntrega','fechaFactura','fechaCantidadCompleta')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',(int)$orden_id)
        ->whereIn('item_ordens.estadoItem_id',[1,4])
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

        foreach ($detalleOrden as $key) 
        {
            $itemC = 0;

            $add = Carbon::parse($key->fechaSolicitudProveedor)->addDays($key->diasEntregaProveedor);
            $add->addDays($key->diasfestivosNoHabilesProveedor);
            $fechaEstimadaLlegada[] = $add->format('d-m-Y');

            $diasRealesEntregaProveedor[] = Carbon::parse($key->fechaCantidadCompleta)->diffInDays($key->fechaSolicitudProveedor);

            $add->addDays($key->diasTransitoCliente);
            $add->addDays($key->diasFestivoNoHabilesCliente);

            //dd($key->fechaAceptacionCliente);
            $diasPrometidosCliente[] = Carbon::parse($key->fechaAceptacionCliente)->diffInDays($add);
            
            $diasP = $diasPrometidosCliente[$itemC];
            $fechaPrometidaCliente[] = Carbon::parse($key->fechaAceptacionCliente)->addDays($diasP);


            $diasRealesEntregaCliente[] = Carbon::parse($key->fechaAceptacionCliente)->diffInDays($key->fechaRealEntrega);
            //dd($diasRealesEntregaCliente[0]);
            $vs[] = Carbon::parse($key->fechaFactura)->diffInDays($key->fechaRealEntrega);
            $itemC++;
        }

        return view('trabajos.ordenes.detalleAsignadaOrden', compact('detalleOrden','variables','detallePeso','fechaEstimadaLlegada','diasRealesEntregaProveedor','diasPrometidosCliente','fechaPrometidaCliente','diasRealesEntregaCliente','vs'))->with('orden_id',$orden_id);
    }

    public function actualizarOrden(Request $request)
    {
        

        foreach ($request['detalle_id'] as $key => $value)
        {
            //dd($request);          

            //Validamos cual item de la orden esta dividido
            if(isset($request['itemDividido'.$value]))
            {
                //dd($request['itemDividido'].$value);

                //Hallamos el tamaño o cantidad de item en los que se dividio
                $tamaño = count($request['itemDividido'.$value]);
                echo "El tamaño del arreglo es ".$tamaño;

                //dd('detener');
                //Vamos a crear los item segun la cantidad necesaria
                foreach ($request['itemDividido'.$value] as $k => $values) {
                    $cantidadItem = $values;

                    //dd($request['pesoLb']);

                    $item = new ItemOrden;

                    $item->orden_id = $request->get('ordenId');
                    $item->item_id = $value;
                    $item->estadoItem_id = 1;
                    $item->sede_id = $request['sedeId'][$key];
                    $item->marca = $request['marca'][$key];
                    $item->referencia = $request['referencia'][$key];
                    $item->descripcion = $request['descripcion'][$key];
                    $item->cantidad = $cantidadItem;
                    $item->pesoLb = $request['pesoLb'][$key];
                    $item->pesoPromedio = $request['pesoPromedio'][$key];
                    $item->comentarios = $request['comentarios'][$key];
                    $item->costoUnitario = $request['costoUnitario'][$key];
                    $item->margenUsa = $request['margenUsa'][$key];
                    $item->save();
                }


                $item = ItemOrden::where('id',$value)->first();
                $item->estadoItem_id = 2;
                $item->update();
            }
        }
        //dd($request);
        $arr=['fechaSolicitudProveedor'=>'','diasEntregaProveedor' =>'','diasfestivosNoHabilesProveedor'=>'','bodega'=>'','guiaInternacional' =>'','invoice' =>'','fechaInvoice'=>'','diasTransitoCliente'=>'','diasFestivoNoHabilesCliente'=>'','guiaInternaDestino'=>'','facturaCop' =>'','fechaRealEntrega' =>'','fechaFactura' =>''];
        foreach ($request['diasEntregaProveedor'] as $key => $value)
        {
            $detalle_id = $request['detalle_id'][$key];

            if($value!=null)
            {
                //dd($request['bodega'][$key]);
                 $arr['fechaSolicitudProveedor'] = $request['fechaSolicitudProveedor'][$key];
            }else
            {
                unset($arr['fechaSolicitudProveedor']);
            }

            if($value!=null)
            {
                $arr['diasEntregaProveedor'] = $value;

            }else
            {
                unset($arr['diasEntregaProveedor']);
            }

            if($value!=null)
            {
                //dd($request['bodega'][$key]);
                 $arr['diasfestivosNoHabilesProveedor'] = $request['diasfestivosNoHabilesProveedor'][$key];
            }else
            {
                unset($arr['diasfestivosNoHabilesProveedor']);
            }

            if($value!=null)
            {
                //dd($request['bodega'][$key]);
                 $arr['bodega'] = $request['bodega'][$key];
            }else
            {
                unset($arr['bodega']);
            }

            if($value!=null)
            {
                 $arr['guiaInternacional'] = $request['guiaInternacional'][$key];
            }else
            {
                unset($arr['guiaInternacional']);
            }

            if($value!=null)
            {
                 $arr['invoice'] = $request['invoice'][$key];
            }else
            {
                unset($arr['invoice']);
            }

            if($value!=null)
            {
                 $arr['fechaInvoice'] = $request['fechaInvoice'][$key];
            }else
            {
                unset($arr['fechaInvoice']);
            }

            if($value!=null)
            {
                 $arr['diasTransitoCliente'] = $request['diasTransitoCliente'][$key];
            }else
            {
                unset($arr['diasTransitoCliente']);
            }

            if($value!=null)
            {
                 $arr['diasFestivoNoHabilesCliente'] = $request['diasFestivoNoHabilesCliente'][$key];
            }else
            {
                unset($arr['diasFestivoNoHabilesCliente']);
            }

            if($value!=null)
            {
                 $arr['guiaInternaDestino'] = $request['guiaInternaDestino'][$key];
            }else
            {
                unset($arr['guiaInternaDestino']);
            }

            if($value!=null)
            {
                 $arr['facturaCop'] = $request['facturaCop'][$key];
            }else
            {
                unset($arr['facturaCop']);
            }

            if($value!=null)
            {
                 $arr['fechaRealEntrega'] = $request['fechaRealEntrega'][$key];
            }else
            {
                unset($arr['fechaRealEntrega']);
            }

            if($value!=null)
            {
                 $arr['fechaFactura'] = $request['fechaFactura'][$key];
            }else
            {
                unset($arr['fechaFactura']);
            }

            if(count($arr)>0)
            {
                //dd($arr);
                DB::table('item_ordens')
                ->where('id', $detalle_id)
                ->update($arr);
                $arr=['fechaSolicitudProveedor'=>'','diasfestivosNoHabilesProveedor'=>'','diasEntregaProveedor' =>'','bodega'=>'','guiaInternacional' =>'','invoice' =>'','fechaInvoice'=>'','diasTransitoCliente'=>'','diasFestivoNoHabilesCliente'=>'','guiaInternaDestino'=>'','facturaCop' =>'','fechaRealEntrega' =>'','fechaFactura' =>''];
            }
        }

        $orden_id = (int)$request->ordenId;

        $detalleOrden = itemOrden::select('ordens.id','ordens.estado_id','ordens.Trm','ordens.convencion_id','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','costoUnitario','margenUsa','fechaAceptacionCliente','fechaSolicitudProveedor','diasEntregaProveedor','fechaCantidadCompleta','diasfestivosNoHabilesProveedor','bodega','guiaInternacional','invoice','fechaInvoice','diasTransitoCliente','diasFestivoNoHabilesCliente','guiaInternaDestino','facturaCop','fechaRealEntrega','fechaFactura')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->whereIn('item_ordens.estadoItem_id',[1,4])
        ->get();
        /*Calculo de la fecha de llegada del producto del proveedor*/
        foreach ($detalleOrden as $key) 
        {
            $itemC = 0;

            $add = Carbon::parse($key->fechaSolicitudProveedor)->addDays($key->diasEntregaProveedor);
            $add->addDays($key->diasfestivosNoHabilesProveedor);
            $fechaEstimadaLlegada[] = $add->format('d-m-Y');

            if($key->bodega >= $key->cantidad)
            {
                $fecha = Carbon::now();
                $fecha->format('d-m-Y');
                $item = ItemOrden::where('id',(int)$key->id)->first();
                $item->fechaCantidadCompleta = $fecha;
                $item->update();
            }

            $diasRealesEntregaProveedor[] = Carbon::parse($key->fechaCantidadCompleta)->diffInDays($key->fechaSolicitudProveedor);

            $add->addDays($key->diasTransitoCliente);
            $add->addDays($key->diasFestivoNoHabilesCliente);

            //dd($key->fechaAceptacionCliente);
            $diasPrometidosCliente[] = Carbon::parse($key->fechaAceptacionCliente)->diffInDays($add);
            //dd($diasPrometidosCliente[0]);
            
            $diasP = $diasPrometidosCliente[$itemC];
            $fechaPrometidaCliente[] = Carbon::parse($key->fechaAceptacionCliente)->addDays($diasP);

            $diasRealesEntregaCliente[] = Carbon::parse($key->fechaAceptacionCliente)->diffInDays($key->fechaRealEntrega);
            //dd($diasRealesEntregaCliente[0]);
            $vs[] = Carbon::parse($key->fechaFactura)->diffInDays($key->fechaRealEntrega);
            $itemC++;
        }
        /*Ingreso fecha con la cantidad completada*/
    
        //dd($diasRealesEntregaProveedor);

        $variables = VariableEditable::all();

        $detallePeso = DB::table('item_ordens')
        ->join('ordens','item_ordens.orden_id','ordens.id')
        ->join('sedes','item_ordens.sede_id','sedes.id')
        ->where('item_ordens.estadoItem_id','=',1)
        ->where('ordens.id','=',$orden_id)
        ->select('ordens.id','sede_id','sedes.nombre',
                DB::raw('sum(pesoLb * cantidad) as PesoSede'),
                DB::raw('sum(cantidad) as cantidadProductos'),
                DB::raw('count(cantidad) as cantidadSede'))
        ->groupBy('sedes.id')
        ->get();
        //dd($detallePeso);
        return view('trabajos.ordenes.detalleAsignadaOrden',compact('detalleOrden','variables','detallePeso','fechaEstimadaLlegada','diasRealesEntregaProveedor','diasPrometidosCliente','fechaPrometidaCliente','diasRealesEntregaCliente','vs'))->with('orden_id',$orden_id);
    }

    public function actualizarVariableOrden(Request $request)
    {
        //dd($request);
        $orden= Orden::where('id',(int)$request->ordenId)->first();
        //dd($orden);
        $orden->nombreVar1 = $request->nombreVariable1;
        $orden->valorVar1 = $request->valorVariable1;
        $orden->nombreVar2 = $request->nombreVariable2;
        $orden->valorVar2 = $request->valorVariable2;
        $orden->nombreVar3 = $request->nombreVariable3;
        $orden->valorVar3 = $request->valorVariable3;
        $orden->update();

        //dd($orden);
        return back()->with('flash','Se actualizaron los valores');
    }

    public function actualizarItem(Request $request)
    {

        $item = ItemOrden::where('id',$request->detalleId)->first();
        $item->estadoItem_id = 4;
        $item->update();

        //dd($item);

        $detalleOrden = itemOrden::select('ordens.id','ordens.Trm','ordens.convencion_id','sedes.nombre','item_ordens.estadoItem_id','item_ordens.sede_id','item_ordens.id','marca','referencia','descripcion','cantidad','comentarios','pesoLb','costoUnitario','margenUsa','diasEntregaProveedor','bodega','guiaInternacional','invoice','fechaInvoice','diasPrometidosCliente','guiaInternaDestino','facturaCop','fechaRealEntrega','fechaFactura')
        ->join('ordens','item_ordens.orden_id','=','ordens.id')
        ->join('sedes','item_ordens.sede_id','=','sedes.id')
        ->where('ordens.id','=',$orden_id)
        ->where('item_ordens.estadoItem_id','=',1)
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
        //dd('LLego aqui');
        return view('trabajos.ordenes.detalleAsignadaOrden',compact('detalleOrden','variables','detallePeso'))->with('orden_id',$orden_id);
    }

    //Funcion para ingresar los datos de proveedor y fechas de entrega
    public function actualizarParaFacturar(Request $request)
    {


        $Orden = Orden::where('id', (int)$request->get('orden_id'))->first();
        $Orden->estado_id = 13;
        $Orden->update();

        foreach ($request['item_id'] as $key => $value)
        {
            //dd((int)$value);
            $item = ItemOrden::where('id',(int)$value)->first();
            $item->estadoItem_id = 4;
            $item->update();
        }

        $user = auth()->user()->id;
        //dd($user);
        $ordenAsignadas = Orden::select('ordens.id','ordens.estado_id','ordens.user_id','ordens.estado_id','ordens.created_at','historial_ordens.userAsignado_id','users.name')
        ->join('historial_ordens','historial_ordens.orden_id','=','ordens.id')
        ->join('users','ordens.user_id','=','users.id')
        ->where('historial_ordens.userAsignado_id','=',$user)->first()
        ->whereIn('ordens.estado_id',[3,6,9,12])
        ->get();
        //dd($ordenAsignadas);

        $user = User::where('rol_id','=',1)
        ->get();
        //dd($user);
        $orden = $request->orden_id;
        OrdenParaFacturar::dispatch($user,$orden);

        if($ordenAsignadas == null)
        {
            //dd('Viene nula la consulta');
            return view('trabajos.ordenes.asignadas_a_mi',compact('ordenAsignadas'));
        }
        else {
            //dd('La consulta trae valores');
           return view('trabajos.ordenes.asignadas_a_mi',compact('ordenAsignadas'));
        }

    }
    /*
    Funcion para recibir archivo excel e importar datos a la base de datos.
    */
    public function importarExcel(Request $request){
        $newname="ordenes".".".explode(".",$_FILES['file']['name'])[1];

        $filename = $request->file('file')->move('plantilla/leidos/');
        
        rename($filename,'plantilla/leidos/'.$newname);

        Excel::import(new OrdensImport, 'plantilla/leidos/'.$newname);
        
        return  response()->json(['respuesta'=>true,'mensaje'=>'Se han registrado tus ordenes']);




    }
}