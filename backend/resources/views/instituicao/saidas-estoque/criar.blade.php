@extends('instituicao.layout')
@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar saída de estoque',
        'breadcrumb' => [
            'Saídas de estoque' => route('instituicao.saidas-estoque.index'),
            'Cadastrar saída de estoque',
        ],
    ])
    @endcomponent
    <div class="card">
        <form action="{{ route('instituicao.saidas-estoque.store') }}" method="post">
            <div class="card-body">
                @csrf

                <div class="row">
                    <div class="col-md-8 col-sm-10 form-group">
                        <label for="estoque-select" class="form-control-label">Estoque de origem <span
                                class="text-danger">*</span></label>
                        <select name="estoques_id" id="estoque-select" style="width: 100%" class="form-control">
                            <option value=""></option>
                            @foreach ($estoques as $estoque)
                                <option value="{{ $estoque->id }}"
                                    @if (old('estoques_id') == $estoque->id) selected="selected" @endif>{{ $estoque->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('estoques_id'))
                            <small class="form-control-feedback text-danger">{{ $errors->first('estoques_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-control-label" for="centros_custos_id">Centro de custo</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right"
                                title="" data-original-title="Qual o centro de custo esta saída será relacionada"></i>
                        </label>
                        <select name="centros_custos_id" id="centros_custos_id" class="form-control">
                            <option value="">Não especificado</option>
                            @if (!empty($centro_custo))
                                <option value="{{ $centro_custo->id }}" selected>{{ $centro_custo->descricao }}</option>
                            @endif
                        </select>
                        @if ($errors->has('centros_custos_id'))
                            <div class="form-control-feedback text-danger">{{ $errors->first('centros_custos_id') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-control-label" for="tipo_destino">Destino da saída</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right"
                                title="" data-original-title="Qual o tipo de destinatário da saída"></i>
                        </label>
                        <select name="tipo_destino" id="tipo_destino" class="form-control">
                            <option value="">Não especificado</option>
                            @foreach (\App\SaidaEstoque::destino_saida as $id => $destino)
                                <option value="{{ $id }}" @if (old('tipo_destino') == $id) selected @endif>
                                    {{ $destino }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('tipo_destino'))
                            <div class="form-control-feedback text-danger">{{ $errors->first('tipo_destino') }}</div>
                        @endif
                    </div>

                    <div class="col-md-8 row" id="agendamento_select">
                        <div class="col-md-6 col-sm-6 form-group">
                            <input type="hidden" name="agendamento_id" id="agendamento-id-input" @if(!empty($agendamento)) value="{{$agendamento->id}}" @endif>
                            <label for="agendamento_id" class="form-control-label">Agendamento <span
                                    class="text-danger">*</span></label>
                            <select id="agendamento_id" style="width: 100%"
                                class="form-control monospace-select2 @if ($errors->has('agendamento_id')) form-control-danger @endif">
                                @if (!empty($agendamento) && !empty($agendamento->data))
                                    <option value="{{ json_encode($agendamento) }}">#{{ $agendamento->id }} -
                                        {{ (new DateTime($agendamento->data))->format('d/m/Y - H:s') }}
                                        {{ $agendamento->pessoa->nome }} - {{ $agendamento->prestador->nome }}</option>
                                @elseif(!empty($agendamento))
                                    <option value="{{ $agendamento->id }}">#{{ $agendamento->id }} -
                                        {{ (new DateTime($agendamento->created_at))->format('d/m/Y') }}
                                        {{ $agendamento->pessoa->nome }} - {{ $agendamento->prestador->nome }}</option>
                                @endif
                            </select>
                            @if ($errors->has('agendamento_id'))
                                <small
                                    class="form-control-feedback text-danger">{{ $errors->first('agendamento_id') }}</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="procedimento_text">Procedimento</label>
                            <span class="form-control d-block" id="procedimento_text">
                                @if(!empty($agendamento) && !empty($agendamento->agendamentoProcedimento[0]))
                                    @foreach ($agendamento->agendamentoProcedimento as $key => $agendamento_procedimento)
                                        @if($key > 0)
                                            {{ ', ' }}
                                        @endif
                                        {{  $agendamento_procedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao }}
                                    @endforeach
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-10 form-group" id="paciente_select">
                        <label for="pessoa_id" class="form-control-label">Paciente <span
                                class="text-danger">*</span></label>
                        <select id="pessoa_id" name="pessoa_id" style="width: 100%"
                            class="form-control  @if ($errors->has('pessoa_id')) form-control-danger @endif">
                            @if (!empty($paciente))
                                <option value="{{ $paciente->id }}">{{ $paciente->nome }}</option>
                            @endif
                        </select>
                        @if ($errors->has('pessoa_id'))
                            <small class="form-control-feedback text-danger">{{ $errors->first('pessoa_id') }}</small>
                        @endif
                    </div>

                    <div class="col-12 col-sm-10 form-group">
                        <label for="observacoes-input" class="form-control-label">Observações</label>
                        <textarea name="observacoes" id="observacoes-input" rows="4" class="form-control">{{ old('observacoes') }}</textarea>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <label for="produto-select" class="form-control-label">Produtos que serão enviados <i
                                class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right"
                                title=""
                                data-original-title="Escolha produtos e depois pressione o botão &#34;+&#34; para adicionar-lo lista de produtos"></i></label>
                        <div class="input-group">
                            <div class="col p-0">
                                <select id="produto-select" style="width: 100%"
                                    class="form-control @if ($errors->has('produtos')) form-control-danger @endif"></select>
                            </div>
                            <div class="px-1">
                                <button id="adicionar-produto-button" type="button" class="btn btn-primary"><i
                                        class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        @if ($errors->has('produtos'))
                            <small class="form-control-feedback text-danger">{{ $errors->first('produtos') }}</small>
                        @endif
                    </div>
                    <div class="my-3 col-12 pb-2 table-container">
                        <table class="table table-bordered p-0">
                            <colgroup>
                                <col style="width: 300px">
                                <col style="width: auto">
                                <col style="width: 100px">
                                <col style="width: 100px">
                                <col style="width: auto">
                                <col style="width: 150px">
                                <col style="width: 150px">
                                <col style="width: 50px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="header-compact" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="Código de barras">
                                        Cód. Barras</th>
                                    <th>Produto</th>
                                    <th>Custo</th>
                                    <th>Lote</th>
                                    <th>Unidade</th>
                                    <th class="header-compact" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="Quantidade presente no lote">Máximo</th>
                                    <th class="header-compact" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="Quantidade recebida">Quantidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="produtos-escolhidos-container">
                            </tbody>
                        </table>
                        @if ($errors->has('produtos'))
                            <div class="form-control-feedback text-danger">{{ $errors->first('produtos') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 col-sm">
                        <label for="display-total-produtos">Valor Total
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right"
                                title=""
                                data-original-title="Valor de todos os produtos considerando o desconto"></i></label>
                        <span type="text" id="display-total-produtos" class="form-control d-block">R$ 0,00</span>
                        @if ($errors->has('valor_total'))
                            <small class="form-control-feedback text-danger">{{ $errors->first('valor_total') }}</small>
                        @endif
                    </div>
                    <div id="pagamento-display" class="form-group col-md-3 col-sm" style="display: none">
                        <label for="display-pagamento-restante">Valor restante a pagar
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right"
                                title=""
                                data-original-title="Valor que resta para pagar, resultado de calcular os pagamentos adicionados e o desconto"></i></label>
                        <span type="text" id="display-pagamento-restante" class="form-control d-block">R$ 0,00</span>
                    </div>
                    <div class="input-group col-md col-sm-12 d-flex align-items-center">
                        <input type="checkbox" name="gerar_conta" id="check-gerar-conta-receber"
                            @if (!empty(old('gerar_conta'))) checked="checked" @endif>
                        <label for="check-gerar-conta-receber" class="my-0 ml-3">Gerar conta a receber</label>
                    </div>
                </div>

                <div id="pagamento-tab" class="row">
                    <div class="col-12 px-0 input-group">
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-12 form-group">
                            <h4>Pagamento</h4>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-control-label" for="conta_id">Conta caixa <span
                                    class="text-danger">*</span></span>
                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right"
                                    title=""
                                    data-original-title="Esta é a conta onde será debitada o dinheiro, se for pagamento na empresa por dinheiro 'Caixa' e na conta no banco utilizando cheque ou pagamento online 'Conta Banco XX..."></i>
                            </label>
                            <select name="conta_id" id="conta_id" class="form-control select2-simple"
                                style="width: 100%">
                                <option value="" disabled hidden @if (empty(old('conta_id'))) selected @endif>
                                    Selecione uma conta</option>
                                @foreach ($contas_caixa as $conta)
                                    <option value="{{ $conta->id }}"
                                        @if (old('conta_id') == $conta->id) selected @endif>
                                        {{ $conta->descricao }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('conta_id'))
                                <div class="form-control-feedback text-danger">{{ $errors->first('conta_id') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-control-label">Plano de conta: <span class="text-danger">*</span></span>
                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right"
                                    title=""
                                    data-original-title="Este é o filtro por tipo de pagamento, que deve ser escolhido o plano exato que se associa ao pagamento"></i>
                            </label>
                            <select name="plano_conta_id"
                                class="form-control selectfild2 @if ($errors->has('plano_conta_id')) form-control-danger @endif"
                                style="width: 100%">
                                <option value="" disabled hidden @if (empty(old('plano_conta_id'))) selected @endif>
                                    Selecione um plano de conta</option>
                                @foreach ($planos_conta as $item)
                                    <option value="{{ $item->id }}"
                                        @if (old('plano_conta_id') == $item->id) selected="selected" @endif>{{ $item->codigo }}
                                        - {{ $item->descricao }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('plano_conta_id'))
                                <div class="form-control-feedback text-danger">{{ $errors->first('plano_conta_id') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div id="pagamentos-container" class="col-12"></div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('instituicao.saidas-estoque.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light"><i
                            class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                </a>
                <button type="submit" class="btn btn-success waves-effect waves-light mr-0"><i
                        class="mdi mdi-check"></i>
                    Salvar</button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/views/instituicao/saidas-estoque/SaidaEstoque.js') }}"></script>
    {{-- Template Produto Selecionado --}}
    <script type="text/template" id="produto-selecionado-template">
        <tr class="produtos-item" class="centered-table-line">
            <input data-name="id-entrada-produto-input" type="hidden" name="produtos[#][id_entrada_produto]">
            <td>
                <input data-name="codigo-de-barras-input" type="text" name="produtos[#][codigo_de_barras]" class="form-control">
                <div class="form-control-feedback text-danger" data-error="codigo-de-barras-input"></div>
            </td>
            <td data-name="produto-nome"></td>
            <td data-name="valor-produto" class="valor-produto"></td>
            <td data-name="lote-text"></td>
            <td data-name="unidade"></td>
            <td data-name="quantidade-maxima-text"></td>
            <td>
                <input data-name="quantidade-input" type="number" min="0" name="produtos[#][quantidade]" class="form-control quantidade-input" value="0">
                <div class="form-control-feedback text-danger" data-error="quantidade-input"></div>
            </td>
            <td><button data-name="remover-produto-button" type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button></td>
        </tr>
    </script>
    {{-- Template Pagamento --}}
    <script type="text/template" id="pagamento-template">
        <div class="card pagamento-item" style="flex-wrap: wrap">
            <div class="card-header border-bottom d-flex bg-light">
                <div class="col ml-0">
                    <h4 data-name="titulo" class="m-0"></h4>
                </div>
                <div class="d-flex">
                    <button data-name="remover-button" onclick="" type="button" class="btn btn-danger btn-sm mr-3" data-toggle="tooltip" data-placement="top" data-original-title="Remover este pagamento"><i class="fas fa-trash"></i></button>
                    <button data-name="adicionar-button" type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Adicionar mais um pagamento"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="d-flex pt-3" style="flex-wrap: wrap">
                <div class="form-group col-md-3 col-sm">
                    <label for="tipo-pagamento-&">Forma de pagamento </label>
                    <select data-name="forma-pagamento" name="pagamentos[&][forma_pagamento]" id="tipo-pagamento-&"
                        class="form-control">
                        @foreach ($formas_pagamento as $forma_pagamento)
                            <option value="{{$forma_pagamento}}">{{ App\ContaReceber::forma_pagamento_texto($forma_pagamento)}}</option>
                        @endforeach
                    </select>
                    <div class="form-control-feedback text-danger" data-error="forma-pagamento"></div>
                </div>
                <div class="form-group col-md-3 col-sm">
                    <label for="pagamento-valor-&">Valor:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">R$</span>
                        </div>
                        <input data-name="valor" class="pagamento-valor form-control" type="text" name="pagamentos[&][valor]" id="pagamento-valor-&" class="form-control">
                        <div class="form-control-feedback text-danger" data-error="valor"></div>
                    </div>
                </div>
                <div class="form-group col-md-3 col-sm">
                    <label for="pagamento-data-&">Data <span class="text-danger">*</span></label>
                    <input data-name="data" type="date" name="pagamentos[&][data]" id="pagamento-data-&"
                        class="form-control">
                    <div class="form-control-feedback text-danger" data-error="data"></div>
                </div>
                <div class="col-md-3 col-sm p-0 input-group d-flex align-items-center py-2">
                    <input data-name="recebido" type="checkbox" name="pagamentos[&][recebido]" id="pagamento-recebido-&">
                    <label for="pagamento-recebido-&" class="mx-3 mb-0 mt-2 d-block">Recebido</label>
                </div>
            </div>
        </div>
    </script>
    {{-- Scripts --}}
    <script>
        const produtosContainer = $('#produtos-escolhidos-container');
        const produtoTemplate = new HtmlTemplate('#produto-selecionado-template');
        const pagamentosContainer = $('#pagamentos-container');
        const pagamentoTemplate = new HtmlTemplate('#pagamento-template', '&');

        const SaidasEstoque = new SaidaEstoque(null, produtosContainer, produtoTemplate,
            '{{ json_encode($errors->messages()) }}');
        const Pagamentos = new PagamentosSaida(null, pagamentosContainer, pagamentoTemplate, $('#display-total-produtos'),
            $('#display-pagamento-restante'), '{{ json_encode($errors->messages()) }}');

        $(document).ready(function() {
            $('#adicionar-produto-button').on('click', () => {
                let produto = null;
                if (!$('#produto-select').val()) {
                    return;
                } else {
                    try {
                        produto = JSON.parse($('#produto-select').val())
                    } catch {}
                    if (!produto) {
                        return;
                    }
                }
                SaidasEstoque.adicionar(produto);
            });


            $('#estoque-select').select2({
                placeholder: "Escolha um estoque de origem",
            });

            $('#produto-select').select2({
                placeholder: 'Busque por nome do produto ou lote',
                ajax: {
                    url: "{{ route('instituicao.ajax.getentradaprodutos') }}",
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            search: params.term,
                            paginate: true,
                            '_token': '{{ csrf_token() }}',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.items, function(obj) {
                                return {
                                    id: JSON.stringify(obj),
                                    text: `#${obj.lote} - ${obj.produto.descricao} [un: ${obj.produto.unidade ? obj.produto.unidade.descricao : 'unidade'}]`
                                };
                            }),
                            pagination: {
                                more: data.next ? true : false
                            }

                        }
                    },
                },
                minimumInputLength: 1,
                language: {
                    searching: function() {
                        return 'Buscando produtos no estoque';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                },
                escapeMarkup: function(m) {
                    return m;
                },
            });

            $('.select2-simple').select2();

            $('#centros_custos_id').select2({
                placeholder: "Pesquise por nome do paciente",
                allowClear: true,

                ajax: {
                    url: "{{ route('instituicao.ajax.buscar-centros-custo') }}",
                    dataType: 'json',
                    type: 'post',
                    delay: 100,

                    data: function(params) {
                        return {
                            search: params.term, // search term
                            page: params.page || 1,
                            '_token': '{{ csrf_token() }}',
                        };
                    },

                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.descricao}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3,
                language: {
                    searching: function() {
                        return 'Buscando centros de custo';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                }
            });

            $('#agendamento_id').select2({
                placeholder: "Busque a partir da data ou paciente",
                ajax: {
                    url: "{{ route('instituicao.ajax.buscaagendamentos') }}",
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            search: params.term,
                            status: 1,
                            '_token': '{{ csrf_token() }}'
                        };
                    },
                    processResults: function(data, params) {
                        agendamentos_search = Array()
                        return {
                            results: $.map(data.results, function(obj) {
                                const data = new Date(obj.data_hora);
                                let time = data.toLocaleString("default", {
                                    hour: "2-digit"
                                }) + ':' + data.toLocaleString("default", {
                                    minute: "2-digit"
                                });
                                if (time.length == 4) {
                                    time = time.split(':');
                                    time = `${time[0]}:0${time[1]}`;
                                }
                                let date = `${data.toLocaleString("default", { day: "2-digit" })}/${data.toLocaleString("default", { month: "2-digit" })}/${data.toLocaleString("default", { year: "numeric" })}`;
                                const option = {
                                    id: JSON.stringify(obj),
                                    text: `#${obj.agendamento_id} ${date} - ${time} - ${obj.pessoa.nome} - ${obj.agendamento.instituicoes_prestadores.prestador.nome}`
                                }
                                // cria uma lista com os resultados para reutilizar os nomes
                                agendamentos_search.push(option)
                                return option;
                            }),
                            pagination: {
                                more: data.pagination.more
                            }
                        }
                    }
                },
                minimumInputLength: 3,
                language: {
                    searching: function() {
                        return 'Buscando agendamento';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                },
                escapeMarkup: function(m) {
                    return m;
                }
            }).on('select2:select', function(e) {
                const dados = JSON.parse($(e.target).val());
                $('#agendamento-id-input').val(dados.agendamento_id);
                if(dados.agendamento.agendamento_procedimento.length > 0) {
                    let texto = "";
                    dados.agendamento.agendamento_procedimento.forEach((agendamento_procedimento, key) => {
                        if(key > 0) {
                            texto += ', ';
                        }
                        texto += agendamento_procedimento.procedimento_instituicao_convenio.procedimento_instituicao.procedimento.descricao;
                    });
                    $('#procedimento_text').text(texto);
                }
            });

            $("#pessoa_id").select2({
                placeholder: "Pesquise por nome do paciente",
                allowClear: true,

                ajax: {
                    url: "{{ route('instituicao.contasPagar.getPacientes') }}",
                    dataType: 'json',
                    type: 'get',
                    delay: 100,

                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },

                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.nome} ${(item.cpf) ? '- (' + item.cpf + ')' : ''}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3,
                language: {
                    searching: function() {
                        return 'Buscando pacientes';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                }
            });

            // Switch que exibe/oculta campos de paciente e agendamento
            const switchDestino = function(val) {
                if (val == 1) {
                    $('#agendamento_select').hide();
                    $('#paciente_select').show();
                } else if (val == 2) {
                    $('#paciente_select').hide();
                    $('#agendamento_select').show();
                } else {
                    $('#paciente_select').hide();
                    $('#agendamento_select').hide();
                }
            };
            $('#tipo_destino').on('change', (e) => switchDestino($(e.target).val()));
            switchDestino($('#tipo_destino').val());

            // Carregando produtos
            const produtos = Array.from({!! json_encode($produtos) !!});
            if (produtos.length > 0) {
                SaidasEstoque.preencher(produtos);
            }

            // Carregando pagamentos
            const contas_receber = Array({!! json_encode($contas_receber) !!})[0];
            if (contas_receber.length > 0) {
                Pagamentos.preencher(contas_receber);
            }

            // Switch que exibe/oculta a geração de conta
            const exibirContaReceber = (val) => {
                if (val) {
                    $('#pagamento-tab, #pagamento-display').show();
                    if (Pagamentos.pagamentos.id == 0) {
                        Pagamentos.adicionar();
                    }
                } else {
                    $('#pagamento-tab, #pagamento-display').hide();
                    Pagamentos.limpar();
                }
            }
            $('#check-gerar-conta-receber').on('change', (e) => exibirContaReceber($(e.target).prop('checked')));
            exibirContaReceber($('#check-gerar-conta-receber').prop('checked'));
        });
    </script>
@endpush
