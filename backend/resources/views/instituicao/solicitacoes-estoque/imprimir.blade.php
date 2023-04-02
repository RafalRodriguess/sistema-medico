@extends('layouts.material')
@section('conteudo')
    <div class="p-3">
        <h2>Solicitação de estoque #{{ $solicitacao->id }} de
            {{ (new \DateTime($solicitacao->updated_at))->format('d/m/Y') }}</h2>
        <hr>
        <h4>Origem e destino</h4>
        <div class="row">
            <div class="col-md-6 col-sm-8 form-group">
                <label class="form-control-label p-0 m-0">Estoque de origem:</label>
                <div>
                    {{ $solicitacao->estoqueOrigem->descricao }}
                </div>
            </div>

            @switch($solicitacao->destino)
                @case(1)
                    <div class="col-md-6 col-sm-8 form-group">
                        <label class="form-control-label p-0 m-0">Atendimento</label>
                        <div>
                            @php
                                $atendimento = $solicitacao->estoqueDestino()['agendamento_atendimento']->first();
                            @endphp
                            {{ "{$atendimento->data_hora} - COD: {$atendimento->id}" }}
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-10 form-group">
                        <label class="form-control-label p-0 m-0">Paciente</label>
                        <div>
                            @if ($atendimento)
                                {{ $atendimento->pessoa->nome }}
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-10 form-group">
                        <label class="form-control-label p-0 m-0">Prestador solicitante</label>
                        @php
                            $prestador = $solicitacao->estoqueDestino()['instituicao_prestador']->first();
                        @endphp
                        <div>
                            {{ $prestador->prestador->nome }}
                        </div>
                    </div>
                @break

                @case(2)
                    <div class="col-md-6 col-sm-8 form-group">
                        <label class="form-control-label p-0 m-0">Unidade de internação</label>
                        @php
                            $unidade_internacao = $solicitacao->estoqueDestino()['unidade_internacao'];
                            $unidade_internacao = !empty($unidade_internacao) ? $unidade_internacao->first() : null;
                        @endphp
                        <div>
                            @if (!empty($unidade_internacao))
                                {{ $unidade_internacao->nome }}
                            @else
                                Não especificado
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-8 form-group">
                        <label class="form-control-label p-0 m-0">Setor</label>
                        <div>
                            @php
                                $setor = $solicitacao->estoqueDestino()['setor']->first();
                            @endphp
                            {{ $setor->descricao }}
                        </div>
                    </div>
                @break

                @case(3)
                    <div class="col-md-6 col-sm-8 form-group">
                        <label class="form-control-label p-0 m-0">Estoque de destino</label>
                        <div>
                            @php
                                $estoque = $solicitacao->estoqueDestino;
                            @endphp
                            {{ $estoque->descricao }}
                        </div>
                    </div>
                @break
            @endswitch
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <hr>
                <h4>Produtos requisitados</h4>
            </div>
            <div class="col-12">
                <table class="table">
                    <colgroup>
                        <col style="width: 100px">
                        <col style="width: auto">
                        <col style="width: 150px">
                        <col style="width: 150px">
                        <col style="width: 30%">
                        <col style="width: 100px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Quantidade requistada</th>
                            <th>Quantidade atendida</th>
                            <th>Motivo divergência</th>
                            <th title="Confirma item da solicitação">Confirma</th>
                        </tr>
                    </thead>
                    <tbody id="produtos-solicitados-container">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <hr>
                <h4>Produtos enviados</h4>
            </div>
            <div class="col-12">
                <table class="table">
                    <colgroup>
                        <col style="width: 300px">
                        <col style="width: auto">
                        <col style="width: auto">
                        <col style="width: 150px">
                        <col style="width: 150px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th title="Código de barras">Cód. Barras</th>
                            <th title="Código do produto">Descrição</th>
                            <th>Lote</th>
                            <th>Unidade</th>
                            <th>Quantidade enviada</th>
                        </tr>
                    </thead>
                    <tbody id="produtos-recebidos-container">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('estilos')
    <style>
        h4 {
            font-weight: 500;
            margin-bottom: 1rem;
        }

        label.form-control-label {
            font-weight: 500;
            margin-bottom: 0.5rem !important;
        }

        .preloader {
            display: none !important;
        }

        aside,
        footer,
        .topbar {
            display: none !important;
        }

        .page-wrapper {
            margin: 0 !important;
            background: white;
            padding: 0;
        }

        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        .border-none {
            text-align: center;
            border: none;
        }

        input,
        select {
            pointer-events: none;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/views/instituicao/solicitacoes-estoque/AtenderSolicitacao.js') }}"></script>
    {{-- Produtos necessarios template --}}
    <script type="text/template" id="produtos-solicitados-template">
        <tr class="produto-necessario-input">
            <td data-name="produto-id-texto"></td>
            <td data-name="produto-descricao" class="name"></td>
            <td data-name="produto-unidade" class="unidade"></td>
            <td data-name="quantidade-atendida"></td>
            <td data-name="motivo-divergencia-texto"></td>
            <td data-name="confirma-icone"></td>
        </tr>
    </script>
    {{-- Template Produtos atendidos --}}
    <script type="text/template" id="produtos-recebidos-template">
        <tr class="produtos-recebidos-entry centered-table-line">
            <td data-name="codigo_de_barras"></td>
            <td data-name="produto-descricao" class="in-descricao"></td>
            <td data-name="produto-lote" class="in-lote"></td>
            <td data-name="produto-unidade" class="in-unidade"></td>
            <td data-name="quantidade-texto" class="in-quantidade_maxima"></td>
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

            $('#adicionar-produto-button').on('click', (e) => Atendimento.adicionar($('#produto-select').val(),
                true));

            $('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });
        })
    </script>
@endpush
