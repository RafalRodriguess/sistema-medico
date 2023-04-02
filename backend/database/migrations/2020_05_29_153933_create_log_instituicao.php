<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_instituicao', function (Blueprint $table) {
            $table->id();
            // $table->integer('instituicao_id')->unsigned();
            $table->foreignId("instituicao_id")->nullable()->default(null)->references("id")->on("instituicoes");
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
        Schema::dropIfExists('log_instituicao');
    }
}
