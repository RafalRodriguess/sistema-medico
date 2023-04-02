<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModeloConclusao\CriarModeloConclusaoRequest;
use App\Instituicao;
use App\ModeloConclusao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ModelosConclusoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_conclusao');
    
        return view('instituicao.configuracoes.modelo_conclusao.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_conclusao');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $usuario_logado = $request->user('instituicao');
        $prestadores = $instituicao->prestadores()->where('tipo', 2)->with(['prestador','especialidade']);

        if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_modelo_conclusao')){
            $prestadores->where('instituicao_usuario_id', $usuario_logado->id);
        }

        $prestadores = $prestadores->get();

        $motivos = $instituicao->motivosConclusao()->get();

        return view('instituicao.configuracoes.modelo_conclusao.criar', \compact('prestadores', 'motivos'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarModeloConclusaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_conclusao');

        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $instituicao_prestador = $instituicao->prestadores()->where('id', $dados['instituicao_prestador_id'])->first();

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id, 403);

        DB::transaction(function() use ($request, $instituicao, $dados){
            $usuario_logado = $request->user('instituicao');
            
            $modelo = ModeloConclusao::create($dados);
            $modelo->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modeloConclusao.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de conclusão criado com sucesso!'
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
    public function edit(Request $request, ModeloConclusao $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_conclusao');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicaoPrestador->instituicoes_id, 403);
        $usuario_logado = $request->user('instituicao');
        $prestadores = $instituicao->prestadores()->where('tipo', 2)->with(['prestador','especialidade']);

        if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_modelo_conclusao')){
            $prestadores->where('instituicao_usuario_id', $usuario_logado->id);
        }

        $prestadores = $prestadores->get();

        $motivos = $instituicao->motivosConclusao()->get();

        return view('instituicao.configuracoes.modelo_conclusao.editar',\compact("modelo", "prestadores", "motivos"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarModeloConclusaoRequest $request, ModeloConclusao $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_conclusao');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicaoPrestador->instituicoes_id, 403);

        DB::transaction(function() use ($request, $instituicao, $modelo){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
                        
            $modelo->update($dados);
            $modelo->criarLogEdicao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modeloConclusao.edit', [$modelo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de conclusão alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ModeloConclusao $modelo)
    {  
        $this->authorize('habilidade_instituicao_sessao', 'excluir_modelo_conclusao');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $modelo->instituicaoPrestador->instituicoes_id, 403);

        DB::transaction(function() use ($request, $instituicao, $modelo){
            $usuario_logado = $request->user('instituicao');
            
            $modelo->delete();
            $modelo->criarLogExclusao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.modeloConclusao.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de conclusão excluido com sucesso!'
        ]);
    }
}
