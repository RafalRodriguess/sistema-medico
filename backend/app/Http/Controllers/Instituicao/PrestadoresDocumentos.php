<?php

namespace App\Http\Controllers\Instituicao;

use App\DocumentoPrestador;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentosPrestadores\{
    CreateDocumentoPrestador,
    EditDocumentoPrestador
};
use App\Instituicao;
use Illuminate\Http\Request;
use App\Prestador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PrestadoresDocumentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Prestador $prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_documento_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $instituicao_prestador = $prestador->instituicaoPrestador($instituicao->id);

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id, 403);

        return view('instituicao.prestadores-documentos.lista', [
            'prestador' => $prestador,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Prestador $prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_documento_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $instituicao_prestador = $prestador->instituicaoPrestador($instituicao->id);

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id, 403);
        
        return view('instituicao.prestadores-documentos.criar', [
            'prestador' => $prestador,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDocumentoPrestador $request, Prestador $prestador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_documento_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $instituicao_prestador = $prestador->instituicaoPrestador($instituicao->id);

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id, 403);

        $dados = $request->validated();

        DB::transaction(function () use ($dados, $prestador, $request, 
            $instituicao, $instituicao_prestador){
            for ($i=0; $i < count($dados['documentos']); $i++) { 

                $documento = $dados['documentos'][$i];
                $arquivo = $documento['arquivo'];
                $arquivo_path = "documentos/prestadores/prestador"."_"."$prestador->id"."/";
                $current_time = Carbon::now()->timestamp;
                $arquivo_nome = $arquivo->getClientOriginalName();
                $arquivo_new_nome = "$current_time"."_"."$arquivo_nome";
                $upload = $arquivo->storeAs($arquivo_path, $arquivo_new_nome, 'public');

                if(!$upload){
                    return redirect()->back()->with('message', [
                        'icon' => 'error',
                        'title' => 'Falha.',
                        'text' => 'Falha ao fazer upload de arquivo'
                    ]);
                }
                
                $documento = $instituicao_prestador->documentos()->create([
                    'file_path_name' => "$arquivo_path"."$arquivo_new_nome",
                    'tipo' => $documento['tipo'],
                    'descricao' => $documento['descricao'],
                    'prestador_id' => $prestador->id,
                    'instituicao_id' => $instituicao->id,
                    'instituicao_prestador_id' => $instituicao_prestador->id
                ]);

                $documento->criarLogCadastro($request->user('instituicao'));
            }
        });
        $message = 'Documento criado com sucesso!';
        if(count($dados['documentos']) > 1 ){
            $message = 'Documentos criados com sucesso!';
        }
        return redirect()->route('instituicao.prestadores.documentos.index', [$prestador])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => $message,
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
    public function edit(Request $request, Prestador $prestador, DocumentoPrestador $documento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_documento_prestador');

        $this->authorize('habilidade_instituicao_sessao', 'editar_documento_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $instituicao_prestador = $prestador->instituicaoPrestador($instituicao->id);

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id &&
            $instituicao_prestador->id === $documento->instituicao_prestador_id, 403);
        
        return view('instituicao.prestadores-documentos.editar', [
            'prestador' => $prestador,
            'documento' => $documento,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditDocumentoPrestador $request, 
        Prestador $prestador, DocumentoPrestador $documento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_documento_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $instituicao_prestador = $prestador->instituicaoPrestador($instituicao->id);

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id &&
            $instituicao_prestador->id === $documento->instituicao_prestador_id, 403);

        $dados = $request->validated();

        $documento = DB::transaction(function() use ($dados, $instituicao, $request, $documento){

            $documento->update($dados);

            $documento->criarLogEdicao($request->user('instituicao'), $instituicao->id);

            return $documento;
        });

        return redirect()->route('instituicao.prestadores.documentos.edit', [$prestador, $documento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Documento editado com sucesso!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, 
        Prestador $prestador, DocumentoPrestador $documento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_documento_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $instituicao_prestador = $prestador->instituicaoPrestador($instituicao->id);

        abort_unless($instituicao->id === $instituicao_prestador->instituicoes_id &&
            $instituicao_prestador->id === $documento->instituicao_prestador_id, 403);

        DB::transaction(function() use ($instituicao, $request, $documento){

            Storage::disk('public')->delete($documento->file_path_name);

            $documento->delete();

            $documento->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.prestadores.documentos.index', [$prestador])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Documento excluído com sucesso!',
        ]);
    }

    public function download(Request $request, string $file_path_name)
    {
        if(Storage::disk('public')->exists($file_path_name)) {

            return Storage::disk('public')->download($file_path_name);
        } 
        
        return response()->status(404);
    }
}
