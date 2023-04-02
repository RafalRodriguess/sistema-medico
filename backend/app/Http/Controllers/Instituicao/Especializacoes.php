<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Especializacao;
use App\Http\Requests\Especializacoes\CriarEspecializacaoRequest;
use App\Instituicao;
use Illuminate\Support\Facades\DB;

class Especializacoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_especializacao');

        return view('instituicao.especializacoes/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_especializacao');

        return view('instituicao.especializacoes/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarEspecializacaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_especializacao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        DB::transaction(function () use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $especializacao = $instituicao->especializacoes()->create($dados);
            $especializacao->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.especializacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especialização criada com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Especializacao  $especializacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Especializacao $especializacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_especializacao');

        return view('instituicao.especializacoes/editar', \compact('especializacao'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CriarEspecializacaoRequest  $request
     * @param  Especializacao $especializacao
     * @return \Illuminate\Http\Response
     */
    public function update(CriarEspecializacaoRequest $request, Especializacao $especializacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_especializacao');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$especializacao->instituicoes_id, 403);

        $dados = $request->validated();

        DB::transaction(function () use ($request, $especializacao, $dados, $instituicao){
            $especializacao->update($dados);

            $usuario_logado = $request->user('instituicao');

            $especializacao->criarLogEdicao(
              $usuario_logado, $instituicao
            );


            return $especializacao;
        });


        return redirect()->route('instituicao.especializacoes.index', [$especializacao])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especialização atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Especializacao $especializacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Especializacao $especializacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_especializacao');

        DB::transaction(function () use ($request, $especializacao){
            $especializacao->delete();
            $especializacao->criarLogExclusao($request->user('instituicao'));
        });

        return redirect()->route('instituicao.especializacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especializacao excluída com sucesso!'
        ]);
    }
}