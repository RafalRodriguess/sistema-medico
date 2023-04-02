<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoParto\CriarTipoPartoRequest;
use App\Instituicao;
use App\TipoParto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoPartos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_tipo_partos');

        return view('instituicao.tipo_partos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipo_partos');

        return view('instituicao.tipo_partos.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarTipoPartoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipo_partos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $tipoParto = $instituicao->tipoPartos()->create($dados);
            $tipoParto->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.tipoPartos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de parto criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoParto $tipo_parto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipo_partos');

        return view('instituicao.tipo_partos.editar',\compact("tipo_parto"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarTipoPartoRequest $request, TipoParto $tipo_parto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipo_partos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$tipo_parto->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $tipo_parto){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $tipo_parto->update($dados);
            $tipo_parto->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.tipoPartos.edit', [$tipo_parto])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de parto alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TipoParto $tipo_parto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_tipo_partos');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$tipo_parto->instituicao_id, 403);
        DB::transaction(function () use ($tipo_parto, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $tipo_parto->delete();
            $tipo_parto->criarLogExclusao($usuario_logado, $instituicao);
            

            return $tipo_parto;
        });
        
        return redirect()->route('instituicao.tipoPartos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de parto excluído com sucesso!'
        ]);
    }
}
