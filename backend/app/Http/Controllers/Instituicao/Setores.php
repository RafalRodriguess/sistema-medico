<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Setor;
use App\Http\Requests\Setores\{
    InstituicaoCreateSetor,
    InstituicaoEditSetor,
};
use App\Instituicao;

class Setores extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_setores');

        return view('instituicao.setores.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_setores');

        return view('instituicao.setores.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateSetor $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_setores');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $instituicao, $request){
            $setore = $instituicao->setores()->create($dados);
            $setore->criarLogCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.setores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Setor criado com sucesso!'
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
    public function edit(Request $request, Setor $setore)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_setores');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $setore->instituicao_id, 403);

        return view('instituicao.setores.editar', [
            'setore' => $setore
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoEditSetor $request, Setor $setore)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_setores');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $setore->instituicao_id, 403);

        $dados = $request->validated();

        $setore = DB::transaction(function() use ($dados, $instituicao, $request, $setore){
            $setore->update($dados);
            $setore->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $setore;
        });

        return redirect()->route('instituicao.setores.edit', [$setore])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Setor atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Setor $setore)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_setores');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $setore->instituicao_id, 403);

        DB::transaction(function () use ($setore, $request, $instituicao) {
            $setore->delete();
            $setore->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.setores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Setor excluído com sucesso!'
        ]);
    }
}
