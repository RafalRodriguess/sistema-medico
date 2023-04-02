
@extends('layouts/material')

@section('conteudo')
@component('components/page-title', [
    'titulo' => "Editar Conta a Pagar #{$contasPagar->id}",
    'breadcrumb' => [
        'Contas a pagar' => route('contas_pagar.index'),
        'Editar Conta a Pagar',
    ],
])
@endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('contas_pagar.update', [$contasPagar]) }}" method="post">
                @method('put')
                @csrf


                <div class="form-body">

                        <div class="row">


                            <div class="col-md-4">
                                <div class="form-group @error('contas_id') has-danger @enderror">
                                    <label class="control-label">Conta caixa <span class="text-danger">*</span></label>
                                           <select class="select2 @error('contas_id') form-control-danger @enderror" name="contas_id"  style="width: 100%">
                                            <option value="">Selecione uma conta</option>
                                            @foreach ($contas as $conta)
                                                <option value="{{ $conta->id }}"
                                                    @if(old('contas_id', $contasPagar->contas_id) == $conta->id) selected="selected" @endif>
                                                    {{ $conta->descricao }}
                                                </option>
                                            @endforeach
                                           </select>
                                           @error('contas_id')
                                           <div class="form-control-feedback">{{ $message }}</div>
                                       @enderror

                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group @error('planos_contas_id') has-danger @enderror">
                                    <label class="control-label">Plano de contas <span class="text-danger">*</span></label>
                                           <select class="select2 @error('planos_contas_id') form-control-danger @enderror" name="planos_contas_id"  style="width: 100%">
                                            <option value="">Selecione um plano de conta</option>
                                            @foreach ($planos_conta as $plano_conta)
                                                <option value="{{ $plano_conta->id }}"
                                                    @if(old('planos_contas_id', $contasPagar->planos_contas_id) == $plano_conta->id) selected="selected" @endif>
                                                    {{ $plano_conta->descricao }}
                                                </option>
                                            @endforeach
                                           </select>
                                           @error('planos_contas_id')
                                           <div class="form-control-feedback">{{ $message }}</div>
                                       @enderror

                                </div>
                            </div>



                           <div class="col-md-4">
                            <div class="form-group @error('fornecedores_id') has-danger @enderror">
                                <label class="control-label">Fornecedor</label>
                                       <select class="select2 @error('fornecedores_id') form-control-danger @enderror" name="fornecedores_id"  style="width: 100%">
                                        <option value="">Selecione um fornecedor</option>
                                        @foreach ($fornecedores as $fornecedor)
                                            <option value="{{ $fornecedor->id }}"
                                                @if(old('fornecedores_id', $contasPagar->fornecedores_id) == $fornecedor->id) selected="selected" @endif>
                                                {{ $fornecedor->descricao }}
                                            </option>
                                        @endforeach
                                       </select>
                                       @error('fornecedores_id')
                                       <div class="form-control-feedback">{{ $message }}</div>
                                   @enderror

                            </div>
                           </div>


                           <div class="col-md-6">
                            <div class="form-group @error('veiculos_id') has-danger @enderror">
                                <label class="control-label">Vincular Veículo</label>
                                       <select class="select2 @error('veiculos_id') form-control-danger @enderror" name="veiculos_id"  style="width: 100%">
                                        <option value="">Selecione um veículo</option>
                                        @foreach ($veiculos as $veiculo)
                                            <option value="{{ $veiculo->id }}"
                                                @if(old('veiculos_id', $contasPagar->veiculos_id) == $veiculo->id) selected="selected" @endif>
                                                {{ $veiculo->placa }}
                                            </option>
                                        @endforeach
                                       </select>
                                       @error('veiculos_id')
                                       <div class="form-control-feedback">{{ $message }}</div>
                                   @enderror

                            </div>
                           </div>

                           <div class="col-md-6">
                            <div class="form-group @error('clientes_id') has-danger @enderror">
                                <label class="control-label">Vincular Cliente</label>
                                       <select class="select2 @error('clientes_id') form-control-danger @enderror" name="clientes_id"  style="width: 100%">
                                        <option value="">Selecione um cliente</option>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}"
                                                @if(old('clientes_id', $contasPagar->clientes_id) == $cliente->id) selected="selected" @endif>
                                                {{ $cliente->nome }}
                                            </option>
                                        @endforeach
                                       </select>
                                       @error('clientes_id')
                                       <div class="form-control-feedback">{{ $message }}</div>
                                   @enderror

                            </div>
                           </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Data emissão
                                <span class="text-danger">*</span>
                                {{-- tooltip --}}
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="mdi mdi-information-outline"></i>
                                    <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                       Data em que o serviço foi prestado
                                    </span></span></span>
                                    </a>
                                    {{-- end tooltip --}}
                                </label>
                                <input type="text" name="data_competencia" alt="date" value="{{ old('data_competencia', $contasPagar->data_competencia->format("d/m/Y")) }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Nº do documento
                                </label>
                                <input type="text" name="num_documento" value="{{ old('num_documento', $contasPagar->num_documento) }}" class="form-control">
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Descrição
                                </label>
                                <input type="descricao" name="descricao" value="{{ old('descricao', $contasPagar->descricao) }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Situação
                                <span class="text-danger">*</span>
                                </label>
                                <select name="status" id="status" class="form-control" data-placeholder="Selecione" tabindex="1" onchange="verifica_situacao_conta()">
                                    <option @if(old('status', $contasPagar->status) == 'aberta') selected="selected" @endif value="aberta">A receber</option>
                                    <option @if(old('status', $contasPagar->status) == 'quitada') selected="selected" @endif value="quitada">Recebida</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Data de vencimento <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="data_vencimento" alt="date" value="{{ old('data_vencimento', $contasPagar->data_vencimento->format("d/m/Y")) }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">valor <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="valor_pagar" alt="money" value="{{ old('valor_pagar', $contasPagar->valor_pagar) }}" class="form-control">
                            </div>
                        </div>



                    </div>




                        <div id="info_pgto" style="{{ ($contasPagar->status == 'aberta') ? 'display:none' : '' }}">

                            <h3 class="card-title">Dados do pagamento
                            </h3>
                            <hr>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group @error('formas_pagamento_id') has-danger @enderror">
                                        <label class="control-label">Forma de pagamento</label>
                                               <select class="select2 @error('formas_pagamento_id') form-control-danger @enderror" name="formas_pagamento_id" id="formas_pagamento_id"  style="width: 100%" onchange="verifica_info_cheque()">
                                                @foreach ($formas_pagamento as $forma_pagamento)
                                                    <option value="{{ $forma_pagamento->id }}"
                                                        @if(old('formas_pagamento_id', $contasPagar->formas_pagamento_id) == $forma_pagamento->id) selected="selected" @endif>
                                                        {{ $forma_pagamento->descricao }}
                                                    </option>
                                                @endforeach
                                               </select>
                                               @error('formas_pagamento_id')
                                               <div class="form-control-feedback">{{ $message }}</div>
                                           @enderror

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Data do pagamento <span class="text-danger">*</span>
                                        </label>
                                        @php
                                        if(!empty($contasPagar->data_pagamento)):
                                        $data_pgto = $contasPagar->data_pagamento->format("d/m/Y");
                                        else:
                                        $data_pgto = '';
                                        endif;
                                        @endphp
                                        <input type="text" name="data_pagamento" alt="date" value="{{ old('data_pagamento', $data_pgto) }}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Valor do pagamento <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="valor_pagamento" alt="money" value="{{ old('valor_pagamento', $contasPagar->valor_pagamento) }}" class="form-control">
                                    </div>
                                </div>


                            </div>

                        </div>


                        <div id="info_cheque" style="{{ (!empty($contasPagar->formas_pagamento_id && $contasPagar->formas_pagamento_id == 3) ? '' : 'display:none') }}">

                           <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Banco <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="banco_cheque" value="{{ old('banco_cheque', $contasPagar->banco_cheque) }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Nº do cheque <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="num_cheque" value="{{ old('num_cheque', $contasPagar->num_cheque) }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Titular <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="titular_cheque" value="{{ old('titular_cheque', $contasPagar->titular_cheque) }}" class="form-control">
                                </div>
                            </div>

                           </div>



                        </div>




                </div>




                <div class="form-group text-right">
                         <a href="{{ route('contas_pagar.index') }}">
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

function verifica_situacao_conta(){

    if($('#status').val() == 'quitada'){
        $('#info_pgto').show();
    }else{
        $('#info_pgto').hide();
    }

}

function verifica_info_cheque(){

    if($('#formas_pagamento_id').val() == 3){
        $('#info_cheque').show();
    }else{
        $('#info_cheque').hide();
    }

}



</script>

@endpush
