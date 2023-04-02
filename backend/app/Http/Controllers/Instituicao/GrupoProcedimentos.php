<?php

namespace App\Http\Controllers\Instituicao;

use App\GrupoFaturamento;
use App\Http\Controllers\Controller;
use App\Http\Requests\GruposProcedimentos\CreateGrupoProcedimentoRequest;
use App\Instituicao;
use App\GruposProcedimentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupoProcedimentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_grupos');

        return view('instituicao.grupos_procedimentos/lista');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_grupos');
        $tipos = GrupoFaturamento::tipoValores();
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $gruposFaturamento = $instituicao->gruposFaturamento()->orderBy('descricao', 'ASC')->get();
        return view('instituicao.grupos_procedimentos/criar', \compact('tipos', 'gruposFaturamento'));
    }


    public function edit(Request $request, GruposProcedimentos $grupos_procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_grupos');
        $grupo = $grupos_procedimento;
        $tipos = GrupoFaturamento::tipoValores();
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $gruposFaturamento = $instituicao->gruposFaturamento()->orderBy('descricao', 'ASC')->get();
        
        return view('instituicao.grupos_procedimentos/editar', \compact('grupo', 'tipos', 'gruposFaturamento'));
    }


    public function update(CreateGrupoProcedimentoRequest $request, GruposProcedimentos $grupos_procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_grupos');
        $dados = $request->validated();
        $dados['principal'] = $request->boolean('principal');

        DB::transaction(function () use ($request, $grupos_procedimento, $dados) {
            $grupos_procedimento->update($dados);

            $usuario_logado = $request->user('instituicao');
            $grupos_procedimento->criarLogEdicao($usuario_logado);

            return $grupos_procedimento;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('instituicao.gruposProcedimentos.edit', [$grupos_procedimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupos atualizado com sucesso!'
        ]);
    }



    public function store(CreateGrupoProcedimentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_grupos');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $dados = $request->validated();
        $dados['principal'] = $request->boolean('principal');

        DB::transaction(function () use ($dados, $request, $instituicao) {
            $grupo = $instituicao->grupoProcedimentos()->create($dados);
            $usuario_logado = $request->user('instituicao');
            $grupo->criarLogCadastro($usuario_logado);
            return $grupo;
        });


        return redirect()->route('instituicao.gruposProcedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupo criado com sucesso!'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Procedimento  $procedimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, GruposProcedimentos $grupos_procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_grupos');
        DB::transaction(function () use ($request, $grupos_procedimento) {
            $grupos_procedimento->delete();

            $usuario_logado = $request->user('instituicao');
            $grupos_procedimento->criarLogExclusao($usuario_logado);

            return $grupos_procedimento;
        });

        return redirect()->route('instituicao.gruposProcedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento excluído com sucesso!'
        ]);
    }
}
