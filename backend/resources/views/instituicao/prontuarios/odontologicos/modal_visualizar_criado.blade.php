<style>
    #table_criado_show input[type='checkbox']{
        position: relative;
        left: 0px;
        opacity: 1;
    }
    .hr-line{
        width: 100%;
        border-style: dashed;
    }
    /* #itens_modal_criado .fixed-table-footer{
        position: absolute!important;
        bottom: 0!important;
    } */
</style>
<div id="modalVisualizar" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detalhes do orçamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="orcamento_id" id="orcamento_id" value="{{($orcamento) ? $orcamento->id : ''}}">
        
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#itens_modal_criado" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Itens</span></a> </li>
                    @can('habilidade_instituicao_sessao', 'aprovar_orcamento_odontologico')
                        <li class="nav-item"> <a class="nav-link" id="financeiro_modal_criado_tab" data-toggle="tab" href="#financeiro_modal_criado" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Financeiro</span></a> </li>
                    @endcan
                    @can('habilidade_instituicao_sessao', 'visualizar_laboratorio_odontologico')
                        <li class="nav-item"> <a class="nav-link" id="laboratorio_modal_criado_tab" data-toggle="tab" href="#laboratorio_modal_criado" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Laboratório</span></a> </li>
                    @endcan
                </ul>
                <div class="tab-content tabcontent-border">
                    
                    <div class="tab-pane p-20 active" id="itens_modal_criado" role="tabpanel" >
                        <div id="table_criado_show" style="display: none">
                            <table data-toggle="table" data-height="300" id="table_criado" >
                                <thead>
                                    <tr>
                                        <th data-field="state" data-checkbox="true" class="checkbox_modal_criado state_table_head"></th>
                                        <th class=" dentes_table_head" data-field="dentes">Dentes</th>
                                        <th class=" procedimento_table_head" data-field="procedimento">Procedimento</th>
                                        <th class=" convenio_table_head" data-field="convenio">Convenio</th>
                                        <th class=" regiao_table_head" data-field="regiao">Região</th>
                                        <th class=" desconto_table_head" data-field="desconto">Desconto/Acréscimo</th>
                                        <th class=" valor_table_head" data-field="valor">Valor</th>
                                    </tr>
                                </thead>
                                <tbody class="itens_modal_table_body">
                                    @foreach ($orcamento->itens as $item)
                                        <tr class="tr_modal_criado " data-valor="{{$item->valor}}" data-id="{{$item->id}}" @if ($item->procedimento_instituicao_convenio_id)
                                            data-convenio="{{$item->procedimentos->convenios->id}}"
                                        @endif data-desconto="{{$item->desconto}}"> 
                                            <td></td>
                                            <td data-id="{{$item->id}}">{{$item->dente_id}}</td>
                                            <td>{{$item->procedimentosItens->descricao}}</td>
                                            <td class="select2convenio">
                                                {{-- <div class="form-group"> --}}
                                                    <select class="select2CriadoOd" name="procedimento_instituicao_convenio_id" style="width: 90%" onchange="changeConvenio(this)">
                                                        <option value="">Selecione um convenio</option>
                                                        @foreach ($item->procedimentosItens->procedimentoInstituicaoOdontologico[0]->instituicaoProcedimentosConvenios as $convenio)
                                                            <option value="{{$convenio->id}}" data-valor="{{$convenio->pivot->valor}}" @if ($item->procedimento_instituicao_convenio_id) @if ($item->procedimentos->convenios->id == $convenio->id)
                                                                selected
                                                            @endif @endif>{{$convenio->nome}}</option>
                                                        @endforeach
                                                    </select>
                                                {{-- </div> --}}
                                            </td>
                                            <td>
                                                @if ($item->regiao)
                                                    {{($item->regiao) ? $item->regiao->descricao : ''}}
                                                @elseif(count($item->regiaoProcedimento) > 0)
                                                    @foreach ($item->regiaoProcedimento as $keyR => $regiao)
                                                        @if ($keyR == 0)
                                                            {{$regiao->descricao}}
                                                        @else
                                                             / {{$regiao->descricao}}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="input_desconto_orcamento"><input type="text" alt="signed-decimal" class="form-control desc_juros_multa vertical-spin" data-bts-button-up-class="btn btn-secondary btn-outline down-button" data-bts-button-down-class="btn btn-secondary btn-outline up-button" name="desc_juros_multa" placeholder="-0,00" value="{{($item->desconto != 0) ? $item->desconto : '-0,00'}}"></td>
                                            <td data-valor="{{$item->valor}}" class="valor_item">
                                                @if ($item->procedimento_instituicao_convenio_id)
                                                    R$ {{number_format($item->procedimentos->valor, 2, ',','.')}}
                                                @else
                                                    R$ {{number_format($item->valor, 2, ',','.')}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="position: relative; left: 0px; opacity: 1;"></td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                        <td >Total</td>
                                        <td class='total_procedimentos_criado'>R$ {{number_format($orcamento->valor_total, 2, ',', '.')}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-control-label">Convênios</label>
                                <select name="convenios_id" id="convenios_id" class="form-control select2CriadoOdontologico" style="width: 100%">
                                    <option value="">Selecione um convênio</option>
                                    @foreach ($convenios as $item)
                                        <option value="{{$item->id}}">{{$item->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <input type="checkbox" id="todos_checkbox" name="todos_checkbox" checked class="filled-in chk-col-teal todos_checkbox"/>
                                <label class="todos_checkbox_label" for="todos_checkbox" style="margin-top: 40px;">Somente convênios não selecionados<label>
                            </div>
                        </div>

                        <hr class="hr-line">
                        
                        <form id="formCriadoOdontologicoNegociadorResponsavel">
                            @csrf
                            <input type="hidden" name="itens_alteracoes" id="itens_alteracoes">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label">Negociador</label>
                                    <select name="negociador_id_item" id="negociador_id_item" class="form-control select2CriadoOdontologico" style="width: 100%">
                                        <option value="">Selecione um negociador</option>
                                        @foreach ($usuarios as $item)
                                            <option value="{{$item->id}}" @if ($orcamento->negociador_id == $item->id)
                                                selected
                                            @endif>{{$item->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-control-label">Responsavel</label>
                                    <select name="responsavel_id_item" id="responsavel_id_item" class="form-control select2CriadoOdontologico" style="width: 100%">
                                        <option value="">Selecione um responsavel</option>
                                        @foreach ($usuarios as $item)
                                            <option value="{{$item->id}}" @if ($orcamento->responsavel_id == $item->id)
                                                selected
                                            @endif>{{$item->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-groupn text-right pb-2">
                                {{-- @can('habilidade_instituicao_sessao', 'alterar_orcamento_odontologico_negociador_responsavel') --}}
                                    <button type="button" class="btn btn-info waves-effect nav-link alterar_negociador_responsavel">Salvar alterações</button>
                                {{-- @endcan --}}
                            </div>
                        </form>

                        <hr class="hr-line">

                        <div class="form-groupn text-right pb-2">
                            @can('habilidade_instituicao_sessao', 'aprovar_orcamento_odontologico')
                                <button type="button" class="btn btn-info waves-effect nav-link aprovar_orcamento_modal">Aprovar orçamento</button>
                            @endcan
                        </div>
                    </div>
                    <div class="tab-pane p-20" id="financeiro_modal_criado" role="tabpanel">
                        <form id="pagamentoCriadoOdontologico">
                            @csrf
                            <input type="hidden" name="itens_aprovados" id="itens_aprovados">
                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label class="form-control-label">Negociador</label>
                                    <select name="negociador_id" id="negociador_id" class="form-control select2CriadoOdontologico" style="width: 100%">
                                        <option value="">Selecione um negociador</option>
                                        @foreach ($usuarios as $item)
                                            <option value="{{$item->id}}" @if ($orcamento->negociador_id == $item->id)
                                                selected
                                            @endif>{{$item->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-control-label">Responsavel</label>
                                    <select name="responsavel_id" id="responsavel_id" class="form-control select2CriadoOdontologico" style="width: 100%">
                                        <option value="">Selecione um responsavel</option>
                                        @foreach ($usuarios as $item)
                                            <option value="{{$item->id}}" @if ($orcamento->responsavel_id == $item->id)
                                                selected
                                            @endif>{{$item->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <input type="hidden" id="verificar_total_a_pagar_pagamento" name="total_a_pagar_pagamento" @if ($orcamento->valor_total > 0)
                                    value="{{$orcamento->valor_total}}"
                                @endif>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Total a pagar</span></label>
                                    <input type="text" alt="decimal" class="form-control mask_item total_a_pagar_pagamento" value="{{$orcamento->valor_total}}" disabled readonly>
                                </div>
                                @can('habilidade_instituicao_sessao', 'desconto_orcamento_odontologico')
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="form-control-label">Desconto (%)</span></label>
                                                <input type="text" alt="porcentagem" class="form-control mask_item desconto_pagamento_porcento" name="desconto_porcento" value="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-control-label">Desconto (R$)</span></label>
                                                <input type="text" alt="decimal" class="form-control mask_item desconto_pagamento" name="desconto" value="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                @endcan
                        
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">Diferença de valores</span></label>
                                    <input type="text" alt="signed-decimal" class="form-control mask_item diferenca_pagamento" name="diferenca" value="{{$orcamento->valor_total}}" disabled readonly>
                                </div>
                        
                                <div class="col-md-12">
                                    <div class="forma_pagamento row">
                        
                                        <div class="form-group col-md-12 add-class-forma-pagamento" >
                                            <span alt="default" class="add-forma-pagamento fas fa-plus-circle" style="cursor: pointer">
                                                <a class="mytooltip" href="javascript:void(0)">
                                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar forma de pagamento"></i>
                                                </a>
                                            </span>
                                        </div>
                                                    
                                    </div>
                                </div>
                            </div>
                            <div class="form-groupn text-right pb-2">
                                @can('habilidade_instituicao_sessao', 'aprovar_orcamento_odontologico')
                                    <button type="button" class="btn btn-info waves-effect nav-link salvar_orcamento_modal_criado">Finalizar orçamento</button>
                                @endcan
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane p-20" id="laboratorio_modal_criado" role="tabpanel">
                        @include('instituicao/prontuarios/odontologicos/laboratorio')
                    </div>
                    
                </div>
                
            </div>
            <div class="modal-footer">
                {{-- <div class="row"> --}}
                    <div class="form-groupn col-md-6 text-left pb-2">
                        <button type="button" class="btn btn-secondary waves-effect" onclick="liberaImprimirOdontologico({{$orcamento->id}})">Imprimir tudo</button>
                        <button type="button" class="btn btn-secondary waves-effect waves-light" onclick="liberaImprimirOdontologicoTotal({{$orcamento->id}})">Imprimir somente total</button>
                    </div>
                    <div class="form-groupn col-md-6 text-right pb-2">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>

<script>
    var checkedRows = [];
    var inicio = 0;
    var quantidade_forma_pagamento  = 0;
    var $table = $('#table_criado');
    var $table_lab = $("#laboratorio_modal_criado").find('#table_lab');
    var inicio = true;
    var verifica_up = false;

    $(document).ready(function() {
        $(".select2CriadoOdontologico").select2()
        $(".mask_item").setMask()
        $(".vertical-spin").TouchSpin({
            verticalbuttons: true
        });

        setTimeout(() => {
            
            $("#table_criado_show").css('display', 'block')
            // setaValoresCheckRow()
            $table.bootstrapTable().on('check.bs.table', function (e, row) {
                
                // checkedRows.push({valor: row._valor_data['valor'], id: row._dentes_data['id']});
                setaValoresCheckRow()
                // calculaTotalCriado()
            }).on('uncheck.bs.table', function (e, row) {
                $.each(checkedRows, function(index, value) {
                    if (value.id == row._dentes_data['id']) {
                        checkedRows.splice(index,1);
                        return false; 
                    }
                });
                calculaTotalCriado()
            }).on('check-all.bs.table', function (e, row) {
                checkedRows = []
                // $.each(row, function(index, value){
                //     checkedRows.push({valor: value._valor_data['valor']});
                // })
                setaValoresCheckRow()
                calculaTotalCriado()
            }).on('uncheck-all.bs.table', function (e, row) {
                checkedRows = []
                calculaTotalCriado()
            }).on('post-header.bs.table', function(){
                $(".select2CriadoOd").select2()
            })
            iniciaModalCriadoOdontologico()
            $(".desc_juros_multa").setMask();
            $(".desc_juros_multa").css('text-align', 'left');
            $(".input_desconto_orcamento").css('width', '20%');
            $(".state_table_head").css('width', '5%');
            $(".dentes_table_head").css('width', '5%');
            $(".procedimento_table_head").css('width', '20%');
            $(".convenio_table_head").css('width', '20%');
            $(".regiao_table_head").css('width', '10%');
            $(".desconto_table_head").css('width', '20%');
            $(".valor_table_head").css('width', '20%');
            inicio = false;

            $table_lab.bootstrapTable().on('check.bs.table', function (e, row) {
                
                // checkedRows.push({valor: row._valor_data['valor'], id: row._dentes_data['id']});
                // setaValoresCheckRow()
                // calculaTotalCriado()
            })
            $(".lab_state_table_head").css('width', '1%');
            $(".lab_dentes_table_head").css('width', '19%');
            $(".lab_procedimento_table_head").css('width', '20%');
            $(".lab_regiao_table_head").css('width', '20%');
            $(".lab_desconto_table_head").css('width', '20%');
            $(".lab_valor_table_head").css('width', '20%');
            $(".valor_lab").setMask();
        }, 500);
        

        // $('.checkbox_modal_criado').find('[name="btSelectAll"]').prop('checked', true)
        // $('.itens_modal_table_body').find('[name="btSelectItem"]').prop('checked', true)
        // $('.itens_modal_table_body').find('.tr_modal_criado').addClass('selected');
        // calculaTotalCriado()
    })
    
    $('#table_criado_show').on('click', '.bootstrap-touchspin-up', function(){
        var desc = retornaFormatoValor($(this).parents('.input_desconto_orcamento').find('.desc_juros_multa').val())
        if( desc < 0){
            desc = parseFloat(desc) * -1;
            $(this).parents('.input_desconto_orcamento').find('.desc_juros_multa').val(desc.toFixed(2)).setMask()
        }else if(desc == 0){
            $(this).parents('.input_desconto_orcamento').find('.desc_juros_multa').val('0').setMask()
        }
        $(".desc_juros_multa").css('text-align', 'left');
        verifica_up = true;
        calculaDesconto($(this).parents('.input_desconto_orcamento').find('.desc_juros_multa'))
    })
    
    $('#table_criado_show').on('click', '.bootstrap-touchspin-down', function(){
        var desc = retornaFormatoValor($(this).parents('.input_desconto_orcamento').find('.desc_juros_multa').val())
        if( desc > 0){
            desc = parseFloat(desc) * -1;
            $(this).parents('.input_desconto_orcamento').find('.desc_juros_multa').val(desc.toFixed(2)).setMask()
            
        }else if(desc == 0){
            $(this).parents('.input_desconto_orcamento').find('.desc_juros_multa').val('-0').setMask()
        }
        $(".desc_juros_multa").css('text-align', 'left');
        verifica_up = false;
        calculaDesconto($(this).parents('.input_desconto_orcamento').find('.desc_juros_multa'))
    })

    $("#table_criado_show").on('change', '.desc_juros_multa', function(){
        if(inicio == false){
            calculaDesconto($(this));
        }
    })

    function calculaDesconto(element){
        // CALCULA DESCONTO
        if(element.parents('.tr_modal_criado').attr('data-convenio')){

            var desc = retornaFormatoValor(element.val());

            var coluna_valor = element.parents('.tr_modal_criado').find('.valor_item')
            var valor = coluna_valor.attr('data-valor');

            var porcentual_desconto = (desc*100)/valor;                
            if(desc < 0){
                porcentual_desconto = porcentual_desconto*-1;
            }

            if(porcentual_desconto > desconto_maximo && desc < 0){
                Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo+"%", "error")
                element.val('-0.00');
                desc = 0;
            }
            
            var total = parseFloat(valor) + parseFloat(desc);

            var id = element.parents('.tr_modal_criado').attr('data-id');

            element.attr('data-desc', desc);
            element.parents('.tr_modal_criado').attr('data-desc', desc);
            element.parents('.tr_modal_criado').attr('data-valor', total.toFixed(2));

            calculaValorLaboratorio(element.parents('.tr_modal_criado').find('.select2CriadoOd'));

            if(element.parents('.tr_modal_criado').find('[name="btSelectItem"]').is(':checked')){
                $.each(checkedRows, function(index, value) {
                    if (value.id == id) {
                        value.desconto = desc
                        value.valor = total.toFixed(2)
                        return
                    }
                });
            }

            total = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL', minimumFractionDigits: 2}).format(total);
            coluna_valor.text('');
            coluna_valor.text(total);

            calculaTotalCriado()    
            

        }else{
            $.toast({
                title: 'Error',
                text: 'Selecione um convenio e depois informe o desconto/acréscimo!',
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'error',
                hideAfter: 9000,
                stack: 10
            })
            element.val('-0.00');
        }
    }

    function setaValoresCheckRow(){
        checkedRows = [];
        $(".tr_modal_criado").each(function(index, element){
            if($(element).find('.select2CriadoOd option:selected').val() != ""){
                
                if($(element).find('[name="btSelectItem"]').is(':checked')){
                    var laboratorio = getValorLab($(element).find('.select2CriadoOd'))
                    checkedRows.push({valor: $(element).attr('data-valor'), id: $(element).attr('data-id'), convenio: $(element).attr('data-convenio'), desconto: $(element).attr('data-desconto'), laboratorio: laboratorio});
                    calculaValorLaboratorio($(element).find('.select2CriadoOd'))
                }
            }else{
                $.toast({
                    heading: 'Error',
                    text: 'Selecione um convênio!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 9000,
                    stack: 10
                })
                $(element).removeClass('selected');
                $(element).find('[name="btSelectItem"]').prop('checked', false);
            }
        });
        
        calculaTotalCriado()
    }
    
    function iniciaModalCriadoOdontologico(){
        checkedRows = [];
        $(".tr_modal_criado").each(function(index, element){
            if($(element).find('.select2CriadoOd option:selected').val() != ""){
                $(element).addClass('selected');
                $(element).find('[name="btSelectItem"]').prop('checked', true);
                
                var total = $(element).attr('data-valor');
                
                if($(element).attr('data-desconto') != "0"){
                    
                    var desc = $(element).attr('data-desconto');
                    var valor = $(element).attr('data-valor');

                    total = parseFloat(valor) + parseFloat(desc);
                    $(element).attr('data-valor', total);
                }
                var laboratorio = getValorLab($(element).find('.select2CriadoOd'))
                
                checkedRows.push({valor: total, id: $(element).attr('data-id'), convenio: $(element).attr('data-convenio'), desconto: $(element).attr('data-desconto'), laboratorio: laboratorio});
                calculaValorLaboratorio($(element).find('.select2CriadoOd'))
                if($(element).attr('data-desconto') != "0"){
                    total = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL', minimumFractionDigits: 2}).format(total);
                    $(element).find('.valor_item').text('');
                    $(element).find('.valor_item').text(total);
                }
            }

            
        });
        
        calculaTotalCriado()
    }

    function changeConvenio(element){
        if($(element).val() != ""){
            $(element).parents('.tr_modal_criado').addClass('selected');
            $(element).parents('.tr_modal_criado').find('[name="btSelectItem"]').prop('checked', true);
            $(element).parents('.tr_modal_criado').attr('data-convenio', $(element).val())
            $(element).parents('.tr_modal_criado').attr('data-valor', $(element).find("option:selected").attr('data-valor'))
            var valor = $(element).find("option:selected").attr('data-valor');
            $(element).parents('.tr_modal_criado').find('.valor_item').text('R$ '+valor.replace('.',','))
            $(element).parents('.tr_modal_criado').find('.valor_item').attr('data-valor', $(element).find("option:selected").attr('data-valor'))

            calculaValorLaboratorio(element);

            var id = $(element).parents('.tr_modal_criado').attr('data-id')
            $.each(checkedRows, function(index, value) {
                if (value.id == id) {
                    checkedRows.splice(index,1);
                    return false; 
                }
            });

            checkedRows.push({valor: $(element).parents('.tr_modal_criado').attr('data-valor'), id: $(element).parents('.tr_modal_criado').attr('data-id'), convenio: $(element).parents('.tr_modal_criado').attr('data-convenio'), desconto: $(element).parents('.tr_modal_criado').attr('data-desconto')});
        }else{
            $(element).parents('.tr_modal_criado').removeClass('selected');
            $(element).parents('.tr_modal_criado').find('[name="btSelectItem"]').prop('checked', false);
            $(element).parents('.tr_modal_criado').attr('data-convenio', "")
            var id = $(element).parents('.tr_modal_criado').attr('data-id')
            $(element).parents('.tr_modal_criado').attr('data-valor', "")
            $(element).parents('.tr_modal_criado').find('.valor_item').text('R$ 0,00')
            calculaValorLaboratorio(element);
            $.each(checkedRows, function(index, value) {
                if (value.id === id) {
                    checkedRows.splice(index,1);
                    return false; 
                }
            });
        }
        
        calculaTotalCriado()
        // setaValoresCheckRow()
    }

    $("#convenios_id").on('change', function(){
        var id = $("#convenios_id").val();
        $(".select2CriadoOd").each(function(index, element){
            if(!$(element).parents('.tr_modal_criado').find('[name="btSelectItem"]').is(':checked') || !$("#todos_checkbox").is(':checked')){
                $(element).find('option').each(function(i,e){
                    if($(e).val() == id){
                        $(e).attr('selected', true);
                    }
    
                    if($(e).val() != id && $(e).find(':selected')){
                        $(e).attr('selected', false);
                    }
                })
                $(element).change()
            }
        })
    })
    
    function calculaTotalCriado(){
        var total = 0;
        var ids = "";
        // if(inicio == 0){
        //     $('.tr_modal_criado').each(function(index, element){
        //         var valor = $(element).attr('data-valor');
        //         var id = $(element).attr('data-id');
        //         if(ids == ""){
        //             ids = id;
        //         }else{
        //             ids = ids+";"+id
        //         }
        //         checkedRows.push({valor: valor, id: id});
        //     })
            
        //     inicio = 1;
        // }else{
            $.each(checkedRows, function(index, value) {
                var valor = value.valor;
                var laboratorio = 0.00;
                if(value.laboratorio > 0){
                    laboratorio = value.laboratorio
                }
                
                if(ids == ""){
                    ids = value.id+','+value.convenio+','+value.desconto+','+laboratorio;
                }else{
                    ids = ids+";"+value.id+','+value.convenio+','+value.desconto+','+laboratorio;
                }
                total += parseFloat(valor);
            });

            $(".total_procedimentos_criado").text("R$ "+total.toFixed(2).replace('.',','))
            $(".total_a_pagar_pagamento").val(total.toFixed(2)).setMask()
            diferencaValor()
        // }
        
        $("#itens_aprovados").val(ids)
        $("#itens_alteracoes").val(ids)
    }
    
    $(".aprovar_orcamento_modal").on('click', function(){
        $('#financeiro_modal_criado_tab').trigger('click')
    })

    $(".alterar_negociador_responsavel").on('click', function(e){
        e.preventDefault()
        e.stopPropagation()

        var formData = new FormData($("#formCriadoOdontologicoNegociadorResponsavel")[0]);

        var paciente_id = $("#paciente_id").val();
        var orcamento_id = $("#orcamento_id").val();

        $.ajax({
            url: "{{route('instituicao.odontologico.alterarNegociadorResponsavel', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Orçamento alterado com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function(response) {
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

    $(".desconto_pagamento_porcento").on('change', function(){
        var total_a_pagar_pagamento = retornaFormatoValor($(".total_a_pagar_pagamento").val())
        var desconto_pagamento_porcento = $(".desconto_pagamento_porcento").val()

        var desconto_pagamento = (desconto_pagamento_porcento*total_a_pagar_pagamento)/100;

        $(".desconto_pagamento").val(desconto_pagamento.toFixed(2)).setMask()

        diferencaValor()
    })

    $(".desconto_pagamento").on('change', function(){
        diferencaValor()
    })

    function diferencaValor(){
        var total_a_pagar_pagamento = retornaFormatoValor($(".total_a_pagar_pagamento").val())
        if(total_a_pagar_pagamento == 0){
            $("#verificar_total_a_pagar_pagamento").val(null)
        }else{
            $("#verificar_total_a_pagar_pagamento").val(total_a_pagar_pagamento)
        }
        var desconto_pagamento = 0;
        if($(".desconto_pagamento").length > 0){
            desconto_pagamento = retornaFormatoValor($(".desconto_pagamento").val())
        }

        if(total_a_pagar_pagamento > 0){
            var porcentual_desconto = (parseFloat(desconto_pagamento)*100)/parseFloat(total_a_pagar_pagamento);
            
            
            if(porcentual_desconto > desconto_maximo){
                Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo+"%", "error")
                $(".desconto_pagamento").val('0.00');
                $(".desconto_pagamento_porcento").val('0.00');
                desconto_pagamento = 0;
            }// var diferenca_pagamento = parseFloat(total_a_pagar_pagamento) - parseFloat(desconto_pagamento);
        }

        var valor_total_pago = 0;
        $(".valor_pagamento").each(function(index, element){
            var valor = retornaFormatoValor($(element).val())
            valor_total_pago = parseFloat(valor_total_pago) + parseFloat(valor);
        })

        var diferenca_pagamento = (parseFloat(total_a_pagar_pagamento) - parseFloat(desconto_pagamento)) - parseFloat(valor_total_pago);
        if(total_a_pagar_pagamento > 0 && diferenca_pagamento == 0){
            $("#verificar_total_a_pagar_pagamento").val(null)
        }
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
        quantidade_forma_pagamento++;
    }

    $('.forma_pagamento').on('click', '.item-forma-pagamento .remove-forma-pagamento', function(e){
        e.preventDefault()
        
        $(e.currentTarget).parents('.item-forma-pagamento').remove();
        diferencaValor()
        if ($('.forma_pagamento').find('.item-forma-pagamento').length == 0) {
            quantidade_forma_pagamento = 0;
            addFormaPagamento();
        }

    });
   
    $('.forma_pagamento').on('change', '.item-forma-pagamento .forma_pagamento_odontologico', function(e){
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
            forma_pagamento = $(e.currentTarget).parents('.item-forma-pagamento').find('.forma_pagamento_odontologico').val();
            if(forma_pagamento == 'cartao_credito'){
                index = $(e.currentTarget).parents('.item-forma-pagamento').find('.num_parcelas').val()-1;
                taxa = ($(this).find("option:selected").val() != '') ? $(this).find("option:selected").data('credito')[index] : 0;
                
                valor = $(e.currentTarget).parents('.item-forma-pagamento').find('.valor_pagamento').val().replace('.', '').replace(',','.')
                valor_taxa = valor * taxa / 100;

                valor_taxa = valor_taxa ? valor_taxa : 0;

                $(e.currentTarget).parents('.item-forma-pagamento').find('.taxa_cartao').val(valor_taxa.toFixed(2))
                
                data_vencimento = new Date();
                data_vencimento.setDate( data_vencimento.getDate() + $(this).find("option:selected").data('dias_credito'));
                
                $(e.currentTarget).parents('.item-forma-pagamento').find('.vencimento').val(data_vencimento.toISOString().split('T')[0])
                
                
                
            }else if(forma_pagamento == 'cartao_debito'){
                taxa = ($(this).find("option:selected").val() != '') ? $(this).find("option:selected").data('debito') : 0;
                
                valor = $(e.currentTarget).parents('.item-forma-pagamento').find('.valor_pagamento').val().replace('.', '').replace(',','.')
                valor_taxa = valor * taxa / 100;

                valor_taxa = valor_taxa ? valor_taxa : 0;

                $(e.currentTarget).parents('.item-forma-pagamento').find('.taxa_cartao').val(valor_taxa.toFixed(2))

                data_vencimento = new Date();
                data_vencimento.setDate( data_vencimento.getDate() + $(this).find("option:selected").data('dias_debito'));

            
                $(e.currentTarget).parents('.item-forma-pagamento').find('.vencimento').val(data_vencimento.toISOString().split('T')[0])
                
            }

            $(e.currentTarget).parents('.item-forma-pagamento').find('.taxa_cartao').setMask()
        });

        $('.forma_pagamento').on('change', '.item-forma-pagamento .num_parcelas', function(e){
            forma_pagamento = $(e.currentTarget).parents('.item-forma-pagamento').find('.forma_pagamento_odontologico').val();
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

    function retornaFormatoValor(valor){
        var novo = valor;
        novo = novo.replace('.','')
        novo = novo.replace(',','.')
        return novo;
    }

    function calculaValorLaboratorio(element){

        total = $(element).find('option:selected').attr('data-valor');
        if($(element).parents('.tr_modal_criado').attr('data-desconto') != "0"){
            var desc = $(element).parents('.tr_modal_criado').attr('data-desconto');
            var valor = total;

            total = parseFloat(valor) + parseFloat(desc);
        }
        // if($("#laboratorio_modal_criado").length > 0){
            total = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL', minimumFractionDigits: 2}).format(total);
            $("#laboratorio_modal_criado").find(".item_"+$(element).parents('.tr_modal_criado').attr('data-id')).find('.valor_item').text('');
            $("#laboratorio_modal_criado").find(".item_"+$(element).parents('.tr_modal_criado').attr('data-id')).find('.valor_item').text(total);
        // }
    }

    function getValorLab(element){
        // if($("#laboratorio_modal_criado").length > 0){
            var valor_lab = $("#laboratorio_modal_criado").find(".item_"+$(element).parents('.tr_modal_criado').attr('data-id')).find('.input_desconto_orcamento').find('.valor_lab').val()  
            valor_lab = retornaFormatoValor(valor_lab);
            return valor_lab;
        // }
    }

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
                <select name="pagamento[#][forma_pagamento]" class="form-control selectfild2Pagamento forma_pagamento_odontologico"  style="width: 100%">
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
                <label class="form-control-label">Data *</label>
                <input type="date" value="{{date('Y-m-d')}}" class="form-control " name="pagamento[#][data]">
            </div>
            <div class="form-group col-md-3 recebido_class">
                <input type="checkbox" id="pagamento_plano_conta_id" name="pagamento[#][recebido]" checked class="filled-in chk-col-teal pagamento_plano_conta_id"/>
                <label class="pagamento_plano_conta_id_label" for="pagamento_plano_conta_id" style="margin-top: 33px;">Recebido<label>
            </div>
            <div class="form-group col-md-3 num_parcelas_class parcela_boleto" style="display: none">
                <label class="form-control-label">Nº parcelas *</label>
                <input type="number" class="form-control" name="pagamento[#][num_parcelas]" value="1">
            </div>
            <div class="form-group col-md-3 num_parcelas_class cartao_debito" style="display: none">
                <label class="form-control-label">Maquina de cartão</label>
                <select name="pagamento[#][maquina_id]" class="form-control selectfild2Pagamento maquina_id_agendamento" style="width: 100%">
                    <option value="">Nenhuma</option>
                    @foreach ($maquinas_cartao as $item)
                        <option value="{{$item->id}}" data-credito="{{$item->taxa_credito}}" data-debito="{{$item->taxa_debito}}" data-dias_credito="{{$item->dias_parcela_credito}}" data-dias_debito="{{$item->dias_parcela_debito}}">{{$item->codigo}} {{$item->descricao}}</option>
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