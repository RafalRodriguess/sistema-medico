<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModeloTermoFolhaSala\CriarModeloTermoFolhaSalaRequest;
use App\Instituicao;
use App\ModeloTermoFolhaSala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModeloTermosFolhaSala extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_termo_folha_sala');
        return view('instituicao.configuracoes.modelo_termo_folha.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_termo_folha_sala');
        return view('instituicao.configuracoes.modelo_termo_folha.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarModeloTermoFolhaSalaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_termo_folha_sala');

        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        DB::transaction(function() use ($request, $instituicao, $dados){
            $usuario_logado = $request->user('instituicao');
            
            $modelo = $instituicao->modelosTermoFolhaSala()->create($dados);
            $modelo->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modelosTermoFolhaSala.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo criado com sucesso!'
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
    public function edit(Request $request, ModeloTermoFolhaSala $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_termo_folha_sala');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicao_id, 403);
        return view('instituicao.configuracoes.modelo_termo_folha.editar', compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarModeloTermoFolhaSalaRequest $request, ModeloTermoFolhaSala $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_termo_folha_sala');

        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicao_id, 403);

        DB::transaction(function() use ($request, $instituicao, $dados, $modelo){
            $usuario_logado = $request->user('instituicao');
            
            $modelo->update($dados);
            $modelo->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modelosTermoFolhaSala.edit', [$modelo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ModeloTermoFolhaSala $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_modelo_termo_folha_sala');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $modelo->instituicao_id, 403);

        DB::transaction(function() use ($request, $instituicao, $modelo){
            $usuario_logado = $request->user('instituicao');
            
            $modelo->delete();
            $modelo->criarLogExclusao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.modelosTermoFolhaSala.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo excluido com sucesso!'
        ]);
    }

    public function getModelos(Request $request){
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $modelos = $instituicao->modelosTermoFolhaSala()->where('tipo', $request->tipo)->orderBy('nome', 'asc')->get();

        return response()->json($modelos);
    }

    public function imprmirModelo(Request $request, ModeloTermoFolhaSala $modelo)
    {
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $modelo->instituicao_id, 403);

        return view('instituicao.configuracoes.modelo_termo_folha.imprimir', compact('modelo'));
    }
}
