<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\unidade\CriarUnidadeRequest;
use App\Instituicao;
use App\Unidade;
use Illuminate\Support\Facades\DB;

class Unidades extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_unidade');

        return view('instituicao.unidades.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_unidade');

        return view('instituicao.unidades.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarUnidadeRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_unidade');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $tipoParto = $instituicao->unidades()->create($dados);
            $tipoParto->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.unidades.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Unidade criado com sucesso!'
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
    public function edit(Request $request, Unidade $unidade)
    { 
        $this->authorize('habilidade_instituicao_sessao', 'editar_unidade');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$unidade->instituicao_id, 403);
        return view('instituicao.unidades.editar',\compact("unidade"));
    }
 
    public function update(CriarUnidadeRequest $request, Unidade $unidade)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_unidade');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$unidade->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $unidade){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $unidade->update($dados);
            $unidade->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.unidades.index', [$unidade])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Unidades alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Unidade $unidade)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_classes');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$unidade->instituicao_id, 403);
        DB::transaction(function () use ($unidade, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $unidade->delete();
            $unidade->criarLogExclusao($usuario_logado, $instituicao); 

            return $unidade;
        });
        
        return redirect()->route('instituicao.unidades.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Unidades excluído com sucesso!'
        ]);
    }
}
