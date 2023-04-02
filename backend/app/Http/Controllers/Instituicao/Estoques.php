<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use Illuminate\Http\Request;

use App\Http\Requests\Estoque\CriarEstoqueRequest;
use App\Estoque;    
use Illuminate\Support\Facades\DB;

class Estoques extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoques'); 
        return view('instituicao.estoques.lista');
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoques'); 
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $centro_custos = $instituicao->centrosCustos()->get(); 
        return view('instituicao.estoques.criar', \compact('centro_custos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarEstoqueRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoques');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $tipoParto = $instituicao->estoques()->create($dados);
            $tipoParto->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.estoques.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'estoque criado com sucesso!'
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
    public function edit(Request $request, Estoque $estoque)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoques'); 

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $centro_custos = $instituicao->centrosCustos()->get(); 

        return view('instituicao.estoques.editar',\compact("estoque","centro_custos"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarEstoqueRequest $request, Estoque $estoque)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoques');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$estoque->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $estoque){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $estoque->update($dados);
            $estoque->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.estoques.index', [$estoque])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Estoque alterado com sucesso!'
        ]);
    }
 
    public function destroy(Request $request, Estoque $estoque)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_estoques');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$estoque->instituicao_id, 403);
        DB::transaction(function () use ($estoque, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $estoque->delete();
            $estoque->criarLogExclusao($usuario_logado, $instituicao); 

            return $estoque;
        });
        
        return redirect()->route('instituicao.estoques.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Estoques excluído com sucesso!'
        ]);
    }
}
