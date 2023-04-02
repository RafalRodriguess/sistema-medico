<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCarteirasPlanosConvenios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas_carteiras_planos_convenio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->references('id')->on('pessoas');
            $table->foreignId('convenio_id')->references('id')->on('convenios');
            $table->foreignId('plano_id')->references('id')->on('convenios_planos');

            $table->string('carteirinha');
            $table->date('validade');
            $table->integer('status')->default('1')->comment('1 -> ativa, 0 -> inativa');
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
        Schema::dropIfExists('pessoas_carteiras_planos_convenio');
    }
}
