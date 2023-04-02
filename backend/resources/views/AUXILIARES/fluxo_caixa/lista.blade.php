@extends('layouts/material')


@push('scripts')
    <!-- jQuery peity -->
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>

    <script>
        $(function() {
            $('#FrmFluxoCaixa').on('change', 'select', function () {
                $('#FrmFluxoCaixa').submit();
            });
        })
    </script>
@endpush

@push('estilos')
    <link href="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}" rel="stylesheet">
@endpush

@section('conteudo')
          
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                        <div class="col-md-5 col-8 align-self-center">
                            <h3 class="text-themecolor m-b-0 m-t-0">Fluxo de Caixa</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Financeiro</a></li>
                                <li class="breadcrumb-item active">Fluxo de Caixa</li>
                            </ol>
                        </div>
                        
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-12">  
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('fluxo_caixas.index') }}" id="FrmFluxoCaixa">
                                        <h4 class="card-title">Filtros</h4>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select class="select2" name="clientes_id"  style="width: 100%">
                                                        <option value="">Cliente</option>
                                                        @foreach ($clientes as $cliente)
                                                            <option value="{{ $cliente->id }}"
                                                                @if(request()->clientes_id == $cliente->id) selected="selected" @endif>
                                                                {{ $cliente->nome }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select class="select2" name="veiculos_id"  style="width: 100%">
                                                        <option value="">Veículo</option>
                                                        @foreach ($veiculos as $veiculo)
                                                            <option value="{{ $veiculo->id }}"
                                                                @if(request()->veiculos_id == $veiculo->id) selected="selected" @endif>
                                                                {{ $veiculo->placa }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select class="select2" name="fornecedores_id"  style="width: 100%">
                                                    <option value="">Fornecedor</option>
                                                        @foreach ($fornecedores as $fornecedor)
                                                            <option value="{{ $fornecedor->id }}"
                                                                @if(request()->fornecedores_id == $fornecedor->id) selected="selected" @endif>
                                                                {{ $fornecedor->descricao }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select class="select2" name="contas_id"  style="width: 100%">
                                                        <option value="">Conta</option>
                                                        @foreach ($contas as $conta)
                                                            <option value="{{ $conta->id }}"
                                                                @if(request()->contas_id == $conta->id) selected="selected" @endif>
                                                                {{ $conta->descricao }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="select2" name="fornecedores_id"  style="width: 100%">
                                                <option value="">Plano de conta</option>
                                                @foreach ($planos_conta as $plano_conta)
                                                    <option value="{{ $plano_conta->id }}"
                                                        @if(old('planos_contas_id') == $plano_conta->id) selected="selected" @endif>
                                                        {{ $plano_conta->descricao }}
                                                    </option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="select2" name="fornecedores_id"  style="width: 100%">
                                                <option value="">Forma de pagamento</option>
                                                @foreach ($formas_pagamento as $forma_pagamento)
                                                        <option value="{{ $forma_pagamento->id }}"
                                                            @if(old('formas_pagamento_id') == $forma_pagamento->id) selected="selected" @endif>
                                                            {{ $forma_pagamento->descricao }}
                                                        </option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                
                                                <input type="text" name="data_competencia" alt="date" value="{{ date('d/m/Y') }}" class="form-control">
                                                <label class="control-label"> 
                                                    {{-- tooltip --}}
                                                    <a class="mytooltip" href="javascript:void(0)"> 
                                                        <i class="mdi mdi-information-outline"></i>
                                                        <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                                        Data inicial das transações
                                                        </span></span></span>
                                                        </a>
                                                        {{-- end tooltip --}}
                                            </label>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group">
                                                
                                                <input type="text" name="data_competencia" alt="date" value="{{ date('d/m/Y') }}" class="form-control">
                                                <label class="control-label"> 
                                                    {{-- tooltip --}}
                                                    <a class="mytooltip" href="javascript:void(0)"> 
                                                        <i class="mdi mdi-information-outline"></i>
                                                        <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                                            Data final das transações
                                                        </span></span></span>
                                                        </a>
                                                        {{-- end tooltip --}}
                                            </label>
                                            </div>
                                        </div>

                                        </div>
                                    </form>

                                    <h6 class="card-subtitle">Resultados</h6>

                                    {{-- @dump($movimentacoes->toArray()) --}}


                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Data/hora</th>
                                                    <th>Tipo</th>
                                                    <th>Conta</th>
                                                    <th>Plano de conta</th>
                                                    <th>Descrição</th>
                                                    <th>Data vencimento</th>
                                                    <th>Valor</th>
                                                    <th>Data pagamento</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($movimentacoes as $conta)
                                                    <tr>
                                                        <td>{{ $conta->data_pagamento->format("d/m/Y H:s") }}</td>
                                                        <td>
                                                            @if ($conta instanceof \App\ContaPagar)
                                                                <span class="label label-danger"><-</span>
                                                            @else
                                                                <span class="label label-success">-></span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $conta->conta->descricao }}</td>
                                                        <td>{{ $conta->plano_conta->descricao }}</td>
                                                        <td>{{ $conta->descricao }}</td>
                                                        <td>03/02/2020</td>
                                                        <td>R$ 35,00</td>
                                                        <td>03/02/2020</td>
                                                    </tr>
                                                @endforeach



                                                {{-- <tr>
                                                    <td>03/03/2020 16:50</td>
                                                    <td><span class="label label-danger"><-</span></td>
                                                    <td>Caixa</td>
                                                    <td>Despesa manutenção de veículos</td>
                                                    <td>Pgto lavagem fiat uno HKO-8899</td>
                                                    <td>03/02/2020</td>
                                                    <td>R$ 35,00</td>
                                                    <td>03/02/2020</td>
                                                </tr>
                                                <tr>
                                                    <td>03/03/2020 16:50</td>
                                                    <td><span class="label label-success">-></span></td>
                                                    <td>Caixa</td>
                                                    <td>Receita de vendas de veículos</td>
                                                    <td>Venda veículo fiat uno HKO-8899 - Cliente: Kennedy Rafael</td>
                                                    <td>03/02/2020</td>
                                                    <td>R$ 35.000,00</td>
                                                    <td>03/02/2020</td>
                                                </tr>
                                                <tr>
                                                    <td>03/03/2020 16:50</td>
                                                    <td><span class="label label-success">-></span></td>
                                                    <td>Caixa</td>
                                                    <td>Receita de comissões de veículos</td>
                                                    <td>Comissão veículo ford ka HUI-9856 - Cliente: Max Stanley</td>
                                                    <td>03/02/2020</td>
                                                    <td>R$ 5.000,00</td>
                                                    <td>03/02/2020</td>
                                                </tr> --}}
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End PAge Content -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Right sidebar -->
                    <!-- ============================================================== -->
                    <!-- .right-sidebar -->

                    <!-- ============================================================== -->
                    <!-- End Right sidebar -->
                    <!-- ============================================================== -->
                     
@endsection