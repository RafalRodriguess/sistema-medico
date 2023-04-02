<?php

use App\Pessoa;
use App\SenhaTriagem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePacienteSenhasTriagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Atualizando registros antigos
        $senhas_antigas = [];
        try {
            $senhas_antigas = SenhaTriagem::whereNotNull('nome_paciente')
                ->select('senhas_triagem.*', 'instituicoes_id')
                ->join('filas_totem', 'filas_totem.id', 'filas_totem_id')
                ->join('filas_triagem', 'filas_triagem.id', 'filas_triagem_id')
                ->get()->toArray();
        } catch (\Exception $e) {
        }

        foreach ($senhas_antigas as $senha) {
            $pessoa = Pessoa::create([
                'tipo' => 2,
                'personalidade' => 1,
                'nome' => $senha['nome_paciente'],
                'nome_mae' => $senha['nome_mae'] ?? '',
                'cpf' => $senha['cpf'] ?? '',
                'instituicao_id' => $senha['instituicoes_id']
            ]);

            SenhaTriagem::find($senha['id'])->update([
                'pessoa_id' => $pessoa->id
            ]);
        }

        Schema::table('senhas_triagem', function (Blueprint $table) {
            $table->dropColumn([
                'nome_paciente',
                'nome_mae',
                'cpf',
            ]);
            $table->string('doencas_cronicas')->nullable();
            $table->string('alergias')->nullable();
            $table->boolean('primeiro_atendimento')->default(0);
            $table->boolean('reincidencia')->default(0);
            $table->unsignedBigInteger('prestador_id')->nullable();

            $table->dropForeign('fk_senha_triagem_pessoa');
            $table->foreign('prestador_id', 'fk_triagem_prestador')->references('id')->on('prestadores')->onDelete('set null');
            $table->foreign('pessoa_id', 'fk_senha_triagem_pessoa')->references('id')->on('pessoas')->onDelete('set null')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('senhas_triagem', function (Blueprint $table) {
            $table->string('nome_paciente')->nullable()->comment('Nome do paciente caso este não esteja cadastrado no sistema');
            $table->string('nome_mae')->nullable();
            $table->string('cpf')->nullable();

            $table->dropColumn([
                'doencas_cronicas',
                'alergias',
                'primeiro_atendimento',
                'reincidencia'
            ]);
        });
    }
}
