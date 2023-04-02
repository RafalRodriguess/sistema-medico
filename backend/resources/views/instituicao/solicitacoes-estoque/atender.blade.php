@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Atender solicitação: #{$solicitacao->id}",
        'breadcrumb' => [
            'Solicitações de estoque' => route('instituicao.solicitacoes-estoque.index'),
            'Atender solicitação de estoque',
        ],
    ])
    @endcomponent
    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.solicitacoes-estoque.atender.update', $solicitacao) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 col-sm-10 form-group">
                        <label class="form-control-label p-0 m-0">Destino</label>
                        <select class="form-control" disabled>
                            <option class="selected">{{ $opcoes_destino[$solicitacao->destino] }}</option>
                        </select>
                    </div>

                    <div class="col-md-8 col-sm-10 form-group">
                        <label class="form-control-label p-0 m-0">Estoque de origem</label>
                        <select style="width: 100%" class="form-control" disabled>
                            <option selected>{{ $solicitacao->estoqueOrigem->descricao }}</option>
                        </select>
                    </div>
                </div>

                @switch($solicitacao->destino)
                    @case(1)
                        <div id="form-destino-1" class="row form-switcher">
                            <div class="col-md-6 col-sm-10 form-group">
                                <label class="form-control-label p-0 m-0">Atendimento</label>
                                <select style="width: 100%" class="form-control" disabled>
                                    @php
                                        $atendimento = $solicitacao->estoqueDestino()['agendamento_atendimento']->first();
                                    @endphp
                                    <option selected>{{ "{$atendimento->data_hora} - COD: {$atendimento->id}" }}</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-10 form-group">
                                <label class="form-control-label p-0 m-0">Paciente</label>
                                <input value="@if ($atendimento) {{ $atendimento->pessoa->nome }} @endif" readonly
                                    class="form-control">
                            </div>

                            <div class="col-md-6 col-sm-10 form-group">
                                <label class="form-control-label p-0 m-0">Prestador solicitante</label>
                                <select style="width: 100%" class="form-control" disabled>
                                    @php
                                        $prestador = $solicitacao->estoqueDestino()['instituicao_prestador'];
                                    @endphp
                                    <option selected>{{ $prestador->first()->prestador->nome }}</option>
                                </select>
                            </div>
                        </div>
                    @break

                    @case(2)
                        <div id="form-destino-2" class="row form-switcher">
                            <div class="col-md-6 col-sm-8 form-group">
                                <label class="form-control-label p-0 m-0">Unidade de internação</label>
                                @php
                                    $unidade_internacao = $solicitacao->estoqueDestino()['unidade_internacao'];
                                    $unidade_internacao = !empty($unidade_internacao) ? $unidade_internacao->first() : null;
                                @endphp
                                <select style="width: 100%" class="form-control" disabled>
                                    @if (!empty($unidade_internacao))
                                        <option selected>{{ $unidade_internacao->nome }}</option>
                                    @else
                                        <option selected>Não especificado</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-6 col-sm-8 form-group">
                                <label class="form-control-label p-0 m-0">Setor</label>
                                <select style="width: 100%" class="form-control" disabled>
                                    @php
                                        $setor = $solicitacao->estoqueDestino()['setor']->first();
                                    @endphp
                                    <option selected>{{ $setor->descricao }}</option>
                                </select>
                            </div>
                        </div>
                    @break

                    @case(3)
                        <div id="form-destino-3" class="row form-switcher">
                            <div class="col-md-8 col-sm-10 form-group">
                                <label class="form-control-label p-0 m-0">Estoque de destino</label>
                                <select style="width: 100%" class="form-control" disabled>
                                    @php
                                        $estoque = $solicitacao->estoqueDestino;
                                    @endphp
                                    <option selected>{{ $estoque->descricao }}</option>
                                </select>
                            </div>
                        </div>
                    @break
                @endswitch

                <div class="row col-12">
                    <div class="card col-12 px-0 py-3 shadow-none">
                        <div class="form-group col-md-12">
                            <h4>Produtos requisitados</h4>
                            <div class="mt-4">
                                <table class="table table-bordered">
                                    <colgroup>
                                        <col style="width: 100px">
                                        <col style="width: auto">
                                        <col style="width: 150px">
                                        <col style="width: 150px">
                                        <col style="width: 150px">
                                        <col style="width: 200px">
                                        <col style="width: 100px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Descrição</th>
                                            <th>Unidade</th>
                                            <th>Quantidade</th>
                                            <th>Atendidos</th>
                                            <th>Motivo divergência</th>
                                            <th title="Confirma item da solicitação">Confirma</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produtos-solicitados-container">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row col-12">
                    <div class="card col-12 px-0 py-3 shadow-none">
                        <div class="form-group col-md-12">
                            <div class="col-md-8 px-0">
                                <h4
                                    class="form-control-label p-0 mx-0 mb-4 @if ($errors->has('produtos')) has-danger @endif">
                                    Produtos que serão enviados</h4>
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
                                    <small
                                        class="form-control-feedback text-danger">{{ $errors->first('produtos') }}</small>
                                @endif
                            </div>
                            <div class="mt-4 table-container">
                                <table class="table table-bordered">
                                    <colgroup>
                                        <col style="width: 300px">
                                        <col style="width: auto">
                                        <col style="width: auto">
                                        <col style="width: 150px">
                                        <col style="width: 150px">
                                        <col style="width: 150px">
                                        <col style="width: 50px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th title="Código de barras">Cód. Barras</th>
                                            <th title="Código do produto">Descrição</th>
                                            <th>Lote</th>
                                            <th>Unidade</th>
                                            <th title="Quantidade presente no lote atual">Quant.</th>
                                            <th title="Quantidade recebida">Recebido</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produtos-recebidos-container">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.solicitacoes-estoque.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                                class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i
                            class="mdi mdi-check"></i>
                        Salvar</button>
                </div>
            </form>
        </div>
    </div>
    <style>
        .centered-table-line td {
            vertical-align: middle;
        }

        th[title] {
            cursor: help;
        }

        .in-id {
            cursor: pointer;
        }
    </style>
