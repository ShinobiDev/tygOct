<?php

use Illuminate\Database\Seeder;
use App\EstadoItem;

class EstadosItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoItem::truncate();

        $estadoItem = new EstadoItem;
        $estadoItem->nombre = "Cotizado";
        $estadoItem->save();

        $estadoItem = new EstadoItem;
        $estadoItem->nombre = "Dividido";
        $estadoItem->save();

        $estadoItem = new EstadoItem;
        $estadoItem->nombre = "Pendiente";
        $estadoItem->save();

        $estadoItem = new EstadoItem;
        $estadoItem->nombre = "Entregado";
        $estadoItem->save();

        $estadoItem = new EstadoItem;
        $estadoItem->nombre = "Facturado";
        $estadoItem->save();
    }
}
