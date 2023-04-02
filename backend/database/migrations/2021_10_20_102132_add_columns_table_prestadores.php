<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTablePrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->string('nome_fantasma')->nullable()->after('nome');
            $table->dropColumn('nome');
            $table->integer('tipo')->after('nome_fantasma');
            $table->string('cpf', '15')->nullable()->change();
            $table->string('cnpj', '18')->nullable();
            $table->string('pis', '255')->nullable();
            $table->string('pasep', '255')->nullable();
            $table->string('nir', '255')->nullable();
            $table->string('proe', '255')->nullable();
            $table->string('nome_social')->nullable()->after('nome_fantasma');
            //
            $table->string('razao_social')->nullable()->after('nome_fantasma');
            $table->string('rua')->nullable()->after('razao_social');
            $table->string('bairro')->nullable()->after('razao_social');
            $table->string('cidade')->nullable()->after('razao_social');
            $table->string('estado')->nullable()->after('razao_social');
            $table->integer('numero')->nullable()->after('razao_social');
            $table->integer('numero_cooperativa')->nullable()->after('numero');
            $table->string('nome_banco')->nullable()->after('numero_cooperativa');
            $table->string('agencia')->nullable()->after('numero_cooperativa');
            $table->string('conta_bancaria')->nullable()->after('numero_cooperativa');
            $table->boolean('anestesista')->nullable()->after('conta_bancaria');
            $table->boolean('auxiliar')->nullable()->after('anestesista');
            //
            $table->string('sexo')->nullable()->after('nome_social');
            $table->string('nascimento', '10')->nullable()->after('sexo');
            $table->string('identidade', '13')->nullable()->after('cpf');
            $table->string('identidade_orgao_expedidor', '255')->nullable()->after('identidade');
            $table->string('identidade_uf', '2')->nullable()->after('identidade_orgao_expedidor');
            $table->string('identidade_data_expedicao', '10')->nullable()->after('identidade_uf');
            $table->string('nome_da_mae', '255')->nullable()->after('identidade_data_expedicao');
            $table->string('nome_do_pai', '255')->nullable()->after('nome_da_mae');
            $table->string('naturalidade')->nullable()->after('nome_do_pai');
            $table->string('nacionalidade')->nullable()->after('naturalidade');
            $table->string('numero_cartao_sus', '18')->nullable()->after('nacionalidade');
            $table->integer('tipo_conselho_id')->nullable();
            $table->integer('carga_horaria_mensal')->nullable();
            $table->dropColumn('cod');
            $table->dropColumn('crm');
            $table->boolean('ativo')->after('tipo_conselho_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->dropColumn('nome_fantasma');
            $table->string('nome', '255');
            $table->dropColumn('tipo');
            $table->dropColumn('cnpj');
            $table->dropColumn('pis');
            $table->dropColumn('pasep');
            $table->dropColumn('nir');
            $table->dropColumn('proe');
            $table->dropColumn('nome_social');

            $table->dropColumn('razao_social');
            $table->dropColumn('rua');
            $table->dropColumn('bairro');
            $table->dropColumn('cidade');
            $table->dropColumn('estado');
            $table->dropColumn('numero');
            $table->dropColumn('numero_cooperativa');
            $table->dropColumn('nome_banco');
            $table->dropColumn('agencia');
            $table->dropColumn('conta_bancaria');
            $table->dropColumn('anestesista');
            $table->dropColumn('auxiliar');

            $table->dropColumn('sexo');
            $table->dropColumn('nascimento');
            $table->dropColumn('identidade');
            $table->dropColumn('identidade_orgao_expedidor');
            $table->dropColumn('identidade_uf');
            $table->dropColumn('identidade_data_expedicao');
            $table->dropColumn('nome_da_mae');
            $table->dropColumn('nome_do_pai');
            $table->dropColumn('naturalidade');
            $table->dropColumn('nacionalidade');
            $table->dropColumn('numero_cartao_sus');
            $table->dropColumn('tipo_conselho_id');
            $table->dropColumn('carga_horaria_mensal');
            $table->string('cod', '255');
            $table->string('crm', '15');
            $table->dropColumn('ativo');
        });
    }
}
