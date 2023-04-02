<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRegrasCobrancaHasItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regras_cobranca_has_itens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('regra_cobranca_id')->references('id')->on('regras_cobranca');
            $table->foreignId('grupo_procedimento_id')->references('id')->on('grupos_procedimentos');
            $table->foreignId('faturamento_id')->references('id')->on('faturamentos');
            $table->decimal('pago', 5,2);
            $table->string('base');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regras_cobranca_has_itens');
    }
}
