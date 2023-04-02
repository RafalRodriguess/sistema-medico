<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModelosRecibo\CreateModeloReciboRequest;
use App\Instituicao;
use App\ModeloRecibo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModelosRecibo extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_recibo');
        return view('instituicao.configuracoes.modelo_recibo.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_recibo');
        return view('instituicao.configuracoes.modelo_recibo.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateModeloReciboRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_recibo');

        $dados = $request->validated();

        $dados['texto'] = trim($dados['texto']);


        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        DB::transaction(function() use ($request, $instituicao, $dados){
            $usuario_logado = $request->user('instituicao');
            
            $modelo = $instituicao->modelosRecibo()->create($dados);
            $modelo->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modelosRecibo.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de recibo criado com sucesso!'
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
    public function edit(Request $request, ModeloRecibo $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_recibo');
        return view('instituicao.configuracoes.modelo_recibo.editar', compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateModeloReciboRequest $request, ModeloRecibo $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_recibo');

        $dados = $request->validated();

        $dados['texto'] = trim($dados['texto']);

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        DB::transaction(function() use ($request, $instituicao, $dados, $modelo){
            $usuario_logado = $request->user('instituicao');
            
            $modelo->update($dados);
            $modelo->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modelosRecibo.edit', [$modelo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de recibo alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getModelo(Request $request, ModeloRecibo $modelo){
        $texto = "";
        
        for($i = 0; $i < $modelo->vias; $i++){
            $texto .= $modelo->texto."<br><hr><br>";
        }

        return view('instituicao.configuracoes.modelo_recibo.visualizar_recibo', compact('modelo', 'texto'));
    }
}
