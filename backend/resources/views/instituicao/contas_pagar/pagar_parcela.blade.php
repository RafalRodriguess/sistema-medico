<div id="modalPagarConta" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pagar parcela #{{$conta->id}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="formPagarParcela">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="parcela_id_pagar" id="parcela_id_pagar" value="{{$conta->id}}">
                    <div class="row">
                        <div class="form-group col-md-3" @if ($conta->status == 1) style="display: none" @endif>
                            <label for="conta_id" class="control-label">Conta caixa: *</label>
                            <select name="conta_id" id="conta_id" class="form-control select2pagar " style="width: 100%" @if ($conta->status == 1) readonly @endif required>
                                <option value="">Selecione um caixa</option>
                                @foreach ($contas as $item)
                                    <option value="{{$item->id}}" @if ($conta->conta_id == $item->id)
                                        selected="selected"
                                    @endif>{{$item->descricao}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3" @if ($conta->status == 1) style="display: none" @endif>
                            <label for="plano_conta_id" class="control-label">Plano de conta:</label>
                            <select class="form-control select_parcela select2pagar" name="plano_conta_id" id="plano_conta_id" style="width: 100%" @if ($conta->status == 1) readonly @endif>
                                <option value="">Selecione um plano de conta</option>
                                @foreach ($planos_conta as $item)
                                    <option value="{{$item->id}}"@if ($conta->plano_conta_id == $item->id)
                                        selected="selected"
                                    @endif>{{$item->codigo}} - {{ $item->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-md-3" @if ($conta->status == 1) style="display: none" @endif>
                            <label for="forma_pagamento_input" class="control-label">Metodo de pagamento: *</label>
                            <select class="form-control select_parcela select2pagar" name="forma_pagamento" id="forma_pagamento_input" style="width: 100%" @if ($conta->status == 1) readonly @endif required>
                                <option value="">Selecione um metodo de pagamento</option>
                                @foreach ($metodo_pagamento as $metodo)
                                    <option value="{{$metodo}}"@if ($conta->forma_pagamento == $metodo)
                                        selected="selected"
                                    @endif>{{ App\ContaPagar::forma_pagamento_texto($metodo)}}</option>
                                @endforeach
                            </select>
                        </div>
                       

                        <div class="cheque col-md-6" style="display: none">
                            <div class="row">
                                <div class="form-group col-md-6 @if($errors->has('titular')) has-danger @endif">
                                    <label class="form-control-label">Titular</label>
                                    <input class="form-control cheque_input" type="text" name="titular" id="titular" value="{{old('titular', $conta->titular)}}">
                                    @if($errors->has('titular'))
                                        <div class="form-control-feedback">{{ $errors->first('titular') }}</div>
                                    @endif
                                </div>
                            
                                <div class="col-md-6 form-group @if($errors->has('numero_cheque')) has-danger @endif">
                                    <label for="form-control-label">Número do cheque</label>
                                    <input class="form-control cheque_input" type="text" name="numero_cheque" id="numero_cheque" value="{{old('numero_cheque', $conta->numero_cheque)}}">
                                    @if ($errors->has('numero_cheque'))
                                        <div class="form-control-feedback">{{ $errors->first('numero_cheque')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 banco_opcao_pix form-group @if($errors->has('chave_pix')) has-danger @endif" style="display: none">
                            <label for="form-control-label">Chave pix</label>
                            <input class="form-control chave_pix_input" type="text" name="chave_pix" id="chave_pix" value="{{old('chave_pix', $conta->chave_pix)}}">
                            @if ($errors->has('chave_pix'))
                                <div class="form-control-feedback">{{ $errors->first('chave_pix')}}</div>
                            @endif
                        </div>

                        <div class="transferencia_bancaria col-md-6" style="display: none">
                            <div class="row">
                                <div class="form-group col-md-6 @if($errors->has('conta')) has-danger @endif">
                                    <label class="form-control-label">Conta</label>
                                    <input class="form-control transferencia_input" type="text" name="conta" id="conta_input" value="{{old('conta', $conta->conta)}}">
                                    @if($errors->has('conta'))
                                        <div class="form-control-feedback">{{ $errors->first('conta') }}</div>
                                    @endif
                                </div>
                            
                                <div class="col-md-6 form-group @if($errors->has('agencia')) has-danger @endif">
                                    <label for="form-control-label">Agencia</label>
                                    <input class="form-control transferencia_input" type="text" name="agencia" id="agencia_input" value="{{old('agencia', $conta->agencia)}}">
                                    @if ($errors->has('agencia'))
                                        <div class="form-control-feedback">{{ $errors->first('agencia')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 banco_opcao form-group @if($errors->has('banco')) has-danger @endif" style="display: none">
                            <label for="form-control-label">Banco</label>
                            <input class="form-control banco_input" type="text" name="banco" id="banco" value="{{old('banco', $conta->banco)}}">
                            @if ($errors->has('banco'))
                                <div class="form-control-feedback">{{ $errors->first('banco')}}</div>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="valor_pagar_parcela_pagar" class="control-label">Valor a pagar: *</label>
                            <input type="text" alt="decimal" class="form-control" id="valor_pagar_parcela_pagar"
                            value="{{$conta->valor_parcela}}" required readonly>
                        </div>
                        
                        
                        <div class="form-group col-md-3">
                            <label for="valor_pagar_parcela" class="control-label">Valor pago: *</label>
                            <input type="text" alt="decimal" class="form-control" id="valor_pagar_parcela" name="valor_pago" value="{{$conta->valor_pago}}" required>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="desc_juros_multa" class="control-label">Desc/Juros/Multa: *</label>
                            <input type="text" alt="signed-decimal" class="form-control" id="desc_juros_multa" name="desc_juros_multa" placeholder="-0,00" value="{{$conta->desc_juros_multa}}" required readonly>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="data_pagamento_parcela" class="control-label">Data quitação: *</label>
                            <input type="date" class="form-control" id="data_pagamento_parcela" name="data_pago" value="{{date('Y-m-d')}}" required>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="parcela_compensacao_pagar" class="control-label">Data compensação:</label>
                            <input type="date" class="form-control" id="parcela_compensacao_pagar" name="data_compensacao" value="{{$conta->data_compensacao}}">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="parcela_obs_pagar" class="control-label">Observação:</label>
                            <textarea type="text" class="form-control" id="parcela_obs_pagar" name="obs">{{$conta->obs}}</textarea>
                        </div>

                        <div class="form-group col-md-12 pagar_menor bg-danger text-white" style="display: none;">
                            <span class=''>O valor pago esta menor que o valor da parcela! deseja confirmar o pagamento a menor com desconto ou gerar uma nova parcela com a diferenca para um novo vencimento?</span>
                        </div>

                        <div class="form-group col-md-12 pagar_menor" style="display: none;">
                            <input type="checkbox" id="pagar_menor" name="pagar_menor" value="1" class="filled-in" checked/>
                            <label for="pagar_menor">Confirmar pagamento a menor?<label>
                        </div>

                        <div class="form-group col-md-4 pagar_menor" style="display: none;">
                            <label for="data_vencimento" class="control-label">Novo vencimento:</label>
                            <input type="date" class="form-control" name="data_vencimento" id="data_vencimento">
                        </div>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="submit" id="salvar_pagamento_parcela" class="btn btn-danger waves-effect waves-light salvar_pagamento_parcela">Pagar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var status = "{{$conta->status}}"
    $('input[type="text"]').setMask();
    $(document).ready(function() {
        $(".select2pagar").select2()
        formaPagamentoCheque();
        valorParcela();
    })

    function formaPagamentoCheque(){
        if(status == 0){
            if($("#forma_pagamento_input").val() == 'cheque'){
                $(".cheque").css('display', 'block');
                $(".banco_opcao").css('display', 'block');
                $(".transferencia_bancaria").css('display', 'none');
                $(".banco_opcao_pix").css('display', 'none');

                $(".transferencia_input").val('')
                $(".chave_pix_input").val('')

            }else if($("#forma_pagamento_input").val() == 'transferencia_bancaria'){
                $(".transferencia_bancaria").css('display', 'block');
                $(".banco_opcao").css('display', 'block');
                $(".cheque").css('display', 'none');
                $(".banco_opcao_pix").css('display', 'none');

                $(".cheque_input").val('')
                $(".chave_pix_input").val('')
            }else if($("#forma_pagamento_input").val() == 'pix'){
                $(".transferencia_bancaria").css('display', 'block');
                $(".banco_opcao").css('display', 'block');
                $(".cheque").css('display', 'none');
                $(".banco_opcao_pix").css('display', 'block');

                $(".cheque_input").val('')
            }else{
                $(".cheque").css('display', 'none');
                $(".banco_opcao").css('display', 'none');
                $(".transferencia_bancaria").css('display', 'none');
                $(".banco_opcao_pix").css('display', 'none');

                $(".cheque_input").val('')
                $(".chave_pix_input").val('')
                $(".banco_input").val('')
                $(".transferencia_input").val('')
            }
        }
    }

    $("#forma_pagamento_input").on('change', function() {
        formaPagamentoCheque()
    })

    function valorParcela(){
        valor = ($("#valor_pagar_parcela_pagar").val()).replace('.','')
        valor = valor.replace(',','.')
        
        valor_pagar = ($("#valor_pagar_parcela").val()).replace('.','')
        valor_pagar = valor_pagar.replace(',','.')

        result = parseFloat(valor_pagar) - parseFloat(valor);

        $("#desc_juros_multa").val(result.toFixed(2));
        $("#desc_juros_multa").setMask();

        if(result < 0){
            if(!$("#pagar_menor").is(':checked')){
                $("#desc_juros_multa").val(0);
            }

            $("#desc_juros_multa").css('color', 'red');
            $(".pagar_menor").css('display', 'block');
            $("#pagar_menor").prop( "checked", false );
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

    $("#valor_pagar_parcela").on('change', function(){
        valorParcela();
    });

    $("#pagar_menor").on('change', function(){
        if($("#pagar_menor").is(':checked')){
            $("#data_vencimento").prop( "disabled", true );
            valorParcela();
        }else{
            $("#data_vencimento").prop( "disabled", false );
            $("#desc_juros_multa").val(0);
        }
    })

    $("#formPagarParcela").on('submit', function(e){
        e.preventDefault()

        var formData = new FormData($(this)[0]);
        id = $('#parcela_id_pagar').val();
        $.ajax({
            url: "{{route('instituicao.contasPagar.contaPagar', ['conta' => 'contaPagarId'])}}".replace('contaPagarId', id),
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
                $("#modalPagarConta").modal('hide');
                $.toast({
                    heading: 'Sucesso',
                    text: 'Parcela paga com sucesso',
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'success',
                    hideAfter: 3000,
                    stack: 10
                });
                callRenderPage();
                refreshPage();
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
  })
</script>