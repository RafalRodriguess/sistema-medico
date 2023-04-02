<?php

namespace App\Http\Controllers\Instituicao;

use App\EstoqueEntradaProdutos;
use App\EstoqueEntradas;
use App\Http\Controllers\Controller;
use App\Http\Requests\Relatorios\ResultadosRelatorioEstoqueRequest;
use App\Instituicao;
use App\SaidaEstoque;
use Closure;
use Hamcrest\Type\IsString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RelatoriosEstoque extends Controller
{
    private const ERROR_UNAVAILABLE_DIRECTORY = 1001;
    private const ERROR_UNABLE_TO_PROCESS = 1002;

    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $estoques = $instituicao->estoques()->get();
        $centros_custos = $instituicao->centrosCustos()->get();
        $tipos_saida = [
            0 => 'Todos os tipos',
            1 => 'Saídas para pacientes',
            2 => 'Saídas de agendamento',
        ];

        return view('instituicao.relatorios_estoque.lista', \compact(
            'estoques',
            'centros_custos',
            'tipos_saida'
        ));
    }


    /**
     * Rota que exibe as estatísticas das entradas de estoque filtradas com os parâmetros passados na request
     * @param ResultadosRelatorioEstoqueRequest $request
     * @param bool $return_raw Caso true, ao invés de retornar uma resposta, retorna a coleção de dados buscados
     */
    public function relatorioEntradas(ResultadosRelatorioEstoqueRequest $request, bool $return_raw = false)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque');
        $dados = collect($request->validated());
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $entradas = EstoqueEntradas::with([
            'estoque',
            'estoqueEntradaProdutos',
            'estoqueEntradaProdutos.produto',
            'estoqueEntradaProdutos.produto.unidade',
            'fornecedor'
        ])
            ->whereRaw('CONCAT(data_emissao, " ", data_hora_entrada) >= ? AND CONCAT(data_emissao, " ", data_hora_entrada) <= ?', [
                $dados->get('start') . ' 00:00:00',
                $dados->get('end') . ' 23:59:59'
            ])
            ->where('estoque_entradas.instituicao_id', $instituicao->id)
            ->orderBy('estoque_entradas.id', 'desc');

        // Filtrando por estoques
        if (!empty($dados->get('estoques'))) {
            $entradas->whereIn('estoque_entradas.id_estoque', $dados->get('estoques'));
        }

        // Filtrando por produtos
        if (!empty($dados->get('produtos'))) {
            $entradas->whereHas('estoqueEntradaProdutos', function (Builder $query) use ($dados) {
                $query->whereIn('estoque_entradas_produtos.id_produto', $dados->get('produtos'));
            });
        }

        // Filtrando por centro de custo
        if (!empty($dados->get('centros_custos'))) {
            $entradas->whereHas('estoque', function (Builder $query) use ($dados) {
                $query->whereIn('centro_custo_id', $dados->get('centros_custos'));
            });
        }

        if (!$return_raw) {
            return view('instituicao.relatorios_estoque.resultados-entrada', [
                'resultados' => $entradas->get(),
                'data_inicio' => $dados->get('start'),
                'data_fim' => $dados->get('end'),
            ]);
        } else {
            return $entradas->get();
        }
    }

    /**
     * Rota que gera um arquivo CSV com as entradas de estoque de acordo com
     * os filtros passados na request
     * @param ResultadosRelatorioEstoqueRequest $request
     */
    public function gerarArquivoEntrada(ResultadosRelatorioEstoqueRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque');
        $entradas = $this->relatorioEntradas($request, true)->chunk(100);
        $result = self::gerarArquivoCsv(
            $entradas,
            [
                'ID ENTRADA',
                'PRODUTO',
                'LOTE',
                'UNIDADE',
                'QUANTIDADE',
                'VALOR CUSTO',
                'VALOR VENDA',
                '',
                'ID ENTRADA',
                'ESTOQUE',
                'DATA',
                'FORNECEDOR',
            ],
            'entrada_estoque',
            function ($entrada) {
                $resultado = [[], []];

                // Processando coluna de produtos
                foreach ($entrada->estoqueEntradaProdutos as $entradaProduto) {
                    array_push($resultado[0], [
                        $entradaProduto->id_entrada,
                        $entradaProduto->produto->descricao,
                        $entradaProduto->lote,
                        $entradaProduto->produto->unidade->descricao,
                        self::toBrFloat($entradaProduto->quantidade),
                        self::toBrFloat($entradaProduto->valor_custo),
                        self::toBrFloat($entradaProduto->valor)
                    ]);
                }

                // Processando coluna de entradas
                array_push($resultado[1], [
                    $entrada->id,
                    $entrada->estoque->descricao,
                    $entrada->data_emissao . ' ' . $entrada->data_hora_baixa,
                    $entrada->fornecedor->nome_fantasia
                ]);
                return $resultado;
            }
        );

        if (is_string($result) !== true) {
            return redirect()->back()->with('message', [
                'icon' => 'error',
                'title' => 'Falha ao gerar o arquivo!',
                'text' => 'houve uma falha ao gerar o arquivo, tente novamente mais tarde.'
            ]);
        } elseif (Storage::disk('local')->exists($result)) {
            return response()->download(storage_path('app' . DIRECTORY_SEPARATOR . $result))->deleteFileAfterSend(true);;
        }
    }

    /**
     * Rota que exibe as estatísticas das saídas de estoque filtradas com os parâmetros passados na request
     * @param ResultadosRelatorioEstoqueRequest $request
     * @param bool $return_raw Caso true, ao invés de retornar uma resposta, retorna a coleção de dados buscados
     */
    public function relatorioSaidas(ResultadosRelatorioEstoqueRequest $request, bool $return_raw = false)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque');
        $dados = collect($request->validated());
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $tipo_saida = $dados->get('destino-saida-estoque', 0);

        $saidas_estoque = SaidaEstoque::with([
            'estoque',
            'produtosSaida',
            'produtosSaida.baixaProduto',
            'produtosSaida.entradaProduto',
            'produtosSaida.entradaProduto.produto',
            'produtosSaida.entradaProduto.produto.unidade',
            'produtosSaida.entradaProduto.fornecedor',
            'baixaEstoque',
            'agendamento',
            'agendamento.instituicoesPrestadores',
            'agendamento.instituicoesPrestadores.prestador',
            'paciente'
        ])
            ->whereHas('baixaEstoque', function (Builder $query) use ($dados) {
                $query->whereRaw('CONCAT(estoque_baixa.data_emissao, " ", estoque_baixa.data_hora_baixa) >= ? AND CONCAT(estoque_baixa.data_emissao, " ", estoque_baixa.data_hora_baixa) <= ?', [
                    $dados->get('start') . ' 00:00:00',
                    $dados->get('end') . ' 23:59:59'
                ]);
            })
            ->where('saida_estoque.instituicoes_id', $instituicao->id)
            ->orderBy('saida_estoque.id', 'desc');

        if ($tipo_saida != 0) {
            $saidas_estoque->where('tipo_destino', $tipo_saida);
        }

        if (!empty($dados->get('estoques'))) {
            $saidas_estoque->whereIn('saida_estoque.estoques_id', $dados->get('estoques'));
        }
        if (!empty($dados->get('produtos'))) {
            $saidas_estoque->whereHas('produtosSaida.entradaProduto', function (Builder $query) use ($dados) {
                $query->whereIn('estoque_entradas_produtos.produto_id', $dados->get('produtos'));
            });
        }
        if (!empty($dados->get('centros_custos'))) {
            $saidas_estoque->whereHas('estoque', function (Builder $query) use ($dados) {
                $query->whereIn('centro_custo_id', $dados->get('centros_custos'));
            });
        }

        if (!$return_raw) {
            return view('instituicao.relatorios_estoque.resultados-saida', [
                'resultados' => $saidas_estoque->get(),
                'data_inicio' => $dados->get('start'),
                'data_fim' => $dados->get('end'),
            ]);
        } else {
            return $saidas_estoque->get();
        }
    }

    /**
     * Rota que gera um arquivo CSV com as saídas de estoque de acordo com
     * os filtros passados na request
     * @param ResultadosRelatorioEstoqueRequest $request
     */
    public function gerarArquivoSaida(ResultadosRelatorioEstoqueRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque');
        $saidas = $this->relatorioSaidas($request, true)->chunk(100);
        $result = self::gerarArquivoCsv(
            $saidas,
            [
                'ID SAIDA',
                'PRODUTO',
                'LOTE',
                'UNIDADE',
                'QUANTIDADE',
                'VALOR CUSTO',
                'VALOR VENDA',
                '',
                'ID SAIDA',
                'ESTOQUE',
                'DATA',
                'TIPO',
                'PACIENTE',
                'AGENDAMENTO',
                'PRESTADOR',
            ],
            'saida_estoque',
            function ($saida) {
                $resultado = [[], []];
                $data_agendamento = $saida->tipo_destino == 2 ? (new \DateTime($saida->agendamento->data))->format('d/m/Y - H:i:s') : '';

                // Processando coluna de produtos
                foreach ($saida->produtosSaida as $produtoSaida) {
                    array_push($resultado[0], [
                        $produtoSaida->saida_estoque_id,
                        $produtoSaida->entradaProduto->produto->descricao,
                        $produtoSaida->entradaProduto->lote,
                        $produtoSaida->entradaProduto->produto->unidade->descricao,
                        self::toBrFloat($produtoSaida->baixaProduto->quantidade),
                        self::toBrFloat($produtoSaida->entradaProduto->valor_custo),
                        self::toBrFloat($produtoSaida->entradaProduto->valor)
                    ]);
                }

                // Processando coluna de saidas
                array_push($resultado[1], [
                    $saida->id,
                    $saida->estoque->descricao,
                    $saida->baixaEstoque->data_emissao . ' ' . $saida->baixaEstoque->data_hora_baixa,
                    SaidaEstoque::destino_saida[$saida->tipo_destino],
                    $saida->tipo_destino == 1 ? $saida->paciente->nome : $saida->agendamento->pessoa->nome,
                    $saida->tipo_destino == 2 ? "#{$saida->agendamento_id} {$data_agendamento}" : '',
                    $saida->tipo_destino == 2 ? $saida->agendamento->instituicoesPrestadores->prestador->nome : ''
                ]);
                return $resultado;
            }
        );

        if (is_string($result) !== true) {
            return redirect()->back()->with('message', [
                'icon' => 'error',
                'title' => 'Falha ao gerar o arquivo!',
                'text' => 'houve uma falha ao gerar o arquivo, tente novamente mais tarde.'
            ]);
        } elseif (Storage::disk('local')->exists($result)) {
            return response()->download(storage_path('app' . DIRECTORY_SEPARATOR . $result))->deleteFileAfterSend(true);;
        }
    }

    public function relatorioPosicao(ResultadosRelatorioEstoqueRequest $request, bool $return_raw = false)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque');
        $dados = collect($request->validated());
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $produtos = $instituicao->produtos()
            ->with([
                'unidade',
                'estoqueEntradas',
                'estoqueEntradas.fornecedor',
                'estoqueEntradas.entradaTrashed.estoqueTrashed'
            ])->whereHas('estoqueEntradas', function (Builder $query) use ($instituicao) {
                $query->where('instituicao_id', $instituicao->id);
            })
            ->whereNull('produtos.deleted_at');

        if (!empty($dados->get('estoques'))) {
            $produtos->whereHas('estoqueEntradas.entradaTrashed.estoqueTrashed', function (Builder $query) use ($dados) {
                $query->whereIn('estoques.id', $dados->get('estoques'));
            });
        }
        if (!empty($dados->get('produtos'))) {
            $produtos->whereIn('produtos.id', $dados->get('produtos'));
        }
        if (!empty($dados->get('centros_custos'))) {
            $produtos->whereHas('estoqueEntradas.entradaTrashed.estoqueTrashed', function (Builder $query) use ($dados) {
                $query->whereIn('estoques.centro_custo_id', $dados->get('centros_custos'));
            });
        }

        if (!$return_raw) {
            return view('instituicao.relatorios_estoque.resultados-posicao', [
                'resultados' => $produtos->get(),
                'data_inicio' => $dados->get('start'),
                'data_fim' => $dados->get('end'),
            ]);
        } else {
            return $produtos->get();
        }
    }

    public function gerarArquivoPosicao(ResultadosRelatorioEstoqueRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_estoque');
        $produtos = $this->relatorioPosicao($request, true)->chunk(100);
        $result = self::gerarArquivoCsv(
            $produtos,
            [
                'ID PRODUTO',
                'ID ENTRADA',
                'LOTE',
                'ESTOQUE',
                'QUANTIDADE',
                'VALOR CUSTO',
                'VALOR VENDA',
                '',
                'ID PRODUTO',
                'DESCRICAO',
                'UNIDADE'
            ],
            'posicao_estoque',
            function ($produto) {
                $resultado = [[], []];

                // Processando coluna de produtos
                foreach ($produto->estoqueEntradas as $entradaProduto) {
                    array_push($resultado[0], [
                        $entradaProduto->id_produto,
                        $entradaProduto->id_entrada,
                        $entradaProduto->lote,
                        $entradaProduto->entradaTrashed->estoqueTrashed->descricao,
                        self::toBrFloat($entradaProduto->quantidade_estoque),
                        self::toBrFloat($entradaProduto->valor_custo),
                        self::toBrFloat($entradaProduto->valor)
                    ]);
                }

                // Processando coluna de saidas
                array_push($resultado[1], [
                    $produto->id,
                    $produto->descricao,
                    $produto->unidade->descricao
                ]);
                return $resultado;
            }
        );

        if (is_string($result) !== true) {
            return redirect()->back()->with('message', [
                'icon' => 'error',
                'title' => 'Falha ao gerar o arquivo!',
                'text' => 'houve uma falha ao gerar o arquivo, tente novamente mais tarde.'
            ]);
        } elseif (Storage::disk('local')->exists($result)) {
            return response()->download(storage_path('app' . DIRECTORY_SEPARATOR . $result))->deleteFileAfterSend(true);;
        }
    }

    /**
     * Subrotina para gerar o arquivo CSV no arquivo passado e com os dados passados, 
     * deve-se passar uma função para processar esses dados na variavel $processar
     * ela recebe o dado da iteração e retorna um array com os dados das linhas e colunas
     * @param Collection $dados A coleção de dados, ela deve vir chunked
     * @param array $titulos Representa a primeira linha reservada aos titulos das colunas
     * @param string $nome Parte do nome do arquivo para ser inserido
     * @param Closure $processar Função que processa cada dado ao iteragir pelos itens da coleção $dados 
     * (já considerando o chunked portanto serão itens da coleção original)
     * @return int|string O código do erro ocorrido ou o nome do arquivo quando tudo rodou corretamente
     */
    public static function gerarArquivoCsv($dados, $titulos, $nome, $processar)
    {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $file_path_name = 'temp/relatorios_estoque';
        if (!Storage::disk('local')->makeDirectory($file_path_name)) {
            return self::ERROR_UNAVAILABLE_DIRECTORY;
        }
        $file_path_name .= DIRECTORY_SEPARATOR . time() . "_{$nome}_" . $instituicao->id . '_' . date('d-m-Y') . '.csv';
        $file = fopen(storage_path('app' . DIRECTORY_SEPARATOR . $file_path_name), 'wb');
        $error = null;
        try {
            foreach ($dados as $chunk_index => $chunk) {
                $processado = [];
                foreach ($chunk as $linha) {
                    // Processando cada entrada do chunk
                    $resultado = $processar($linha);
                    // Fazendo merge nas colunas já processadas e as que foram processadas
                    foreach ($resultado as $key => $coluna) {
                        if (!empty($processado[$key])) {
                            $processado[$key] = array_merge($processado[$key], $coluna);
                        } else {
                            $processado[$key] = $coluna;
                        }
                    }
                }

                $column_sizes = [];
                $max_line = 0;
                // Caso primeira linha insere os títulos no buffer
                $buffer = $chunk_index == 0 && !empty($titulos) ? (implode("\t", $titulos) . PHP_EOL) : '';
                $count_columns = 0;
                // Define os tamanhos das colunas para iteragir sobre elas
                collect($processado)->map(function ($item, $index) use (&$max_line, &$column_sizes, &$count_columns) {
                    $count = count($item);
                    $column_sizes[$index] = $count;
                    $max_line = max($max_line, $count);
                    ++$count_columns;
                });

                for ($line = 0; $line < $max_line; $line++) {
                    for ($column = 0; $column < $count_columns; $column++) {
                        if (($column_sizes[$column] ?? 0) > $line) {
                            $buffer .= implode("\t", $processado[$column][$line]);
                        }
                        if (($column_sizes[$column + 1] ?? 0) > $line) {
                            $buffer .= "\t\t";
                        }
                    }
                    fwrite($file, $buffer . PHP_EOL);
                    $buffer = '';
                }
            }
        } catch (\Exception $e) {
            $error = $e;
        } finally {
            fclose($file);
        }

        if (!empty($error)) {
            return self::ERROR_UNABLE_TO_PROCESS;
        } else {
            return $file_path_name;
        }
    }

    /**
     * Troca pontos por vírgulas nos valores de ponto flutuante
     * @param $float Valor float
     * @return string O valor convertido
     */
    public static function toBrFloat($float)
    {
        return str_replace('.', ',', $float . '');
    }
}
