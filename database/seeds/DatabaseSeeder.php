<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(EstadosItemTableSeeder::class);
        $this->call(EstadosOrdenTableSeeder::class);
        $this->call(VariablesEditablesTableSeeder::class);
        $this->call(ConvencionTableSeeder::class);
        //$this->call(SedesTableSeeder::class);

    }
}
