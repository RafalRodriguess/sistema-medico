<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InstituicaoTransferencia\InstituicaoTransferenciaRequest;
use App\Instituicao;
use App\InstituicaoTransferencia;
use Illuminate\Support\Facades\DB;

class InstituicoesTransferencia extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_instituicoes_transferencia');

        return view('instituicao.internacao.instituicoes-transferencia.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_instituicoes_transferencia');
        
        return view('instituicao.internacao.instituicoes-transferencia.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoTransferenciaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_instituicoes_transferencia');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $instituicao, $request){
            $instituicao_transferencia = $instituicao->instituicoesTransferencia()->create($dados);
            $instituicao_transferencia->criarLogCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.internacao.instituicoes-transferencia.index')
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Instituicao para transferência registrada com sucesso!'
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
    public function edit(Request $request, InstituicaoTransferencia $instituicao_transferencia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_instituicoes_transferencia');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $instituicao_transferencia->instituicao_id, 403);
        
        return view('instituicao.internacao.instituicoes-transferencia.editar', [
            'instituicao_transferencia' => $instituicao_transferencia
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoTransferenciaRequest $request, 
        InstituicaoTransferencia $instituicao_transferencia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_instituicoes_transferencia');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $instituicao_transferencia->instituicao_id, 403);

        $dados = $request->validated();

        $instituicao_transferencia = DB::transaction(function() use ($dados, $instituicao, 
            $request, $instituicao_transferencia){
            $instituicao_transferencia->update($dados);
            $instituicao_transferencia->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $instituicao_transferencia;
        });

        return redirect()->route('instituicao.internacao.instituicoes-transferencia.edit', [$instituicao_transferencia])
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Instituicao para transferência editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, InstituicaoTransferencia $instituicao_transferencia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_instituicoes_transferencia');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $instituicao_transferencia->instituicao_id, 403);

        DB::transaction(function() use ($instituicao, $request, $instituicao_transferencia){
            $instituicao_transferencia->delete();
            $instituicao_transferencia->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.internacao.instituicoes-transferencia.index')
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Instituicao para transferência excluída com sucesso!'
        ]);
    }
}
