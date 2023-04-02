<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeColumnEntregasExameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('entregas_exame')->update(['status' => 0]);

        Schema::table('entregas_exame', function(Blueprint $table) {
            $table->dropForeign('entregas_exame_instituicao_paciente_id_foreign');
            $table->dropColumn('instituicao_paciente_id');

            $table->unsignedBigInteger('pessoa_id')->nullable();
            $table->foreign('pessoa_id', 'fk_entrega_pessoa_id')
                ->references('id')
                ->on('pessoas')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entregas_exame', function(Blueprint $table) {
            $table->dropForeign('fk_entrega_pessoa_id');
            $table->dropColumn('pessoa_id');

            $table->unsignedBigInteger('instituicao_paciente_id');
            $table->foreign('instituicao_paciente_id', 'entregas_exame_instituicao_paciente_id_foreign')
                ->references('id')
                ->on('instituicao_has_pacientes')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });
    }
}
