<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModeloArquivo\CriarModeloArquivoRequest;
use App\Http\Requests\ModeloArquivo\EditarModeloArquivoRequest;
use App\Instituicao;
use App\ModeloArquivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ModeloArquivos extends Controller
{
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_arquivo');

        return view('instituicao.configuracoes.modelo_arquivos.lista');
    }

    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_arquivo');

        return view('instituicao.configuracoes.modelo_arquivos.criar');
    }

    public function store(CriarModeloArquivoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modelo_arquivo');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        DB::transaction(function() use($request, $instituicao){
            $dados = $request->validated();

            $usuario_logado = $request->user('instituicao');
            // $data['paciente_pasta_id'] = $pasta->id;

            $random = Str::random(5);
            $arquivo_nome = str_replace(' ', '_',$dados['descricao']);
            $arquivo_venda = $arquivo_nome."_".$random.".".$request->arquivo_upload->extension();

            $path = Storage::disk('public')->putFileAs(
                'modelo_arquivos/', $request->file('arquivo_upload'), $arquivo_venda
            );

            $data['descricao'] = $dados['descricao'];
            $data['diretorio'] = $path;

            $arquivo = $instituicao->modeloArquivos()->create($data);
            $arquivo->criarLogCadastro($usuario_logado, $instituicao->id);

        });

        return redirect()->route('instituicao.modeloArquivo.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de arquivo criado com sucesso!'
        ]);
    }

    public function edit(Request $request, ModeloArquivo $arquivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_arquivo');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $arquivo->instituicao_id, 403);

        return view('instituicao.configuracoes.modelo_arquivos.editar', \compact('arquivo'));
    }
    
    public function update(EditarModeloArquivoRequest $request, ModeloArquivo $arquivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modelo_arquivo');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $arquivo->instituicao_id, 403);

        DB::transaction(function() use($request, $arquivo, $instituicao){
            $data['descricao'] = $request->validated()['descricao'];
            if ($request->hasFile('arquivo_upload')) {
                Storage::disk('public')->delete($arquivo->diretorio);

                $random = Str::random(5);
                $arquivo_nome = str_replace(' ', '_',$request->input('descricao'));
                $arquivo_venda = $arquivo_nome."_".$random.".".$request->arquivo_upload->extension();
    
                $path = Storage::disk('public')->putFileAs(
                    'modelo_arquivos/', $request->file('arquivo_upload'), $arquivo_venda
                );

                $data['diretorio'] = $path;
            }

            $arquivo->update($data);
            $usuario_logado = $request->user('instituicao');
            $arquivo->criarLogExclusao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modeloArquivo.edit', [$arquivo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de arquivo editado com sucesso!'
        ]);
    }

    public function baixarArquivo(Request $request, ModeloArquivo $arquivo)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        abort_unless($instituicao->id === $arquivo->instituicao_id, 403);

        if(Storage::disk('public')->exists($arquivo->diretorio)){
            return Storage::disk('public')->download($arquivo->diretorio);
        }else if(Storage::exists($arquivo->diretorio)){
            return Storage::download($arquivo->diretorio);
        }

    }
    
    public function destroy(Request $request, ModeloArquivo $arquivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_modelo_arquivo');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $arquivo->instituicao_id, 403);

        DB::transaction(function() use($request, $arquivo, $instituicao){
            
            if(Storage::disk('public')->exists($arquivo->diretorio)){
                Storage::disk('public')->delete($arquivo->diretorio);
            }else if(Storage::exists($arquivo->diretorio)){
                Storage::delete($arquivo->diretorio);
            }

            $arquivo->delete();
            $usuario_logado = $request->user('instituicao');
            $arquivo->criarLogExclusao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.modeloArquivo.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modelo de arquivo excluido com sucesso!'
        ]);
    }

    public function visualizarArquivo(Request $request, ModeloArquivo $arquivo)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $arquivo->instituicao_id, 403);
        
        return view('instituicao.configuracoes.modelo_arquivos.modal_visualizar', \compact('arquivo'));
    }
}
