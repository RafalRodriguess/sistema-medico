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
            <div id="procedimentos">

            </div>
            @foreach ($procedimentos as $procedimento)
            <div class="col-md-22 itensProc">
                <div class="row">

                    <div class="col-md-4">
                        <h2><span class="nameProc">
                                {{$procedimento->descricao}}
                            </span><br>
                        </h2>
                        <small>
                            {{$procedimento->tipo}}
                        </small>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Valor do procedimento:*</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="text" id="procedimento_{{$procedimento->id}}" name="input_procedimento[{{$procedimento->id}}]" alt="money" class="form-control valorProc" required value="{{$procedimento->valor}}">

                            </div>

                        </div>
                    </div>
                    <div class="col-md-5" align="right">
                        @can('habilidade_instituicao_sessao', 'excluir_convenios')
                        <button type="button" data-procedimento_instituicao="{{$procedimento->procedimentos_instituicoes_id}}" data-convenio="{{$convenio->id}}" class="btn btn-secondary btnRemoveProc" onclick="removeProc($(this))" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-close-circle"></i> Remover Procedimento
                        </button>
                        @endcan
                    </div>

                </div>
                <div>
                </div>

            </div>
            @endforeach

            <template>
                <div class="col-md-12 itensProc" style="background: #9dd1ff33;">
                    <div class="row">
                        <div class="col-md-4">
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


                        <div class="col-md-5" align="right">
                            <button type="button" class="btn btn-secondary btnRemoveProc" onclick="removeProc($(this))" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-close-circle"></i> Remover Procedimento
                            </button>
                        </div>

                    </div>
                    <div>
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

@push('scripts');
<script>
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
                    console.log(data)
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.descricao
                            };
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
                    // insere name do procedimento no campo de valor
                    blocoHtml.querySelectorAll(".valorProc")[0].setAttribute("name", "input_procedimento[" + obj.id + "]");
                    // incorpora o template ao html
                    $('#procedimentos').prepend(blocoHtml)
                    // insere a mascara no campo de valor
                    $("#procedimento_" + obj.id).setMask();
                    //limpa o select de procedimentos
                    $("#selectProcedimento").empty();
                    $('#loading').addClass('loading-off');
                })
            },

        })
    })


    function removeProc(el) {

        var convenio = $(el).data('convenio');
        var procedimento = $(el).data('procedimento_instituicao');

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

                            $(el).closest('.itensProc').remove()
                        } else {
                            $('#loading').addClass('loading-off');
                        }
                    },

                })
            }
        });

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
</style>
