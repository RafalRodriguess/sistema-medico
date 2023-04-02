<?php

namespace App\Http\Controllers\API;

use App\Prontuario;
use App\Http\Controllers\Controller;
use App\Http\Resources\InstituicaoCollection;
use App\Http\Resources\ProntuariosCollection;
use App\Usuario;
use Illuminate\Http\Request;

class ProntuariosController extends Controller
{
    public function index(Request $request)
    {

        $usuario = $request->user('sanctum');
        $arrayIds = array();

        //\DB::enableQueryLog();
        //$usuario->load([ 'prontuarios', 'prontuarios.instituicao:instituicoes.id,nome']);
        //dd(\DB::getQueryLog());
        $filtro = null;
        if($request->filtro > 0){
            $filtro = $request->filtro;
        }
        

        //VAMOS PEGAR OS IDS DAS INSTITUICOES VINCULADAS DO USUARIO
        $usuarioInstituicoes = $usuario->instituicao()
                                   //->select('instituicao_has_pacientes.id')
                                   ->when($filtro, function($q) use ($filtro){
                                        $q->where('instituicoes.id', $filtro);
                                    })
                                   ->where('usuario_id', $usuario->id)
                                   ->get();

                                   //return $usuarioInstituicoes;
        
        if(!empty($usuarioInstituicoes)){
            foreach($usuarioInstituicoes as $usuarioInstituicao){
                $arrayIds[] = $usuarioInstituicao->pivot->id;
            }
        }

        //return $arrayIds;

       /* TERMINAR DEPOIS COM MARCOS ISSO ACIMA */

        //return $usuarioInstituicoes;
        $prontuarios = Prontuario::query()
            ->whereIn('instituicao_has_pacientes_id', $arrayIds)
            ->orderByDesc('data')
            // ->whereHas('produtos')
            //->search($request->query('pesquisa', ''), false)
            ->get();

        $prontuarios->load(['instituicao:instituicoes.id,nome']);
        
        //TEM QUE TERMINAR DE PEGAR A INSTITUIÇÃO
        //return $prontuarios;

        return new ProntuariosCollection($prontuarios);
    }


    public function visualizar(Request $request)
    {

        $prontuario = Prontuario::query()
            ->where('id', $request->prontuarioId)
            // ->whereHas('produtos')
            //->search($request->query('pesquisa', ''), false)
            ->get();

            return new ProntuariosCollection($prontuario);
            

    }

    public function getInstituicoes(Request $request)
    {
        $usuario = $request->user('sanctum');
        $insituicoes = $usuario->instituicao()
        //->select('instituicao_has_pacientes.id')
        ->where('usuario_id', $usuario->id)
        ->get();

        return new InstituicaoCollection($insituicoes);
    }
}
