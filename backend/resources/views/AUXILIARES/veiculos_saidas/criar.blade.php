@extends('layouts/material')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Saída de Veículo',
        'breadcrumb' => [
            'Veículos' => route('veiculos_saidas.index'),
            'Saída',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('veiculos.store') }}" method="post">
                @csrf

                <div class="form-body">

                        <h3 class="card-title">Dados da saída</h3>
                        <hr>

                        <div class="row">


                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Tipo de saída
                                            {{-- tooltip --}}
                                            <a class="mytooltip" href="javascript:void(0)">
                                            <i class="mdi mdi-information-outline"></i>
                                            <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                                Estoque: veículo adquirido pela empresa<br /> Consignado: veículo de cliente disponibilizado para venda
                                            </span></span></span>
                                            </a>
                                            {{-- end tooltip --}}
                                            </label>
                                            <select name="tipo" class="form-control" data-placeholder="Selecione" tabindex="1">
                                                <option @if(old('tipo') == 'vendido') selected="selected" @endif value="vendido">Venda</option>
                                                <option @if(old('tipo') == 'troca') selected="selected" @endif value="troca">Troca</option>
                                                <option @if(old('tipo') == 'devolvido') selected="selected" @endif value="devolvido">Devolução (p/ veículo consignado)</option>
                                            </select>
                                        </div>
                                </div>


                                <div class="col-md-3">
                                        <div class="form-group @error('veiculos_id') has-danger @enderror">
                                            <label class="control-label">Veículo <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('veiculos_id') form-control-danger @enderror" name="veiculos_id"  style="width: 100%">
                                                    <option value="">Selecione o veículo</option>
                                                    @foreach ($veiculos as $veiculo)
                                                        <option value="{{ $veiculo->id }}"
                                                            @if(old('veiculos_id') == $veiculo->id) selected="selected" @endif>
                                                            {{ $veiculo->modelo->descricao.' - '.$veiculo->placa }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('veiculos_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>

                                <div class="col-md-2">
                                        <div class="form-group @if($errors->has('km')) has-danger @endif">
                                            <label class="control-label">Data da saída <span class="text-danger">*</span></label>
                                            <input type="text" name="km" value="{{ old('km') }}" class="form-control @if($errors->has('km')) form-control-danger @endif">
                                            @if($errors->has('km'))
                                                <div class="form-control-feedback">{{ $errors->first('km') }}</div>
                                            @endif
                                        </div>
                                </div>

                                <div class="col-md-4">
                                        <div class="form-group @error('clientes_id') has-danger @enderror">
                                            <label class="control-label">Cliente <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('clientes_id') form-control-danger @enderror" name="clientes_id"  style="width: 100%">
                                                    <option value="">Selecione o cliente</option>
                                                    @foreach ($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}"
                                                            @if(old('clientes_id') == $cliente->id) selected="selected" @endif>
                                                            {{ $cliente->nome }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('clientes_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>



                        </div>

                        <h3 class="card-title">Dados do pagamento</h3>
                        <hr>

                        <div class="row">
                                <div class="col-md-3">
                                        <div class="form-group @if($errors->has('km')) has-danger @endif">
                                            <label class="control-label">Valor integral da venda R$ <span class="text-danger">*</span></label>
                                            <input type="text" name="km" value="{{ old('km') }}" class="form-control @if($errors->has('km')) form-control-danger @endif">
                                            @if($errors->has('km'))
                                                <div class="form-control-feedback">{{ $errors->first('km') }}</div>
                                            @endif
                                        </div>
                                </div>
                        </div>



                        <div class="row lancamento_contas_receber">

                                <div class="card-body p-b-0">
                                        {{-- <h4 class="card-title">Customtab Tab</h4> --}}
                                        <h6 class="card-subtitle">Utilize o formulário abaixo para adicionar parcelas e forma de pagamento</h6> </div>

                                <!-- hidden para contarmos as parcelas -->
                                <input type="hidden" id="qtd_parcelas" value="1">

                                <div class="col-md-12">

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs customtab" id="tab_parcelas" role="tablist">
                                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#parcela1" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Parcela 1</span></a> </li>
                                        {{-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#parcela2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Parcela 2</span></a> </li> --}}
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content" id="div_parcelas">

                                        <div class="tab-pane active" id="parcela1" role="tabpanel" style="padding-top:20px;">

                                                <div class="row">

                                                        <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="control-label">Valor <span class="text-danger">*</span></label>
                                                                    <input type="text" name="valor_pagamento_id[]" value="" class="form-control">
                                                                </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Conta caixa <span class="text-danger">*</span></label>
                                                                           <select class="form-control" name="contas_pagamento_id[]"  style="width: 100%">
                                                                            @foreach ($contas as $conta)
                                                                                <option value="{{ $conta->id }}">
                                                                                    {{ $conta->descricao }}
                                                                                </option>
                                                                            @endforeach
                                                                           </select>

                                                                </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">Plano de conta <span class="text-danger">*</span></label>
                                                                           <select class="form-control" name="planos_contas_pagamento_id[]"  style="width: 100%">

                                                                            @foreach ($planos_contas as $plano_conta)
                                                                                <option value="{{ $plano_conta->id }}">
                                                                                    {{ $plano_conta->descricao }}
                                                                                </option>
                                                                            @endforeach
                                                                           </select>

                                                                </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Forma de pagamento <span class="text-danger">*</span></label>
                                                                           <select class="form-control" name="formas_pagamento_id[]"  style="width: 100%">

                                                                            @foreach ($formas_pagamento as $forma_pagamento)
                                                                                <option value="{{ $forma_pagamento->id }}">
                                                                                    {{ $forma_pagamento->descricao }}
                                                                                </option>
                                                                            @endforeach
                                                                           </select>


                                                                </div>
                                                        </div>


                                                </div>

                                                <div class="row">

                                                        <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label class="control-label">Data <span class="text-danger">*</span></label>
                                                                    <input type="text" name="data_pagamento[]" value="{{ old('km') }}" class="form-control">

                                                                </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                                <div class="form-group demo-checkbox">
                                                                        <label class="control-label">Status
                                                                        {{-- tooltip --}}
                                                                        <a class="mytooltip" href="javascript:void(0)">
                                                                        <i class="mdi mdi-information-outline"></i>
                                                                        <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                                                            Marcado para contas recebidas, <br /> Desmarcado para contas a receber
                                                                        </span></span></span>
                                                                        </a>
                                                                        {{-- end tooltip --}}
                                                                        </label>
                                                                        <input type="checkbox" id="basic_checkbox_2" name="status_pgto[]" class="filled-in" checked="">
                                                                        <label for="basic_checkbox_2">Recebido</label>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-2">

                                                            <div class="form-group">
                                                                <label class="control-label">&nbsp;</label>
                                                                <a href="{{ route('veiculos.create') }}" target="_blank">
                                                                <button type="button" onclick="javascript:void()" class="btn waves-effect waves-light btn-block btn-info">+ Vincular veículo</button>
                                                                </a>
                                                            </div>
                                                    </div>



                                                </div>

                                        </div>




                                    </div>

                                </div>


                        </div>

                        <hr>

                        <div class="row lancamento_contas_receber">

                                <div class="col-md-2">
                                        <button type="button" onclick="addParcela()" class="btn waves-effect waves-light btn-block btn-info">+ Adicionar parcela</button>
                                </div>

                        </div>









                </div>




                <div class="form-group text-right">
                         <a href="{{ route('veiculos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push("scripts")

<script>

    function addParcela(){

        var qtd_parcelas = $('#qtd_parcelas').val();

        qtd_parcelas = parseInt(qtd_parcelas) + 1;

        var li_tab = '<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parcela'+qtd_parcelas+'" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Parcela '+qtd_parcelas+'</span></a> </li>';

        var div_tab = $('#parcela1').html();

       // alert(div_tab);

        var div_tab = '<div class="tab-pane" style="padding-top:20px;" id="parcela'+qtd_parcelas+'" role="tabpanel">'+div_tab+'<div class="row"><div class="col-md-5"><div class="form-group"><label class="control-label">&nbsp;</span></label><button type="button" class="btn waves-effect waves-light btn-block btn-danger">- Remover parcela</button></div></div></div> </div>';

        // alert(div_tab);
        // debugger

        $('#tab_parcelas').append(li_tab);

        $('#div_parcelas').append(div_tab);

        $('#qtd_parcelas').val(qtd_parcelas);

    }

    function exibe_veiculo(){
        $.ajax({
            url: 'http://betar2.test/veiculos/1',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                alert(data.km)
            }
        })
    }

</script>

@endpush
