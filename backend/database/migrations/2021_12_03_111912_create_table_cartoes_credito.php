<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCartoesCredito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartoes_credito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicao_id')->references('id')->on("instituicoes");
            $table->string("descricao");
            $table->integer('bandeira')->comment('1 - Mastercard, 2 - Visa, 3 - American Express, 4 - Hipercard, 5 - Elo');
            $table->float("limite")->default(0);
            $table->integer("fechamento");
            $table->integer("vencimento");
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
        Schema::dropIfExists('cartoes_credito');
    }
}
