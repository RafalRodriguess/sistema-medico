<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use App\Pessoa;
use App\PessoaDocumento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Fornecedor\{
    CreateFornecedorRequest,
    EditFornecedorRequest
};
use Illuminate\Support\Facades\Storage;

class Fornecedores extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_fornecedores');

        return view('instituicao.fornecedores.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_fornecedores');

        return view('instituicao.fornecedores.criar', [
            'personalidades' => Pessoa::getPersonalidades(),
            'tipos_documentos' => PessoaDocumento::getTipos(),
            'referencia_relacoes' => Pessoa::getRelacoesParentescos()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFornecedorRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_fornecedores');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        $fornecedor = null;

        if(!empty($dados['cpf'])) $fornecedor = Pessoa::where('instituicao_id', $instituicao->id)
            ->where('cpf', $dados['cpf'])->where('tipo', 3)->first();

        if(!empty($dados['cnpj'])) $fornecedor = Pessoa::where('instituicao_id', $instituicao->id)
            ->where('cnpj', $dados['cnpj'])->where('tipo', 3)->first();

        if($fornecedor) {
            return redirect()->back()->with('mensagem', [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Fornecedor já cadastrado!'
            ]);
        }

        DB::transaction(function() use ($instituicao, $request, $dados) {
            $dados['tipo'] = 3;
            $fornecedor = $instituicao->instituicaoPessoas()->create($dados);
            $fornecedor->criarLogCadastro($request->user('instituicao'), $instituicao->id);
            if(isset($dados['documentos'])) {
                $documentos = collect($dados['documentos'])
                    ->filter(function ($documento) {
                        return !is_null($documento);
                    })
                    ->map(function($documento) use ($fornecedor) {
                        $arquivo = $documento['arquivo'];
                        $arquivo_path = "documentos/fornecedores/fornecedor"."_".$fornecedor->id."/";
                        $arquivo_new_nome = Carbon::now()->timestamp."_".$arquivo->getClientOriginalName();
                        $arquivo->storeAs($arquivo_path, $arquivo_new_nome, 'public');
                        return [
                            'tipo' => $documento['tipo'],
                            'file_path_name' => $arquivo_path.$arquivo_new_nome,
                            'descricao' => $documento['descricao'],
                        ];
                    });
                foreach($documentos as $documento) {
                    $novo_documento = $fornecedor->documentos()->create($documento);
                    $novo_documento->criarLogCadastro($request->user('instituicao'), $instituicao->id);
                }
            }
        });

        return redirect()->route('instituicao.fornecedores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Fornecedor criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getFornecedor(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_fornecedores');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $fornecedor = null;
        $fornecedor = Pessoa::where('instituicao_id', $instituicao->id)
            ->where($request->documento, $request->valor)->where('tipo', 3)->first();
   
        if($fornecedor) return response()->json([
            'status' => 0,
            'mensagem' => strtoupper($request->documento)." já registrado como fornecedor",
            'documento' => $request->documento,
        ]);

        return response()->json([
            'status' => 1,
            'mensagem' => strtoupper($request->documento)." ainda não registrado como fornecedor.",
            'documento' => $request->documento,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Pessoa $fornecedor)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_fornecedores');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $fornecedor->instituicao_id, 403);

        return view('instituicao.fornecedores.editar', [
            'personalidades' => Pessoa::getPersonalidades(),
            'referencia_relacoes' => Pessoa::getRelacoesParentescos(),
            'fornecedor' => $fornecedor
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditFornecedorRequest $request, Pessoa $fornecedor)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_fornecedores');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $fornecedor->instituicao_id, 403);

        $dados = $request->validated();

        $fornecedor = DB::transaction(function() use ($instituicao, $request, $dados, $fornecedor) {
            $dados['tipo'] = 3;
            $fornecedor->update($dados);
            $fornecedor->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $fornecedor;
        });

        return redirect()->route('instituicao.fornecedores.edit', [$fornecedor])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Fornecedor editado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pessoa $fornecedor)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_fornecedores');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $fornecedor->instituicao_id, 403);

        $usuario_logado = $request->user('instituicao');

        DB::transaction(function() use ($instituicao, $request, $fornecedor, $usuario_logado) {
            $fornecedor->delete();
            $documentos = $fornecedor->documentos()->get();
            foreach($documentos as $documento){
                Storage::disk('public')->delete($documento->file_path_name);
                $documento->delete();
                $documento->criarLogExclusao($usuario_logado, $instituicao->id);
            }
            $fornecedor->criarLogExclusao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.fornecedores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Fornecedor excluído com sucesso!'
        ]);
    }

}
