<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendamentoAtendimento;
use App\Agendamentos;
use App\CentroCusto;
use App\Conta;
use App\ContaReceber;
use App\EstoqueBaixa;
use App\EstoqueEntradaProdutos;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaidasEstoque\CriarSaidaEstoqueRequest;
use App\Instituicao;
use App\Pessoa;
use App\PlanoConta;
use App\ProdutoBaixa;
use App\SaidaEstoque;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class SaidasEstoque extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * 
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_saida_estoque');

        return view('instituicao.saidas-estoque/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * 
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_saida_estoque');
        $instituicao = Instituicao::find(request()->session()->get("instituicao"));
        $estoques = $instituicao->estoques()->get();
        $contas_caixa = $instituicao->contas()->get();
        $formas_pagamento = ContaReceber::formas_pagamento();
        $planos_conta = $instituicao->planosContas()->where('padrao', 0)->get();

        // Buscando campos antigos caso existam
        $plano_conta_selecionado = PlanoConta::find(old('plano_conta_id')) ?? new PlanoConta();
        $pessoa_selecionada = Pessoa::find(old('pessoa_id')) ?? new Pessoa();
        $agendamento_selecionado = AgendamentoAtendimento::buscarAgendamentoPorPacientes($instituicao)->where('agendamento_atendimentos.agendamento_id', old('agendamento_id'))->first() ?? new AgendamentoAtendimento();
        $conta_caixa_selecionada = Conta::find(old('conta_id')) ?? new Conta();

        $produtos = old('produtos', []);
        $contas_receber = [];
        if (!empty($produtos)) {
            $produtos_estoque = [];
            EstoqueEntradaProdutos::buscarProdutosEmEstoque('', null, collect($produtos)->pluck('id_entrada_produto')->toArray())
                ->get()
                ->map(function ($produto) use (&$produtos_estoque) {
                    $produtos_estoque[$produto->id] = $produto;
                });

            $produtos = collect($produtos)->filter(function ($item) use ($produtos_estoque) {
                return !empty($produtos_estoque[$item['id_entrada_produto'] ?? -1] ?? null);
            })->map(function ($produto) use ($produtos_estoque) {
                $produtos_estoque[$produto['id_entrada_produto']]->codigo_de_barras = $produto['codigo_de_barras'];
                $produtos_estoque[$produto['id_entrada_produto']]->quantidade_selecionada = $produto['quantidade'];
                return $produtos_estoque[$produto['id_entrada_produto']];
            });
        }
        if (!empty(old('pagamentos'))) {
            // Ajustando array de contas a receber para ser visualizado corretamente no front
            $contas_receber = array_map(function ($item) {
                return [
                    'valor_parcela' => $item['valor'],
                    'data_vencimento' => $item['data'],
                    'forma_pagamento' => $item['forma_pagamento']
                ];
            }, old('pagamentos'));
        }

        $paciente = $instituicao->pacientes()->where('pessoas.id', old('pessoa_id'))->first();
        $agendamento = Agendamentos::find(old('agendamento_id'));
        $centro_custo = CentroCusto::find(old('centros_custos_id'));

        return view('instituicao.saidas-estoque/criar', \compact(
            'instituicao',
            'estoques',
            'contas_caixa',
            'formas_pagamento',
            'planos_conta',
            'plano_conta_selecionado',
            'pessoa_selecionada',
            'agendamento_selecionado',
            'conta_caixa_selecionada',
            'produtos',
            'contas_receber',
            'paciente',
            'agendamento',
            'centro_custo'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function store(CriarSaidaEstoqueRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_saida_estoque');

        DB::transaction(function () use ($request) {
            $instituicao = Instituicao::findOrFail(request()->session()->get("instituicao"));
            $dados = collect($request->validated());
            $usuario_logado = $request->user('instituicao');
            // Criando baixa de estoque
            $baixa_estoque = EstoqueBaixa::create([
                'estoque_id' => $dados->get('estoques_id'),
                'motivo_baixa_id' => SaidaEstoque::buscarMotivoSaidaEstoque()->id,
                'data_emissao' => \date('Y-m-d'),
                'data_hora_baixa' => \date('H:i:s'),
                'usuario_id' => $usuario_logado->id,
                'instituicao_id' => $instituicao->id
            ]);


            // Definindo o que será armazenado em pessoa e agendamento
            $tipo_destino = $dados->get('tipo_destino');
            $pessoa_id = null;
            $agendamento_id = null;
            if ($tipo_destino == 1) {
                $pessoa_id = $instituicao->instituicaoPessoas()->findOrFail($dados->get('pessoa_id'))->id;
            } else if ($tipo_destino == 2) {
                $agendamento = Agendamentos::find($dados->get('agendamento_id'));
                $agendamento_id = $agendamento->id;
                $pessoa_id = $agendamento->pessoa_id;
            }

            // Criando saida de estoque
            $saida_estoque = SaidaEstoque::create(
                array_merge($request->validated(), [
                    'agendamento_id' => $agendamento_id,
                    'pessoa_id' => $pessoa_id,
                    'estoque_baixa_id' => $baixa_estoque->id,
                    'usuarios_id' => $usuario_logado->id,
                    'instituicoes_id' => $instituicao->id
                ])
            );

            // Validando e adicionando os produtos selecionados a baixa de estoque
            $valor_custo = 0;
            $produtos_baixa = collect($dados->get('produtos', []))->filter(function ($produto_baixa) use ($instituicao, &$valor_custo, $baixa_estoque) {
                // Filtrando por instituicao
                $entrada = EstoqueEntradaProdutos::buscarProdutosEmEstoque('', $baixa_estoque, $produto_baixa['id_entrada_produto'])->first();

                // Filtrando por quantidade válida
                if (!empty($entrada) && ($entrada->saldo_total ?? 0) >= ($produto_baixa['quantidade'] ?? 0)) {
                    $valor_custo += $entrada->valor;
                    return true;
                }
                return false;
            })->map(function ($produto_baixa) use ($baixa_estoque) {
                $produto = $baixa_estoque->estoqueBaixaProdutos()->create($produto_baixa);
                $produto->codigo_de_barras = $produto_baixa->codigo_de_barras ?? '';
                return $produto;
            });
            $baixa_estoque->overwrite($baixa_estoque->estoqueBaixaProdutos(), $produtos_baixa->toArray(), function ($new, $index, $method) use ($usuario_logado, $instituicao) {
                if ($method == 'create') {
                    $new->criarLogCadastro($usuario_logado, $instituicao->id);
                } else {
                    $new->criarLogEdicao($usuario_logado, $instituicao->id);
                }
            });

            // Gerando os produtos da saida de estoque
            $produtos_saida = $produtos_baixa->map(function ($produto_baixa) use ($saida_estoque) {
                return [
                    'codigo_de_barras' => $produto_baixa->codigo_de_barras,
                    'estoque_baixa_produtos_id' => $produto_baixa->id,
                    'valor' => $produto_baixa->entradaProduto()->first()->valor ?? 0
                ];
            });
            $saida_estoque->overwrite($saida_estoque->produtosSaida(), $produtos_saida->toArray(), function ($new, $index, $method) use ($usuario_logado, $instituicao) {
                if ($method == 'create') {
                    $new->criarLogCadastro($usuario_logado, $instituicao->id);
                } else {
                    $new->criarLogEdicao($usuario_logado, $instituicao->id);
                }
            });

            // Gerando pagamentos
            if ($dados->get('gerar_conta', false)) {
                $valor_total = 0;
                $pagamentos = collect($request->get('pagamentos', []))
                    ->filter(function ($pagamento) use (&$valor_total) {
                        $valor = floatvalue($pagamento['valor']);
                        if ($valor > 0) {
                            $valor_total += $valor;
                            return true;
                        }
                    })
                    ->map(function ($pagamento, $key) use ($dados, $pessoa_id, $agendamento_id, $instituicao, $valor_total) {
                        return [
                            'agendamento_id' => $agendamento_id,
                            'pessoa_id' => $pessoa_id,
                            'instituicao_id' => $instituicao->id,
                            'num_parcela' => $key,
                            'data_vencimento' => $pagamento['data'],
                            'conta_id' => $dados->get('conta_id'),
                            'plano_conta_id' => $dados->get('plano_conta_id'),
                            'forma_pagamento' => $pagamento['forma_pagamento'],
                            'valor_parcela' => floatvalue($pagamento['valor']),
                            'valor_total' => $valor_total,
                            'valor_pago' => (array_key_exists('recebido', $pagamento)) ? floatvalue($pagamento['valor']) : null,
                            'data_pago' => (array_key_exists('recebido', $pagamento)) ? $pagamento['data'] : null,
                            'status' => (array_key_exists('recebido', $pagamento)) ? 1 : 0,
                        ];
                    });

                $saida_estoque->overwrite($saida_estoque->contasReceber(), $pagamentos->toArray(), function ($new, $key, $method) use ($usuario_logado, $instituicao) {
                    if ($method == 'create') {
                        $new->criarLogCadastro($usuario_logado, $instituicao->id);
                    } else {
                        $new->criarLogEdicao($usuario_logado, $instituicao->id);
                    }
                });
            }

            $saida_estoque->criarLogInstituicaoCadastro($usuario_logado, $instituicao->id);
            $baixa_estoque->criarLogCadastro($usuario_logado);
        });

        return redirect()->route('instituicao.saidas-estoque.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Saída de estoque criada com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * 
     */
    public function edit(Request $request, SaidaEstoque $saida)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_saida_estoque');
        $instituicao = Instituicao::findOrFail(request()->session()->get("instituicao"));
        abort_unless($instituicao->id == $saida->instituicoes_id, 403);
        $estoques = $instituicao->estoques()->get();
        $contas_caixa = $instituicao->contas()->get();
        $formas_pagamento = ContaReceber::formas_pagamento();
        $planos_conta = $instituicao->planosContas()->where('padrao', 0)->get();
        $contas_receber = $saida->contasReceber()->get();
        $contas_receber_base = $contas_receber->first() ?? new ContaReceber();

        $plano_conta_selecionado = PlanoConta::find(old('plano_conta_id', $contas_receber_base->plano_conta_id ?? -1)) ?? new PlanoConta();
        $pessoa_selecionada = Pessoa::find(old('pessoa_id', $contas_receber_base->pessoa_id ?? -1)) ?? new Pessoa();
        $agendamento_selecionado = AgendamentoAtendimento::buscarAgendamentoPorPacientes($instituicao)->where('agendamento_atendimentos.agendamento_id', old('agendamento_id', $contas_receber_base->agendamento_id ?? -1))->first() ?? new AgendamentoAtendimento();
        $conta_caixa_selecionada = Conta::find(old('conta_id', $contas_receber_base->conta_id)) ?? new Conta();

        $produtos = old(
            'produtos',
            $saida->produtosSaida()
                ->with([
                    'entradaProduto',
                    'entradaProduto.produto',
                    'entradaProduto.produto.unidade',
                    'entradaProduto.produto.classe',
                    'entradaProduto.produto.unidade',
                    'entradaProduto.fornecedor',
                    'baixaProduto'
                ])
                ->get()
        );
        $contas_receber = [];
        if (!empty(old('produtos'))) {
            // A partir da request, buscando indexando as entradas relevantes e depois retornando uma coleção de entradas com codigo de barras e quantidade selecionada
            $produtos_estoque = [];
            EstoqueEntradaProdutos::buscarProdutosEmEstoque('', $saida->baixaEstoque()->first(), collect($produtos)->pluck('id_entrada_produto')->toArray())
                ->get()
                ->map(function ($produto) use (&$produtos_estoque) {
                    $produtos_estoque[$produto->id] = $produto;
                });

            $produtos = collect($produtos)->filter(function ($item) use ($produtos_estoque) {
                return !empty($produtos_estoque[$item['id_entrada_produto'] ?? -1] ?? null);
            })->map(function ($produto) use ($produtos_estoque) {
                $produtos_estoque[$produto['id_entrada_produto']]->codigo_de_barras = $produto['codigo_de_barras'];
                $produtos_estoque[$produto['id_entrada_produto']]->quantidade_selecionada = $produto['quantidade'];
                return $produtos_estoque[$produto['id_entrada_produto']];
            });
        } else {
            // Retornando entradas com os dados do codigo de barras e quantidade
            $produtos = collect($produtos)->map(function ($produto) {
                $entrada = $produto->entradaProduto;
                $entrada->codigo_de_barras = $produto->codigo_de_barras;
                $entrada->quantidade_selecionada = $produto->baixaProduto->quantidade;
                $entrada->quantidade_estoque += $produto->baixaProduto->quantidade;
                return $entrada;
            });
        }
        if (!empty(old('pagamentos'))) {
            // Ajustando array de contas a receber para ser visualizado corretamente no front
            $contas_receber = array_map(function ($item) {
                return [
                    'valor_parcela' => $item['valor'],
                    'data_vencimento' => $item['data'],
                    'forma_pagamento' => $item['forma_pagamento']
                ];
            }, old('pagamentos'));
        } else {
            $contas_receber = $saida->contasReceber()->get();
        }

        $paciente = $instituicao->pacientes()->where('pessoas.id', old('pessoa_id', $saida->pessoa_id))->first();
        $agendamento = Agendamentos::where('id', old('agendamento_id', $saida->agendamento_id))
            ->with([
                'agendamentoProcedimento',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento'
            ])
            ->first();

        $centro_custo = CentroCusto::find(old('centros_custos_id', $saida->centros_custos_id));

        return view('instituicao.saidas-estoque/editar', \compact(
            'saida',
            'produtos',
            'instituicao',
            'estoques',
            'contas_caixa',
            'formas_pagamento',
            'planos_conta',
            'plano_conta_selecionado',
            'agendamento_selecionado',
            'pessoa_selecionada',
            'conta_caixa_selecionada',
            'contas_receber',
            'paciente',
            'agendamento',
            'centro_custo'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     */
    public function update(CriarSaidaEstoqueRequest $request, SaidaEstoque $saida)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_saida_estoque');

        $instituicao = Instituicao::findOrFail(request()->session()->get("instituicao"));
        abort_unless($instituicao->id == $saida->instituicoes_id, 403);
        DB::transaction(function () use ($request, $instituicao, $saida) {
            $dados = collect($request->validated());
            $usuario_logado = $request->user('instituicao');
            // Criando baixa de estoque
            $baixa_estoque = $saida->baixaEstoque()->first();
            $baixa_estoque->update([
                'estoque_id' => $dados->get('estoques_id'),
                'motivo_baixa_id' => SaidaEstoque::buscarMotivoSaidaEstoque()->id,
                'data_emissao' => \date('Y-m-d'),
                'data_hora_baixa' => \date('H:i:s'),
                'usuario_id' => $usuario_logado->id,
                'instituicao_id' => $instituicao->id
            ]);

            // Definindo o que será armazenado em pessoa e agendamento
            $tipo_destino = $dados->get('tipo_destino');
            $pessoa_id = null;
            $agendamento_id = null;
            if ($tipo_destino == 1) {
                $pessoa_id = $instituicao->instituicaoPessoas()->findOrFail($dados->get('pessoa_id'))->id;
            } else if ($tipo_destino == 2) {
                $agendamento = Agendamentos::find($dados->get('agendamento_id'));
                $agendamento_id = $agendamento->id;
                $pessoa_id = $agendamento->pessoa_id;
            }

            // Criando saida de estoque
            $saida->update(
                array_merge($request->validated(), [
                    'agendamento_id' => $agendamento_id,
                    'pessoa_id' => $pessoa_id,
                    'estoque_baixa_id' => $baixa_estoque->id,
                    'usuarios_id' => $usuario_logado->id,
                    'instituicoes_id' => $instituicao->id
                ])
            );

            // Validando e adicionando os produtos selecionados a baixa de estoque
            $valor_custo = 0;
            $produtos_baixa = collect($dados->get('produtos', []))->filter(function ($produto_baixa) use ($instituicao, &$valor_custo, $baixa_estoque) {
                // Filtrando por instituicao
                $entrada = EstoqueEntradaProdutos::buscarProdutosEmEstoque('', $baixa_estoque, $produto_baixa['id_entrada_produto'])->first();

                // Filtrando por quantidade válida e calculando valor total
                if (!empty($entrada) && ($entrada->saldo_total ?? 0) >= ($produto_baixa['quantidade'] ?? 0)) {
                    $valor_custo += $entrada->valor;
                    return true;
                }
                return false;
            })->toArray();
            // Fazendo baixa dos produtos selecionados
            $baixa_estoque->overwrite($baixa_estoque->estoqueBaixaProdutos(), $produtos_baixa, function ($new, $index, $method) use ($usuario_logado, $instituicao, &$produtos_baixa) {
                if ($method == 'create') {
                    $new->criarLogCadastro($usuario_logado, $instituicao->id);
                } else {
                    $new->criarLogEdicao($usuario_logado, $instituicao->id);
                }
                // Passando dados novos de volta
                $new->codigo_de_barras = $produtos_baixa[$index]['codigo_de_barras'] ?? '';
                $produtos_baixa[$index] = $new;
            });

            // Gerando os produtos da saida de estoque
            $produtos_saida = array_map(function ($produto_baixa) {
                return [
                    'codigo_de_barras' => $produto_baixa->codigo_de_barras,
                    'estoque_baixa_produtos_id' => $produto_baixa->id,
                    'valor' => $produto_baixa->entradaProduto()->first()->valor ?? 0
                ];
            }, $produtos_baixa);
            // Cadastrando produtos da saída
            $saida->overwrite($saida->produtosSaida(), $produtos_saida, function ($new, $index, $method) use ($usuario_logado, $instituicao) {
                if ($method == 'create') {
                    $new->criarLogCadastro($usuario_logado, $instituicao->id);
                } else {
                    $new->criarLogEdicao($usuario_logado, $instituicao->id);
                }
            });

            // Gerando pagamentos
            if ($dados->get('gerar_conta', false)) {
                $valor_total = 0;
                $pagamentos = collect($request->get('pagamentos', []))
                    ->filter(function ($pagamento) use (&$valor_total) {
                        $valor = floatvalue($pagamento['valor']);
                        if ($valor > 0) {
                            $valor_total += $valor;
                            return true;
                        }
                    })
                    ->map(function ($pagamento, $key) use ($dados, $pessoa_id, $agendamento_id, $instituicao, $valor_total) {
                        return [
                            'agendamento_id' => $agendamento_id,
                            'pessoa_id' => $pessoa_id,
                            'instituicao_id' => $instituicao->id,
                            'num_parcela' => $key,
                            'data_vencimento' => $pagamento['data'],
                            'conta_id' => $dados->get('conta_id'),
                            'plano_conta_id' => $dados->get('plano_conta_id'),
                            'forma_pagamento' => $pagamento['forma_pagamento'],
                            'valor_parcela' => floatvalue($pagamento['valor']),
                            'valor_total' => $valor_total,
                            'valor_pago' => (array_key_exists('recebido', $pagamento)) ? floatvalue($pagamento['valor']) : null,
                            'data_pago' => (array_key_exists('recebido', $pagamento)) ? $pagamento['data'] : null,
                            'status' => (array_key_exists('recebido', $pagamento)) ? 1 : 0,
                        ];
                    });

                $saida->overwrite($saida->contasReceber(), $pagamentos->toArray(), function ($new, $key, $method) use ($usuario_logado, $instituicao) {
                    if ($method == 'create') {
                        $new->criarLogCadastro($usuario_logado, $instituicao->id);
                    } else {
                        $new->criarLogEdicao($usuario_logado, $instituicao->id);
                    }
                });
            }

            $saida->criarLogInstituicaoEdicao($usuario_logado, $instituicao->id);
            $baixa_estoque->criarLogEdicao($usuario_logado);
        });

        return redirect()->route('instituicao.saidas-estoque.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Saída de estoque atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * 
     */
    public function destroy(Request $request, SaidaEstoque $saida)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_saida_estoque');

        $instituicao = Instituicao::findOrFail(request()->session()->get("instituicao"));
        abort_unless($instituicao->id == $saida->instituicoes_id, 403);
        $usuario_logado = $request->user('instituicao');

        DB::transaction(function () use ($saida, $instituicao, $usuario_logado) {
            $baixa = $saida->baixaEstoque()->first();
            if($baixa) {
                $baixa->criarLogExclusao($usuario_logado, $instituicao->id);
                $baixa->delete();
            }
            $saida->criarLogInstituicaoExclusao($usuario_logado, $instituicao->id);
            $saida->delete();
        });

        return redirect()->route('instituicao.saidas-estoque.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Saída de estoque excluida com sucesso!'
        ]);
    }

    public function imprimir(Request $request, SaidaEstoque $saida)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_saida_estoque');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($instituicao->id == $saida->instituicoes_id, 403);

        $baixa_estoque = $saida->baixaEstoque()->first();
        $produtos_baixa = $saida->produtosBaixa()->get()->map(function($produtoBaixa) use ($baixa_estoque) {
            $entrada = EstoqueEntradaProdutos::buscarProdutosEmEstoque('', $baixa_estoque, $produtoBaixa->id_entrada_produto)->first();
            $entrada->quantidade_selecionada = $produtoBaixa->quantidade;
            $entrada->codigo_de_barras = $produtoBaixa->codigo_de_barras;
            $entrada->estoque_baixa_produtos_id = $produtoBaixa->id;
            return $entrada;
        });
        $agendamento = $saida->agendamento()->first();
        $procedimentos = $agendamento->agendamentoProcedimento()->with([
            'procedimentoInstituicaoConvenio',
            'procedimentoInstituicaoConvenio.convenios',
            'procedimentoInstituicaoConvenio.procedimentoInstituicao',
            'procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento'
        ])->get();

        return view('instituicao.saidas-estoque/imprimir', \compact(
            'saida',
            'produtos_baixa',
            'agendamento',
            'procedimentos'
        ));
    }
}
