<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoAnestesia\CriarTipoAnestesiaRequest;
use App\Instituicao;
use App\TipoAnestesia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TiposAnestesias extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_tipos_anestesia');

        return view('instituicao.tipos_anestesia.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipos_anestesia');

        return view('instituicao.tipos_anestesia.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarTipoAnestesiaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipos_anestesia');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $tipoAnestesia = $instituicao->TipoAnestesia()->create($dados);
            $tipoAnestesia->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.tiposAnestesia.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de anestesia criado com sucesso!'
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
    public function edit(TipoAnestesia $tipo_anestesia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipos_anestesia');

        return view('instituicao.tipos_anestesia.editar',\compact("tipo_anestesia"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarTipoAnestesiaRequest $request, TipoAnestesia $tipo_anestesia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipos_anestesia');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$tipo_anestesia->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $tipo_anestesia){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $tipo_anestesia->update($dados);
            $tipo_anestesia->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.tiposAnestesia.edit', [$tipo_anestesia])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de anestesia alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TipoAnestesia $tipo_anestesia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_tipos_anestesia');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$tipo_anestesia->instituicao_id, 403);
        DB::transaction(function () use ($tipo_anestesia, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $tipo_anestesia->delete();
            $tipo_anestesia->criarLogExclusao($usuario_logado, $instituicao);
            

            return $tipo_anestesia;
        });
        
        return redirect()->route('instituicao.tiposAnestesia.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de Anestesia excluído com sucesso!'
        ]);
    }
}
