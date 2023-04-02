<?php

namespace App\Http\Controllers\Instituicao;

use App\Acomodacao;
use App\Http\Controllers\Controller;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Acomodacoes\{InstituicaoCreateAcomodacao};

class Acomodacoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_acomodacoes');

        return view('instituicao.internacao.acomodacoes.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_acomodacoes');

        return view('instituicao.internacao.acomodacoes.criar', [
            'tipos_acomodacoes' => Acomodacao::getTipos(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateAcomodacao $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_acomodacoes');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $request, $instituicao) {
            $dados['extra_virtual'] = (isset($dados['extra_virtual']))? true: false;

            $acomodacao = $instituicao->acomodacoes()->create($dados);
            $acomodacao->criarLogCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.internacao.acomodacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Acomodação criada com sucesso!'
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
    public function edit(Acomodacao $acomodacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_acomodacoes');

        return view('instituicao.internacao.acomodacoes.editar', [
            'tipos_acomodacoes' => Acomodacao::getTipos(),
            'acomodacao' => $acomodacao
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoCreateAcomodacao $request, Acomodacao $acomodacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_acomodacoes');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($acomodacao->instituicao_id === $instituicao->id, 403);

        $dados = $request->validated();

        $acomodacao = DB::transaction(function() use ($dados, $request, $acomodacao, $instituicao) {
            $dados['cobertura'] = (isset($dados['cobertura']))? true: false;
            $acomodacao->update($dados);
            $acomodacao->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $acomodacao;
        });

        return redirect()->route('instituicao.internacao.acomodacoes.edit', [$acomodacao])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Acomodação editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Acomodacao $acomodacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_acomodacoes');

        $instituicao_id = $request->session()->get('instituicao');

        abort_unless($acomodacao->instituicao_id === $instituicao_id, 403);

        DB::transaction(function() use ($request, $acomodacao, $instituicao_id) {
            $acomodacao->delete();
            $acomodacao->criarLogExclusao($request->user('instituicao'), $instituicao_id);
        });

        return redirect()->route('instituicao.internacao.acomodacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Acomodação excluida com sucesso!'
        ]);
    }
}
