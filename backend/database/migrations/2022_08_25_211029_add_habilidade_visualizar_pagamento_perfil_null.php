<?php

use App\InstituicaoHabilidade;
use App\InstituicaoUsuario;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHabilidadeVisualizarPagamentoPerfilNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $array = ['visualizar_valor_procedimento'];

        $habilidades = InstituicaoHabilidade::whereIn('nome_unico', $array)->get();

        $usuarios = InstituicaoUsuario::get();

        foreach ($usuarios as $key => $usuario) {
            $instituicoes = $usuario->instituicao()->wherePivot('perfil_id', null)->get();
            if(count($instituicoes) > 0){
                foreach ($instituicoes as $key => $instituicao) {
                    foreach ($habilidades as $key => $habilidade) {
                        $dadosHabilidades[$habilidade->id] = [
                            'instituicao_id' => $instituicao->id,
                            'habilitado' => 1,
                        ];   
                        
                        $usuario->instituicaoHabilidades()->attach($dadosHabilidades);
                    }
                }
            }
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