@endsection
@push('scripts')
    <script src="{{ asset('js/views/instituicao/solicitacoes-estoque/AtenderSolicitacao.js') }}"></script>
    {{-- Produtos necessarios template --}}
    <script type="text/template" id="produtos-solicitados-template">
        <tr class="produto-necessario-input">
            <input data-name="produto-solicitado-id" type="hidden" name="produtos[#][id]">
            <input data-name="produto-id" class="produto-id" type="hidden">
            <td data-name="produto-id-texto"></td>
            <td data-name="produto-descricao" class="name"></td>
            <td data-name="produto-unidade" class="unidade"></td>
            <td><span data-name="quantidade" class="d-block form-control"></span></td>
            <td><span data-name="quantidade-atendida" class="d-block form-control quantidade-atendida">0</span></td>
            <td>
                <select data-name="motivo-divergencia" name="produtos[#][motivos_divergencia_id]" id="#_motivos_divergencia_select" class="form-control select2">
                    @foreach($motivos_divergencia as $motivo)
                        <option value="{{$motivo->id}}">{{$motivo->descricao}}</option>
                    @endforeach
                </select>
            </td>
            <td><div class="d-flex justify-content-center"><input data-name="confirma" name="produtos[#][confirma_item]" type="checkbox"></div></td>
        </tr>
    </script>
    {{-- Template Produtos atendidos --}}
    <script type="text/template" id="produtos-recebidos-template">
        <tr class="produtos-recebidos-entry centered-table-line">
            <input data-name="id_entrada_produto" class="in-produto-id" type="hidden" name="produtos_recebidos[#][id_entrada_produto]">
            <td>
                <input data-name="codigo_de_barras" type="text" name="produtos_recebidos[#][codigo_de_barras]" class="form-control">
                <span data-error="codigo_de_barras" class="text-danger"></span>
            </td>
            <td data-name="produto-descricao" class="in-descricao"></td>
            <td data-name="produto-lote" class="in-lote"></td>
            <td data-name="produto-unidade" class="in-unidade"></td>
            <td data-name="produto-maximo" class="in-quantidade_maxima"></td>
            <td>
                <input data-name="quantidade" type="number" min="0" name="produtos_recebidos[#][quantidade]" class="max-quantidade form-control ammount-selected-input">
                <span data-error="quantidade" class="text-danger"></span>
            </td>
            <td><button data-name="remover-produto-button" type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button></td>
        </tr>
    </script>
    {{-- Scripts --}}
    <script>
        const template_produto_solicitado = new HtmlTemplate('#produtos-solicitados-template');
        const produtos_solicitados = Array.from({!! json_encode($produtos) !!});
        const template_produto_atendido = new HtmlTemplate('#produtos-recebidos-template');
        const produtos_atendidos = Array.from({!! json_encode($produtos_atendidos) !!});

        // Objeto que controla o form
        let Atendimento = null;

        $(document).ready(function() {
            // Instanciando formulário
            Atendimento = new AtenderSolicitacao(
                produtos_solicitados, 
                template_produto_solicitado, 
                template_produto_atendido, 
                $('#produtos-solicitados-container'), 
                $('#produtos-recebidos-container')
            );
            // Preenchendo caso necessário
            if (produtos_atendidos.length > 0) {
                Atendimento.preencher(produtos_atendidos);
            }

            $('#adicionar-produto-button').on('click', (e) => Atendimento.adicionar($('#produto-select').val(), true));

            $('.select2').select2();

            $('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
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
        })
    </script>
@endpush
