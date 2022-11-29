<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_to_deliver', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_order')->unsigned();
            $table->foreign('id_order')->references('id')->on('orders');
            $table->bigInteger('id_deliver')->unsigned()->nullable();
            $table->foreign('id_deliver')->references('id')->on('deliver')->onUpdate('cascade');
            $table->enum('estado', ['A Preparar', 'Entregue', 'Em Espera', 'A Entregar'])->default('Em Espera');
            $table->string('local_entrega');
            $table->string('local_restaurante')->default('39.74289749923697, -8.822979656717283');
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
        Schema::dropIfExists('orders_to_deliver');
    }
};
