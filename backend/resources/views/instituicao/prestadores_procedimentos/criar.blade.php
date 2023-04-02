@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
'titulo' => 'Vincular Convênios',
'breadcrumb' => [
'Administração',
'Prestadores' => route('instituicao.prestadores.index'),
'Novo',
],
])
@endcomponent


<div class="card">
    <div class="card-body">
        <form action="{{ route('instituicao.vincular.salvar') }}" method="post">
            @csrf

            <input type="hidden" name="id_prestador" value="{{$prestador->id}}">

            <div class="row">

                <div class="col-md-4 form-group">
                    <br>
                    <h3>{{$prestador->nome}}</h3>

                </div>

                <div class=" col-md-8 form-group">
                    <label class="form-control-label">Procedimento: *</label>
                    <div class="row">
                        <div class="col-md-8">
                            <select id="selectProcedimento" class="form-control select2" name="id_procedimento" placeholder="Adicione o procedimento">
                            </select>
                        </div>
                    </div>

                </div>


            </div>

            <div id="procedimentos" class="row">


            </div>

            <template>
                <div class="col-md-6 itensConv">
                    <div class="row" style="background-color: #f0f0f0;margin:10px;    padding: 10px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Status:</label>
                                <div class="switch">
                                    <label> Desativado<input name="" class="inputCheck" type="checkbox"><span class="lever"></span>Ativado
                                    </label>

                                    <br>
                                    <br>
                                    <input type="text" placeholder="Qtd atendimento por dia" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2><span class="nameConv">
                                    <!-- NOME DO PROCEDIMENTO -->
                                </span><br>
                            </h2>
                            <small>
                                <!-- TIPO DO PROCEDIMENTO -->
                            </small>
                        </div>
                    </div>

                </div>
            </template>

            <div class="form-group text-right">
                <a href="{{ route('instituicao.prestadores.procedimentos', [$prestador->id]) }}">
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
    $(document).ready(function() {

        $("#selectProcedimento").select2({
            placeholder: "Selecione o procedimento",
            ajax: {
                url: '{{route("instituicao.vinculacao.getprocedimentos")}}',
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



    $('#selectProcedimento').on('select2:select', function(e) {
        var data = e.params.data;


        var procedimento = e.params.data.id


        $.ajax({
            url: '{{route("instituicao.vinculacao.getconvenios")}}',
            method: 'POST',
            dataType: 'json',
            data: {
                procedimento: procedimento,
                instituicao_prestador: '{{$instituicao_prestador->id}}',
                id_prestador: '{{$instituicao_prestador->prestadores_id}}',
                '_token': '{{csrf_token()}}'
            },
            beforeSend: function() {
                $('#loading').removeClass('loading-off');
            },
            success: function(data) {

                if (data) {
                    $('#procedimentos').html('')
                    results: $.map(data, function(obj) {

                        //carregam template html
                        var template = document.getElementsByTagName("template")[0];

                        var blocoHtml = template.content.cloneNode(true);
                        // inser o tipo do procedimento
                        blocoHtml.querySelectorAll("small")[0].textContent = obj.descricao;
                        // insere a descricao do procedimento
                        blocoHtml.querySelectorAll(".nameConv")[0].textContent = obj.nome;

                        // insere name do procedimento no campo de valor
                        blocoHtml.querySelectorAll(".inputCheck")[0].setAttribute("name", "input_procedimento[" + obj.id + "]");

                        blocoHtml.querySelectorAll(".inputCheck")[0].setAttribute("value", obj.id);

                        // incorpora o template ao html
                        $('#procedimentos').prepend(blocoHtml)
                        // insere a mascara no campo de valor
                        $("#procedimento_" + obj.id).setMask();
                        //limpa o select de procedimentos
                        $('#loading').addClass('loading-off');
                    })

                }
                else {
                    $('#procedimentos').html('')
                    $('#selectProcedimento').val(null).trigger('change');
                    $.toast({
                        heading: 'Alerta!',
                        text: 'Procedimento já vinculado a este Profissional',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'warning',
                        hideAfter: 9000,
                        stack: 10
                    });
                    $('#loading').addClass('loading-off');
                }


            },

        })


    });
</script>
@endpush

<style>
    .itensConv {

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
