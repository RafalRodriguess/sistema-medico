<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntregasExameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregas_exame', function (Blueprint $table) {
            $table->id();
            $table->string('observacao')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreignId('usuario_id', 'fk_entregas_exame_usuario')
                ->references('id')
                ->on('instituicao_usuarios')
                ->onDelete('cascade')
                ->comment('usuario que fez a alteracao de status');

            $table->foreignId('local_entrega_id', 'fk_entregas_exame_local')
                ->references('id')
                ->on('locais_entrega_exame')
                ->onDelete('cascade');

            $table->foreignId('instituicao_paciente_id', 'fk_entrega_instituicao_paciente')
                ->references('id')
                ->on('instituicao_has_pacientes')
                ->onDelete('cascade');

            $table->foreignId('setor_exame_id', 'fk_entrega_setor_exame')
                ->references('id')
                ->on('setores_exame')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entregas_exame');
    }
}
