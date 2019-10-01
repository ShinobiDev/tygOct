<?php

use Illuminate\Database\Seeder;
use App\VariableEditable;

class VariablesEditablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VariableEditable::truncate();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "TRM";
        $variableEditable->valor = 3250;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "Valor Libra";
        $variableEditable->valor = 2.90;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "IVA";
        $variableEditable->valor = 19;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "Empaque";
        $variableEditable->valor = 6.980;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "Cinta";
        $variableEditable->valor = 8;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "Costo3";
        $variableEditable->valor = 10;
        $variableEditable->save();
    }
}
