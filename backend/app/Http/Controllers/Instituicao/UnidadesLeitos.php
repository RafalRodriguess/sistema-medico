<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use Illuminate\Http\Request;
use App\UnidadeInternacao;
use App\Http\Requests\Leitos\{
    InstituicaoCreateLeito,
    InstituicaoEditLeito
};
use App\UnidadeLeito;
use Illuminate\Support\Facades\DB;

class UnidadesLeitos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UnidadeInternacao $unidade_internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_leitos');
        
        return view('instituicao.internacao.leitos.lista', [
            'unidade_internacao' => $unidade_internacao
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, UnidadeInternacao $unidade_internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_leitos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        return view('instituicao.internacao.leitos.criar', [
            'unidade_internacao' => $unidade_internacao,
            'tipos_unidades' => UnidadeInternacao::getTiposUnidades(),
            'centros_custos' => $instituicao->ccs()->get(),
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
    public function store(InstituicaoCreateLeito $request, UnidadeInternacao $unidade_internacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_leitos');

        $dados = $request->validated();

        DB::transaction(function () use ($dados, $request, $unidade_internacao){
            foreach($dados['leitos'] as $leito){
                $novo_leito = $unidade_internacao->leitos()->create($leito);
                $novo_leito->criarLogCadastro($request->user('instituicao'));
            }
        });
        
        $message = 'Leito criado com sucesso!';
        if(count($dados['leitos']) > 1){
            $message = 'Leitos criados com sucesso!';
        }
        return redirect()->route('instituicao.internacao.leitos.index', [$unidade_internacao])
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => $message
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
    public function edit(
        Request $request, UnidadeInternacao $unidade_internacao, UnidadeLeito $leito)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_leitos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        return view('instituicao.internacao.leitos.editar', [
            'unidade_internacao' => $unidade_internacao,
            'leito' => $leito,
            'tipos_unidades' => UnidadeInternacao::getTiposUnidades(),
            'centros_custos' => $instituicao->ccs()->get(),
            'medicos' => $instituicao->medicos()->get(),
            'acomodacoes' => $instituicao->acomodacoes()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoEditLeito $request, 
        UnidadeInternacao $unidade_internacao, UnidadeLeito $leito)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_leitos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($unidade_internacao->id === $leito->unidade_id || 
            $instituicao->id === $unidade_internacao->instituicao_id , 403);

        $dados = $request->validated();

        $leito = DB::transaction(function () use ($dados, $request, $leito, $instituicao){
            $leito->update($dados);
            $leito->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $leito;
        });
        
        return redirect()->route('instituicao.internacao.leitos.edit', [$unidade_internacao, $leito])
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Leito editado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, 
        UnidadeInternacao $unidade_internacao, UnidadeLeito $leito)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_leitos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($unidade_internacao->id === $leito->unidade_id || 
            $instituicao->id === $unidade_internacao->instituicao_id , 403);

        DB::transaction(function () use ($request, $leito, $instituicao){
            $leito->delete();
            $leito->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });
        
        return redirect()->route('instituicao.internacao.leitos.index', [$unidade_internacao])
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Leito excluido com sucesso!'
        ]);
    }
}
