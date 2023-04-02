<div id="modalVerFinanceiro" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Gerar financeiro</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="javascript:void(0)" id="formSalvaFinanceiro">
                @csrf
                <div class="modal-body">
                    <div class="card-body">                            
                        <div class="row">

                            <div class="form-group col-md-4 @if($errors->has('tipo')) has-danger @endif">
                                <label class="form-control-label">Tipo: *</span>
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Deve ser escolhido o fornecedor do serviço ou venda, caso não seja cadastrado, cadastrar o mesmo antes de inserir a conta"></i>
                                </label>
                                <select name="tipo" class="form-control selectfild2 @if($errors->has('tipo')) form-control-danger @endif" style="width: 100%" id="tipo">
                                    @foreach ($tipos as $item)
                                        <option value="{{$item}}" @if (old('tipo', 'prestador') == $item)
                                            selected="selected"
                                        @endif>{{App\ContaPagar::tipos_texto_all($item)}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('tipo'))
                                    <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                                @endif
                            </div>
                            
                            <div class="form-group col-md-8 pessoas @if($errors->has('pessoa_id')) has-danger @endif">
                                <label class="form-control-label">Paciente: *</span></label>
                                <select name="pessoa_id" class="form-control selectfild2 @if($errors->has('pessoa_id')) form-control-danger @endif" style="width: 100%" id="pessoa_id">
                                    <option value="">Selecione um Paciente</option>
                                </select>
                                @if($errors->has('pessoa_id'))
                                    <div class="form-control-feedback">{{ $errors->first('pessoa_id') }}</div>
                                @endif
                            </div>
                            
                            <div class="form-group col-md-8 prestadores @if($errors->has('prestador_id')) has-danger @endif" style="display: none">
                                {{-- {{dd($prestadores)}} --}}
                                <label class="form-control-label">Prestadores: *</span></label>
                                <select name="prestador_id" class="form-control selectfild2 @if($errors->has('prestador_id')) form-control-danger @endif" style="width: 100%" id="prestador_id">
                                    <option value="">Selecione um prestador</option>
                                    <option value="{{$repasse['prestador_id']}}" selected>{{$repasse['prestador_nome']}}</option>
                                </select>
                                @if($errors->has('prestador_id'))
                                    <div class="form-control-feedback">{{ $errors->first('prestador_id') }}</div>
                                @endif
                            </div>
        
                            <div class="form-group col-md-8 fornecedores @if($errors->has('pessoa_id')) has-danger @endif" style="display: none">
                                <label class="form-control-label">Fornecedores: *</span></label>
                                <select name="pessoa_id" class="form-control selectfild2 @if($errors->has('pessoa_id')) form-control-danger @endif" style="width: 100%" id="fornecedor_id">
                                    <option value="">Selecione um fornecedor</option>
                                </select>
                                @if($errors->has('pessoa_id'))
                                    <div class="form-control-feedback">{{ $errors->first('pessoa_id') }}</div>
                                @endif
                            </div>
        
                            <div class="form-group col-md-12">
                                <label class="form-control-label">Descrição *:
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Caso queira detalhar melhor o seu pagamento"></i>
                                </label>
                                <input type="text" name="descricao" class="form-control" value="{{old('descricao', $repasse['descricao'])}}">
                            </div>
        
                            <div class="form-group col-md-2">
                                <label class="form-control-label">Nº. documento:
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Caso tenha número do boleto, número da nota, etc..."></i>
                                </label>
                                <input type="text" name="numero_doc" class="form-control" value="{{old('numero_doc')}}">
                            </div>
        
                            <div class="form-group col-md-2 @if($errors->has('data_vencimento')) has-danger @endif">
                                <label class="form-control-label">Dt vencimento: *
                                    {{-- <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i> --}}
                                </label>
                                <input type="date" name="data_vencimento" id="data_vencimento" class="form-control @if ($errors->has('data_vencimento')) form-control-danger @endif" value="{{old('data_vencimento', date('Y-m-d'))}}">
                                @if ($errors->has('data_vencimento'))
                                    <div class="form-control-feedback">{{$errors->first('data_vencimento')}}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-2 @if($errors->has('total')) has-danger @endif">
                                <label class="form-control-label">Valor Total: *</label>
                                <input type="text" alt="decimal" name="total" id="total" class="form-control set-mask @if ($errors->has('total')) form-control-danger @endif" value="{{old('total', $repasse['valor_total'])}}" onchange="totalCC(this)">
                                @if ($errors->has('total'))
                                    <div class="form-control-feedback">{{$errors->first('total')}}</div>
                                @endif
                            </div>
        
                            <div class="form-group col-md-2 @if($errors->has('data_emissao_nf')) has-danger @endif">
                                <label class="form-control-label">Data emissão NF:
                                    {{-- <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que vence a compra ou despesa"></i> --}}
                                </label>
                                <input type="date" name="data_emissao_nf" id="data_emissao_nf" class="form-control @if ($errors->has('data_emissao_nf')) form-control-danger @endif" value="{{old('data_emissao_nf')}}">
                                @if ($errors->has('data_emissao_nf'))
                                    <div class="form-control-feedback">{{$errors->first('data_emissao_nf')}}</div>
                                @endif
                            </div>
        
                            <div class="form-group col-md-2" style="margin-top: 34px;">
                                <input type="checkbox" id="nf_imposto" value="1" name="nf_imposto" @if (old('nf_imposto') == 1)
                                    checked=""
                                @endif class="filled-in" />
                                <label for="nf_imposto">NF com imposto<label>
                            </div>
                            
                            <div class="form-group col-md-4 @if($errors->has('plano_conta_id')) has-danger @endif">
                                <label class="form-control-label">Plano de conta:</span>
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Este é o filtro por tipo de pagamento, que deve ser escolhido o plano exato que se associa ao pagamento"></i>
                                </label>
                                <select name="plano_conta_id" class="form-control selectfild2 @if($errors->has('plano_conta_id')) form-control-danger @endif" style="width: 100%">
                                    <option value="">Selecione um plano de conta</option>
                                    @foreach ($planosConta as $item)
                                        <option value="{{$item->id}}" @if (old('plano_conta_id') == $item->id)
                                            selected="selected"
                                        @endif>{{$item->codigo}} - {{$item->descricao}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('plano_conta_id'))
                                    <div class="form-control-feedback">{{ $errors->first('plano_conta_id') }}</div>
                                @endif
                            </div>
        
                            <div class="form-group col-md-4 @if($errors->has('forma_pagamento')) has-danger @endif">
                                <label class="form-control-label">Metodo de pagamento:</label>
                                <select class="form-control select_parcela selectfild2 @if($errors->has('forma_pagamento')) form-control-danger @endif" name="forma_pagamento" id="forma_pagamento" style="width: 100%">
                                    <option value="">Selecione uma metodo de pagamento</option>
                                    @foreach ($metodos_pagamento as $metodo)
                                        <option value="{{$metodo}}"@if (old('forma_pagamento') == $metodo)
                                            selected="selected"
                                        @endif>{{ App\ContaPagar::forma_pagamento_texto($metodo)}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('forma_pagamento'))
                                    <div class="form-control-feedback">{{$errors->first('forma_pagamento')}}</div>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <div class="conta row">
                                    <div class="col-md-12 item-conta">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="javascrit:void(0)" class="small remove-conta">(remover)</a>
                                            </div>
                                            <div class="form-group dados_parcela col-md-4">
                                                <label class="form-control-label">Conta:</span></label>
                                                <select name="conta_caixa[0][conta_id]" class="form-control selectfild2" style="width: 100%">
                                                    <option value="">Selecione uma conta *</option>
                                                    @foreach ($contas as $item)
                                                        <option value="{{$item->id}}">{{$item->descricao}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="form-control-label">Valor *</span></label>
                                                <input type="text" alt="decimal" class="form-control valor_conta mask_item" name="conta_caixa[0][valor]"  onchange="totalConta(this)">
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="form-group col-md-12 add-contas" >
                                        <span alt="default" class="add-conta fas fa-plus-circle">
                                            <a class="mytooltip" href="javascript:void(0)">
                                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar conta"></i>
                                            </a>
                                        </span>
                                    </div>
                                    
                                    <div class="form-group col-md-3">
                                        <label class="form-control-label">Total conta</label>
                                        <input class="form-control set-mask" alt="decimal" type="text" readonly id="total_conta">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="centro_custo row">
        
                                    @include('instituicao.relatorios.atendimentos.centro_custo_criar')
        
                                    <div class="form-group col-md-12 add-centro-custo" >
                                        <span alt="default" class="add-cc fas fa-plus-circle">
                                            <a class="mytooltip" href="javascript:void(0)">
                                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar centro de custo"></i>
                                            </a>
                                        </span>
                                    </div>
                                    
                                    <div class="form-group col-md-3">
                                        <label class="form-control-label">Total centro de custo</label>
                                        <input class="form-control set-mask" alt="decimal" type="text" readonly id="centro_custo_total">
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="row">            
        
                                    <div class="cheque_data col-md-9">
                                        <div class="row">
                                            <div class="form-group col-md-4 @if($errors->has('data_compensacao')) has-danger @endif">
                                                <label class="form-control-label">Data compensação: *
                                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data em que a compra ou despesa ocorreu"></i>
                                                </label>
                                                <input type="date" name="data_compensacao" id="data_compensacao" class="form-control @if ($errors->has('data_compensacao')) form-control-danger @endif" value="{{old('data_compensacao', date('Y-m-d'))}}" disabled>
                                                @if ($errors->has('data_compensacao'))
                                                    <div class="form-control-feedback">{{$errors->first('data_compensacao')}}</div>
                                                @endif
                                            </div>
        
                                            <div class="form-group col-md-4 @if($errors->has('titular')) has-danger @endif">
                                                <label class="form-control-label">Titular</label>
                                                <input class="form-control" type="text" name="titular" id="titular" value="{{old('titular')}}">
                                                @if($errors->has('titular'))
                                                    <div class="form-control-feedback">{{ $errors->first('titular') }}</div>
                                                @endif
                                            </div>
                                        
                                            <div class="col-md-4 form-group @if($errors->has('numero_cheque')) has-danger @endif">
                                                <label for="form-control-label">Número do cheque</label>
                                                <input class="form-control" type="text" name="numero_cheque" id="numero_cheque" value="{{old('numero_cheque')}}">
                                                @if ($errors->has('numero_cheque'))
                                                    <div class="form-control-feedback">{{ $errors->first('numero_cheque')}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="col-md-8 cartao_credito form-group @if($errors->has('cartao_credito_id')) has-danger @endif" style="display: none">
                                        <label for="form-control-label">Cartão de crédito</label>
                                        <select class="form-control select_parcela selectfild2 @if($errors->has('cartao_credito_id')) form-control-danger @endif" name="cartao_credito_id" id="cartao_credito_id" style="width: 100%">
                                            <option value="">Selecione um cartão de credito</option>
                                            @foreach ($cartoes as $item)
                                                <option value="{{$item->id}}"@if (old('cartao_credito_id') == $item->id)
                                                    selected="selected"
                                                @endif>{{$item->descricao}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('cartao_credito_id'))
                                            <div class="form-control-feedback">{{ $errors->first('cartao_credito_id')}}</div>
                                        @endif
                                    </div>
        
                                    <div class="col-md-4 cartao_credito form-group @if($errors->has('data_compra_cartao')) has-danger @endif" style="display: none">
                                        <label for="form-control-label">Data da compra</label>
                                        <input type="date" name="data_compra_cartao" id="data_compra_cartao" class="form-control @if ($errors->has('data_compra_cartao')) form-control-danger @endif" value="{{old('data_compra_cartao', date('Y-m-d'))}}">
                                        @if ($errors->has('data_compra_cartao'))
                                            <div class="form-control-feedback">{{ $errors->first('data_compra_cartao')}}</div>
                                        @endif
                                    </div>
        
                                    <div class="col-md-3 banco_opcao_pix form-group @if($errors->has('chave_pix')) has-danger @endif" style="display: none">
                                        <label for="form-control-label">Chave pix</label>
                                        <input class="form-control" type="text" name="chave_pix" id="chave_pix" value="{{old('chave_pix')}}">
                                        @if ($errors->has('chave_pix'))
                                            <div class="form-control-feedback">{{ $errors->first('chave_pix')}}</div>
                                        @endif
                                    </div>
                                    
                                    <div class="transferencia_bancaria col-md-6" style="display: none">
                                        <div class="row">
                                            <div class="form-group col-md-6 @if($errors->has('conta')) has-danger @endif">
                                                <label class="form-control-label">Conta</label>
                                                <input class="form-control" type="text" name="conta" id="conta" value="{{old('conta')}}">
                                                @if($errors->has('conta'))
                                                    <div class="form-control-feedback">{{ $errors->first('conta') }}</div>
                                                @endif
                                            </div>
                                        
                                            <div class="col-md-6 form-group @if($errors->has('agencia')) has-danger @endif">
                                                <label for="form-control-label">Agencia</label>
                                                <input class="form-control" type="text" name="agencia" id="agencia" value="{{old('agencia')}}">
                                                @if ($errors->has('agencia'))
                                                    <div class="form-control-feedback">{{ $errors->first('agencia')}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="col-md-3 banco_opcao form-group @if($errors->has('banco')) has-danger @endif" style="display: none">
                                        <label for="form-control-label">Banco</label>
                                        <input class="form-control" type="text" name="banco" id="banco" value="{{old('banco')}}">
                                        @if ($errors->has('banco'))
                                            <div class="form-control-feedback">{{ $errors->first('banco')}}</div>
                                        @endif
                                    </div>
                                    
                                    
                                    <div class="form-group col-md-12 tipo_parcelamento">
                                        <label class="form-control-label">Tipo de parcelamento: *
                                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Selecione o período de cobrança"></i>
                                        </label>
                                        <select class="form-control" name="tipo_parcelamento" id="tipo_parcelamento">
                                            <option value="">Parcela única</option>
                                            <option value="diario" @if (old('tipo_parcelamento') == 'diario') selected="selected" @endif>Diário</option>
                                            <option value="semanal" @if (old('tipo_parcelamento') == 'semanal') selected="selected" @endif>Semanal</option>
                                            <option value="quinzenal" @if (old('tipo_parcelamento') == 'quinzenal') selected="selected" @endif>Quinzenal</option>
                                            <option value="mensal" @if (old('tipo_parcelamento') == 'mensal') selected="selected" @endif>Mensal</option>
                                            <option value="anual" @if (old('tipo_parcelamento') == 'anual') selected="selected" @endif>Anual</option>
                                        </select>
                                    </div>
        
                                    <div class="form-group num_parcelas">
                                        <label class="form-control-label">Tipo</label>
                                        <div class="demo-radio-button">
                                            <input name="tipo_divisao" type="radio" id="radio_1" value="dividir" checked />
                                            <label for="radio_1">Dividir valor</label>
                                            <input name="tipo_divisao" type="radio" id="radio_2" value="replicar"/>
                                            <label for="radio_2">Replicar valor</label>
                                        </div>
                                    </div>
        
                                    <div class="form-group col-md-2 num_parcelas @if($errors->has('num_parcelas')) has-danger @endif" style="display: none">
                                        <label class="form-control-label">Nº de parcelas: *</label>
                                        <input type="number" name="num_parcelas" class="form-control @if ($errors->has('num_parcelas')) form-control-danger @endif" value="{{old('num_parcelas', 2)}}" id="num_parcelas">
                                        @if ($errors->has('num_parcelas'))
                                            <div class="form-control-feedback">{{$errors->first('num_parcelas')}}</div>
                                        @endif
                                    </div>
        
                                    <div class="col-md-2 gerar_parcelas" style="display: none">
                                        <button type="button" class="btn btn-danger waves-effect waves-light m-r-10" style="margin-top: 31px; width: 100%" onclick="gerarParcelas()">Gerar parcelas</button>
                                    </div>
        
                                    <div class="col-md-12">
                                        <div class="parcelas_extras row"></div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="form-group col-md-12 @if($errors->has('obs')) has-danger @endif">
                                <label class="form-control-label">Observação: </label>
                                <textarea name="obs" id="obs" class="form-control" cols="5" rows="4">{{old('obs', $repasse['obs'])}}</textarea>
                            </div>

                            @can('habilidade_instituicao_sessao', 'pagar_contas_pagar')
                                <div class="form-group col-md-2" style="margin-top: 34px;">
                                    <input type="checkbox" id="pagar" name="status" value="1" class="filled-in" />
                                    <label for="pagar">Pagar?<label>
                                </div>    
                            
                                <div class="form-group col-md-12" id="campos_pagamento" style="display: none">
                                    <div class="row border mt-3">
                                        <div class="form-group col-md-3">
                                            <label for="valor_pagar_parcela" class="control-label">Valor pago: *</label>
                                            <input type="text" alt="decimal" class="form-control set-mask" id="valor_pagar_parcela" name="valor_pago" value="" >
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label for="desc_juros_multa" class="control-label">Desc/Juros/Multa: *</label>
                                            <input type="text" alt="signed-decimal" class="form-control" id="desc_juros_multa" name="desc_juros_multa" placeholder="-0,00" value="" readonly>
                                        </div>
                
                                        <div class="form-group col-md-3">
                                            <label for="data_pagamento_parcela" class="control-label">Data quitação: *</label>
                                            <input type="date" class="form-control" id="data_pagamento_parcela" name="data_pago" value="" >
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            
                        </div>
                        
                                    
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="button" onclick="salvaFinanceiro()" class="btn btn-success waves-effect waves-light m-r-10 salvaFinanceiro" value="Confirmar">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var quantidade = 0
    var quantidade_cc = 0;
    var quantidade_contas = 0;


    function salvaFinanceiro(){
        
        var dados = new FormData($('#formSalvaFinanceiro')[0]);
        
        $.ajax("{{route('instituicao.relatorioAtendimento.salvaFinanceiro')}}", {
            method: "POST",
            data: dados,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function (result) {
                if(result.icon === 'error'){
                    $.toast({
                        heading: result.title,
                        text: result.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: result.icon,
                        hideAfter: 3000,
                        stack: 10
                    });
                }else{
                    $('#modalVerFinanceiro').modal('hide');
                    $.toast({
                        heading: result.title,
                        text: result.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: result.icon,
                        hideAfter: 3000,
                        stack: 10
                    });
                }
                
                
                // $(".table-responsive").html(result);
                // $(".imprimir").css('display', 'block')
                // ativarClass();
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function (response) {
                if(response.responseJSON.errors){
                    Object.keys(response.responseJSON.errors).forEach(function(key) {
                        $.toast({
                            heading: 'Erro',
                            text: response.responseJSON.errors[key][0],
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 9000,
                            stack: 10
                        });

                    });
                }
            }
        })
    }

    $(document).ready(function(){
        tipoValue();
        tipoParcelamento();
        formaPagamentoCheque();
        $(".set-mask").setMask();
        $(".set-mask").removeClass('set-mask');

        $("#pessoa_id").select2({
            placeholder: "Pesquise por nome do paciente",
            allowClear: true,
            minimumInputLength: 3,

            language: {
                searching: function () {
                    return 'Buscando paciente (aguarde antes de selecionar)…';
                },

                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                },
            },

            ajax: {
                url:"{{route('instituicao.contasPagar.getPacientes')}}",
                dataType: 'json',
                type: 'get',
                delay: 100,

                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page || 1
                    };
                },

                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: _.map(data.results, item => ({
                            id: Number.parseInt(item.id),
                            text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''}`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
        });

        $("#fornecedor_id").select2({
            placeholder: "Pesquise por nome do fornecedor",
            allowClear: true,
            minimumInputLength: 3,

            language: {
                searching: function () {
                    return 'Buscando fornecedor (aguarde antes de selecionar)…';
                },

                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                },
            },

            ajax: {
                url:"{{route('instituicao.contasPagar.getFornecedores')}}",
                dataType: 'json',
                type: 'get',
                delay: 100,

                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page || 1
                    };
                },

                processResults: function (data, params) {
                    params.page = params.page || 1;
                    console.log(data);
                    return {
                        results: _.map(data.results, item => ({
                            id: Number.parseInt(item.id),
                            text: `${(item.personalidade == 1) ? item.nome : item.razao_social}`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
        });

        $("#prestador_id").select2({
            placeholder: "Pesquise por nome do prestador",
            allowClear: true,
            minimumInputLength: 3,

            language: {
                searching: function () {
                    return 'Buscando prestador (aguarde antes de selecionar)…';
                },

                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                },
            },

            ajax: {
                url:"{{route('instituicao.contasPagar.getPrestadores')}}",
                dataType: 'json',
                delay: 100,

                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page || 1
                    };
                },

                processResults: function (data, params) {
                    params.page = params.page || 1;
                    console.log(data)
                    return {
                        results: _.map(data.results, item => ({
                            id: Number.parseInt(item.prestador.id),
                            text: `${item.prestador.nome}`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
        });
    })

    $(".addForncedor").on('click', function(){
        $(".modal").modal('show')
    })
    
    function formaPagamentoCheque(){
        if($("#forma_pagamento").val() == 'cheque'){
            $(".cheque_data").css('display', 'block');
            $(".banco_opcao").css('display', 'block');
            $("#data_compensacao").prop('disabled', false);
            $(".transferencia_bancaria").css('display', 'none');
            $(".banco_opcao_pix").css('display', 'none');
            $(".cartao_credito").css('display', 'none');

        }else if($("#forma_pagamento").val() == 'transferencia_bancaria'){
            $(".transferencia_bancaria").css('display', 'block');
            $(".banco_opcao").css('display', 'block');
            $(".cheque_data").css('display', 'none');
            $(".banco_opcao_pix").css('display', 'none');
            $(".cartao_credito").css('display', 'none');
            $("#data_compensacao").prop('disabled', true);

        }else if($("#forma_pagamento").val() == 'pix'){
            $(".transferencia_bancaria").css('display', 'block');
            $(".banco_opcao").css('display', 'block');
            $(".banco_opcao_pix").css('display', 'block');
            $(".cheque_data").css('display', 'none');
            $(".cartao_credito").css('display', 'none');
            $("#data_compensacao").prop('disabled', true);
        
        }else if($("#forma_pagamento").val() == 'cartao_credito'){
            $(".cartao_credito").css('display', 'block');
            $(".transferencia_bancaria").css('display', 'none');
            $(".banco_opcao").css('display', 'none');
            $(".banco_opcao_pix").css('display', 'none');
            $(".cheque_data").css('display', 'none');
            $("#data_compensacao").prop('disabled', true);
            $("#data_vencimento").prop('readonly', true);

        }else{
            $(".cheque_data").css('display', 'none');
            $(".banco_opcao").css('display', 'none');
            $(".banco_opcao_pix").css('display', 'none');
            $("#data_compensacao").prop('disabled', true);
            $(".transferencia_bancaria").css('display', 'none');
            $(".cartao_credito").css('display', 'none');
        }
    }

    $("#forma_pagamento").on('change', function() {
        formaPagamentoCheque()
    })

    function cartaoCredito(){
        if($("#cartao_credito_id").val() != ''){
            id = $('#cartao_credito_id').val();
            
            $.ajax({
                url: "{{route('instituicao.contasPagar.getCartao', ['cartao_id' => 'cartao_credito_id'])}}".replace('cartao_credito_id', id),
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(retorno){
                    $('#data_vencimento').val(retorno['data_vencimento']);
                    
                }
            })
        }
    }

    $("#cartao_credito_id").on('change', function() {
        cartaoCredito()
    })

    function tipoValue(){
        if($("#tipo").val() == 'paciente'){
            $(".pessoas").css('display', 'block');
            $(".fornecedores").css('display', 'none');
            $(".prestadores").css('display', 'none');
            $("#prestador_id").val('').change();
            $("#fornecedor_id").val('').change();
        }else if($("#tipo").val() == 'prestador'){
            $(".pessoas").css('display', 'none');
            $("#pessoa_id").val('').change();
            $("#fornecedor_id").val('').change();
            $(".prestadores").css('display', 'block');
            $(".fornecedores").css('display', 'none');
        }else if($("#tipo").val() == 'fornecedor'){
            $(".fornecedores").css('display', 'block');
            $("#pessoa_id").val('').change();
            $("#prestador_id").val('').change();
            $(".prestadores").css('display', 'none');
            $(".pessoas").css('display', 'none');
        }
    }

    $("#tipo").on('change', function(){
        tipoValue();
    })

    function tipoParcelamento(){
        if($("#tipo_parcelamento").val() == ''){
            $(".tipo_parcelamento").removeClass('col-md-3');
            $(".tipo_parcelamento").addClass('col-md-12');
            $(".num_parcelas").css('display', 'none');
            $(".gerar_parcelas").css('display', 'none');
        }else{
            $(".tipo_parcelamento").removeClass('col-md-12');
            $(".tipo_parcelamento").addClass('col-md-3');
            $(".num_parcelas").css('display', 'block');
            $("#num_parcelas").val(2);
            $(".gerar_parcelas").css('display', 'block');
        }
    }

    $("#tipo_parcelamento").on('change', function(){
        tipoParcelamento();
    })

    function gerarParcelas(){
        quantidade = 0;

        tipo_parcelamento = $("#tipo_parcelamento").val()
        num_parcelas = $("#num_parcelas").val();
        data_vencimento = $("#data_vencimento").val()
        total = $("#total").val()

        data_vencimento = data_vencimento.split('-');
        
        data = new Date(data_vencimento[0], data_vencimento[1] - 1, data_vencimento[2])
        // data = acrescimoData(tipo_parcelamento, data);
        data = ( data.getFullYear() + "-" + (adicionaZero(data.getMonth()+1).toString()) + "-" + adicionaZero(data.getDate().toString()));

        var valueRadio = $('input[name=tipo_divisao]:checked').val();
        if(valueRadio == 'dividir'){
            dividirValor(tipo_parcelamento, num_parcelas, total, data)
        }else{
            replicarValor(tipo_parcelamento, num_parcelas, total, data)
        }
    }

    $(".parcelas_extras").on('change', '.primeira_parcela', function(){
        var primeira_parcela = $(".primeira_parcela").val();
        primeira_parcela = primeira_parcela.replace('.','')
        primeira_parcela = primeira_parcela.replace(',','.')
        primeira_parcela = parseFloat(primeira_parcela)

        var total = $("#total").val();
        total = total.replace('.','')
        total = total.replace(',','.')
        total = parseFloat(total)

        var num_parcelas = $("#num_parcelas").val();
        var novo_total = (total - primeira_parcela).toFixed(2);
        var valor_parcela = (novo_total/(num_parcelas-1)).toFixed(2);

        if(valor_parcela < 0){
            $.toast({
                heading: 'Error',
                text: 'Parcela maior que o valor total a pagar',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3000,
                stack: 10
            });
            $(".salvar_form").prop('disabled', true);
        }else{
            $(".salvar_form").prop('disabled', false);

            $("[name*='][valor]']").each(function(index, e){
                if(index > 0){
                    
                    valor_parcela_utilizar = 0;

                    if(index == 1){
                        total_parcelas = valor_parcela*(num_parcelas-1);

                        if(total_parcelas == novo_total){
                            valor_parcela_utilizar = valor_parcela;
                        }else if(total_parcelas > novo_total){
                            valor_parcela_utilizar = total_parcelas - novo_total;
                            valor_parcela_utilizar = valor_parcela_utilizar.toFixed(2)
                            valor_parcela_utilizar = parseFloat(valor_parcela) - parseFloat(valor_parcela_utilizar);
                        }else{
                            valor_parcela_utilizar = novo_total - total_parcelas;
                            valor_parcela_utilizar = valor_parcela_utilizar.toFixed(2)

                            valor_parcela_utilizar = parseFloat(valor_parcela) + parseFloat(valor_parcela_utilizar);
                        }
                        
                    }else{
                        valor_parcela_utilizar = valor_parcela;
                    }
                    
                    $(e).val(valor_parcela_utilizar)
                    $(e).setMask();
                }
            })
        }

    })

    function replicarValor(tipo_parcelamento, num_parcelas, total, data){
        html = "";

        for (let index = 0; index < num_parcelas; index++) {
            data = data.split('-');

            data = new Date(data[0], data[1] - 1, data[2])
            data = acrescimoData(tipo_parcelamento, data);
            data = ( data.getFullYear() + "-" + (adicionaZero(data.getMonth()+1).toString()) + "-" + adicionaZero(data.getDate().toString()));

            var primeira = ''
            if(index == 0){
                primeira = 'primeira_parcela'
            }

            html += "<div class='form-group col-md-6 @if($errors->has('parcelas."+quantidade+".data_vencimento')) has-danger @endif'>"
                +"<label class='form-control-label'>"+index+"º) parcela: *</label>"
                +"<input type='date' name='parcelas["+quantidade+"][data_vencimento]' class='form-control @if ($errors->has('parcelas."+quantidade+".data_vencimento')) form-control-danger @endif' value='"+data+"'>"
                +"@if ($errors->has('parcelas."+quantidade+".data_vencimento'))"
                    +"<div class='form-control-feedback'>{{$errors->first('parcelas."+quantidade+".data_vencimento')}}</div>"
                +"@endif"
            +"</div>"   

            html += "<div class='form-group col-md-6 @if($errors->has('parcelas."+quantidade+".valor')) has-danger @endif'>"
                +"<label class='form-control-label'>Valor: *</label>"
                +"<input type='text' alt='decimal' name='parcelas["+quantidade+"][valor]' class='form-control "+primeira+" set-mask @if ($errors->has('parcelas."+quantidade+".valor')) form-control-danger @endif' value='"+total+"'>"
                +"@if ($errors->has('parcelas."+quantidade+".valor'))"
                    +"<div class='form-control-feedback'>{{$errors->first('parcelas."+quantidade+".valor')}}</div>"
                +"@endif"
            +"</div>" 

            quantidade ++;
        }
        
                
        $(".parcelas_extras").html(html)

        $(".set-mask").setMask();
        $(".set-mask").removeClass('set-mask');
    }

    function dividirValor(tipo_parcelamento, num_parcelas, total, data){
        html = "";
        total = total.replace('.','')
        total = total.replace(',','.')
        total = parseFloat(total)
        valor_parcela = (total/num_parcelas).toFixed(2);
        valor_parcela = parseFloat(valor_parcela)
        for (let index = 0; index < num_parcelas; index++) {
            data = data.split('-');

            data = new Date(data[0], data[1] - 1, data[2])
            data = acrescimoData(tipo_parcelamento, data);
            data = ( data.getFullYear() + "-" + (adicionaZero(data.getMonth()+1).toString()) + "-" + adicionaZero(data.getDate().toString()));
            if(index == 0){
                total_parcelas = valor_parcela*num_parcelas;
                
                if(total_parcelas == total){
                    valor_parcela_utilizar = valor_parcela;
                }else if(total_parcelas > total){
                    valor_parcela_utilizar = total_parcelas - total;
                    valor_parcela_utilizar = valor_parcela_utilizar.toFixed(2)

                    valor_parcela_utilizar = valor_parcela - valor_parcela_utilizar;
                }else{
                    valor_parcela_utilizar = total - total_parcelas;
                    valor_parcela_utilizar = valor_parcela_utilizar.toFixed(2)
                    valor_parcela_utilizar = valor_parcela + parseFloat(valor_parcela_utilizar);
                }
                
            }else{
                valor_parcela_utilizar = valor_parcela;
            }

            valor_parcela_utilizar = valor_parcela_utilizar.toFixed(2)
            var primeira = ''
            if(index == 0){
                primeira = 'primeira_parcela'
            }
            html += "<div class='form-group col-md-6 @if($errors->has('parcelas."+quantidade+".data_vencimento')) has-danger @endif'>"
                +"<label class='form-control-label'>"+index+"º) parcela: *</label>"
                +"<input type='date' name='parcelas["+quantidade+"][data_vencimento]' class='form-control @if ($errors->has('parcelas."+quantidade+".data_vencimento')) form-control-danger @endif' value='"+data+"'>"
                +"@if ($errors->has('parcelas."+quantidade+".data_vencimento'))"
                    +"<div class='form-control-feedback'>{{$errors->first('parcelas."+quantidade+".data_vencimento')}}</div>"
                +"@endif"
            +"</div>"   

            html += "<div class='form-group col-md-6 @if($errors->has('parcelas."+quantidade+".valor')) has-danger @endif'>"
                +"<label class='form-control-label'>Valor: *</label>"
                +"<input type='text' alt='decimal' name='parcelas["+quantidade+"][valor]' class='form-control "+primeira+" set-mask @if ($errors->has('parcelas."+quantidade+".valor')) form-control-danger @endif' value='"+valor_parcela_utilizar+"'>"
                +"@if ($errors->has('parcelas."+quantidade+".valor'))"
                    +"<div class='form-control-feedback'>{{$errors->first('parcelas."+quantidade+".valor')}}</div>"
                +"@endif"
            +"</div>" 

            quantidade ++;
        }
        
                
        $(".parcelas_extras").html(html)

        $(".set-mask").setMask();
        $(".set-mask").removeClass('set-mask');
    }

    function acrescimoData(tipo_parcelamento, data){
        if(tipo_parcelamento == 'diario'){
            acrescimo_data = 1;
        }
        if(tipo_parcelamento == 'semanal'){
            acrescimo_data = 7;
        }
        if(tipo_parcelamento == 'quinzenal'){
            acrescimo_data = 15;
        }
        if(tipo_parcelamento == 'mensal'){
            acrescimo_data = 1;
            data.setMonth(data.getMonth() + acrescimo_data)
            return data
        }
        if(tipo_parcelamento == 'anual'){
            acrescimo_data = 1;
            data.setYear(data.getFullYear() + acrescimo_data)
            return data
        }

        data.setDate(data.getDate() + acrescimo_data)
        return data
    }

    function adicionaZero(numero){
        if (numero <= 9) 
            return "0" + numero;
        else
            return numero; 
    }

    function retornaFormatoValor(valor){
        var novo = valor;
        novo = novo.replace('.','')
        novo = novo.replace(',','.')
        return novo;
    }

    //script centro de custos

    function totalCC(e){            
            
        var centro_custo_total = 0;

        var valor_total = retornaFormatoValor($("#total").val())
        valor_total = parseFloat(valor_total).toFixed(2)

        $(".valor_cc").each(function(index, element) {
            var valor_cc = retornaFormatoValor($(element).val())
            centro_custo_total = parseFloat(valor_cc) + parseFloat(centro_custo_total);
        })
        
        $("#centro_custo_total").val(centro_custo_total.toFixed(2))
        if(centro_custo_total != valor_total && centro_custo_total > 0){
            $(".salvar_form").prop('disabled', true);
            $.toast({
                heading: 'Error',
                text: 'Valor total centro de custo encontra diferente do valor total',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3000,
                stack: 10
            });
        }else{
            $(".salvar_form").prop('disabled', false);
        }
    }

    function totalConta(e){            
            
        var conta_total = 0;

        var valor_total = retornaFormatoValor($("#total").val())
        valor_total = parseFloat(valor_total).toFixed(2)

        $(".valor_conta").each(function(index, element) {
            var valor_conta = retornaFormatoValor($(element).val())
            conta_total = parseFloat(valor_conta) + parseFloat(conta_total);
        })
        
        $("#total_conta").val(conta_total.toFixed(2))
        if(conta_total != valor_total && conta_total > 0){
            $(".salvar_form").prop('disabled', true);
            $.toast({
                heading: 'Error',
                text: 'Valor total contas encontra-se diferente do valor total',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3000,
                stack: 10
            });
        }else{
            $(".salvar_form").prop('disabled', false);
        }
    }
        
    $('.centro_custo').on('click', '.add-cc', function(){
        addCC();
    });

    function addCC(){
        quantidade_cc++;
        
        $($('#item-cc').html()).insertBefore(".centro-custo");

        $('.mask_item').setMask();
        $('.mask_item').removeClass('mask_item');
        $(".selectfild2").select2();

        $("[name^='cc[#]']").each(function(index, element) {
            const name = $(element).attr('name');

            $(element).attr('name', name.replace('#',quantidade_cc));
        })
    }

    $('.centro_custo').on('click', '.item-cc .remove-cc', function(e){
        e.preventDefault()

        $(e.currentTarget).parents('.item-cc').remove();
        if ($('.centro_custo').find('.item-cc').length == 0) {
            addCC();
        }

        totalCC()
    });

    $('.conta').on('click', '.add-conta', function(){
        addConta();
    });

    function addConta(){
        quantidade_contas++;
        
        $($('#item-conta').html()).insertBefore(".add-contas");

        $('.mask_item').setMask();
        $('.mask_item').removeClass('mask_item');
        $(".selectfild2").select2();

        $("[name^='conta_caixa[#]']").each(function(index, element) {
            const name = $(element).attr('name');

            $(element).attr('name', name.replace('#',quantidade_contas));
        })
    };

    $('.conta').on('click', '.item-conta .remove-conta', function(e){
        e.preventDefault()

        $(e.currentTarget).parents('.item-conta').remove();
        if ($('.conta').find('.item-conta').length == 0) {
            addConta();
        }

        totalCC()
    });

    $('#pagar').on('change', function(){
        if ($(this).prop("checked")) {
            $('#campos_pagamento').css('display', 'block');
        }else{
            $('#campos_pagamento').css('display', 'none');
        }
    })

    $("#valor_pagar_parcela").on('change', function(){
        valorParcela();
    });

    function valorParcela(){
        valor = ($("#total").val()).replace('.','')
        valor = valor.replace(',','.')
        
        valor_pagar = ($("#valor_pagar_parcela").val()).replace('.','')
        valor_pagar = valor_pagar.replace(',','.')

        result = parseFloat(valor_pagar) - parseFloat(valor);

        $("#desc_juros_multa").val(result.toFixed(2));
        $("#desc_juros_multa").setMask();

        if(result < 0){
            $("#desc_juros_multa").css('color', 'red');
            $(".pagar_menor").css('display', 'block');
        }else{
            $("#desc_juros_multa").css('color', 'green');
            $(".pagar_menor").css('display', 'none');
            $("#pagar_menor").prop( "checked", true );
        }

        if(valor_pagar < 0){
            $.toast({
                heading: 'Info',
                text: 'Valor a pagar vazio',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'info',
                hideAfter: 3000,
                stack: 10
            });
        }

    }

    function retornaFormatoValor(valor){
        var novo = valor;
        novo = novo.replace('.','')
        novo = novo.replace(',','.')
        return novo;
    }
</script>

<script type="text/template" id="item-cc">
    <div class="col-md-12 item-cc">
        <div class="row">
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-cc">(remover)</a>
            </div>
            <div class="form-group dados_parcela col-md-8">
                <label class="form-control-label">Centro de custo:</span></label>
                <select name="cc[#][centro_custo_id]" class="form-control selectfild2" style="width: 100%">
                    <option value="">Selecione um centro de custo</option>
                    @foreach ($centroCustos as $item)
                        <option value="{{$item->id}}">{{$item->codigo}} - {{$item->descricao}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="form-control-label">Valor *</span></label>
                <input type="text" alt="decimal" class="form-control valor_cc mask_item" name="cc[#][valor]"  onchange="totalCC(this)">
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="item-conta">
    <div class="col-md-12 item-conta">
        <div class="row">
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-conta">(remover)</a>
            </div>
            <div class="form-group dados_parcela col-md-4">
                <label class="form-control-label">Conta:</span></label>
                <select name="conta_caixa[#][conta_id]" class="form-control selectfild2" style="width: 100%">
                    <option value="">Selecione uma conta</option>
                    @foreach ($contas as $item)
                        <option value="{{$item->id}}">{{$item->descricao}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="form-control-label">Valor *</span></label>
                <input type="text" alt="decimal" class="form-control valor_conta mask_item" name="conta_caixa[#][valor]"  onchange="totalConta(this)">
            </div>
        </div>
    </div>
</script>

