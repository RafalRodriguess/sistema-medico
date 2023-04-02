<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCirurgias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cirurgias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicao_id')->references('id')->on("instituicoes");
            $table->string('descricao');
            $table->string('porte');
            $table->integer('obstetricia')->comment('0 -> Não, 1 -> Sim');
            $table->foreignId('tipo_parto_id')->nullable()->references('id')->on("tipo_partos");
            $table->integer('previsao')->comment('Em minutos');
            $table->text('orientacoes')->nullable();
            $table->text('preparos')->nullable();
         
            
            // int procedimento_convenio_id

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cirurgias');
    }
}
