
    <div class="row">
        
        <div class="form-group col-md-3">
            <label class="form-control-label">Total a pagar</span></label>
            <input type="text" alt="decimal" class="form-control total_a_pagar_pagamento" disabled readonly>
        </div>
        @can('habilidade_instituicao_sessao', 'desconto_agendamentos')
            <div class="col-md-5">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-control-label">Desconto (%)</span></label>
                        <input type="text" alt="porcentagem" class="form-control mask_item desconto_pagamento_porcento" name="desconto_porcento" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">Desconto (R$)</span></label>
                        <input type="text" alt="decimal" class="form-control mask_item desconto_pagamento" name="desconto" value="{{$agendamento->desconto}}">
                    </div>
                </div>
            </div>
        @endcan

        <div class="form-group col-md-4">
            <label class="form-control-label">Diferença de valores</span></label>
            <input type="text" alt="signed-decimal" class="form-control mask_item diferenca_pagamento" name="diferenca" disabled readonly>
        </div>

        <div class="col-md-12">
            <div class="forma_pagamento row">

                @foreach ($agendamento->contaReceber as $key => $item)
                    <div class="col-md-12 item-forma-pagamento">
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-control-label">Conta caixa: 
                                    @if (\Gate::check('habilidade_instituicao_sessao', 'editar_procedimento_pagamanto'))
                                        @if ($item->cancelar_parcela == 0)
                                            <button type="button" class="btn btn-sm btn-rounded btn-danger waves-effect waves-light cancelar-parcela-pagamento" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="right" title="" data-original-title="Cancelar parcela"><i class="fa fa-trash"></i></button>
                                        @else
                                            <span style="color: red">Parcela cancelada!</span>
                                        @endif
                                    @else
                                        @if ($agendamento->status!='finalizado') 
                                            @if ($agendamento->status!='agendado') 
                                                @if ($agendamento->status!='finalizado_medico')
                                                    @if ($item->cancelar_parcela == 0)
                                                        <button type="button" class="btn btn-sm btn-rounded btn-danger waves-effect waves-light cancelar-parcela-pagamento" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="right" title="" data-original-title="Cancelar parcela"><i class="fa fa-trash"></i></button>
                                                    @else
                                                        <span style="color: red">Parcela cancelada!</span>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                    @if ($item->status == 1)
                                        <button type="button" class="mx-2 btn btn-sm btn-rounded btn-primary waves-effect waves-light printRecibo" data-id="{{$item->id}}" data-toggle="tooltip" data-placement="right" title="" data-original-title="Imprimir recibo"><i class="mdi mdi-receipt"></i></button>
                                    @endif
                                </label>

                                <select name="conta_id_{{$key}}" class="form-control selectfild2Pagamento2" style="width: 100%" disabled>
                                    @if ($item->conta_id)
                                        <option value="" selected>{{$item->contaCaixa->descricao}}</option>
                                    @else
                                        <option value="">Selecione</option>
                                    @endif
                                    
                                    {{-- @if(in_array($item->conta_id, array_filter(array_column($contas->toArray(), 'id'))))
                                    
                                        @foreach ($contas as $conta)
                                            <option value="{{$conta->id}}" @if ($item->conta_id == $conta->id) selected @endif>{{$conta->descricao}}</option>
                                        @endforeach
                                    @else
                                        <option value="" selected>{{$item->contaCaixa->descricao}}</option>
                                    @endif --}}
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-control-label">Plano de conta:</label>
                                <select name="plano_conta_id_{{$key}}" class="form-control selectfild2Pagamento2" style="width: 100%" disabled>
                                    @foreach ($planosConta as $plano)
                                        <option value="{{$plano->id}}" @if ($item->plano_conta_id == $plano->id) selected
                                        @endif>{{$plano->codigo}} {{$plano->descricao}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label">Forma de pagamento: </label>
                                <select name="forma_pagamento_{{$key}}" class="form-control selectfild2Pagamento2" style="width: 100%" disabled>
                                    @foreach ($formaPagamento as $forma)
                                        <option value="{{$forma}}" @if ($item->forma_pagamento == $forma)
                                            selected
                                        @endif >{{App\ContaPagar::forma_pagamento_texto($forma)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="form-control-label">Valor *</span></label>
                                <input type="text" alt="decimal" value="{{$item->valor_parcela}}" class="form-control mask_item @if ($item->cancelar_parcela == 0) valor_pagamento @endif" onchange="diferencaValor()" name="valor_{{$key}}" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">Data *</span></label>
                                <input type="date" value="{{$item->data_vencimento}}" class="form-control " name="data_{{$key}}" disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <input type="checkbox" id="pagamento_plano_conta_id_{{$key}}" name="recebido_{{$key}}" class="filled-in chk-col-teal" @if ($item->status == 1)
                                    checked
                                @endif disabled/>
                                <label class="" for="pagamento_plano_conta_id_{{$key}}" style="margin-top: 33px;">Recebido<label>
                            </div>
                        </div>
                    </div>
                @endforeach


                {{-- @if (count($agendamento->contaReceber) == 0) --}}
                @if(\Gate::check('habilidade_instituicao_sessao', 'editar_procedimento_pagamanto'))
                    <div class="form-group col-md-12 add-class-forma-pagamento" >
                        <span alt="default" class="add-forma-pagamento fas fa-plus-circle" style="cursor: pointer">
                            <a class="mytooltip" href="javascript:void(0)">
                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar forma de pagamento"></i>
                            </a>
                        </span>
                    </div>
                @else
                    @if ($agendamento->status!='finalizado') 
                        @if ($agendamento->status!='agendado')
                            @if ($agendamento->status!='finalizado_medico') 
                            @if ($agendamento->status!='em_atendimento')
                                <div class="form-group col-md-12 add-class-forma-pagamento" >
                                    <span alt="default" class="add-forma-pagamento fas fa-plus-circle" style="cursor: pointer">
                                        <a class="mytooltip" href="javascript:void(0)">
                                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar forma de pagamento"></i>
                                        </a>
                                    </span>
                                </div>
                            @endif
                            @endif
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- <div class="col-md-12">
        <a href="javascrit:void(0)" class="small remove-forma-pagamento">(remover)</a>
    </div> --}}

    <script>
        var quantidade_forma_pagamento = 0;
        var desconto_maximo_d = "{{$desconto_maximo_descricao}}";
        
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            setTimeout(() => {
                // addFormaPagamento()
                $('.mask_item').setMask();
                $('.mask_item').removeClass('mask_item');
                $(".selectfild2Pagamento2").select2();
                quantidade_forma_pagamento = $(".item-forma-pagamento").length
                
                if(quantidade_forma_pagamento > 0){
                    diferencaValor();
                }else{
                    var diferenca_pagamento = retornaFormatoValor($(".total_a_pagar_pagamento").val())
                    $(".diferenca_pagamento").val(diferenca_pagamento)
                    $(".diferenca_pagamento").setMask()
                }
                
                
            }, 500);
        })
        // function quantidadeCC(){
            
        // }
        $(".desconto_pagamento").on('change', function(){
            diferencaValor()
        })
        $(".desconto_pagamento_porcento").on('change', function(){
            var total_a_pagar_pagamento = retornaFormatoValor($(".total_a_pagar_pagamento").val())
            var desconto_pagamento_porcento = $(".desconto_pagamento_porcento").val()

            var desconto_pagamento = (desconto_pagamento_porcento*total_a_pagar_pagamento)/100;
            if(desconto_pagamento_porcento > desconto_maximo_d){
                desconto_pagamento = 0;
                Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo_d+"%", "error")
            }

            $(".desconto_pagamento").val(desconto_pagamento.toFixed(2)).setMask()

            diferencaValor()
        })

        function diferencaValor(){
            var total_a_pagar_pagamento = retornaFormatoValor($(".total_a_pagar_pagamento").val())
            if($(".desconto_pagamento").length > 0){
                var desconto_pagamento = retornaFormatoValor($(".desconto_pagamento").val())
                var desconto_pagamento_porcento = (desconto_pagamento*100)/total_a_pagar_pagamento;
                
                if(desconto_pagamento_porcento > desconto_maximo_d){
                    desconto_pagamento = 0;
                    desconto_pagamento_porcento = 0;
                    Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo_d+"%", "error")
                    $(".desconto_pagamento").val(0).setMask()
                }

                $(".desconto_pagamento_porcento").val(desconto_pagamento_porcento.toFixed(2))
                
            }else{
                $(".desconto_pagamento_porcento").val(0)
            }
            

                // var diferenca_pagamento = parseFloat(total_a_pagar_pagamento) - parseFloat(desconto_pagamento);

            var valor_total_pago = 0;
            $(".valor_pagamento").each(function(index, element){
                var valor = retornaFormatoValor($(element).val())
                valor_total_pago = parseFloat(valor_total_pago) + parseFloat(valor);
            })

            var diferenca_pagamento = (parseFloat(total_a_pagar_pagamento) - parseFloat(desconto_pagamento)) - parseFloat(valor_total_pago);
            $(".diferenca_pagamento").val(diferenca_pagamento.toFixed(2))
            $(".diferenca_pagamento").setMask()
            
        }
        
        $('.forma_pagamento').on('click', '.add-forma-pagamento', function(){
            addFormaPagamento();
        });

        function addFormaPagamento(){            
            
            $($('#item-forma-pagamento').html()).insertBefore(".add-class-forma-pagamento");

            $('.mask_item').setMask();
            $('.mask_item').removeClass('mask_item');
            $(".selectfild2Pagamento").select2();

            $("[name^='pagamento[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_forma_pagamento));
            })
                
            var valor = retornaFormatoValor($(".diferenca_pagamento").val())
            $("[name='pagamento["+quantidade_forma_pagamento+"][valor]']").val(valor);
            $("[name='pagamento["+quantidade_forma_pagamento+"][valor]']").setMask();          

            
            $(".pagamento_plano_conta_id").attr('id', 'pagamento_plano_conta_id_'+quantidade_forma_pagamento);
            $(".pagamento_plano_conta_id").removeClass('pagamento_plano_conta_id');
            $(".pagamento_plano_conta_id_label").attr('for', 'pagamento_plano_conta_id_'+quantidade_forma_pagamento);
            $(".pagamento_plano_conta_id_label").removeClass('pagamento_plano_conta_id_label');
            diferencaValor()
            $('[data-toggle="tooltip"]').tooltip();
            quantidade_forma_pagamento++;
        }

        $('.forma_pagamento').on('click', '.item-forma-pagamento .remove-forma-pagamento', function(e){
            e.preventDefault()
            
            $(e.currentTarget).parents('.item-forma-pagamento').remove();
            diferencaValor()
            if ($('.forma_pagamento').find('.item-forma-pagamento').length == 0) {
                quantidade_forma_pagamento = 0;
            }
            if($(".total_a_pagar_pagamento").val() > 0){
                if ($('.forma_pagamento').find('.item-forma-pagamento').length == 0) {
                    addFormaPagamento();
                }
            }

        });

        $('.forma_pagamento').on('click', '.printRecibo', function(){
            id = $(this).data('id');
            
            if($("#acompanhanteCheckEdicao").is(":checked")){            
                Swal.fire({
                    title: "Confirmar!",
                    text: 'Deseja gerar recibo no nome do acompanhante?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não, cancelar!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if(result.isConfirmed){
                        console.log("aqui 1");
                        printRecibo(id, 1)
                    }else{
                        console.log("aqui 0");
                        printRecibo(id, 0)
                    }
                })
            }else{
                printRecibo(id, 0)
            }
            
        })

        function printRecibo(id, acompanhante){
            $.ajax("{{ route('instituicao.contasReceber.printRecibo', ['conta' => 'contaId']) }}".replace('contaId', id), {
                method: "GET",
                data: {
                    "_token": "{{csrf_token()}}",
                    "acompanhante": acompanhante,
                    "agendamento": 1,
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                    $(".print-div").css("display", "block");
                },
                success: function (resultado) {
                    $(".print-div").html(resultado);
                    
                    window.print();
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                    $(".print-div").css("display", "none");
                }
            });
        }

        $('.forma_pagamento').on('change', '.item-forma-pagamento .forma_pagamento_agendamento', function(e){
            e.preventDefault()

            if($(e.currentTarget).val() == "cartao_credito"){
                // $(e.currentTarget).parents('.item-forma-pagamento').find('.recebido_class').css('display', 'none');
                $(e.currentTarget).parents('.item-forma-pagamento').find('.num_parcelas_class').css('display', 'block');
            }else if($(e.currentTarget).val() == "cartao_debito"){
                $(e.currentTarget).parents('.item-forma-pagamento').find('.num_parcelas_class').css('display', 'none');
                $(e.currentTarget).parents('.item-forma-pagamento').find('.cartao_debito').css('display', 'block');
            }else if($(e.currentTarget).val() == "boleto_cobranca"){
                $(e.currentTarget).parents('.item-forma-pagamento').find('.num_parcelas_class').css('display', 'none');
                $(e.currentTarget).parents('.item-forma-pagamento').find('.cartao_debito').css('display', 'none');
                $(e.currentTarget).parents('.item-forma-pagamento').find('.parcela_boleto').css('display', 'block');
            }else{
                $(e.currentTarget).parents('.item-forma-pagamento').find('.recebido_class').css('display', 'block');
                $(e.currentTarget).parents('.item-forma-pagamento').find('.num_parcelas_class').css('display', 'none');
            }

        });

        $('.forma_pagamento').on('change', '.item-forma-pagamento .maquina_id_agendamento', function(e){
            forma_pagamento = $(e.currentTarget).parents('.item-forma-pagamento').find('.forma_pagamento_agendamento').val();
            if(forma_pagamento == 'cartao_credito'){
                index = $(e.currentTarget).parents('.item-forma-pagamento').find('.num_parcelas').val()-1;
                taxa = ($(this).find("option:selected").val() != '') ? $(this).find("option:selected").data('credito')[index] : 0;
                
                valor = $(e.currentTarget).parents('.item-forma-pagamento').find('.valor_pagamento').val().replace('.', '').replace(',','.')
                valor_taxa = valor * taxa / 100;

                valor_taxa = valor_taxa ? valor_taxa : 0;
                
                data_vencimento = new Date();
                data_vencimento.setDate( data_vencimento.getDate() + $(this).find("option:selected").data('dias_credito'));

                $(e.currentTarget).parents('.item-forma-pagamento').find('.taxa_cartao').val(valor_taxa.toFixed(2))
                $(e.currentTarget).parents('.item-forma-pagamento').find('.vencimento').val(data_vencimento.toISOString().split('T')[0])
                
            }else if(forma_pagamento == 'cartao_debito'){
                taxa = ($(this).find("option:selected").val() != '') ? $(this).find("option:selected").data('debito') : 0;
                
                valor = $(e.currentTarget).parents('.item-forma-pagamento').find('.valor_pagamento').val().replace('.', '').replace(',','.')
                valor_taxa = valor * taxa / 100;

                valor_taxa = valor_taxa ? valor_taxa : 0;

                data_vencimento = new Date();
                data_vencimento.setDate( data_vencimento.getDate() + $(this).find("option:selected").data('dias_debito'));

                $(e.currentTarget).parents('.item-forma-pagamento').find('.taxa_cartao').val(valor_taxa.toFixed(2))
                $(e.currentTarget).parents('.item-forma-pagamento').find('.vencimento').val(data_vencimento.toISOString().split('T')[0])
            }

            $(e.currentTarget).parents('.item-forma-pagamento').find('.taxa_cartao').setMask()
        });

        $('.forma_pagamento').on('change', '.item-forma-pagamento .num_parcelas', function(e){
            forma_pagamento = $(e.currentTarget).parents('.item-forma-pagamento').find('.forma_pagamento_agendamento').val();
            if(forma_pagamento == 'cartao_credito'){
                index = $(this).val()-1;
                taxa = ($(this).find("option:selected").val() != '') ? $(e.currentTarget).parents('.item-forma-pagamento').find(".maquina_id_agendamento option:selected").data('credito')[index] : 0
                
                valor = $(e.currentTarget).parents('.item-forma-pagamento').find('.valor_pagamento').val().replace('.', '').replace(',','.')
                valor_taxa = valor * taxa / 100;

                valor_taxa = valor_taxa ? valor_taxa : 0;

                data_vencimento = new Date();
                data_vencimento.setDate( data_vencimento.getDate() + $(this).find("option:selected").data('dias_credito'));

                $(e.currentTarget).parents('.item-forma-pagamento').find('.taxa_cartao').val(valor_taxa.toFixed(2))
                $(e.currentTarget).parents('.item-forma-pagamento').find('.vencimento').val(data_vencimento.toISOString().split('T')[0])
                
                
            }else if(forma_pagamento == 'cartao_debito'){
                taxa = ($(this).find("option:selected").val() != '') ? $(e.currentTarget).parents('.item-forma-pagamento').find(".maquina_id_agendamento option:selected").data('debito') : 0
                
                valor = $(e.currentTarget).parents('.item-forma-pagamento').find('.valor_pagamento').val().replace('.', '').replace(',','.')
                valor_taxa = valor * taxa / 100;

                valor_taxa = valor_taxa ? valor_taxa : 0;

                data_vencimento = new Date();
                data_vencimento.setDate( data_vencimento.getDate() + $(this).find("option:selected").data('dias_debito'));

                $(e.currentTarget).parents('.item-forma-pagamento').find('.taxa_cartao').val(valor_taxa.toFixed(2))
                $(e.currentTarget).parents('.item-forma-pagamento').find('.vencimento').val(data_vencimento.toISOString().split('T')[0])
            }

            $(e.currentTarget).parents('.item-forma-pagamento').find('.taxa_cartao').setMask()
        });
    </script>

    <script type="text/template" id="item-forma-pagamento">
        <div class="col-md-12 item-forma-pagamento">
            <hr>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="form-control-label">Conta caixa: <a href="javascrit:void(0)" class="small remove-forma-pagamento">(remover)</a></label>
                    <select name="pagamento[#][conta_id]" class="form-control selectfild2Pagamento" style="width: 100%">
                        @foreach ($contas as $conta)
                            <option value="{{$conta->id}}">{{$conta->descricao}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-control-label">Plano de conta:</label>
                    <select name="pagamento[#][plano_conta_id]" class="form-control selectfild2Pagamento" style="width: 100%">
                        @foreach ($planosConta as $plano)
                            <option value="{{$plano->id}}">{{$plano->codigo}} {{$plano->descricao}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label">Forma de pagamento: </label>
                    <select name="pagamento[#][forma_pagamento]" class="form-control selectfild2Pagamento forma_pagamento_agendamento" style="width: 100%">
                        @foreach ($formaPagamento as $item)
                            <option value="{{$item}}">{{App\ContaPagar::forma_pagamento_texto($item)}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label class="form-control-label">Valor *</label>
                    <input type="text" alt="decimal" class="form-control mask_item valor_pagamento" onchange="diferencaValor()" name="pagamento[#][valor]">
                </div>
                <div class="form-group col-md-3 recebido_class">
                    <label class="form-control-label">
                        Data *                       
                    </label>
                    <input type="date" value="{{date('Y-m-d')}}" class="form-control vencimento" name="pagamento[#][data]"/> <i class="fa fa-question-circle help num_parcelas_class" data-toggle="tooltip" data-placement="right" title="" data-original-title="Data de vencimento da primeira parcela. (As proximas serão geradas a cada 30 dias da primeira)" style="display: none;"></i> 
                </div>
                <div class="form-group col-md-3 recebido_class">
                    <input type="checkbox" id="pagamento_plano_conta_id" name="pagamento[#][recebido]" checked class="filled-in chk-col-teal pagamento_plano_conta_id"/>
                    <label class="pagamento_plano_conta_id_label" for="pagamento_plano_conta_id" style="margin-top: 33px;">Recebido<label>
                </div>
                <div class="form-group col-md-3 num_parcelas_class parcela_boleto" style="display: none">
                    <label class="form-control-label">Nº parcelas *</label>
                    <input type="number" class="form-control num_parcelas" name="pagamento[#][num_parcelas]" value="1">
                </div>
                <div class="form-group col-md-3 num_parcelas_class cartao_debito" style="display: none">
                    <label class="form-control-label">Maquina de cartão</label>
                    <select name="pagamento[#][maquina_id]" class="form-control selectfild2Pagamento maquina_id_agendamento" style="width: 100%">
                        <option value="">Nenhuma</option>
                        @foreach ($maquinas_cartao as $item)
                            <option value="{{$item->id}}" data-credito="{{$item->taxa_credito}}" data-dias_credito="{{$item->dias_parcela_credito}}" data-debito="{{$item->taxa_debito}}" data-dias_debito="{{$item->dias_parcela_debito}}">{{$item->codigo}} {{$item->descricao}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3 num_parcelas_class cartao_debito" style="display: none">
                    <label class="form-control-label">Taxa de cartão</label>
                    <input type="text" alt="money" class="form-control taxa_cartao" name="pagamento[#][taxa]" readonly>
                </div>

                <div class="form-group col-md-3 num_parcelas_class cartao_debito" style="display: none">
                    <label class="form-control-label">Cod Autorização</label>
                    <input type="text" class="form-control" name="pagamento[#][cod_aut]" >
                </div>
            </div>
        </div>
    </script>

