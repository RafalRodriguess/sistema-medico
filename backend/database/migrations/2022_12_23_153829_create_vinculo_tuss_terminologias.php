<?php

use App\VinculoTussTerminologia;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVinculoTussTerminologias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinculo_tuss_terminologias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cod_tabela')->comment('código utilizado para identificar a tabela a ser importada');
            $table->string('descricao', 100)->comment('descrição da tabela a ser importada');
            $table->string('cabecalho', 200)->comment('insira a primeira linha do CSV para validar na hora de importar');
            $table->timestamps();
            $table->softDeletes();
        });

        VinculoTussTerminologia::create([
            'cod_tabela' => 22,
            'descricao' => 'Procedimentos e eventos em saúde',
            'cabecalho' => 'Código do Termo;Termo;Data de início de vigência;Data de fim de vigência;Data de fim de implantação',
        ]);

        VinculoTussTerminologia::create([
            'cod_tabela' => 23,
            'descricao' => 'Caráter do atendimento',
            'cabecalho' => 'Código do Termo;Termo;Data de início de vigência;Data de fim de vigência;Data de fim de implantação',
        ]);

        VinculoTussTerminologia::create([
            'cod_tabela' => 24,
            'descricao' => 'Código brasileiro de ocupação CBO',
            'cabecalho' => 'Código do Termo;Termo;Data de início de vigência;Data de fim de vigência;Data de fim de implantação',
        ]);

        VinculoTussTerminologia::create([
            'cod_tabela' => 39,
            'descricao' => 'Motivo de encerramento',
            'cabecalho' => 'Código do Termo;Termo;Data de início de vigência;Data de fim de vigência;Data de fim de implantação',
        ]);

        VinculoTussTerminologia::create([
            'cod_tabela' => 43,
            'descricao' => 'Sexo',
            'cabecalho' => 'Código do Termo;Termo;Data de início de vigência;Data de fim de vigência;Data de fim de implantação',
        ]);

        VinculoTussTerminologia::create([
            'cod_tabela' => 50,
            'descricao' => 'Tipo de Atendimento',
            'cabecalho' => 'Código do Termo;Termo;Data de início de vigência;Data de fim de vigência;Data de fim de implantação',
        ]);

        VinculoTussTerminologia::create([
            'cod_tabela' => 52,
            'descricao' => 'Tipo de consulta',
            'cabecalho' => 'Código do Termo;Termo;Data de início de vigência;Data de fim de vigência;Data de fim de implantação',
        ]);

        VinculoTussTerminologia::create([
            'cod_tabela' => 63,
            'descricao' => 'Grupos de procedimentos e itens assistenciais para envio de dados para ANS',
            'cabecalho' => 'Código;Grupo;Data de início de vigência;Data de fim de vigência;Data de fim de implantação',
        ]);

        VinculoTussTerminologia::create([
            'cod_tabela' => 64,
            'descricao' => 'Forma de envio para ANS de procedimentos e itens assistenciais',
            'cabecalho' => 'Terminologia;Código TUSS;Forma de envio;Código do grupo;Descrição do grupo;Data de início de vigência;Data de fim de vigência;Data de fim de implantação',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vinculo_tuss_terminologias');
    }
}
