<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecoEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endereco_entregas', function (Blueprint $table) {

            $table->id();
            $table->string('nome','255');
            $table->string('cpf','15');
            $table->string('rua','255');
            $table->string('numero','10');
            $table->string('bairro','100');
            $table->string('cidade','100');
            $table->string('estado','100');
            $table->string('cep','10');
            $table->string('complemento', '255')->nullable();
            $table->string('referencia', '255')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('endereco_entregas');
    }
}
