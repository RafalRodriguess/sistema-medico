<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrestadoresSolicitantes\CreatePrestadoresSolicitantesRequest;
use App\Instituicao;
use App\PrestadorSolicitante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestadoresSolicitantes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $this->authorize('habilidade_instituicao_sessao', 'visualizar_solicitantes');
       

       return view('instituicao.solicitantes/lista');
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_solicitantes');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.solicitantes/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePrestadoresSolicitantesRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_solicitantes');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $solicitante = $instituicao->solicitantes()->create($dados);
            $solicitante->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.solicitantes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Prestador solicitante criado com sucesso!'
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
    public function edit(Request $request, PrestadorSolicitante $solicitante)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_solicitantes');

        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao===$solicitante->instituicao_id, 403);
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.solicitantes.editar',\compact("solicitante"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePrestadoresSolicitantesRequest $request, PrestadorSolicitante $solicitante)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_solicitantes');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$solicitante->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $solicitante){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $solicitante->update($dados);
            $solicitante->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.solicitantes.edit', [$solicitante])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Prestador solicitante alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PrestadorSolicitante $solicitante)
    {
        
        $instituicaoId = $request->session()->get('instituicao');
        abort_unless($instituicaoId === $solicitante->instituicao_id, 403);
        
        $this->authorize('habilidade_instituicao_sessao', 'excluir_solicitantes');

        DB::transaction(function () use ($request, $solicitante){
            $solicitante->delete();

            $usuario_logado = $request->user('instituicao');
            $solicitante->criarLogExclusao($usuario_logado);

            return $solicitante;
        });

        return redirect()->route('instituicao.solicitantes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Prestador solicitante excluído com sucesso!'
        ]);
    }
}
