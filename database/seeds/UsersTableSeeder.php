<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	User::truncate();

        $user = new User;
        $user->name = "Administrador";
        $user->email = "stalindesarrollador@gmail.com";
        $user->password = bcrypt('12345678');
        $user->telefono = "6236344";
        $user->rol_id = 1;
        $user->estado_id = 1;
        $user->documento = "90064562";
        $user->save();

        $user = new User;
        $user->name = "Usuario Col";
        $user->email = "stalin1@misena.edu.co";
        $user->password = bcrypt('12345678');
        $user->telefono = "6236344";
        $user->rol_id = 2;
        $user->estado_id = 1;
        $user->documento = "900642702";
        $user->save();

        $user = new User;
        $user->name = "Usuario USA";
        $user->email = "stalinchacu@outlook.com";
        $user->password = bcrypt('12345678');
        $user->telefono = "6236344";
        $user->rol_id = 8;
        $user->estado_id = 1;
        $user->documento = "923425235";
        $user->save();
    }
}
