<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMedicamentosAddPrestador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicamentos_add_prestador', function (Blueprint $table) {
            $table->foreignId('instituicao_usuario_id')->references('id')->on('instituicao_usuarios');
            $table->foreignId('instituicao_medicamento_id')->references('id')->on('instituicao_medicamentos');
            $table->string('quantidade')->nullable();
            $table->text('posologia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicamentos_add_prestador');
    }
}
