<?php

use Illuminate\Database\Seeder;
use App\EstadoOrden;

class EstadosOrdenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoOrden::truncate();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Por Cotizar";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Por Cotizar Sin Asignar";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Por Cotizar Asignado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Cotizado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Cotizado Sin Asignar";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Cotizado Asignado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Orden";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Orden Sin Asignar";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Orden Asignado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Ordenado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Ordenado Sin Asignar";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Ordenado Asignado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Entregado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Cancelado";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Negociacion";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Negociacion Aceptada";
        $estadoOrden->save();

        $estadoOrden = new EstadoOrden;
        $estadoOrden->nombreEstado = "Facturada";
        $estadoOrden->save();
    }
}
