<?php

namespace App\Imports;

use App\ItemOrden;
use App\Orden;
use App\Sede;
use Maatwebsite\Excel\Concerns\ToModel;

class ItemOrdensImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
        if(!is_null($row[0]) && $row[0] != "sede"){
           $item=$this->validar_casillas($row);
       
            if($item != false){
               


                return new ItemOrden([
                    'orden_id'=>$item['orden_id'],
                    //'item_id'=>$item['item_id'],
                    'estadoItem_id'=>$item['estado_item'],
                    'sede_id'=>$item['sede_id'],
                    'marca'=>$item['marca'],
                    'descripcion'=>$item['descripcion'],
                    'referencia'=>$item['referencia'],
                    'cantidad'=>$item['cantidad'],
                    'vin'=>$item['vin'],
                    'placa' =>$item['placa'],
                    'comentarios'=>$item['comentarios']

                ]);

            }
        }
        
    }
    //funcion para validar las casillas del excel, retorena un arreglo con las casillas listas para insercion
    public function validar_casillas($fila){
              
            $item=[];
            foreach ($fila as $key => $value) {
                if(!is_null($value)){

                    $id_orden=$this->crear_orden(1,auth()->user()->id);

                    if($id_orden == false){
                        return false;
                    }
                    
                    $id_sede=$this->buscar_sede($fila[0]);

                    if($id_sede == false){
                        return false;
                    }
                   
                    $item['orden_id'] = $id_orden;
                    //$item['item_id'] = $this->buscar_item($value[]);
                    $item['estado_item']=1;
                    $item['sede_id']=$id_sede;
                    $item['marca']=$fila[1];
                    $item['referencia']=$fila[2];
                    $item['descripcion']=$fila[4];
                    $item['cantidad']=$fila[3];
                    $item['vin']=$fila[6];
                    $item['placa']=$fila[7];
                    $item['comentarios']=$fila[8];
                }
            }
       
        return $item;
    }

    public function buscar_sede($nombre_sede){
        $sede=Sede::where('nombre',$nombre_sede)->first();
        
        return !is_null($sede) ? $sede->id : false; 
    }
    public function buscar_item(){
        //pendiente impplemetación
    }

    public function crear_orden($id_usuario = 1,$id_cliente = 1,$id_estado = 1,$id_convencion = 1,$trm = 1){
        $orden = Orden::firstOrCreate(['user_id'=>$id_usuario,'cliente_id'=>$id_cliente,'estado_id'=>$id_estado,'convencion_id'=>$id_convencion,'Trm'=>$trm]);
        return !is_null($orden) ? $orden->id: false;
    }

}
