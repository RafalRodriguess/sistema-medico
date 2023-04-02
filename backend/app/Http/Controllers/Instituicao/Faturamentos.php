<?php

namespace App\Http\Controllers\Instituicao;

use App\Faturamento;
use App\FaturamentoProcedimento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Faturamento\CriarFaturamentoRequest;
use App\Http\Requests\Faturamento\CriarProcedimentoFaturamentoRequest;
use App\Http\Requests\Faturamento\ImportarFaturamentoRequest;
use App\Instituicao;
use App\Procedimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Faturamentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_faturamentos');
        return view('instituicao.faturamentos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_faturamentos');
        $tipo = Faturamento::tipoValor();
        $tipo_tiss = Faturamento::tipoTISSValor();
        return view('instituicao.faturamentos.criar', \compact('tipo', 'tipo_tiss'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarFaturamentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_faturamentos');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function () use ($request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $faturamentos = $instituicao->faturamentos()->create($dados);
            $faturamentos->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.faturamento.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Faturamento criado com sucesso!'
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
    public function edit(Request $request, Faturamento $faturamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_faturamentos');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $faturamento->instituicao_id, 403);

        $tipo = Faturamento::tipoValor();
        $tipo_tiss = Faturamento::tipoTISSValor();

        return view('instituicao.faturamentos.editar', \compact('faturamento', 'tipo', 'tipo_tiss'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarFaturamentoRequest $request, Faturamento $faturamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_faturamentos');
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $faturamento->instituicao_id, 403);
        DB::transaction(function () use ($request, $instituicao, $faturamento) {
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $faturamento->update($dados);
            $faturamento->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.faturamento.edit', [$faturamento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Faturamento alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Faturamento $faturamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_faturamentos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $faturamento->instituicao_id, 403);
        DB::transaction(function () use ($faturamento, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $faturamento->delete();
            $faturamento->criarLogExclusao($usuario_logado, $instituicao);

            return $faturamento;
        });

        return redirect()->route('instituicao.faturamento.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Faturamento excluída com sucesso!'
        ]);
    }

    public function procedimentos(Request $request, Faturamento $faturamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_faturamentos_procedimentos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $faturamento->instituicao_id, 403);

        $procedimentos = $faturamento->procedimentos()->orderBy('data_vigencia', 'DESC')->paginate(25);

        return view('instituicao.faturamentos.procedimentos', compact('procedimentos', 'faturamento'));
    }

    public function salvarProcedimento(CriarProcedimentoFaturamentoRequest $request, Faturamento $faturamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_faturamentos_procedimentos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $faturamento->instituicao_id, 403);

        $dados = collect($request->validated()['proc'])
            ->filter(function ($proc) {
                return !is_null($proc['procedimento_id']);
            })
            ->map(function ($proc) use ($request) {
                $ativo = (array_key_exists('ativo', $proc)) ? true : false;
                $data = explode('/', $proc['data_vigencia']);
                $data = $data[2] . '-' . $data[1] . '-' . $data[0];
                return [
                    'procedimento_id' => $proc['procedimento_id'],
                    'vl_honorario' => $proc['vl_honorario'],
                    'vl_operacao' => $proc['vl_operacao'],
                    'vl_total' => $proc['vl_total'],
                    'descricao' => $proc['descricao'],
                    'data_vigencia' => $data,
                    'ativo' => $ativo,
                ];
            });

        DB::transaction(function () use ($request, $instituicao, $faturamento, $dados) {
            $usuario_logado = $request->user('instituicao');
            $arrayDados = [];
            foreach ($dados as $key => $value) {
                $faturamento->procedimentos()->create($value);
                $arrayDados[] = $value;
            }

            $faturamento->criarLog($usuario_logado, 'Adicionando procedimentos ao faturamento', $arrayDados, $instituicao);
        });

        return redirect()->route('instituicao.faturamento.procedimentos', [$faturamento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento inserido com sucesso!'
        ]);
    }

    public function statusProcedimento(Request $request, Faturamento $faturamento, FaturamentoProcedimento $proc)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_faturamentos_procedimentos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao === $faturamento->instituicao_id, 403);
        abort_unless($proc->faturamento_id === $faturamento->id, 403);

        DB::transaction(function () use ($instituicao, $request, $proc) {
            $ativo = false;
            if ($proc->ativo == false) {
                $ativo = true;
            }

            $usuario_logado = $request->user('instituicao');

            $proc->update(['ativo' => $ativo]);
            $proc->criarLogEdicao($usuario_logado, $instituicao);
        });

        return response()->json(true);
    }

    public function selecionarImportacao(Request $request, Faturamento $faturamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'importar_faturamentos_convenios');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($faturamento->instituicao_id == $instituicao->id, 404);

        return view('instituicao.faturamentos.importar', \compact('faturamento'));
    }

    function inserirProcedimentosImportados(array $dados, array $ids, Faturamento $faturamento)
    {
        // Busca procedimentos cujos IDS estão tentando ser inseridos
        $procedimentos_atuais = DB::table('faturamento_has_procedimentos')
            ->select('procedimento_id', 'data_vigencia')
            ->where('faturamento_id', $faturamento->id)
            ->whereIn('procedimento_id', $ids)
            ->get();

        // Para cada procedimento cujo id foi citado no array $dados verifica qual está mais atualizado
        foreach ($procedimentos_atuais as $procedimento) {
            foreach ($ids as $index => $id) {
                if ($procedimento->procedimento_id == $id && (new \DateTime($dados[$index]['data_vigencia'])) < (new \DateTime($procedimento->data_vigencia))) {
                    unset($dados[$index]);
                }
            }
        }

        // Insere tudo no db e atualiza todos os da lista de atualizar
        collect($dados)->chunk(500)->map(function ($batch) {
            DB::table('faturamento_has_procedimentos')->insert($batch->toArray());
        });
    }

    public function importar(ImportarFaturamentoRequest $request, Faturamento $faturamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'importar_faturamentos_convenios');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($faturamento->instituicao_id == $instituicao->id, 404);

        // Verificando se o arquivo é do tipo csv
        $file = $request->file('arquivo');
        if ($file->getClientMimeType() != 'text/csv') {
            return redirect()->back()->withErrors([
                'arquivo' => 'Formato de arquivo não suportado, somente arquivos .csv são suportados!'
            ]);
        }

        // Criar arquivo temporário para ser analizado
        $stream = null;
        $file_name = 'temp_upload_' . time();
        $file_upload_path = storage_path('app/temp');
        try {
            $file_name = 'temp_upload_' . time();
            $file = $file->move($file_upload_path, $file_name);
            $stream = fopen("$file_upload_path/$file_name", 'r');
        } catch (\Exception $e) {
            if (file_exists("$file_upload_path/$file_name")) {
                unlink("$file_upload_path/$file_name");
            }

            return redirect()->back()->withErrors([
                'arquivo' => "Erro, não foi possível ler o arquivo"
            ]);
        }

        function isMultiByte($line, $line_size, $offset) : bool {
            return $offset + 1 < $line_size && mb_strlen($line[$offset] . $line[$offset + 1], 'UTF-8') != strlen($line[$offset] . $line[$offset + 1]);
        }

        // Iniciar análise
        set_time_limit(180);
        $results = [];
        $ids_procedimento = [];
        $error = true;
        try {
            // pular titulos
            fgets($stream);
            // Ler dados do arquivo
            $count_result = 0;
            for (; false !== ($line = fgets($stream)); $count_result ++) {
                // Formata os dados lidos para inserção
                $line = mb_convert_encoding($line, 'UTF-8', 'ISO-8859-1');
                $parsed = [];
                $buffer = '';
                $line_size = strlen($line) - 1;
                $delimiter = false;
                for ($i = 0; $i <= $line_size; $i++) {
                    $char = $line[$i] ?? null;

                    // Considerando áspas para uso de vírgula em valores decimais
                    if($char == '"' || $char == "'") {
                        $delimiter = !$delimiter;
                        continue;
                    }

                    if(!$delimiter && $char == ',' || $i == $line_size) {
                        array_push($parsed, $buffer);
                        $buffer = '';
                        continue;
                    }

                    $buffer .= $char;
                }

                $date = explode('/', $parsed[0]);
                // Armazenando os ids para substituir caso necessario
                $ids_procedimento["$count_result"] = $parsed[1];
                // Formatando e inserindo no buffer para inserção no banco
                $results["$count_result"] = [
                    'faturamento_id' => $faturamento->id,
                    'data_vigencia' => "{$date[2]}-{$date[1]}-{$date[0]}",
                    'procedimento_id' => $parsed[1],
                    'descricao' => $parsed[2],
                    'vl_honorario' => floatvalue($parsed[3]),
                    'vl_operacao' => floatvalue($parsed[4]),
                    'vl_total' => floatvalue($parsed[5]),
                    'ativo' => 1,
                ];
            }
            // Inserir dados restantes caso existam
            if ($count_result > 0) {
                $this->inserirProcedimentosImportados($results, $ids_procedimento, $faturamento);
                $error = false;
            }
        } finally {
            if (file_exists("$file_upload_path/$file_name")) {
                unlink("$file_upload_path/$file_name");
            }
            fclose($stream);
        }

        return redirect()->route('instituicao.faturamento.procedimentos', $faturamento)
            ->with('message', (!$error) ? [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Faturamentos importados com sucesso!'
            ] : [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Não foi possível gerar os faturamentos a partir do arquivo enviado, certifique-se que o arquivo enviado é válido'
            ]);
    }
}
