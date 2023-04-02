<?php

namespace App\Http\Controllers\Instituicao;

use App\Conta;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contas\CriarContasRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Contas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_contas');
        return view('instituicao.contas.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_contas');
        return view('instituicao.contas.criar', [
            'opcoes_tipo' => Conta::opcoes_tipo
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarContasRequest $request, Conta $contas)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_contas');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            $dados['saldo_inicial'] = str_replace([".",","],["","."], $dados['saldo_inicial']);
            $contas = $instituicao->contas()->create($dados);
            $contas->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.contas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Conta criado com sucesso!'
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
    public function edit(Request $request, Conta $conta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_contas');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$conta->instituicao_id, 403);
        return view('instituicao.contas.editar', [
            'opcoes_tipo' => Conta::opcoes_tipo,
            'conta' => $conta
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarContasRequest $request, Conta $conta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_contas');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$conta->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $conta){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();            
            $dados['saldo_inicial'] = str_replace([".",","],["","."], $dados['saldo_inicial']);
            $conta->update($dados);
            $conta->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.contas.edit', [$conta])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Contao alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Conta $conta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_contas');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$conta->instituicao_id, 403);
        DB::transaction(function () use ($conta, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $conta->delete();
            $conta->criarLogExclusao($usuario_logado, $instituicao);

            return $conta;
        });

        return redirect()->route('instituicao.contas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Conta excluída com sucesso!'
        ]);
    }
}
