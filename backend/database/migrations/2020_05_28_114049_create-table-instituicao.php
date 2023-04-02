<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicoes', function (Blueprint $table) {
            // $table->engine = 'InnoDB';
            // $table->increments('id');
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nome', '255');
            $table->string('chave_unica', '255');
            $table->json('metadados')->nullable();
            $table->boolean('habilitado')->default(true);
            $table->text('imagem')->nullable();
            $table->boolean('permite_historico')->default(false);
            $table->foreignId('banco_id')->nullable()->references('id')->on('contas_bancarias');
            $table->string('id_recebedor','100')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituicoes');
    }
}
