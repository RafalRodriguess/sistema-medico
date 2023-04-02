<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnidadeInternacao\{
    InstituicaoCreateUnidadeInternacao,
    InstituicaoEditUnidadeInternacao };
use Illuminate\Http\Request;
use App\UnidadeInternacao;
use App\CentroCusto;
use App\Instituicao;
use Illuminate\Support\Facades\DB;

class UnidadesInternacoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_unidade_internacao');

        return view('instituicao.internacao.unidades-internacoes.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_unidade_internacao');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.internacao.unidades-internacoes.criar', [
            'tipos_unidades' => UnidadeInternacao::getTiposUnidades(),
            'centros_custos' => $instituicao->centrosCustos()->get(),
            'medicos' => $instituicao->medicos()->get(),
            'acomodacoes' => $instituicao->acomodacoes()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateUnidadeInternacao $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_unidade_internacao');

        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        DB::transaction(function() use ($request, $dados, $instituicao){
            $dados['ativo'] = (isset($dados['ativo']))? true: false;
            $unidade_interncacao = $instituicao->unidadesInternacoes()->create($dados);
            if(isset($dados['leitos'])){
                foreach($dados['leitos'] as $leito){
                    $leito = $unidade_interncacao->leitos()->create($leito);
                    $leito->criarLogCadastro($request->user('instituicao'));
                }
            }
            $unidade_interncacao->criarLogCadastro($request->user('instituicao'));
        });

        return redirect()->route('instituicao.internacao.unidade-internacao.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Unidade de Internação criada com sucesso!'
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
    public function edit(Request $request, UnidadeInternacao $unidade_internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_unidade_internacao');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.internacao.unidades-internacoes.editar', [
            'tipos_unidades' => UnidadeInternacao::getTiposUnidades(),
            'centros_custos' => $instituicao->centrosCustos()->get(),
            'medicos' => $instituicao->medicos()->get(),
            'acomodacoes' => $instituicao->acomodacoes()->get(),
            'unidade_internacao' => $unidade_internacao
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoEditUnidadeInternacao $request, UnidadeInternacao $unidade_internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_unidade_internacao');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($unidade_internacao->instituicao_id === $instituicao->id, 403);

        $dados = $request->validated();

        $unidade_internacao = DB::transaction(function() use ($request, $dados, $instituicao, $unidade_internacao){
            $dados['ativo'] = (isset($dados['ativo']))? true: false;
            $unidade_internacao->update($dados);
            $unidade_internacao->criarLogInstituicaoEdicao($request->user('instituicao'), $instituicao->id);
            return $unidade_internacao;
        });

        return redirect()->route('instituicao.internacao.unidade-internacao.edit', [$unidade_internacao])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Unidade de Internação editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, UnidadeInternacao $unidade_internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_unidade_internacao');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($unidade_internacao->instituicao_id === $instituicao->id, 403);

        DB::transaction(function() use ($request, $instituicao, $unidade_internacao){
            $unidade_internacao->delete();
            $unidade_internacao->criarLogInstituicaoExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.internacao.unidade-internacao.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Unidade de Internação excluida com sucesso!'
        ]);
    }
}
