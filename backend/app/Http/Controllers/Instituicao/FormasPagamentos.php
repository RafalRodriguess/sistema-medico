<?php

namespace App\Http\Controllers\Instituicao;

use App\FormaPagamento;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormasPagamentos\CriarFormasPagamentosRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormasPagamentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_forma_pagamento');

        return view('instituicao.formas_pagamentos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_forma_pagamento');

        return view('instituicao.formas_pagamentos.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarFormasPagamentosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_forma_pagamento');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            //dd($dados);
            $motivoParto = $instituicao->formasPagamentos()->create($dados);
            $motivoParto->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.formasPagamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Forma de pagamento criado com sucesso!'
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
    public function edit(FormaPagamento $formaPagamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_forma_pagamento');
        
        return view('instituicao.formas_pagamentos.editar',\compact("formaPagamento"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarFormasPagamentosRequest $request, FormaPagamento $formaPagamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_forma_pagamento');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$formaPagamento->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $formaPagamento){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $formaPagamento->update($dados);
            $formaPagamento->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.formasPagamentos.edit', [$formaPagamento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Forma de pagamento alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FormaPagamento $formaPagamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_forma_pagamento');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$formaPagamento->instituicao_id, 403);
        DB::transaction(function () use ($formaPagamento, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $formaPagamento->delete();
            $formaPagamento->criarLogExclusao($usuario_logado, $instituicao);
            

            return $formaPagamento;
        });
        
        return redirect()->route('instituicao.formasPagamentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'forma de pagamento excluído com sucesso!'
        ]);
    }
}
