<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModeloImpressao\CriarModeloImpressaoRequest;
use App\Instituicao;
use App\ModeloImpressao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ModelosImpressao extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_impressao');
    
        return view('instituicao.configuracoes.modelo_impressao.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_impressao');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $usuario_logado = $request->user('instituicao');
        $prestadores = $instituicao->prestadores()->where('tipo', 2)->whereDoesnthave('modeloImpressao')->with(['prestador','especialidade']);

        if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_modelo_impressao')){
            $prestadores->where('instituicao_usuario_id', $usuario_logado->id);
        }

        $prestadores = $prestadores->get();

        return view('instituicao.configuracoes.modelo_impressao.criar', \compact('prestadores'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarModeloImpressaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_impressao');

        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $instituicao_prestador = $instituicao->prestadores()->where('id', $dados['instituicao_prestador_id'])->first();

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id, 403);

        if(($instituicao_prestador->modeloImpressao) > 0){
            return redirect()->route('instituicao.modeloImpressao.index')->with('mensagem', [
                'icon' => 'error',
                'title' => 'Error.',
                'text' => 'Modelo de impressão ja cadastrado!'
            ]);
        }

        DB::transaction(function() use ($request, $instituicao, $dados){
            $usuario_logado = $request->user('instituicao');
            
            $modelo = ModeloImpressao::create($dados);
            $modelo->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modeloImpressao.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de impressão criado com sucesso!'
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
    public function edit(Request $request, ModeloImpressao $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_impressao');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicaoPrestador->instituicoes_id, 403);

        return view('instituicao.configuracoes.modelo_impressao.editar',\compact("modelo"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarModeloImpressaoRequest $request, ModeloImpressao $modelo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_impressao');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($instituicao->id === $modelo->instituicaoPrestador->instituicoes_id, 403);

        DB::transaction(function() use ($request, $instituicao, $modelo){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            unset($dados['instituicao_prestador_id']);
            
            $modelo->update($dados);
            $modelo->criarLogEdicao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modeloImpressao.edit', [$modelo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de impressão alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {  

    }
}
