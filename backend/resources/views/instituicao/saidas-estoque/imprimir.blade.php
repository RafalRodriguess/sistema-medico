@extends('layouts.material')
@section('conteudo')
    <div class="p-3">
        <h2>Relatorio Saída de Estoque (Agendamento)</h2>
        <hr>
        <div class="row">
            <div class="col-md-6 col-sm-10 form-group">
                <label class="form-control-label p-0 m-0">Paciente</label>
                <div>
                    {{ $saida->paciente->nome }}
                </div>
            </div>

            <div class="col-md-6 col-sm-8 form-group">
                <label class="form-control-label p-0 m-0">Data do procedimento</label>
                <div>
                    {{ (new \DateTime($agendamento->data_final ?? $agendamento->data ?? $agendamento->updated_at))->format('d/m/Y') }}
                </div>
            </div>

            @php
                $quant_procedimentos = $procedimentos->count();
            @endphp
            <div class="col-6 form-group">
                <label class="form-control-label p-0 m-0">Procedimento{{ $quant_procedimentos > 1 ? 's' : '' }}</label>
                <div>
                    @if ($quant_procedimentos > 1)
                        <ul>
                            @foreach ($procedimentos as $procedimento_agendamento)
                                @php
                                    $procedimento = $procedimento_agendamento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento;
                                @endphp
                                <li>{{ $procedimento_agendamento->procedimentoInstituicaoConvenio->convenios->nome }} - {{ $procedimento->descricao }}</li>
                            @endforeach
                        </ul>
                    @elseif($quant_procedimentos == 1)
                        @php
                            $procedimento = $procedimentos[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento;
                        @endphp
                        <li>{{ $procedimentos[0]->procedimentoInstituicaoConvenio->convenios->nome }} - {{ $procedimento->descricao }}</li>
                    @endif
                </div>
            </div>

            @if($quant_procedimentos == 1 && !empty($procedimentos[0]->procedimentoInstituicaoConvenio->convenios))
                <div class="col-6 form-group">
                    <label class="form-control-label p-0 m-0">Convênio</label>
                    <div>
                        {{ $procedimentos[0]->procedimentoInstituicaoConvenio->convenios->nome }}
                    </div>
                </div>
            @endif

            <div class="col-md-6 col-sm-10 form-group">
                <label class="form-control-label p-0 m-0">Prestador</label>
                <div>
                    {{ $agendamento->prestador->nome }}
                </div>
            </div>

            @if (!empty($agendamento->obs))
                <div class="col-md-6 col-sm-10 form-group">
                    <label class="form-control-label p-0 m-0">Observações</label>
                    <div>
                        {{ $agendamento->obs }}
                    </div>
                </div>
            @endif
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <hr>
                <h4>Produtos</h4>
            </div>
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Medida</th>
                            <th>Quantidade</th>
                            <th>Valor unit</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="produtos-necessarios-container">
                        @php
                            $total_geral = 0;
                        @endphp
                        @foreach ($produtos_baixa as $produto_baixa)
                            <tr>
                                @php
                                    $total_produto = $produto_baixa->quantidade_selecionada * $produto_baixa->valor;
                                    $total_geral += $total_produto;
                                @endphp
                                <td>{{ $produto_baixa->produto->descricao }}</td>
                                <td>{{ $produto_baixa->produto->unidade->descricao }}</td>
                                <td>{{ $produto_baixa->quantidade_selecionada }}</td>
                                <td>R$ {{ tobrl($produto_baixa->valor) }}</td>
                                <td>R$ {{ tobrl($total_produto) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">TOTAL DOS PRODUTOS</th>
                            <td>R$ {{ tobrl(-$total_geral) }}</td>
                        </tr>
                        <tr>
                            <th colspan="4">VALOR DO PROCEDIMENTO</th>
                            <td>R$ {{ tobrl($agendamento->valor_total) }}</td>
                        </tr>
                        <tr>
                            <th colspan="4">TOTAL DO PRESTADOR</th>
                            <td>R$ {{ tobrl($agendamento->valor_total - $total_geral) }}</td>
                        </tr>
                    </tfoot>
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

    aside, footer, .topbar {
        display: none !important;
    }

    .page-wrapper {
        margin: 0 !important;
        background: white;
        padding: 0;
    }

    td, th {
        text-align: center;
        vertical-align: middle;
    }

    .border-none {
        text-align: center;
        border: none;
    }

    input, select {
        pointer-events: none;
    }
</style>
@endpush
@push('scripts')
    <script>
        $(document).ready(() => window.print());
    </script>
@endpush
