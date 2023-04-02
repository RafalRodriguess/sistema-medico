<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovaTabelaLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("comercial_id")->nullable()->default(null)->references("id")->on("comerciais");
            $table->morphs("usuario");
            $table->morphs("registro");
            $table->timestamps();
            $table->string("descricao", 255);
            $table->longText("dados")->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
        Schema::dropIfExists('log');
    }
}
