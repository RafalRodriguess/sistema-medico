@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
'titulo' => 'Vincular Convênios',
'breadcrumb' => [
'Convênios' => route('instituicao.convenios.index'),
'Novo',
],
])
@endcomponent


<div class="card">
    <div class="card-body">
        <form action="{{ route('instituicao.convenios.store') }}" method="post">
            @csrf
            <div class="row">

                <div class="col-md-3 form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Convênio: *</label>
                    <select name="convenio" id="convenioSelect" placeholder="Selecione o Convênio" class="form-control select2" required>
                        <option value="">Selecione</option>
                        @foreach ($convenios as $convenio)
                        <option value="{{ $convenio->id }}">
                            {{ $convenio->nome }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class=" col-md-6 form-group">
                    <label class="form-control-label">Procedimento: *</label>
                    <div class="row">
                        <div class="col-md-8">
                            <select id="selectProcedimento" class="form-control select2" placeholder="Adcione o procedimento">
                            </select>
                        </div>
                        <div class="input-group-append col-md-4">
                            <button id="veincularProc" class="btn btn-outline-secondary" type="button"><i class="mdi mdi-plus"></i> Vincular</button>
                        </div>
                    </div>

                </div>


            </div>
            <div id="procedimentos">

            </div>
            <template>
                <div class="col-md-12 itensProc">
                    <div class="row">

                        <div class="col-md-4 item">
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

                        <div class="col-md-12  mb-2" align="right">
                            <button type="button" class="btn btn-info waves-effect waves-light repasse_medico" data-toggle="collapse"  aria-expanded="true"   aria-haspopup="true" aria-expanded="false" style="margin-top: 30px;">
                                <i class="mdi mdi-square-inc-cash"></i> Repasses
                            </button>
                            <button type="button" class="btn btn-secondary btnRemoveProc" onclick="removeProc($(this))" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-close-circle"></i> Remover Procedimento
                            </button>
                        </div>

                        <div class="accordion repasses_valores">
                            <div data-toggle-column="first" id="" class="collapse repasse_collapse" aria-labelledby="heading">
                                <div class="row profissionaisCheckbox" >
                                    <div class="col-md-12" align="right" >
                                        <button type="button" class="btn btn-info waves-effect waves-light selecionar_todos" onclick="selecionarTodosProfissionais($(this))" style="margin: 10px">
                                            <i class="mdi mdi-account-check"></i> Todos
                                        </button>
                                    </div>
                                    @foreach ($medicos as $key => $item)
                                        <div class="col-md-5">
                                            <h4>{{$item->nome}} </h4>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text repasses_inputs">
                                                        <input type="checkbox" id="customm-{{$item->id}}_#" name="input_procedimento[#][{{$key}}][checkbox]" value="{{$item->id}}" class="filled-in chk-col-cyan" />
                                                        <label for="customm-{{$item->id}}_#" class="mb-0"></label>
                                                    </div>
                                                </div>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text repasses_inputs">
                                                        <label class="form-control-label"></label>
                                                        <select name="input_procedimento[#][{{$key}}][tipo]" class="form-control">
                                                            <option value="dinheiro">R$</option>
                                                            <option value="porcentagem">%</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="text" name="input_procedimento[#][{{$key}}][valor_repasse]" class="form-control setmask" alt="money" aria-label="Text input with checkbox">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text repasses_inputs" style="display: grid">
                                                        <label class="form-control-label"></label>
                                                        <select name="input_procedimento[#][{{$key}}][tipo_cartao]" class="form-control">
                                                            <option value="dinheiro">R$</option>
                                                            <option value="porcentagem">%</option>
                                                        </select>
                                                        <small>Repasse cartão</small>
                                                    </div>
                                                </div>
                                                <input type="text" name="input_procedimento[#][{{$key}}][valor_repasse_cartao]" class="form-control setmask" alt="money" aria-label="Text input with checkbox">
                                                
                                                <div class="input-group mb-3  mx-2 col-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text btn-text-too" data-toggle="tooltip" data-placement="left" title='Valor cobrado pelo profissional, preencher apenas se for maior que o valor cobrado pela instituição. caso contratio deixar zerado!'>?</span>
                                                    </div>
                                                    <input type="text" name="input_procedimento[#][{{$key}}][valor_cobrado]" class="form-control setmask" alt="money" aria-label="Text input with checkbox">
                                                </div>
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
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts');
<script>

    var qtd_procedimento_extra = [];

    $(document).ready(function() {

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
                    // insere name do procedimento no campo de valor
                    blocoHtml.querySelectorAll(".valorProc")[0].setAttribute("name", "input_procedimento[" + obj.id + "][valor]");
                    blocoHtml.querySelectorAll(".valorConv")[0].setAttribute("name", "input_procedimento[" + obj.id + "][valor_convenio]");
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
                })
            },

        })
    })

    function removeProc(el) {
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
                $(el).closest('.itensProc').remove()
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
        margin-top: 30px;
        background: #ff00009c !important;
        color: white !important;
        border: none !important;
    }
    .repasses_inputs{
        background-color: white!important;
        border: none!important;
        padding-top: 0px!important;
    }
</style>
