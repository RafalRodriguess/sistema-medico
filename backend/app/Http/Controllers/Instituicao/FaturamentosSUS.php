<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaturamentoSUS\UploadFaturamentoRequest;
use App\Http\Requests\VincularProcedimentoSusRequest;
use App\Instituicao;
use App\Support\GerarImportacaoSUS;
use App\VinculoSUS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpZip\ZipFile;

/**
 * OBS: este módulo necessita o pacote composer nelexa/zip tal como
 * a extensão php chamada php7.2-zip ativa para que seja possível
 * extrair corretamente os arquivos, lembre de aumentar o tamanho do
 * frame permitido para cada request nas configurações do NGINX de acordo
 * com a necessidade dos arquivos
 */
class FaturamentosSUS extends Controller
{
    /**
     * Exibe a view de vínculos do sus
     */
    public function bindings(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atualizar_vinculos_sus');

        return view('instituicao.faturamento-sus/vinculos');
    }

    /**
     * Exibe a view de vínculos do sus
     */
    public function bind(VincularProcedimentoSusRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atualizar_vinculos_sus');

        DB::transaction(function () use ($request) {
            $usuario_logado = $request->user('instituicao');
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $dados = collect($request->validated());
            if ($dados->get('id_sus', false)) {
                $vinculo = $instituicao->vinculosProcedimentosSus()->create($dados->toArray());
                $vinculo->criarLogInstituicaoCadastro($usuario_logado, $instituicao->id);
            } else {
                $vinculo = $instituicao->vinculosProcedimentosSus()
                    ->where('id_procedimento', $dados->get('id_procedimento'))
                    ->first();
                $vinculo->criarLogInstituicaoExclusao($usuario_logado, $instituicao->id);
                $vinculo->delete();
            }
        });

        return response()->json(['result' => true]);
    }

    /**
     * Exibe a view de atualizar o faturamento sus
     */
    public function import(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atualizar_faturamento_sus');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));

        return view('instituicao.faturamento-sus/importar', \compact('instituicao'));
    }

    /**
     * Recebe o upload do arquivo para atualizar o faturamento e processa o faturamento
     */
    public function importing(UploadFaturamentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atualizar_faturamento_sus');

        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $arquivo = $request->file('arquivo');
        // Verificando se o arquivo é do tipo zip
        if ($arquivo->getClientMimeType() != 'application/zip') {
            return redirect()->back()->withErrors([
                'arquivo' => 'Formato de arquivo não suportado, somente arquivos .zip são suportados!'
            ]);
        }

        $nome_arquivo = time() . '-faturamento-sus';
        $destino_arquivo = "app/temp/sus/";
        $destino_extracao = "{$nome_arquivo}-extracted";
        // Fazendo upload
        Storage::putFileAs(
            'temp/sus',
            $arquivo,
            $nome_arquivo . '.zip'
        );

        // Excluindo os arquivos e pastas após a execução
        function clearFiles($destino_extracao, $nome_arquivo)
        {
            try {
                Storage::deleteDirectory('temp/sus/' . $destino_extracao);
                Storage::delete('temp/sus/' . $nome_arquivo . '.zip');
            } catch (\Exception $e) {
            }
        }

        $zip = new ZipFile();
        try {
            // Abrindo o zip, criando a pasta de extração e extraindo
            $zip->openFile(storage_path($destino_arquivo . $nome_arquivo . '.zip'));
            if (!\File::exists(storage_path($destino_arquivo . $destino_extracao))) {
                \File::makeDirectory(storage_path($destino_arquivo . $destino_extracao), 755, true, true);
            }
            $zip->extractTo(storage_path($destino_arquivo . $destino_extracao));

            // Analisar e inserir no banco de dados
            $faturamento = new GerarImportacaoSUS(storage_path($destino_arquivo . $destino_extracao), 'sus');
            $faturamento->start($instituicao);

            clearFiles($destino_extracao, $nome_arquivo);
        } catch (\Exception $e) {

            clearFiles($destino_extracao, $nome_arquivo);

            return redirect()->back()->with('message', [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Não foi possível gerar os faturamentos a partir do arquivo enviado!'
            ]);
        }

        return redirect()->route('instituicao.faturamento-sus.edit')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Faturamento atualizado com sucesso!'
        ]);
    }

    function getVinculos(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atualizar_vinculos_sus');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $busca = $request->get('search', '');

        $resultados = $instituicao->procedimentosSus()
            ->where(function($query) use ($busca) {
                $query->where('CO_PROCEDIMENTO', 'like', "%$busca%")
                    ->orWhere('NO_PROCEDIMENTO', 'like', "%$busca%");
            })
            ->simplePaginate(50);

        return response()->json([
            'data' => $resultados->items(),
            'next' => !empty($resultados->nextPageUrl()) ? $resultados->nextPageUrl() : false
        ]);
    }
}
