<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Atendimentos\{
    InstituicaoCreateAtendimento,
    InstituicaoEditAtendimento
};
use Illuminate\Support\Facades\DB;
use App\Atendimento;
use App\Instituicao;

class Atendimentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atendimentos');

        return view('instituicao.atendimentos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_atendimentos');

        return view('instituicao.atendimentos.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateAtendimento $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_atendimentos');


        DB::transaction(function() use ($request){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            $dados['instituicao_id'] = $request->session()->get('instituicao');
            $atendimento = Atendimento::create($dados);
            $atendimento->criarLogCadastro($usuario_logado);
        });

        return redirect()->route('instituicao.atendimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atendimento criado com sucesso!'
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Atendimento $atendimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_atendimentos');
        return view('instituicao.atendimentos.editar', \compact('atendimento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoEditAtendimento $request, Atendimento $atendimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_atendimentos');


        $atendimento = DB::transaction(function() use ($request, $atendimento){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            $atendimento->fill($dados);
            $atendimento->update();
            $atendimento->criarLogEdicao($usuario_logado);
            return $atendimento;
        });

        return redirect()->route('instituicao.atendimentos.edit', [$atendimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atendimento atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Atendimento $atendimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_atendimentos');

        DB::transaction(function () use ($atendimento, $request) {


            $atendimento->delete();

            $usuario_logado = $request->user('instituicao');
            $atendimento->criarLogExclusao(
              $usuario_logado
            );

            return $atendimento;
        });

        return redirect()->route('instituicao.atendimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atendimento excluído com sucesso!'
        ]);
    }

    public function getAtendimentos(Request $request)
    {
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        if(empty($request->get('search')))
            return response()->json([]);

        $atendimentos = Atendimento::where('instituicao_id', '=', $instituicao->id)
                                    ->where('nome', 'like', "%{$request->get('search')}%")
                                    ->get();
        return response()->json($atendimentos);
    }
}
