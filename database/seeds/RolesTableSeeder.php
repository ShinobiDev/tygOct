<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::truncate();

        $rol = new Rol;
        $rol->nombre = "Administrador TyG";
        $rol->save();

        $rol = new Rol;
        $rol->nombre = "Empleado TyG";
        $rol->save();

        $rol = new Rol;
        $rol->nombre = "Cliente Natural";
        $rol->save();

        $rol = new Rol;
        $rol->nombre = "Cliente Juridico";
        $rol->save();

        $rol = new Rol;
        $rol->nombre = "Cliente VIP";
        $rol->save();

        $rol = new Rol;
        $rol->nombre = "Administrador Juridico";
        $rol->save();

        $rol = new Rol;
        $rol->nombre = "Empleado Juridico";
        $rol->save();

        $rol = new Rol;
        $rol->nombre = "Usuario TyG USA";
        $rol->save();
    }
}
