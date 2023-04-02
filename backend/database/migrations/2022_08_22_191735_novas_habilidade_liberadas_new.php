<?php

use App\InstituicaoHabilidade;
use App\InstituicaoUsuario;
use App\Ramo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NovasHabilidadeLiberadasNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ramos = Ramo::get();

        $array = ['visualizar_prontuario','visualizar_receituario','visualizar_refracao','visualizar_atestado','visualizar_relatorio','visualizar_exame','visualizar_arquivo','visualizar_resumo','visualizar_historico'];

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

        $usuarios = InstituicaoUsuario::get();

        foreach ($usuarios as $key => $usuario) {
            $instituicoes = $usuario->instituicao()->get();
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
