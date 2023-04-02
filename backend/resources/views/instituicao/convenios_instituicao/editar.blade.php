@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
'titulo' => 'Editar' ,
'breadcrumb' => [
'Convênios' => route('instituicao.convenios.index'),
'Editar',
],
])
@endcomponent


<div class="card">
    <div class="card-body">
        <form action="{{ route('instituicao.convenios.update', [$convenio]) }}" method="post">
            @method('put')
            @csrf
            <input type="hidden" name="convenio" value="{{$convenio->id}}">
            <div class="row">

                <div class="col-md-4 form-group " align="center">
                    <br>
                    <h2>
                        {{$convenio->nome}}
                    </h2>
                </div>

                <div class=" col-md-8 form-group">
                    <label class="form-control-label">Adicionar procedimento: *</label>

                    <div class="row">
                        <div class="col-md-9">
                            <select id="selectProcedimento" class="form-control select2" placeholder="Adcione o procedimento">
                            </select>
                        </div>
                        <div class="input-group-append col-md-3">
                            <button style="width: 100%;" id="veincularProc" class="btn btn-outline-secondary" type="button"><i class="mdi mdi-plus"></i> Vincular</button>
                        </div>
                    </div>

                </div>


            </div>

            <div id="procedimentos"></div>

            <div class="col-md-12 form-group">
                <label class="form-control-label">Procedimentos vinculados</label>
                <div class="row">
                    <div class="col-md-9">
                        <select id="selectProcVinculado" class="form-control selectfild2 selectProcVinculado" placeholder="" multiple>
                            @foreach ($procedimentos as $procedimento)
                                @if (!empty($procedimento->procedimentoInstituicao->procedimento))
                                    <option value="{{$procedimento->procedimentoInstituicao->procedimento->id}}" data-option="{{$procedimento}}">
                                        {{$procedimento->procedimentoInstituicao->procedimento->descricao}} (VP: R$ {{number_format($procedimento->valor, 2, ",", ".")}}) (VC: R$ {{number_format($procedimento->valor_convenio, 2, ",", ".")}})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group-append col-md-3">
                        <button id="exibirProcVinculado" class="btn btn-outline-secondary" type="button"><i class="mdi mdi-check"></i> exibir</button>
                    </div>
                </div>
            </div>

            <div id="procVinculados"></div>

            {{-- @if($procedimentos && false)
            @foreach ($procedimentos as $procedimento)
            <div class="col-md-12 itensProc">
                <div class="row">

                    <div class="col-md-4 item" data-proc_id="{{$procedimento->procedimentoInstituicao->procedimento->id}}">
                        <h2><span class="nameProc">
                                {{$procedimento->procedimentoInstituicao->procedimento->descricao}}
                            </span><br>
                        </h2>
                        <small>
                            {{$procedimento->procedimentoInstituicao->procedimento->tipo}}
                        </small>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Valor do procedimento:*</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="text" id="procedimento_{{$procedimento->procedimentoInstituicao->procedimento->id}}" name="input_procedimento[{{$procedimento->procedimentoInstituicao->procedimento->id}}][valor]" alt="money" class="form-control valorProc" required value="{{$procedimento->valor}}">

                            </div>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Valor do convênio:*</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="text" id="procedimento_conv_{{$procedimento->procedimentoInstituicao->procedimento->id}}" name="input_procedimento[{{$procedimento->procedimentoInstituicao->procedimento->id}}][valor_convenio]" alt="money" class="form-control valorConv" required value="{{$procedimento->valor_convenio}}">

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2" align="right">
                        <button type="button" class="btn btn-info waves-effect waves-light repasse_medico" data-toggle="collapse" data-target="#collapse_{{$procedimento->procedimentoInstituicao->procedimento->id}}"
                                    aria-controls="collapse_{{$procedimento->procedimentoInstituicao->procedimento->id}}" aria-haspopup="true" aria-expanded="false" >
                                    <i class="mdi mdi-square-inc-cash"></i> Repasses
                        </button>
                        @can('habilidade_instituicao_sessao', 'excluir_convenios')
                            <button type="button" data-procedimento_instituicao="{{$procedimento->procedimentoInstituicao->id}}" data-convenio="{{$convenio->id}}" class="btn btn-secondary btnRemoveProc" onclick="removeProc($(this))" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-close-circle"></i> Remover Procedimento
                            </button>
                        @endcan
                    </div>

                    <div class="accordion repasses_valores">
                        <div data-toggle-column="first" class="collapse repasse_collapse" id="collapse_{{$procedimento->procedimentoInstituicao->procedimento->id}}" aria-labelledby="heading_{{$procedimento->procedimentoInstituicao->procedimento->id}}" aria-labelledby="heading">
                            <div class="row" >
                                @foreach ($medicos as $key => $item)
                                    @php
                                        $checkbox = Str::random(10)
                                    @endphp
                                    <div class="col-md-5">
                                        <h4>{{$item->nome}} </h4>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text repasses_inputs">
                                                    <input type="checkbox" id="customm-{{$item->id}}-{{$checkbox}}" name="input_procedimento[{{$procedimento->procedimentoInstituicao->procedimento->id}}][{{$key}}][checkbox]" value="{{$item->id}}" class="filled-in chk-col-cyan" @if (array_key_exists($procedimento->id, $dados))
                                                        @if (array_key_exists($item->id, $dados[$procedimento->id]))
                                                            checked
                                                        @endif
                                                    @endif/>
                                                    <label for="customm-{{$item->id}}-{{$checkbox}}" class="mb-0"></label>
                                                </div>
                                            </div>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text repasses_inputs">
                                                    <label class="form-control-label"></label>
                                                    <select name="input_procedimento[{{$procedimento->procedimentoInstituicao->procedimento->id}}][{{$key}}][tipo]" class="form-control">
                                                        <option value="dinheiro" @if (array_key_exists($procedimento->id, $dados)) @if (array_key_exists($item->id, $dados[$procedimento->id]))
                                                                @if ($dados[$procedimento->id][$item->id]['tipo'] == 'dinheiro')
                                                                    selected
                                                                @endif
                                                            @endif
                                                        @endif>R$</option>
                                                        <option value="porcentagem" @if (array_key_exists($procedimento->id, $dados)) @if (array_key_exists($item->id, $dados[$procedimento->id]))
                                                                @if ($dados[$procedimento->id][$item->id]['tipo'] == 'porcentagem')
                                                                    selected
                                                                @endif
                                                            @endif
                                                        @endif>%</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="text" name="input_procedimento[{{$procedimento->procedimentoInstituicao->procedimento->id}}][{{$key}}][valor_repasse]" class="form-control setmask" alt="money" aria-label="Text input with checkbox" @if (array_key_exists($procedimento->id, $dados)) @if (array_key_exists($item->id, $dados[$procedimento->id]))
                                                value="{{$dados[$procedimento->id][$item->id]['valor_repasse']}}"
                                            @endif @endif>
                                            <div class="input-group mb-3  mx-2 col-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text btn-text-too" data-toggle="tooltip" data-placement="left" title='Valor cobrado pelo profissional, preencher apenas se for maior que o valor cobrado pela instituição. caso contratio deixar zerado!'>?</span>
                                                </div>
                                                <input type="text" name="input_procedimento[{{$procedimento->procedimentoInstituicao->procedimento->id}}][{{$key}}][valor_cobrado]" class="form-control setmask" alt="money" aria-label="Text input with checkbox" @if (array_key_exists($procedimento->id, $dados)) @if (array_key_exists($item->id, $dados[$procedimento->id]))
                                                value="{{$dados[$procedimento->id][$item->id]['valor_cobrado']}}"
                                            @endif @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin-top: 0.5rem; margin-bottom: 1rem; width: 100%;">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                </div>

            </div>
            @endforeach
            @endif --}}

            <template>
                <div class="col-md-12 itensProc" style="background: #9dd1ff33;">
                    <div class="row">
                        <div class="col-md-4 item" id="itemProcId">
                            <h2><span class="nameProc">
                                    <!-- NOME DO PROCEDIMENTO -->
                                </span><br>
                            </h2>
                            <small>
                                <!-- TIPO DO PROCEDIMENTO -->
                            </small>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Valor do procedimento:*</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" name="" alt="money" class="form-control valorProc" required>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Valor do convênio:*</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" name="" alt="money" class="form-control valorConv" required>

                                </div>

                            </div>
                        </div>

                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Código do procedimento no convênio</label>
                                <input type="text" name=""  class="form-control codigo">

                            </div>
                        </div>

                      
                        <div class="col-md-6" @if($instituicao->possui_faturamento_sancoop == 0) style="display:none" @endif>
                            <div class="form-group">
                                <label class="form-control-label">Código do procedimento na Sancoop</label>
                                <input type="text" readonly="readonly" class="form-control sancoop_cod_procedimento">
                            </div>
                        </div>

                        <div class="col-sm-2 p-3 m-0">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input utiliza_parametro_convenio_check" name="" value="1">
                                <label class="form-check-label utiliza_parametro_convenio_label">Utilizar parametros de envio do convenio</label>
                            </div>
                        </div>

                        <div class="col-sm-2 p-3 m-0">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input carteirinha_obrigatoria_check" name="" value="1" >
                                <label class="form-check-label carteirinha_obrigatoria_label">Carteirinha obrigatoria?</label>
                            </div>
                        </div>

                        <div class="col-sm-2 p-3 m-0">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input aut_obrigatoria_check" name="" value="1">
                                <label class="form-check-label aut_obrigatoria_label">Autorizaçõa obrigatoria?</label>
                            </div>
                        </div>

                        <div class="col-md-12" align="right">
                            <button type="button" class="btn btn-info waves-effect waves-light repasse_medico" data-toggle="collapse"  aria-expanded="true"   aria-haspopup="true" aria-expanded="false" style="">
                                <i class="mdi mdi-square-inc-cash"></i> Repasses
                            </button>
                            <button type="button" data-convenio="{{$convenio->id}}" class="btn btn-secondary btnRemoveProc" onclick="removeProc($(this))" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-close-circle"></i> Remover Procedimento
                            </button>
                        </div>

                        <hr>

                        <div class="accordion repasses_valores" style="margin-left: 10px; margin-right: 10px; margin-top: 10px">
                            <div data-toggle-column="first" id="" class="collapse repasse_collapse" aria-labelledby="heading">
                                <div class="row profissionaisCheckbox" >
                                    <div class="col-md-12" align="right" >
                                        <button type="button" class="btn btn-info waves-effect waves-light selecionar_todos" onclick="selecionarTodosProfissionais($(this))" style="margin: 10px">
                                            <i class="mdi mdi-account-check"></i> Todos
                                        </button>
                                    </div>
                                    @foreach ($medicos as $key => $item)
                                        @php
                                            $checkbox = Str::random(10)
                                        @endphp
                                        <div class="col-md-5">
                                            <h4>{{$item->nome}} </h4>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="input-group mb-3  mx-2 col-sm">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text repasses_inputs_exist">
                                                        <input type="checkbox" id="customm-{{$item->id}}-{{$checkbox}}" name="input_procedimento[#][{{$key}}][checkbox]" value="{{$item->id}}" class="filled-in chk-col-cyan ckeckInput" data-med-id="{{$item->id}}" data-elemento="checkbox"/>
                                                        <label for="customm-{{$item->id}}-{{$checkbox}}" class="mb-0 checkLabel"></label>
                                                    </div>
                                                </div>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text repasses_inputs_exist">
                                                        <label class="form-control-label"></label>
                                                        <select name="input_procedimento[#][{{$key}}][tipo]" data-med-id="{{$item->id}}" data-elemento="tipo" class="form-control">
                                                            <option value="dinheiro">R$</option>
                                                            <option value="porcentagem">%</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="text" name="input_procedimento[#][{{$key}}][valor_repasse]" class="form-control setmask" data-med-id="{{$item->id}}" data-elemento="valor_repasse" alt="money" aria-label="Text input with checkbox">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text repasses_inputs" style="display: grid; background: #ebf6ff!important;">
                                                        <label class="form-control-label"></label>
                                                        <select name="input_procedimento[#][{{$key}}][tipo_cartao]" class="form-control" data-elemento="tipo_cartao" data-med-id="{{$item->id}}">
                                                            <option value="dinheiro">R$</option>
                                                            <option value="porcentagem">%</option>
                                                        </select>
                                                        <small>Repasse cartão</small>
                                                    </div>
                                                </div>
                                                <input type="text" name="input_procedimento[#][{{$key}}][valor_repasse_cartao]" data-elemento="valor_repasse_cartao" class="form-control setmask" alt="money" aria-label="Text input with checkbox" data-med-id="{{$item->id}}">

                                                <div class="input-group-prepend">
                                                    <span class="input-group-text btn-text-too" data-toggle="tooltip" data-placement="left" title='Valor cobrado pelo profissional, preencher apenas se for maior que o valor cobrado pela instituição. caso contratio deixar zerado!'>?</span>
                                                </div>
                                                <input type="text" name="input_procedimento[#][{{$key}}][valor_cobrado]" data-med-id="{{$item->id}}" data-elemento="valor_cobrado" class="form-control setmask" alt="money" aria-label="Text input with checkbox">

                                            </div>
                                        </div>
                                        <hr style="margin-top: 0.5rem; margin-bottom: 1rem; width: 100%;">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </template>

            <div class="form-group text-right">
                <a href="{{ route('instituicao.convenios.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                </a>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('estilos')
