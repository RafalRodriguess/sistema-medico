<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFretes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fretes', function (Blueprint $table) {

            $table->id();
            $table->foreignId('comercial_id')->references('id')->on('comerciais');
            $table->enum('tipo_frete', ['entrega', 'retirada'])->nullable();
            $table->enum('tipo_filtro', ['cidade', 'cidade_bairro', 'faixa_cep', 'cep_unico'])->nullable(); 
            $table->boolean('ativado')->nullable()->default(true);
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
        Schema::dropIfExists('fretes');
    }
}
