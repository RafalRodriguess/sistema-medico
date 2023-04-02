<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InstituicaoCollection;
use App\Http\Resources\InstituicaoResource;
use App\Http\Resources\ListaInstituicaoExamesCollection;
use App\Instituicao;
use App\Procedimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstituicoesController extends Controller
{
    public function vincular(Request $request, Instituicao $instituicao)
    {
        
        //return true;

        $integracao = $instituicao->integracao();
        
        $usuario = $request->user('sanctum');

        // $dados = [
        //     'cpf' => $usuario->cpf_numeros, 
        //     'cartao' => $request->input('cartao'),
        // ];

        //return $integracao->getPaciente($dados);
        return $integracao->getDados($usuario);

        // 
        // $instiuicao->instituicaoPaciente()->attach($usuario, ['id_externo' => '', 'metadados' => ''])
    }

    public function index(Request $request)
    {
        $instituicao = Instituicao::query()
            ->where('habilitado', 1)
            // ->whereHas('produtos')
            ->when($request->query('search'),function($q) use ($request){
                $q->where(function($qI) use($request){
                    $qI->orWhere('nome', 'like', "%{$request->query('search')}%");
                    $qI->orWhere(function($queryE) use($request){
                        $queryE->whereHas('procedimentos', function($qE) use($request) {
                            $qE->where('descricao', 'like', "%{$request->query('search')}%");
                        });
                    });
                });
            })
            ->paginate(30);

       

        return new InstituicaoCollection($instituicao);
    }

    public function instituicaoExame(Request $request)
    {     

        $instituicaoExames = Procedimento::
            join('procedimentos_instituicoes', 'procedimentos_instituicoes.procedimentos_id','=','procedimentos.id')
            ->join('instituicoes', 'instituicoes.id', '=', 'procedimentos_instituicoes.instituicoes_id')
            ->join('grupos_procedimentos', 'grupos_procedimentos.id', '=', 'procedimentos_instituicoes.grupo_id')
            ->join('procedimentos_instituicoes_convenios','procedimentos_instituicoes_convenios.procedimentos_instituicoes_id', '=', 'procedimentos_instituicoes.id')
            ->join('convenios','convenios.id','=','procedimentos_instituicoes_convenios.convenios_id')
            ->when($request->query('search'), function($q) use($request){
                $q->where(function($query) use($request){
                    $query->orWhere('instituicoes.nome', 'like', "%{$request->query('search')}%");
                    $query->orWhere('procedimentos.descricao', 'like', "%{$request->query('search')}%");
                });
            })
            ->where('procedimentos.tipo', 'exame')
            ->select('procedimentos.descricao as exame', 'instituicoes.nome as instituicao', 'instituicoes.id as instituicao_id', 'grupos_procedimentos.nome as grupo', 'convenios.nome as convenio', 'procedimentos_instituicoes_convenios.valor as valor')
            ->paginate(30);

        return new ListaInstituicaoExamesCollection($instituicaoExames);

    }

    public function getInstituicao(Request $request, Instituicao $instituicao)
    {
        return new InstituicaoResource($instituicao);
    }
}
