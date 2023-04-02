<?php

namespace App\Http\Controllers\Instituicao;

use App\GanchoModel;
use App\Hooks\TriagemIniciada;
use App\Http\Controllers\Controller;
use App\Http\Requests\TiposChamadaTotem\CriarChamadaTotemRequest;
use App\Instituicao;
use App\TipoChamadaTotem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TiposChamadaTotem extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_tipos_chamada_totem');

        return view('instituicao.tipos-chamada-totem/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipos_chamada_totem');
        $ganchos = TriagemIniciada::all();
        return view('instituicao.tipos-chamada-totem/criar', \compact('ganchos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarChamadaTotemRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipos_chamada_totem');
        
        DB::transaction(function() use ($request) {
            $instituicao = Instituicao::find($request->session()->get("instituicao"));
            $usuario_logado = $request->user('instituicao');
            $tipo_chamada = $instituicao->tiposChamadaTotem()->create($request->validated());
            $tipo_chamada->criarLogInstituicaoCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.totens.tipos-chamada.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo Chamada criado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoChamadaTotem $tipo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipos_chamada_totem');
        $ganchos = TriagemIniciada::all();
        return view('instituicao.tipos-chamada-totem/editar', \compact('tipo', 'ganchos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarChamadaTotemRequest $request, TipoChamadaTotem $tipo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipos_chamada_totem');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($tipo->instituicoes_id == $instituicao->id, 403);

        DB::transaction(function() use ($request, $tipo) {
            $usuario_logado = $request->user('instituicao');
            $tipo->update($request->validated());
            $tipo->criarLogInstituicaoEdicao($usuario_logado, $tipo->instituicoes_id);
        });

        return redirect()->route('instituicao.totens.tipos-chamada.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo Chamada atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TipoChamadaTotem $tipo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipos_chamada_totem');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($tipo->instituicoes_id == $instituicao->id, 403);

        DB::transaction(function() use ($request, $tipo) {
            $usuario_logado = $request->user('instituicao');
            $tipo->delete();
            $tipo->criarLogInstituicaoExclusao($usuario_logado, $tipo->instituicoes_id);
        });

        return redirect()->route('instituicao.totens.tipos-chamada.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo Chamada deletado com sucesso!'
        ]);
    }
}
