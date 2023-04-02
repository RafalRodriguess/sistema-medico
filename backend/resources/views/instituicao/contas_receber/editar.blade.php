@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Conta a Receber #{$contaReceber->id}",
        'breadcrumb' => [
            'Contas a Receber' => route('instituicao.contasReceber.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.contasReceber.update', [$contaReceber]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class="form-group col-md-4 @if($errors->has('conta_id')) has-danger @endif">
                        <label class="form-control-label">Conta caixa: *</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Esta é a conta onde será debitada o dinheiro, se for pagamento na empresa por dinheiro 'Conta Caixa' e na conta no banco utilizando cheque ou pagamento online 'Conta Banco XX..."></i>
                        </label>
                        <select name="conta_id" class="form-control selectfild2 @if($errors->has('conta_id')) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione um caixa</option>
                            @foreach ($contas as $item)
                                <option value="{{$item->id}}" @if (old('conta_id', $contaReceber->conta_id) == $item->id)
                                    selected="selected"
                                @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('conta_id'))
                            <div class="form-control-feedback">{{ $errors->first('conta_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 @if($errors->has('plano_conta_id')) has-danger @endif">
                        <label class="form-control-label">Plano de conta: *</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Este é o filtro por tipo de pagamento, que deve ser escolhido o plano exato que se associa ao pagamento"></i>
                        </label>
                        <select name="plano_conta_id" class="form-control selectfild2 @if($errors->has('plano_conta_id')) form-control-danger @endif" style="width: 100%">
                            <option value="">Selecione um plano de conta</option>
                            @foreach ($planosConta as $item)
                                <option value="{{$item->id}}" @if (old('plano_conta_id', $contaReceber->plano_conta_id) == $item->id)
                                    selected="selected"
                                @endif>{{$item->codigo}} - {{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('plano_conta_id'))
                            <div class="form-control-feedback">{{ $errors->first('plano_conta_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-4 @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</span>
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Deve ser escolhido o fornecedor do serviço ou venda, caso não seja cadastrado, cadastrar o mesmo antes de inserir a conta"></i>
                        </label>
                        <input name="tipo" style="display: none" id="tipo" value="{{$contaReceber->tipo}}">
                        <select name="tipo_nome" class="form-control @if($errors->has('tipo')) form-control-danger @endif" style="width: 100%" id="tipo_nome" disabled>
                            @foreach ($tipos as $item)
                                <option value="{{$item}}" @if (old('tipo', $contaReceber->tipo) == $item)
                                    selected="selected"
                                @endif>{{App\contaReceber::tipos_texto_all($item)}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                            <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 paciente @if($errors->has('pessoa_id')) has-danger @endif" style="display: block">
                        <label class="form-control-label">Pacientes: *</label>
                        <input name="cliente_id" style="display: none" id="pessoa_id" value="{{$contaReceber->pessoa_id}}">
                        <input name="pessoa_nome" disabled class="form-control @if($errors->has('pessoa_id')) form-control-danger @endif" style="width: 100%" id="pessoa_nome" value="{{!empty($paciente) ? $paciente->nome : "Paciente avulso"}}">

                        @if($errors->has('pessoa_id'))
                            <div class="form-control-feedback">{{ $errors->first('pessoa_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 convenio @if($errors->has('convenio_id')) has-danger @endif" style="display: none">
                        <label class="form-control-label">Convênios: *</label>
                        <select name="convenio_id" class="form-control selectfild2 @if($errors->has('convenio_id')) form-control-danger @endif" style="width: 100%" id="convenio_id" readonly>
                            <option value="">Selecione um convênio</option>
                            @foreach ($convenios as $item)
                                <option value="{{$item->id}}" @if (old('convenio_id', $contaReceber->convenio_id) == $item->id)
                                    selected="selected" @endif >{{$item->nome}}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('convenio_id'))
                            <div class="form-control-feedback">{{ $errors->first('convenio_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-4 @if($errors->has('data_compensacao')) has-danger @endif">
                        <label class="form-control-label">Data compensação: *
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que a compra ou despesa ocorreu"></i>
                        </label>
                        <input type="date" name="data_compensacao" class="form-control @if ($errors->has('data_compensacao')) form-control-danger @endif" value="{{old('data_compensacao', $contaReceber->data_compensacao)}}">
                        @if ($errors->has('data_compensacao'))
                            <div class="form-control-feedback">{{$errors->first('data_compensacao')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-5">
                        <label class="form-control-label">Descrição:
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Caso queira detalhar melhor o seu pagamento"></i>
                        </label>
                        <input type="text" name="descricao" class="form-control" value="{{old('descricao', $contaReceber->descricao)}}">
                    </div>



                    <div class="form-group col-md-3">
                        <label class="form-control-label">Nº documento:
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Caso tenha número do boleto, número da nota, etc..."></i>
                        </label>
                        <input type="text" name="num_documento" class="form-control" value="{{old('num_documento', $contaReceber->num_documento)}}">
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('data_vencimento')) has-danger @endif">
                        <label class="form-control-label">Data vencimento: *
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i>
                        </label>
                        <input type="date" name="data_vencimento" id="data_vencimento" class="form-control @if ($errors->has('data_vencimento')) form-control-danger @endif" value="{{old('data_vencimento', $contaReceber->data_vencimento)}}">
                        @if ($errors->has('data_vencimento'))
                            <div class="form-control-feedback">{{$errors->first('data_vencimento')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('valor_parcela')) has-danger @endif">
                        <label class="form-control-label">Valor: *
                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i>
                        </label>
                        <input type="text" alt="decimal" name="valor_parcela" id="valor_parcela" class="form-control @if ($errors->has('valor_parcela')) form-control-danger @endif" value="{{old('valor_parcela', $contaReceber->valor_parcela)}}">
                        @if ($errors->has('valor_parcela'))
                            <div class="form-control-feedback">{{$errors->first('valor_parcela')}}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('forma_pagamento')) has-danger @endif">
                        <label class="form-control-label">Forma de pagamento: *</label>
                        <select class="form-control select_parcela selectfild2 @if($errors->has('forma_pagamento')) form-control-danger @endif" name="forma_pagamento" id="forma_pagamento" style="width: 100%">
                            @foreach ($formas_pagamento as $forma_pagamento)
                                <option value="{{$forma_pagamento}}" @if (old('forma_pagamento', $contaReceber->forma_pagamento) == $forma_pagamento)
                                    selected="selected"
                                @endif>{{ App\ContaReceber::forma_pagamento_texto($forma_pagamento)}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('forma_pagamento'))
                            <div class="form-control-feedback">{{$errors->first('forma_pagamento')}}</div>
                        @endif
                    </div>

                    {{-- <div class="form-group col-md-3 @if($errors->has('forma_recebimento_id')) has-danger @endif">
                        <label class="form-control-label">Forma de recebimento: *</label>
                        <select class="form-control select_parcela selectfild2 @if($errors->has('forma_recebimento_id')) form-control-danger @endif" name="forma_recebimento_id" id="forma_recebimento_id" style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach ($formasRecebimento as $item)
                                <option value="{{$item->id}}"@if (old('forma_recebimento_id', $contaReceber->forma_recebimento_id) == $item->id)
                                    selected="selected"
                                @endif>{{ $item->forma_recebimento }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('forma_recebimento_id'))
                            <div class="form-control-feedback">{{$errors->first('forma_recebimento_id')}}</div>
                        @endif
                    </div> --}}

                    <div class="cheque_data col-md-12" style="display: none">
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('titular')) has-danger @endif">
                                <label class="form-control-label">Titular</label>
                                <input class="form-control" type="text" name="titular" id="titular" value="{{old('titular', $contaReceber->titular)}}">
                                @if($errors->has('titular'))
                                    <div class="form-control-feedback">{{ $errors->first('titular') }}</div>
                                @endif
                            </div>

                            <div class="col-md-3 form-group @if($errors->has('banco')) has-danger @endif">
                                <label for="form-control-label">Banco</label>
                                <input class="form-control" type="text" name="banco" id="banco" value="{{old('banco', $contaReceber->banco)}}">
                                @if ($errors->has('banco'))
                                    <div class="form-control-feedback">{{ $errors->first('banco')}}</div>
                                @endif
                            </div>

                            <div class="col-md-3 form-group @if($errors->has('numero_cheque')) has-danger @endif">
                                <label for="form-control-label">Número do cheque</label>
                                <input class="form-control" type="text" name="numero_cheque" id="numero_cheque" value="{{old('numero_cheque', $contaReceber->numero_cheque)}}">
                                @if ($errors->has('numero_cheque'))
                                    <div class="form-control-feedback">{{ $errors->first('numero_cheque')}}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12 @if($errors->has('obs')) has-danger @endif">
                        <label class="form-control-label">Observação: </label>
                        <textarea name="obs" id="obs" class="form-control" cols="5" rows="4">{{old('obs', $contaReceber->obs)}}</textarea>
                    </div>

                </div>

                <div class="form-group text-right">
                    <a href="{{ route('instituicao.contasReceber.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            tipoValue();
            formaPagamentoCheque();
        })

        function formaPagamentoCheque(){
            if($("#forma_pagamento").val() == 'cheque'){
                $(".cheque_data").css('display', 'block');
                $("#data_compensacao").prop('disabled', false);
            }else{
                $(".cheque_data").css('display', 'none');
                $("#data_compensacao").prop('disabled', true);
            }
        }

        $("#forma_pagamento").on('change', function() {
            formaPagamentoCheque()
        })

        function tipoValue(){
            if($("#tipo").val() == 'paciente'){
                $(".paciente").css('display', 'block');
                $(".convenio").css('display', 'none');
                $("#convenio_id").val('').change();
            }else{
                $(".paciente").css('display', 'none');
                $(".convenio").css('display', 'block');
                $("#paciente_id").val('').change();
            }
        }

        $("#tipo").on('change', function(){
            tipoValue();
        })
    </script>
@endpush
