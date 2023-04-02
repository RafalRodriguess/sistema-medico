<?php

namespace App\Http\Controllers\Instituicao;

use App\CartaoCredito;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cartoescredito\CriarCartaoCreditoRequest;
use App\Http\Requests\PlanosContas\EditarPlanoContasRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartoesCredito extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_cartao_credito');
        return view('instituicao.cartoes_credito.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_cartao_credito');
        return view('instituicao.cartoes_credito.criar', [
            'opcoes_bandeira' => CartaoCredito::opcoes_bandeira
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarCartaoCreditoRequest $request, CartaoCredito $cartoesCredito)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_cartao_credito');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        //dd( $instituicao );
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $cartoesCredito = $instituicao->cartoesCredito()->create($dados);
            $cartoesCredito->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.cartoesCredito.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Cartão de crédito criado com sucesso!'
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
    public function edit(Request $request, CartaoCredito $cartaoCredito)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_cartao_credito');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$cartaoCredito->instituicao_id, 403);
        return view('instituicao.cartoes_credito.editar', [
            'cartaoCredito' => $cartaoCredito,
            'opcoes_bandeira' => CartaoCredito::opcoes_bandeira
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarCartaoCreditoRequest $request, CartaoCredito $cartaoCredito)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_cartao_credito');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$cartaoCredito->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $cartaoCredito){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $cartaoCredito->update($dados);
            $cartaoCredito->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.cartoesCredito.edit', [$cartaoCredito])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Cartão de crédito alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CartaoCredito $cartaoCredito)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_cartao_credito');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$cartaoCredito->instituicao_id, 403);
        DB::transaction(function () use ($cartaoCredito, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $cartaoCredito->delete();
            $cartaoCredito->criarLogExclusao($usuario_logado, $instituicao);

            return $cartaoCredito;
        });

        return redirect()->route('instituicao.cartoesCredito.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Cartão de crédito excluídos com sucesso!'
        ]);
    }

}
