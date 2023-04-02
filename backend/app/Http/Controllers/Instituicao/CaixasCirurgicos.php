<?php

namespace App\Http\Controllers\Instituicao;

use App\CaixaCirurgico;
use App\Http\Controllers\Controller;
use App\Http\Requests\CaixaCirurgico\CriarCaixaCirurgicoRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaixasCirurgicos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_caixas_cirurgicos');

        return view('instituicao.caixa_cirurgico.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_caixas_cirurgicos');

        return view('instituicao.caixa_cirurgico.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarCaixaCirurgicoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_caixas_cirurgicos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $request, $instituicao) {
            $dados['ativo'] = $request->boolean('ativo');

            $caixa_cirurgico = $instituicao->caixasCirurgicos()->create($dados);
            $caixa_cirurgico->criarLogCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.caixasCirurgicos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Caixas cirúrgico criada com sucesso!'
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
    public function edit(Request $request, CaixaCirurgico $caixa_cirurgico)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_caixas_cirurgicos');

        return view('instituicao.caixa_cirurgico.editar', \compact('caixa_cirurgico'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarCaixaCirurgicoRequest $request, CaixaCirurgico $caixa_cirurgico)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_caixas_cirurgicos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($caixa_cirurgico->instituicao_id === $instituicao->id, 403);

        $dados = $request->validated();

        $caixa_cirurgico = DB::transaction(function() use ($dados, $request, $caixa_cirurgico, $instituicao) {
            $dados['ativo'] = $request->boolean('ativo');

            $caixa_cirurgico->update($dados);
            $caixa_cirurgico->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $caixa_cirurgico;
        });

        return redirect()->route('instituicao.caixasCirurgicos.edit', [$caixa_cirurgico])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Caixa cirúrgico editado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CaixaCirurgico $caixa_cirurgico)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_caixas_cirurgicos');

        $instituicao_id = $request->session()->get('instituicao');

        abort_unless($caixa_cirurgico->instituicao_id === $instituicao_id, 403);

        DB::transaction(function() use ($request, $caixa_cirurgico, $instituicao_id) {
            $caixa_cirurgico->delete();
            $caixa_cirurgico->criarLogExclusao($request->user('instituicao'), $instituicao_id);
        });

        return redirect()->route('instituicao.caixasCirurgicos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Centro cirúrgico excluido com sucesso!'
        ]);
    }
}
