<?php

namespace App\Http\Controllers\Instituicao;

use App\GrupoFaturamento;
use App\Http\Controllers\Controller;
use App\Http\Requests\GrupoFaturamento\CriarGrupoFaturamentoRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GruposFaturamento extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_grupo_faturamento');
        
        return view('instituicao.grupos_faturamento/lista');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_grupo_faturamento');
        $tipos = GrupoFaturamento::tipoValores();
        return view('instituicao.grupos_faturamento/criar', \compact('tipos'));
    }

    public function store(CriarGrupoFaturamentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_grupo_faturamento');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $dados = $request->validated();
        $dados['val_grupo_faturamento'] = $request->boolean('val_grupo_faturamento');
        $dados['rateio_nf'] = $request->boolean('rateio_nf');
        $dados['incide_iss'] = $request->boolean('incide_iss');

        DB::transaction(function () use ($dados, $request, $instituicao) {
            $grupo = $instituicao->gruposFaturamento()->create($dados);
            $usuario_logado = $request->user('instituicao');
            $grupo->criarLogCadastro($usuario_logado, $instituicao->id);
            return $grupo;
        });


        return redirect()->route('instituicao.grupoFaturamento.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupo criado com sucesso!'
        ]);
    }


    public function edit(GrupoFaturamento $grupo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_grupo_faturamento');
        $tipos = GrupoFaturamento::tipoValores();
        return view('instituicao.grupos_faturamento/editar', \compact('grupo', 'tipos'));
    }


    public function update(CriarGrupoFaturamentoRequest $request, GrupoFaturamento $grupo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_grupo_faturamento');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($grupo->instituicao_id === $instituicao, 403);
        $dados = $request->validated();
        $dados['val_grupo_faturamento'] = $request->boolean('val_grupo_faturamento');
        $dados['rateio_nf'] = $request->boolean('rateio_nf');
        $dados['incide_iss'] = $request->boolean('incide_iss');
        
        DB::transaction(function () use ($request, $grupo, $dados, $instituicao) {
            $grupo->update($dados);

            $usuario_logado = $request->user('instituicao');
            $grupo->criarLogEdicao($usuario_logado, $instituicao);

            return $grupo;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('instituicao.grupoFaturamento.edit', [$grupo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupos atualizado com sucesso!'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Procedimento  $procedimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, GrupoFaturamento $grupo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_grupo_faturamento');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($grupo->instituicao_id === $instituicao, 403);

        DB::transaction(function () use ($request, $grupo, $instituicao) {
            $grupo->delete();

            $usuario_logado = $request->user('instituicao');
            $grupo->criarLogExclusao($usuario_logado, $instituicao);

            return $grupo;
        });

        return redirect()->route('instituicao.grupoFaturamento.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupo faturamento excluído com sucesso!'
        ]);
    }

    public function ativarDesativar(Request $request, GrupoFaturamento $grupo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'ativar_grupo_faturamento');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($grupo->instituicao_id === $instituicao, 403);

        DB::transaction(function() use($grupo, $request, $instituicao){
            $ativo = 1;
            if($grupo->ativo){
                $ativo = 0;
            }

            $grupo->update(['ativo' => $ativo]);
            $usuario_logado = $request->user('instituicao');
            $grupo->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.grupoFaturamento.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupo faturamento ativado/desativado com sucesso!'
        ]);
    }
}
