<?php

namespace App\Imports;

use App\Orden;
use App\User;
use App\Cliente;
use Maatwebsite\Excel\Concerns\ToModel;


class OrdensImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
         
        if(!is_null($row[0]) ){
           
            if($row[0]!="usuario"){


                //validar usuario 
                $us=User::where("name",$row[0])->first();
                //validar cliente
                $cl=Cliente::where("nombreCliente",$row[0])->first();
               
                return new Orden([
                    'user_id'     => is_null($us) ? 1 : $us->id,
                    'cliente_id'    =>  is_null($cl) ? 1 : $cl->id, 
                    'estado_id' => 0,
                    'convencion_id'=> $row[2],
                    'Trm'=> $row[3],
                    'userAsignado_id'=> '',
                    'fechaAceptacionCliente'=> '',
                    'precioTotalGlobal'=> $row[4],
                    'totalValorArancelCal'=> $row[5],
                    'totalEmpaqueCal'=> $row[6],
                    'totalCintaCal'=> $row[7],
                    'totalCosto3Cal'=> $row[8],
                    'totalCostoUsdColCal'=> $row[9],
                    'nombreVar1'=> $row[10],
                    'valorVar1'=> $row[11],
                    'nombreVar2'=> $row[12],
                    'valorVar2'=> $row[13],
                    'nombreVar3'=> $row[14],
                    'valorVar3'=> $row[15],
                ]);
            
            }
        }
        
    }
}
