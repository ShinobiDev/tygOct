<?php

use Illuminate\Database\Seeder;
use App\Sede;

class SedesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sede::truncate();

        $sede = new Sede;
        $sede->cliente_id = 3;
        $sede->nombre = "Chico";
        $sede->direccion = "Cl 98 # 34 - 56";
        $sede->pais = "Colombia";
        $sede->estado = "Cesar";
        $sede->ciudad = "Valledupar";
        $sede->telefono = "3445653";
        $sede->contactoSede = "Jaime Montoya";
        $sede->save();

        $sede = new Sede;
        $sede->cliente_id = 3;
        $sede->nombre = "Usaquen";
        $sede->pais = "Colombia";
        $sede->estado = "Cundinamarca";
        $sede->ciudad = "Chia";
        $sede->direccion = "Cr 9 # 134 - 23";
        $sede->telefono = "8866432";
        $sede->contactoSede = "Andrea Nuñez";
        $sede->save();


        $sede = new Sede;
        $sede->cliente_id = 4;
        $sede->nombre = "Calle 128";
        $sede->pais = "Colombia";
        $sede->estado = "Cundinamarca";
        $sede->ciudad = "Bogota";
        $sede->direccion = "Cl 128 # 30 - 36";
        $sede->telefono = "4568329";
        $sede->contactoSede = "Tatiana Robles";
        $sede->save();

        $sede = new Sede;
        $sede->cliente_id = 4;
        $sede->nombre = "Calle 170";
        $sede->pais = "Colombia";
        $sede->estado = "Cundinamarca";
        $sede->ciudad = "Bogota";
        $sede->direccion = "Cl 170 # 15 - 83";
        $sede->telefono = "8764453";
        $sede->contactoSede = "Jaime Pardo";
        $sede->save();

        $sede = new Sede;
        $sede->cliente_id = 4;
        $sede->nombre = "Carrera 5";
        $sede->pais = "Colombia";
        $sede->estado = "Cundinamarca";
        $sede->ciudad = "Bogota";
        $sede->direccion = "Cr 5 # 78 - 26";
        $sede->telefono = "2678937";
        $sede->contactoSede = "Rodrigo Arzuaga";
        $sede->save();
    }
}
