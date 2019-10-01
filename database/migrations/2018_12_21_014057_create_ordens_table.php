<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('estado_id');
            $table->unsignedInteger('convencion_id');
            $table->decimal('Trm');
            $table->integer('userAsignado_id')->nullable();
            $table->date('fechaAceptacionCliente')->nullable();
            $table->decimal('precioTotalGlobal')->nullable();
            $table->decimal('totalValorArancelCal')->nullable();
            $table->decimal('totalEmpaqueCal')->nullable();
            $table->decimal('totalCintaCal')->nullable();
            $table->decimal('totalCosto3Cal')->nullable();
            $table->decimal('totalCostoUsdColCal')->nullable();
            $table->string('nombreVar1')->nullable();
            $table->decimal('valorVar1')->nullable();
            $table->string('nombreVar2')->nullable();
            $table->decimal('valorVar2')->nullable();
            $table->string('nombreVar3')->nullable();
            $table->decimal('valorVar3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordens');
    }
}
