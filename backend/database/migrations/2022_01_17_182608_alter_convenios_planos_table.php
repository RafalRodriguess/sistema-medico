<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConveniosPlanosTable extends Migration
{
    private $schema_table = 'convenios_planos';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->text('descricao')->nullable();
            $table->boolean('paga_acompanhante')->default(false);
            $table->boolean('validade_indeterminada')->default(false);
            $table->boolean('senha_guia_obrigatoria')->default(false);
            $table->boolean('valida_guia')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->dropColumn('descricao');
            $table->dropColumn('paga_acompanhante');
            $table->dropColumn('validade_indeterminada');
            $table->dropColumn('senha_guia_obrigatoria');
            $table->dropColumn('valida_guia');
        });
    }
}
