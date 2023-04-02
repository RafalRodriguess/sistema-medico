<?php

use App\InstituicaoHabilidade;
use App\InstituicaoUsuario;
use App\PerfilUsuarioInstituicao;
use App\Ramo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHabilidadeVisualizarPagamentoAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ramos = Ramo::get();

        $array = ['visualizar_valor_procedimento'];

        $habilidades = InstituicaoHabilidade::whereIn('nome_unico', $array)->get();

        $dadosRamos = [];
        foreach ($habilidades as $key => $habilidade) {
            $dadosRamos[] = [
                'habilidade_id' => $habilidade->id
            ];   
        }

        foreach ($ramos as $key => $ramo) {
            $ramo->habilidades()->attach($dadosRamos);
        }

        $perfil = PerfilUsuarioInstituicao::whereIn('id', [1,2])->get();

        foreach ($perfil as $key => $value) {
            $value->habilidades()->attach($dadosRamos);
        }

        $usuarios = InstituicaoUsuario::get();

        foreach ($usuarios as $key => $usuario) {
            $instituicoes = $usuario->instituicao()->wherePivot('perfil_id', '<>', '3')->get();
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
