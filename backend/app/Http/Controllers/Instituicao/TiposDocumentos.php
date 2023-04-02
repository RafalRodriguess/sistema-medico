<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\TiposDocumentos\CriarTiposDocumentosRequest;
use App\Instituicao;
use App\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TiposDocumentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_tipos_documentos');

        return view('instituicao.tipos_documentos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipos_documentos');

        return view('instituicao.tipos_documentos.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarTiposDocumentosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipos_documentos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $tipoDocumento = $instituicao->tiposDocumentos()->create($dados);
            $tipoDocumento->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.tiposDocumentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de documento criado com sucesso!'
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
    public function edit(TipoDocumento $tipoDocumento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipos_documentos');

        return view('instituicao.tipos_documentos.editar',\compact("tipoDocumento"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarTiposDocumentosRequest $request, TipoDocumento $tipoDocumento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipos_documentos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$tipoDocumento->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $tipoDocumento){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $tipoDocumento->update($dados);
            $tipoDocumento->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.tiposDocumentos.edit', [$tipoDocumento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de documento alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TipoDocumento $tipoDocumento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_tipos_documentos');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$tipoDocumento->instituicao_id, 403);
        DB::transaction(function () use ($tipoDocumento, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $tipoDocumento->delete();
            $tipoDocumento->criarLogExclusao($usuario_logado, $instituicao);
            

            return $tipoDocumento;
        });
        
        return redirect()->route('instituicao.tiposDocumentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de documento excluído com sucesso!'
        ]);
    }
}
