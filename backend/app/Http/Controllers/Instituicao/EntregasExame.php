<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use App\EntregaExame;
use App\Http\Requests\EntregasExame\EntregarExameRequest;
use App\Http\Requests\EntregasExame\FinalizarAtualizacaoEntrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregasExame extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_entrega_exames');
        return view('instituicao.entregas-exame.index');
    }

    public function detalhes(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atualizar_entrega_exames');
    }

    public function entregar(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'criar_entrega_exames');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $statuses = EntregaExame::statuses;
        $locais_entrega = $instituicao->locaisEntregaExame()->get();
        $setores = $instituicao->setoresExame()->where('ativo', 1)->get();
        $convenios = $instituicao->convenios()->get();

        return view('instituicao.entregas-exame.entregar', \compact(
            'statuses',
            'locais_entrega',
            'setores',
            'convenios'
        ));
    }

    public function finalizarEntrega(EntregarExameRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'criar_entrega_exames');
        DB::transaction(function() use ($request) {
            $usuario_logado = $request->user('instituicao');
            $dados = collect($request->validated())->merge([
                'usuario_id' => $usuario_logado->id
            ]);
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $entrega_exame = $instituicao->entregasExame()->create($dados->except('procedimentos')->toArray());
            $procedimentos = array_map(function($item) {
                return ['procedimentos_instituicao_id' => $item];
            }, $dados->get('procedimentos', []));
            $entrega_exame->entregasExameProcedimentos()->createMany($procedimentos);
            $entrega_exame->criarLogInstituicaoCadastro($usuario_logado, $instituicao->id);
        });

        return response()->json([
            'result' => true
        ]);
    }

    public function atualizar(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atualizar_entrega_exames');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $entrega = EntregaExame::find($request->validate(['entrega' => ['required', 'exists:entregas_exame,id']])['entrega']);
        $statuses = EntregaExame::statuses;
        $procedimentos = $entrega->procedimentos()->get();

        abort_unless($entrega->instituicao()->first()->id == $instituicao->id, 403);
        return view('instituicao.entregas-exame.atualizar', \compact(
            'entrega',
            'statuses',
            'procedimentos'
        ));
    }

    public function finalizarAtualizacao(FinalizarAtualizacaoEntrega $request, EntregaExame $entrega)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atualizar_entrega_exames');

        DB::transaction(function() use ($request, $entrega) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $usuario_logado = $request->user('instituicao');
            $entrega->update($request->validated());
            $entrega->criarLogInstituicaoEdicao($usuario_logado, $instituicao->id);
        });

        return response()->json([
            'result' => true
        ]);
    }
}
