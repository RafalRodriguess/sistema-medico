<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableComercialHasUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comercial_has_usuarios', function (Blueprint $table) {
            $table->foreignId('comercial_id')->references('id')->on('comerciais');
            $table->foreignId('usuario_id')->references('id')->on('comercial_usuarios');
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
        Schema::dropIfExists('comercial_has_usuarios');
    }
}
