<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFretesEntrega extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fretes_entrega', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fretes_id')->references('id')->on('fretes');
            $table->decimal('valor', 8, 2)->nullable();
            $table->decimal('valor_minimo', 8, 2)->nullable();
            $table->string('cidade', 255)->nullable();
            $table->string('bairro', 255)->nullable();
            $table->string('cep_inicio', 255)->nullable();
            $table->string('cep_fim', 255)->nullable();
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
        Schema::dropIfExists('fretes_entrega');
    }
}
