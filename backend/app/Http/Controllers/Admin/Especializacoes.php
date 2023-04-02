<?php

namespace App\Http\Controllers\Admin;

use App\Especializacao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Especializacoes\CriarEspecializacaoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Especializacoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_admin', 'visualizar_especializacao');

        return view('admin.especializacoes/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_especializacao');

        return view('admin.especializacoes/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarEspecializacaoRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_especializacao');
        DB::transaction(function () use ($request){

            $especializacao = Especializacao::create($request->validated());
            $especializacao->criarLogCadastro($request->user('admin'));
        });

        return redirect()->route('especializacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especialização criada com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Especializacao  $especializacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Especializacao $especializacao)
    {
        $this->authorize('habilidade_admin', 'editar_especializacao');

        return view('admin.especializacoes/editar', \compact('especializacao'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CriarEspecializacaoRequest  $request
     * @param  Especializacao $especializacao
     * @return \Illuminate\Http\Response
     */
    public function update(CriarEspecializacaoRequest $request, Especializacao $especializacao)
    {
        $this->authorize('habilidade_admin', 'editar_especializacao');

        $dados = $request->validated();

        DB::transaction(function () use ($request, $especializacao, $dados){
            $especializacao->update($dados);

            $usuario_logado = $request->user('admin');

            $especializacao->criarLogEdicao(
              $usuario_logado
            );


            return $especializacao;
        });


        return redirect()->route('especializacoes.index', [$especializacao])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especialização atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Especializacao $especializacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Especializacao $especializacao)
    {
        $this->authorize('habilidade_admin', 'excluir_especializacao');

        DB::transaction(function () use ($request, $especializacao){
            $especializacao->delete();
            $especializacao->criarLogExclusao($request->user('admin'));
        });

        return redirect()->route('especializacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especializacao excluída com sucesso!'
        ]);
    }
}
