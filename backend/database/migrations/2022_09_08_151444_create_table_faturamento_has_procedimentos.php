<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFaturamentoHasProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faturamento_has_procedimentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('faturamento_id')->references('id')->on('faturamentos');
            $table->date('data_vigencia');
            $table->bigInteger('procedimento_id');
            $table->string('descricao');
            $table->decimal('vl_honorario', 9,4);
            $table->decimal('vl_operacao', 9,4);
            $table->decimal('vl_total', 9,4);
            $table->tinyInteger('ativo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faturamento_has_procedimentos');
    }
}
