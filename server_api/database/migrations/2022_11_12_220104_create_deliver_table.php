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
        Schema::create('deliver', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telefone')->unique();
            $table->string('matricula')->unique();
            $table->bigInteger('montante')->default(0);
            $table->boolean('blocked')->default(false);
            $table->enum('type', ['D'])->default('D');
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
        Schema::dropIfExists('deliver');
    }
};
