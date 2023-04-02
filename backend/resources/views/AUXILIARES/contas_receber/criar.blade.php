@extends('layouts/material')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Nova conta a receber',
        'breadcrumb' => [
            'Contas a receber' => route('contas_receber.index'),
            'Nova conta a receber',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('contas_receber.store') }}" method="post">
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
                                                    @if(old('contas_id') == $conta->id) selected="selected" @endif>
                                                    {{ $conta->descricao }}
                                                </option>
                                            @endforeach
                                           </select>
                                           @error('contas_id')
                                           <div class="form-control-feedback">{{ $message }}</div>
                                       @enderror

                                </div>
                            </div>


                            <div class="col-md-8">
                                <div class="form-group @error('planos_contas_id') has-danger @enderror">
                                    <label class="control-label">Plano de contas <span class="text-danger">*</span></label>
                                           <select class="select2 @error('planos_contas_id') form-control-danger @enderror" name="planos_contas_id"  style="width: 100%">
                                            <option value="">Selecione um plano de conta</option>
                                            @foreach ($planos_conta as $plano_conta)
                                                <option value="{{ $plano_conta->id }}"
                                                    @if(old('planos_contas_id') == $plano_conta->id) selected="selected" @endif>
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
                                <div class="form-group">
                                    <label class="control-label">Tipo de receita
                                    <span class="text-danger">*</span>
                                    {{-- tooltip --}}
                                    <a class="mytooltip" href="javascript:void(0)">
                                    <i class="mdi mdi-information-outline"></i>
                                    <span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">
                                        Vinculada a um cliente: para associar a receita a um cliente<br /> Avulsa: conta avulsa sem vinculação a cliente
                                    </span></span></span>
                                    </a>
                                    {{-- end tooltip --}}
                                    </label>
                                    <select name="tipo" class="form-control" data-placeholder="Selecione" tabindex="1">
                                        <option @if(old('tipo') == 'cliente') selected="selected" @endif value="cliente">Vinculada a um cliente</option>
                                        <option @if(old('tipo') == 'avulsa') selected="selected" @endif value="avulsa">Avulsa</option>
                                    </select>
                                </div>
                           </div>

                           <div class="col-md-8">
                            <div class="form-group @error('clientes_id') has-danger @enderror">
                                <label class="control-label">Cliente</label>
                                       <select class="select2 @error('clientes_id') form-control-danger @enderror" name="clientes_id"  style="width: 100%">
                                        <option value="">Selecione um cliente</option>
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
                                <input type="text" name="data_competencia" alt="date" value="{{ old('data_competencia') }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Nº do documento
                                </label>
                                <input type="text" name="num_documento" value="{{ old('num_documento') }}" class="form-control">
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Descrição
                                </label>
                                <input type="descricao" name="descricao" value="{{ old('descricao') }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Situação
                                <span class="text-danger">*</span>
                                </label>
                                <select name="status" id="status" class="form-control" data-placeholder="Selecione" tabindex="1" onchange="verifica_situacao_conta()">
                                    <option @if(old('status') == 'aberta') selected="selected" @endif value="aberta">A receber</option>
                                    <option @if(old('status') == 'quitada') selected="selected" @endif value="quitada">Recebida</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Data de vencimento <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="data_vencimento" alt="date" value="{{ old('data_vencimento') }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">valor <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="valor_pagar" alt="money" value="{{ old('valor_pagar') }}" class="form-control">
                            </div>
                        </div>



                    </div>




                        <div id="info_pgto" style="display:none">

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
                                                        @if(old('formas_pagamento_id') == $forma_pagamento->id) selected="selected" @endif>
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
                                        <input type="text" name="data_pagamento" alt="date" value="{{ old('data_pagamento') }}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Valor do pagamento <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="valor_pagamento" alt="money" value="{{ old('valor_pagamento') }}" class="form-control">
                                    </div>
                                </div>


                            </div>

                        </div>


                        <div id="info_cheque" style="display:none">

                           <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Banco <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="banco_cheque" value="{{ old('banco_cheque') }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Nº do cheque <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="num_cheque" value="{{ old('num_cheque') }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Titular <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="titular_cheque" value="{{ old('titular_cheque') }}" class="form-control">
                                </div>
                            </div>

                           </div>



                        </div>




                </div>




                <div class="form-group text-right">
                         <a href="{{ route('contas_receber.index') }}">
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
