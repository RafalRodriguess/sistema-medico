<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConveniosTable3 extends Migration
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
            $table->unsignedTinyInteger('forma_agrupamento')->comment('0 = Diário, 1 = Importação, 2 = Documento, 3 = Nenhuma')->default(0);
            $table->unsignedInteger('retorno_atendimento_ambulatorio')->comment('em dias')->nullable();
            $table->unsignedInteger('retorno_atendimento_externo')->comment('em dias')->nullable();
            $table->unsignedInteger('retorno_atendimento_urgencia')->comment('em horas')->nullable();
            $table->unsignedTinyInteger('fonte_de_remuneracao')->comment('0 = Convênio - Plano privado, 1 = Convênio - Plano público, 2 = Particular pessoa física, 3 = Gratúito, 4 = Financiado com recurso própio da SES, 5 = Financiado com recurso própio da SMS, 6 = DPVAT, 7 = Particular - Pessoa jurídica')->default(0);
            $table->boolean('permitir_atendimento_ambulatorial')->default(false);
            $table->boolean('permitir_atendimento_externo')->default(false);
            $table->string('registro_ans')->nullable();
            $table->boolean('carteira_pede')->default(false);
            $table->boolean('carteira_verif_elig')->default(false);
            $table->boolean('carteira_obg')->default(false);
            $table->unsignedInteger('limite_contas_pre_remessa')->nullable();
            $table->boolean('fechar_conta_amb_sem_impressao')->default(false);
            $table->unsignedInteger('quantidade_alerta_faixa')->nullable();
            $table->unsignedTinyInteger('tipo_cobranca_oncologia')->comment('0 = Tratamento, 1 = Ciclo, 2 = Sessão')->default(0);
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
            $table->dropColumn('forma_agrupamento');
            $table->dropColumn('retorno_atendimento_ambulatorio');
            $table->dropColumn('retorno_atendimento_externo');
            $table->dropColumn('retorno_atendimento_urgencia');
            $table->dropColumn('fonte_de_remuneracao');
            $table->dropColumn('permitir_atendimento_ambulatorial');
            $table->dropColumn('permitir_atendimento_externo');
            $table->dropColumn('registro_ans');
            $table->dropColumn('carteira_pede');
            $table->dropColumn('carteira_verif_elig');
            $table->dropColumn('carteira_obg');
            $table->dropColumn('limite_contas_pre_remessa');
            $table->dropColumn('fechar_conta_amb_sem_impressao');
            $table->dropColumn('quantidade_alerta_faixa');
            $table->dropColumn('tipo_cobranca_oncologia');
        });
    }
}
