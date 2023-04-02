@extends('layouts/material')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Entrada de Veículo',
        'breadcrumb' => [
            'Veículos' => route('veiculos.index'),
            'Entrada',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('veiculos.store') }}" method="post">
                @csrf

                <div class="form-body">

                        <h3 class="card-title">Dados do veículo</h3>
                        <hr>

                        <div class="row">


                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Tipo entrada
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
                                                <option @if(old('tipo') == 'estoque') selected="selected" @endif value="estoque">Estoque</option>
                                                <option @if(old('tipo') == 'consignado') selected="selected" @endif value="consignado">Consignado</option>
                                            </select>
                                        </div>
                                </div>

                                <div class="col-md-4">
                                        <div class="form-group @error('modelos_id') has-danger @enderror">
                                            <label class="control-label">Modelo <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('modelos_id') form-control-danger @enderror" name="modelos_id" style="width: 100%">
                                                   <option value="">Selecione o modelo</option>

                                                   @foreach ($marcas as $marca)
                                                    <optgroup label="{{ $marca->descricao }}">
                                                        @foreach ($marca->modelos as $modelo)
                                                            <option value="{{ $modelo->id }}">
                                                                {{ $modelo->descricao }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                    @endforeach

                                                    {{--@foreach ($modelos as $modelo)
                                                        <option value="{{ $modelo->id }}"
                                                            @if(old('modelos_id') == $modelo->id) selected="selected" @endif>
                                                            {{ $modelo->descricao }}
                                                        </option>
                                                    @endforeach--}}
                                                   </select>
                                                   @error('modelos_id')
                                                        <div class="form-control-feedback">{{ $message }}</div>
                                                   @enderror

                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group @error('cores_id') has-danger @enderror">
                                            <label class="control-label">Cor <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('cores_id') form-control-danger @enderror" name="cores_id"  style="width: 100%">
                                                    <option value="">Selecione a cor do veículo</option>
                                                    @foreach ($cores as $cor)
                                                        <option value="{{ $cor->id }}"
                                                            @if(old('cores_id') == $cor->id) selected="selected" @endif>
                                                            {{ $cor->descricao }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('cores_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group @error('categorias_id') has-danger @enderror">
                                            <label class="control-label">Categoria <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('categorias_id') form-control-danger @enderror" name="categorias_id"  style="width: 100%">
                                                    <option value="">Selecione a categoria do veículo</option>
                                                    @foreach ($categorias as $categoria)
                                                        <option value="{{ $categoria->id }}"
                                                            @if(old('categorias_id') == $categoria->id) selected="selected" @endif>
                                                            {{ $categoria->descricao }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('categorias_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>

                        </div>

                        <div class="row">

                                <div class="col-md-3">
                                        <div class="form-group @error('cambios_id') has-danger @enderror">
                                            <label class="control-label">Câmbio <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('cambios_id') form-control-danger @enderror" name="cambios_id"  style="width: 100%">
                                                    <option value="">Selecione o câmbio do veículo</option>
                                                    @foreach ($cambios as $cambio)
                                                        <option value="{{ $cambio->id }}"
                                                            @if(old('cambios_id') == $cambio->id) selected="selected" @endif>
                                                            {{ $cambio->descricao }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('cambios_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group @error('tracoes_id') has-danger @enderror">
                                            <label class="control-label">Tração <span class="text-danger">*</span></label>
                                                   <select class="select2 @error('tracoes_id') form-control-danger @enderror" name="tracoes_id"  style="width: 100%">
                                                    <option value="">Selecione a tração do veículo</option>
                                                    @foreach ($tracoes as $tracao)
                                                        <option value="{{ $tracao->id }}"
                                                            @if(old('tracoes_id') == $tracao->id) selected="selected" @endif>
                                                            {{ $tracao->descricao }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('tracoes_id')
                                                   <div class="form-control-feedback">{{ $message }}</div>
                                               @enderror

                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Placa
                                            </label>
                                            <input type="text" name="placa" value="{{ old('placa') }}" class="form-control">
                                        </div>
                                </div>

                        </div>

                        <div class="row">

                                <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Opcionais
                                            {{-- tooltip --}}
                                            <a class="mytooltip" href="javascript:void(0)">
                                            <i class="mdi mdi-information-outline"></i>
                                            <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                                Caso veículo possua opcionais como: "kit multimídia", "rodas personalizadas", etc...
                                            </span></span></span>
                                            </a>
                                            {{-- end tooltip --}}
                                            </label>
                                            <input type="text" name="opcionais" value="{{ old('opcionais') }}" class="form-control">
                                        </div>
                                </div>

                        </div>



                        <h3 class="card-title">Dados para venda</h3>
                        <hr>

                        <div class="row">

                                <div class="col-md-2">
                                        <div class="form-group @if($errors->has('km')) has-danger @endif">
                                            <label class="control-label">Km atual <span class="text-danger">*</span></label>
                                            <input type="text" name="km" alt="integer" value="{{ old('km') }}" class="form-control @if($errors->has('km')) form-control-danger @endif">
                                            @if($errors->has('km'))
                                                <div class="form-control-feedback">{{ $errors->first('km') }}</div>
                                            @endif
                                        </div>
                                </div>

                                <div class="col-md-2">
                                        <div class="form-group @if($errors->has('data_entrada')) has-danger @endif">
                                            <label class="control-label">Data entrada <span class="text-danger">*</span></label>
                                            <input type="text" name="data_entrada" alt="date" value="{{ old('data_entrada') }}" class="form-control @if($errors->has('data_entrada')) form-control-danger @endif">
                                            @if($errors->has('data_entrada'))
                                                <div class="form-control-feedback">{{ $errors->first('data_entrada') }}</div>
                                            @endif
                                        </div>
                                </div>


                                <div class="col-md-2">
                                        <div class="form-group @if($errors->has('valor_compra')) has-danger @endif">
                                            <label class="control-label">Valor da compra <span class="text-danger">*</span>
                                                {{-- tooltip --}}
                                                <a class="mytooltip" href="javascript:void(0)">
                                                <i class="mdi mdi-information-outline"></i>
                                                <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                                    Caso seja veículo de estoque
                                                </span></span></span>
                                                </a>
                                                {{-- end tooltip --}}
                                            </label>
                                            <input type="text" name="valor_compra" alt="money" value="{{ old('valor_compra') }}" class="form-control @if($errors->has('valor_compra')) form-control-danger @endif">
                                            @if($errors->has('valor_compra'))
                                                <div class="form-control-feedback">{{ $errors->first('valor_compra') }}</div>
                                            @endif
                                        </div>
                                </div>

                                <div class="col-md-2">
                                        <div class="form-group @if($errors->has('valor_fipe')) has-danger @endif">
                                            <label class="control-label">Valor tabela fipe <span class="text-danger">*</span></label>
                                            <input type="text" name="valor_fipe" alt="money" value="{{ old('valor_fipe') }}" class="form-control @if($errors->has('valor_fipe')) form-control-danger @endif">
                                            @if($errors->has('valor_fipe'))
                                                <div class="form-control-feedback">{{ $errors->first('valor_fipe') }}</div>
                                            @endif
                                        </div>
                                </div>

                                <div class="col-md-4">
                                        <div class="form-group @if($errors->has('valor_sugerido')) has-danger @endif">
                                            <label class="control-label">Valor sugerido para venda <span class="text-danger">*</span></label>
                                            <input type="text" name="valor_sugerido" alt="money" value="{{ old('valor_sugerido') }}" class="form-control @if($errors->has('valor_sugerido')) form-control-danger @endif">
                                            @if($errors->has('valor_sugerido'))
                                                <div class="form-control-feedback">{{ $errors->first('valor_sugerido') }}</div>
                                            @endif
                                        </div>
                                </div>

                        </div>

                        <h3 class="card-title">Dados cliente/fornecedor
                            {{-- tooltip --}}
                            <a class="mytooltip" href="javascript:void(0)">
                            <i class="mdi mdi-information-outline"></i>
                            <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                Selecionar proprietário do veículo disponibilizado
                            </span></span></span>
                            </a>
                            {{-- end tooltip --}}
                        </h3>
                        <hr>

                        <div class="row">

                                <div class="col-md-6">
                                        <div class="form-group @if($errors->has('clientes_id')) has-danger @endif">
                                            <label class="control-label">Cliente/fornecedor <span class="text-danger">*</span></label>
                                                   <select class="select2 @if($errors->has('clientes_id')) form-control-danger @endif" name="clientes_id"  style="width: 100%">
                                                    <option value="">Selecione a cliente/fornecedor</option>
                                                    @foreach ($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}"
                                                            @if(old('clientes_id') == $cliente->id) selected="selected" @endif>
                                                            {{ $cliente->nome }}
                                                        </option>
                                                    @endforeach
                                                   </select>
                                                   @error('clientes_id')
                                                   <div class="form-control-feedback">{{ $errors->first('clientes_id') }}</div>
                                               @enderror

                                        </div>
                                </div>



                        </div>



                        <h3 class="card-title">Adicionais</h3>
                        <hr>

                        <div class="row">

                                <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Observações:</label>
                                            <textarea name="obs" cols="30" class="form-control">{{ old('obs') }}</textarea>
                                        </div>
                                </div>

                        </div>

                        <h3 class="card-title">Dados do pagamento</h3>
                        <hr>

                        <div class="row">
                                <div class="col-md-3">
                                        <div class="form-group @if($errors->has('valor_integral')) has-danger @endif">
                                            <label class="control-label">Valor integral da compra R$ <span class="text-danger">*</span></label>
                                            <input type="text" name="valor_integral" alt="money" value="{{ old('valor_integral') }}" class="form-control @if($errors->has('valor_integral')) form-control-danger @endif">
                                            @if($errors->has('valor_integral'))
                                                <div class="form-control-feedback">{{ $errors->first('valor_integral') }}</div>
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
                                                                    <input type="text" name="valor_pagamento_id[]" alt="money" value="" class="form-control">
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
                                                                    <input type="text" name="data_pagamento[]" alt="date" value="{{ old('km') }}" class="form-control">

                                                                </div>
                                                        </div>

                                                        <div class="col-md-1">
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
