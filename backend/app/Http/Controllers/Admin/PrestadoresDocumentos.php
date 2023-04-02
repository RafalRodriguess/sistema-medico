<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DocumentoPrestador;
use App\Http\Requests\DocumentosPrestadores\{
    AdminCreateDocumentoPrestador,
    AdminEditDocumentoPrestador,
};
use App\Prestador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrestadoresDocumentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Prestador $prestador)
    {
        $this->authorize('habilidade_admin', 'visualizar_documento_prestador');
        return view('admin.prestadores-documentos.lista', [
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
        $this->authorize('habilidade_admin', 'cadastrar_documento_prestador');
        
        return view('admin.prestadores-documentos.criar', [
            'prestador' => $prestador,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCreateDocumentoPrestador $request, Prestador $prestador)
    {
        $this->authorize('habilidade_admin', 'cadastrar_documento_prestador');
        $dados = $request->validated();
        DB::transaction(function () use ($dados, $prestador, $request){
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
                $arquivoDados = [
                    'file_path_name' => "$arquivo_path"."$arquivo_new_nome",
                    'tipo' => $documento['tipo'],
                    'descricao' => $documento['descricao'],
                ];
                $documento = $prestador->documentos()->create($arquivoDados);
                $documento->criarLogCadastro($request->user('admin'));
            }
        });
        $message = 'Documento Criado com sucesso!';
        if(count($dados['documentos']) > 1 ){
            $message = 'Documentos Criados com sucesso!';
        }
        return redirect()->route('prestadores.documentos.index', [$prestador])->with('mensagem', [
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
        $this->authorize('habilidade_admin', 'editar_documento_prestador');
        
        return view('admin.prestadores-documentos.editar', [
            'prestador' => $prestador,
            'documento' => $documento,
            'tipos' => DocumentoPrestador::getTiposDocumentos(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminEditDocumentoPrestador $request, Prestador $prestador, DocumentoPrestador $documento)
    {
        $this->authorize('habilidade_admin', 'editar_documento_prestador');

        $dados = $request->validated();
        $documento = DB::transaction(function () use ($documento, $dados, $request) {
            $documento->fill($dados);
            $documento->update();
            $documento->criarLogEdicao($request->user('admin'));
            return $documento;
        });


        return redirect()->route('prestadores.documentos.edit', [$prestador, $documento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Documento atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Prestador $prestador, DocumentoPrestador $documento)
    {
        $this->authorize('habilidade_admin', 'excluir_documento_prestador');

        DB::transaction(function () use ($documento, $request) {
            $documento->delete();
            $documento->criarLogExclusao($request->user('admin'));
        });


        return redirect()->route('prestadores.documentos.index', [$prestador])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Documento exvluido com sucesso!'
        ]);
    }
}
