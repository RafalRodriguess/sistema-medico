<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableContasBancarias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_bancarias', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name','255');
            $table->string('bank_code','20');
            $table->string('agencia','10');
            $table->string('agencia_dv','5');
            $table->string('conta','10');
            $table->string('conta_dv', '5');
            $table->enum('type', ['conta_corrente','conta_poupanca', 'conta_corrente_conjunta', 'conta_poupanca_conjunta']);
            $table->string('documento_titular', '20');
            $table->string('nome_titular', '30');
            $table->string('id_pagarme', '255');
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
        Schema::dropIfExists('comerciais');
    }
}
