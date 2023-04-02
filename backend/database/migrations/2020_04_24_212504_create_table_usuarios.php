<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', '255');
            $table->date('data_nascimento');
            $table->string('cpf','15');
            $table->string('customer_id','100')->nullable();
            $table->string('telefone','25');
            $table->string('cod','255')->nullable();
            $table->string('password')->nullable()->default(null);
            $table->string('nome_mae', '255')->nullable();
            $table->date('data_nascimento_mae')->nullable();
            $table->string('email', '255')->nullable();
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
        Schema::dropIfExists('usuarios');
    }
}
