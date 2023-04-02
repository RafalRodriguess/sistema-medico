<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use App\Pessoa;
use App\PessoaDocumento;
use Illuminate\Http\Request;
use App\Http\Requests\PessoaDocumento\{
    CreatePessoaDocumentoRequest,
    EditPessoaDocumentoRequest
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PessoasDocumentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_documentos_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);

        return view('instituicao.pessoas.documentos.lista', [
            'tipos_documentos' => PessoaDocumento::getTipos(),
            'pessoa' => $pessoa,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_documentos_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);

        return view('instituicao.pessoas.documentos.criar', [
            'tipos_documentos' => PessoaDocumento::getTipos(),
            'pessoa' => $pessoa,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePessoaDocumentoRequest $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_documentos_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $instituicao, $pessoa, $request) {

            $arquivo = $dados['arquivo'];

            $arquivo_path = "documentos/pessoas/pessoa"."_".$pessoa->id."/";

            $arquivo_new_nome = Carbon::now()->timestamp."_".$dados['arquivo']->getClientOriginalName();

            $arquivo->storeAs($arquivo_path, $arquivo_new_nome, 'public');

            $dados['file_path_name'] = $arquivo_path.$arquivo_new_nome;

            $documento = $pessoa->documentos()->create($dados);

            $documento->criarLogCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.pessoas.documentos.index', [$pessoa])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Documento criada com sucesso!'
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
    public function edit(Request $request, Pessoa $pessoa, PessoaDocumento $documento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_documentos_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id ||
            $pessoa->id === $documento->pessoa_id, 403);

        return view('instituicao.pessoas.documentos.editar', [
            'tipos_documentos' => PessoaDocumento::getTipos(),
            'documento' => $documento,
            'pessoa' => $pessoa,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditPessoaDocumentoRequest $request, Pessoa $pessoa, PessoaDocumento $documento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_documentos_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id ||
            $pessoa->id === $documento->pessoa_id, 403);

        $dados = $request->validated();

        $documento = DB::transaction(function() use ($dados, $instituicao, $request, $documento) {
            $documento->update($dados);
            $documento->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $documento;
        });

        return redirect()->route('instituicao.pessoas.documentos.edit', [$pessoa, $documento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Documento editado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pessoa $pessoa, PessoaDocumento $documento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_documentos_pessoas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id ||
            $pessoa->id === $documento->pessoa_id, 403);

        DB::transaction(function() use ($instituicao, $request, $documento) {

            Storage::disk('public')->delete($documento->file_path_name);

            $documento->delete();

            $documento->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.pessoas.documentos.index', [$pessoa])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Documento excluído com sucesso!'
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