<style>
    .selectProcVinculado {
        max-height: 50px;
        height: 38px;
    }
</style>
@endpush

@push('scripts');
<script>

    function calcula_desconto(valor,id){

        if (parseFloat($('#valor_particular_'+id).val()) <= 0) {
                alert('Preencha o valor particular do exame!');
                // this.value = (0.00).toFixed(2);
                // $('#valor_particular_'+id).focus()
                // ev.preventDefault();
            } else {
                // alert('adf')
                // var valor_venda = parseFloat($('#valor_venda_'+id).val()) + (this.value * $('#valor_compra_'+id).val() / 100);
                // $('#valor_real').val(valor_venda.toFixed(2));

                // if (this.value > 0) {
                    // var desconto = (1 - (parseFloat(valor_venda)) / parseFloat($('#valor_particular_'+id).val())) * 100;
                    // $('#desconto_real_'+id).val(desconto.toFixed(2));

                    var desconto = ((parseFloat($('#valor_particular_'+id).val())) - parseFloat($('#valor_venda_'+id).val())) / parseFloat($('#valor_particular_'+id).val());

                    // alert(desconto)

                    var desconto_final = parseFloat(desconto) * 100;
                    $('#desconto_real_'+id).val(desconto_final.toFixed(2));

                // }

            }

    }

    $(document).ready(function() {

        limpaSelectProc()

        $('#exibirProcVinculado').click(function() {
            $('#procVinculados').html("");

            $('#loading').removeClass('loading-off');
            $('#selectProcVinculado :selected').each(function(index, element){
                var procedimento = $(element).data('option');

                console.log(procedimento)

                var template = document.getElementsByTagName("template")[0];

                var blocoHtml = template.content.cloneNode(true);
                // inser o tipo do procedimento
                blocoHtml.querySelectorAll("small")[0].textContent = procedimento.procedimento_instituicao.procedimento.tipo;
                // insere a descricao do procedimento
                blocoHtml.querySelectorAll(".nameProc")[0].textContent = procedimento.procedimento_instituicao.procedimento.descricao;
                // insere id do procedimento no campo de valor
                blocoHtml.querySelectorAll(".valorProc")[0].setAttribute("id", "procedimento_" + procedimento.procedimento_instituicao.procedimento.id);
                blocoHtml.querySelectorAll(".valorConv")[0].setAttribute("id", "procedimento_conv_" + procedimento.procedimento_instituicao.procedimento.id);
                blocoHtml.querySelectorAll(".codigo")[0].setAttribute("id", "procedimento_codigo_" + procedimento.codigo);
                // insere name do procedimento no campo de valor
                blocoHtml.querySelectorAll(".valorProc")[0].setAttribute("name", "input_procedimento[" + procedimento.procedimento_instituicao.procedimento.id + "][valor]");
                blocoHtml.querySelectorAll(".valorConv")[0].setAttribute("name", "input_procedimento[" + procedimento.procedimento_instituicao.procedimento.id + "][valor_convenio]");
                blocoHtml.querySelectorAll(".codigo")[0].setAttribute("name", "input_procedimento[" + procedimento.procedimento_instituicao.procedimento.id + "][codigo]");
                blocoHtml.querySelectorAll(".item")[0].setAttribute("data-proc_id", procedimento.procedimento_instituicao.procedimento.id);
                blocoHtml.querySelectorAll(".valorConv")[0].setAttribute("value", procedimento.valor_convenio);
                blocoHtml.querySelectorAll(".valorProc")[0].setAttribute("value", procedimento.valor);
                blocoHtml.querySelectorAll(".codigo")[0].setAttribute("value", procedimento.codigo);

                blocoHtml.querySelectorAll(".sancoop_cod_procedimento")[0].setAttribute("value", procedimento.sancoop_cod_procedimento+'('+procedimento.sancoop_desc_procedimento+')');

                blocoHtml.querySelectorAll(".utiliza_parametro_convenio_check")[0].setAttribute("name", "input_procedimento[" + procedimento.procedimento_instituicao.procedimento.id + "][utiliza_parametro_convenio]");
                blocoHtml.querySelectorAll(".carteirinha_obrigatoria_check")[0].setAttribute("name", "input_procedimento[" + procedimento.procedimento_instituicao.procedimento.id + "][carteirinha_obrigatoria]");
                blocoHtml.querySelectorAll(".aut_obrigatoria_check")[0].setAttribute("name", "input_procedimento[" + procedimento.procedimento_instituicao.procedimento.id + "][aut_obrigatoria]");

                if(procedimento.utiliza_parametro_convenio == 1){
                    blocoHtml.querySelectorAll(".utiliza_parametro_convenio_check")[0].setAttribute("checked",  true);
                    blocoHtml.querySelectorAll(".carteirinha_obrigatoria_check")[0].setAttribute("disabled", true);
                    blocoHtml.querySelectorAll(".aut_obrigatoria_check")[0].setAttribute("disabled", true);
                }
                    
                blocoHtml.querySelectorAll(".utiliza_parametro_convenio_check")[0].setAttribute("id", "utiliza_parametro_convenio_"+ procedimento.procedimento_instituicao.procedimento.id);
                blocoHtml.querySelectorAll(".utiliza_parametro_convenio_label")[0].setAttribute("for", "utiliza_parametro_convenio_"+ procedimento.procedimento_instituicao.procedimento.id);
                blocoHtml.querySelectorAll(".utiliza_parametro_convenio_check")[0].setAttribute("onChange", "liberaCheck("+ procedimento.procedimento_instituicao.procedimento.id+")");

                if(procedimento.utiliza_parametro_convenio == 0 && procedimento.carteirinha_obrigatoria == 1){
                    blocoHtml.querySelectorAll(".carteirinha_obrigatoria_check")[0].setAttribute("checked", true);
                }
                blocoHtml.querySelectorAll(".carteirinha_obrigatoria_check")[0].setAttribute("id", "carteirinha_obrigatoria_"+ procedimento.procedimento_instituicao.procedimento.id);
                blocoHtml.querySelectorAll(".carteirinha_obrigatoria_label")[0].setAttribute("for", "carteirinha_obrigatoria_"+ procedimento.procedimento_instituicao.procedimento.id);

                if(procedimento.utiliza_parametro_convenio == 0 && procedimento.aut_obrigatoria == 1){
                    blocoHtml.querySelectorAll(".aut_obrigatoria_check")[0].setAttribute("checked", true);
                }
                blocoHtml.querySelectorAll(".aut_obrigatoria_check")[0].setAttribute("id", "aut_obrigatoria_"+ procedimento.procedimento_instituicao.procedimento.id);
                blocoHtml.querySelectorAll(".aut_obrigatoria_label")[0].setAttribute("for", "aut_obrigatoria_"+ procedimento.procedimento_instituicao.procedimento.id);

                // incorpora o template ao html
                $('#procVinculados').prepend(blocoHtml)
                // insere a mascara no campo de valor
                $("#procedimento_" + procedimento.procedimento_instituicao.procedimento.id).setMask();
                $("#procedimento_conv_" + procedimento.procedimento_instituicao.procedimento.id).setMask();

                var elemento = $("#procedimento_" + procedimento.procedimento_instituicao.procedimento.id).parents('.itensProc');
                elemento.find(".repasse_medico").attr("data-target", "#collapse_" + procedimento.procedimento_instituicao.procedimento.id);
                elemento.find(".repasse_medico").attr("aria-controls", "collapse_" + procedimento.id);
                elemento.find(".repasse_collapse").attr("id", "collapse_" + procedimento.procedimento_instituicao.procedimento.id);
                elemento.find(".repasse_collapse").attr("aria-labelledby", "heading_" + procedimento.procedimento_instituicao.procedimento.id);
                elemento.find('.btnRemoveProc').attr('data-procedimento_instituicao', procedimento.procedimentos_instituicoes_id);

                elemento.find('.repasses_valores').find('.ckeckInput').each(function(index, element){
                    id = $(element).attr('id')
                    $(element).attr('id', id + "-" + procedimento.procedimento_instituicao.procedimento.id);
                });

                elemento.find('.repasses_valores').find('.checkLabel').each(function(index, element){
                    id = $(element).attr('for')
                    $(element).attr('for', id + "-" + procedimento.procedimento_instituicao.procedimento.id);
                })

                elemento.find('.repasses_valores').find('[name^="input_procedimento[#]"]').each(function(index, element){
                    const name = $(element).attr('name');

                    $(element).attr('name', name.replace('#',procedimento.procedimento_instituicao.procedimento.id));
                    if($(element).hasClass('setmask')){
                        $(element).setMask();
                    }

                    for(i = 0; i < procedimento.repasse_medico.length; i++){
                        repasse = procedimento.repasse_medico[i];

                        if($(element).attr("data-med-id") == repasse.id){
                            if($(element).attr("data-elemento") == "checkbox"){
                                $(element).prop('checked', true);
                            }

                            if($(element).attr("data-elemento") == "tipo"){
                                $(element).val(repasse.pivot.tipo);
                            }

                            if($(element).attr("data-elemento") == "valor_repasse"){
                                $(element).val(repasse.pivot.valor_repasse);
                            }
                            
                            if($(element).attr("data-elemento") == "tipo_cartao"){
                                $(element).val(repasse.pivot.tipo_cartao);  
                            }

                            if($(element).attr("data-elemento") == "valor_repasse_cartao"){
                                $(element).val(repasse.pivot.valor_repasse_cartao);
                            }

                            if($(element).attr("data-elemento") == "valor_cobrado"){
                                $(element).val(repasse.pivot.valor_cobrado);
                            }
                        }
                    }
                })

                //limpa o select de procedimentos
                $("#selectProcVinculado").val('').change();

            })
            $('#loading').addClass('loading-off');
        })

        // $("#selectProcVinculado").select2()


        $("#selectProcedimento").select2({
            placeholder: "Selecione o procedimento",
            ajax: {
                url: '{{route("instituicao.getprocedimentos")}}',
                type: 'post',
                dataType: 'json',
                quietMillis: 20,
                data: function(params) {
                    return {
                        q: params.term,
                        '_token': '{{csrf_token()}}',
                    };
                },
                processResults: function(data) {
                    // console.log(data)
                    return {
                        results: $.map(data, function(obj) {
                            if(limpaSelectProc().indexOf(obj.id) ==-1){
                                return {
                                    id: obj.id,
                                    text: obj.descricao
                                };
                            }
                        })
                    }
                }
            },
            escapeMarkup: function(m) {
                return m;
            }
        });

    })

    function liberaCheck(id){
        console.log("aqui")
        if($("#utiliza_parametro_convenio_"+id).is(":checked")){
            $("#carteirinha_obrigatoria_"+id).attr('disabled', true)
            $("#aut_obrigatoria_"+id).attr('disabled', true)
        }else{
            $("#carteirinha_obrigatoria_"+id).attr('disabled', false)
            $("#aut_obrigatoria_"+id).attr('disabled', false)
        }
    }

    $('#veincularProc').click(function() {
        var procedimento = $('#selectProcedimento').val()

        //verifica se o procedimento ja existe na tela
        if (($('#procedimento_' + procedimento).length > 0) || !procedimento) {
            return
        } else {
            $('#loading').removeClass('loading-off');
        }

        $.ajax({
            url: '{{route("instituicao.getprocedimento")}}',
            method: 'POST',
            dataType: 'json',
            data: {
                id: procedimento,
                '_token': '{{csrf_token()}}'
            },
            success: function(data) {
                results: $.map(data, function(obj) {
                    //carregam template html
                    var template = document.getElementsByTagName("template")[0];

                    var blocoHtml = template.content.cloneNode(true);
                    // inser o tipo do procedimento
                    blocoHtml.querySelectorAll("small")[0].textContent = obj.tipo;
                    // insere a descricao do procedimento
                    blocoHtml.querySelectorAll(".nameProc")[0].textContent = obj.descricao;
                    // insere id do procedimento no campo de valor
                    blocoHtml.querySelectorAll(".valorProc")[0].setAttribute("id", "procedimento_" + obj.id);
                    blocoHtml.querySelectorAll(".valorConv")[0].setAttribute("id", "procedimento_conv_" + obj.id);
                    blocoHtml.querySelectorAll(".codigo")[0].setAttribute("id", "procedimento_codigo_" + obj.id);
                    // insere name do procedimento no campo de valor
                    blocoHtml.querySelectorAll(".valorProc")[0].setAttribute("name", "input_procedimento[" + obj.id + "][valor]");
                    blocoHtml.querySelectorAll(".valorConv")[0].setAttribute("name", "input_procedimento[" + obj.id + "][valor_convenio]");
                    blocoHtml.querySelectorAll(".codigo")[0].setAttribute("name", "input_procedimento[" + obj.id + "][codigo]");
                    blocoHtml.querySelectorAll(".item")[0].setAttribute("data-proc_id", obj.id);


                    // incorpora o template ao html
                    $('#procedimentos').prepend(blocoHtml)
                    // insere a mascara no campo de valor
                    $("#procedimento_" + obj.id).setMask();
                    $("#procedimento_conv_" + obj.id).setMask();

                    var elemento = $("#procedimento_" + obj.id).parents('.itensProc');
                    elemento.find(".repasse_medico").attr("data-target", "#collapse_" + obj.id);
                    elemento.find(".repasse_medico").attr("aria-controls", "collapse_" + obj.id);
                    elemento.find(".repasse_collapse").attr("id", "collapse_" + obj.id);
                    elemento.find(".repasse_collapse").attr("aria-labelledby", "heading_" + obj.id);

                    elemento.find('.repasses_valores').find('[name^="input_procedimento[#]"]').each(function(index, element){
                        const name = $(element).attr('name');

                        $(element).attr('name', name.replace('#',obj.id));
                        if($(element).hasClass('setmask')){
                            $(element).setMask();
                        }
                    })

                    //limpa o select de procedimentos
                    $("#selectProcedimento").empty();
                    $('#loading').addClass('loading-off');
                });
            },

        })
    })


    function removeProc(el) {

        var convenio = $(el).attr('data-convenio');
        var procedimento = $(el).attr('data-procedimento_instituicao');
        console.log(convenio)
        console.log(procedimento)
        

            Swal.fire({
                title: "Atenção",
                text: "Certeza que deseja remover este procedimento?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, remover!",
                cancelButtonText: "Não, cancelar!",
            }).then(function(result) {
                if (result.value) {
                    if(procedimento){
                        $('#loading').removeClass('loading-off');
                        $.ajax({
                            url: '{{route("instituicao.convenios.retiraprocedimentoconvenio")}}',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                convenio: convenio,
                                procedimento: procedimento,
                                '_token': '{{csrf_token()}}'
                            },
                            success: function(data) {
                                if (data) {
                                    $('#loading').addClass('loading-off');
        
                                        $.toast({
                                            heading: 'Sucesso!',
                                            text: 'procedimento retirado com sucesso',
                                            position: 'top-right',
                                            loaderBg: '#ff6849',
                                            icon: 'success',
                                            hideAfter: 3000,
                                            stack: 10
                                        });
        
                                    // $('#selectProcVinculado').find("option[value='" + procedimento + "']").remove()
                                    console.log(data)
                                    $('#selectProcVinculado').find('option').filter('[value="'+data+'"]').remove()
        
                                    $(el).closest('.itensProc').remove()
                                } else {
                                    $('#loading').addClass('loading-off');
                                }
                            },
        
                        })
                    }else{
                        $(el).closest('.itensProc').remove()
                    }
                }
            });
        

    }

    function limpaSelectProc(){
        var procedimento = [];

        $('.itensProc').each(function(index, element){
            procedimento.push($(element).find('.item').data('proc_id'));
        })

        return procedimento;
    }

    function selecionarTodosProfissionais(elemento){
        elemento.parents('.profissionaisCheckbox').find('input:checkbox').prop('checked', true)
    }

</script>
@endpush

<style>
    .itensProc {
        border-top: solid 1px #7c787894;
        border-bottom: solid 1px #7c787894;
        padding: 15px 0px;
        margin: 20px 0px;
    }

    .btnRemoveProc {
        /* margin-top: 30px; */
        background: #ff00009c !important;
        color: white !important;
        border: none !important;
    }
    .repasses_inputs{
        background-color: white!important;
        border: none!important;
        padding-top: 0px!important;
    }
    .repasses_inputs_exist{
        background-color: #ebf6ff!important;;
        border: none!important;
        padding-top: 0px!important;
    }
</style>
