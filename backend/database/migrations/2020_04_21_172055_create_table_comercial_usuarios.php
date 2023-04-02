<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableComercialUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comercial_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome','255');
            $table->string('cpf','15');
            $table->string('email','255');
            $table->string('password','255');
            $table->string('foto','255')->nullable();
            $table->tinyInteger('status')->default('0');
            $table->timestamps();
            $table->rememberToken();
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
        Schema::dropIfExists('comercial_usuarios');
    }
}
