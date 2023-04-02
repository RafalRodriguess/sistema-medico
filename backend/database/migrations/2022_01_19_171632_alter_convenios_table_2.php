<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConveniosTable2 extends Migration
{
    private $schema_table = 'convenios';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->unsignedTinyInteger('tipo_convenio')->comment('0 = SIA/SUS, 1 = SIH/SUS, 2 = Convênios, 3 = Particular')->default(2);
            $table->unsignedTinyInteger('guia_obrigatoria')->comment('0 = Atendimento, 1 = Conta, 2 = Não')->default(2);
            $table->boolean('categoria_obrigatoria')->default(false);
            $table->boolean('abate_devolucao')->default(false);
            $table->boolean('filantropia')->default(false);
            $table->boolean('fatura_p_alta')->default(false);
            $table->boolean('desc_conta')->default(false);
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
            $table->dropColumn('tipo_convenio');
            $table->dropColumn('categoria_obrigatoria');
            $table->dropColumn('guia_obrigatoria');
            $table->dropColumn('abate_devolucao');
            $table->dropColumn('filantropia');
            $table->dropColumn('fatura_p_alta');
            $table->dropColumn('desc_conta');
        });
    }
}
