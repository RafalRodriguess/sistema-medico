<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRegrasCobranca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regras_cobranca', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->string('descricao');
            $table->decimal('cir_mesma_via', 5,2);
            $table->decimal('cir_via_diferente', 5,2);
            $table->tinyInteger('horario_especial')->default(0);
            $table->string('base_via_acesso');
            $table->tinyInteger('internacao')->default(0);
            $table->tinyInteger('ambulatorial')->default(0);
            $table->tinyInteger('urgencia_emergencia')->default(0);
            $table->tinyInteger('externo')->default(0);
            $table->tinyInteger('home_care')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regras_cobranca');
    }
}
