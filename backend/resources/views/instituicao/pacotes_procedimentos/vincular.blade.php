@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Vincular procedimento a pacote #{$pacote_procedimento->id} {$pacote_procedimento->descricao}",
        'breadcrumb' => [
            'Pacotes' => route('instituicao.pacotesProcedimentos.index'),
            'Vincular',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class=" col-md form-group">
                    <label class="form-control-label col-sm-2">Procedimentos: </label>
                    <select multiple id="procVincular" class="form-control select2 col-sm-8" >
                        {{-- @foreach($procedimentos as $item)
                            <option value="{{$item->id}}" data-procedimento="{{$item}}">{{$item->descricao}}</option>
                        @endforeach --}}
                    </select>
                    <button class="btn btn-secondary c0l-sm-2" id="vincular">Vincular</button>
                </div>
            </div>

            <form action="{{ route('instituicao.pacotesProcedimentos.salvarVinculo', [$pacote_procedimento]) }}" method="post">
                @method('put')
                @csrf

                <div id="procedimentos">
                    @foreach($procedimentos as $item)
                        <div class="row item" data-proc_id="{{$item->id}}" id="itemProcId_{{$item->id}}">
                            <div class="col-md">
                                <h4><span class="nameProc lead">{{$item->descricao}}</span></h4>
                                <small>{{$item->tipo}}</small>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="hidden" name="procedimento_id[{{$item->id}}]" id="procedimento_{{$item->id}}" class="idProc" value={{$item->id}}>
                                        <button class="btn btn-secondary btnProc" data-id="{{$item->id}}" onclick="removeItem($(this))"><span class="mdi mdi-close"></span></button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    @endforeach

                </div>

                <div class="form-group text-right">
                    <a href="{{ route('instituicao.pacotesProcedimentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts');
    <script>

        $(document).ready(function() {

            limpaSelectProc()

            $("#procVincular").select2({
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

        $("#vincular").on('click', function(){
            $("#procVincular option:selected").each((index,element) => {
                var procedimento = $(element).val();

                //verifica se o procedimento ja existe na tela
                if (($('#itemProcId_' + procedimento).length > 0) || !procedimento) {
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
                            var template = document.getElementsByTagName("template")[0];

                            var blocoHtml = template.content.cloneNode(true);
                            // inser o tipo do procedimento
                            blocoHtml.querySelectorAll("small")[0].textContent = obj.tipo;
                            // insere a descricao do procedimento
                            blocoHtml.querySelectorAll(".nameProc")[0].textContent = obj.descricao;
                            // insere id do procedimento no campo de valor
                            blocoHtml.querySelectorAll(".idProc")[0].setAttribute("id", "procedimento_" + obj.id);
                            // insere name do procedimento no campo de valor
                            blocoHtml.querySelectorAll(".idProc")[0].setAttribute("name", "procedimento_id[" + obj.id + "]");
                            blocoHtml.querySelectorAll(".item")[0].setAttribute("data-proc_id", obj.id);
                            blocoHtml.querySelectorAll(".idProc")[0].setAttribute("value", obj.id);

                            // insere id do procedimento no item
                            blocoHtml.querySelectorAll(".item")[0].setAttribute("id", "itemProcId_" + obj.id);
                            blocoHtml.querySelectorAll(".btnProc")[0].setAttribute("data-id", obj.id);


                            // incorpora o template ao html
                            $('#procedimentos').prepend(blocoHtml);

                            //limpa o select de procedimentos
                            $("#selectProcedimento").empty();
                            $('#loading').addClass('loading-off');
                        })
                    }
                });
            })

            $("#procVincular").val('').change();
        })

        function limpaSelectProc(){
            var procedimento = [];

            $('.item').each(function(index, element){
                procedimento.push($(element).data('proc_id'));
            })

            return procedimento;
        }


        function removeItem(el){
            $(el).closest('.item').remove()
        }

    </script>
@endpush

<template>
    <div class="row item" id="itemProcId">
        <div class="col-md">
            <h4><span class="nameProc lead"><!-- NOME DO PROCEDIMENTO --></span></h4>
            <small><!-- TIPO DO PROCEDIMENTO --></small>
        </div>

        <div class="col-md-1">
            <div class="form-group">
                <div class="input-group mb-3">
                    <input type="hidden" name="procedimento_id[]" class="idProc">
                    <button class="btn btn-secondary btnProc" onclick="removeItem($(this))"><span class="mdi mdi-close"></span></button>
                </div>
            </div>
        </div>
        <hr>
    </div>

</template>
