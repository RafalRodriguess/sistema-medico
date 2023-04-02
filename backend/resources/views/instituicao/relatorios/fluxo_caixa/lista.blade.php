@extends('instituicao.layout')


@push('scripts')
    <!-- jQuery peity -->
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
@endpush

@push('estilos')
    <link href="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}" rel="stylesheet">
    <style>
        @media print {
           * {
                background: transparent;
                color: #000;
                text-shadow: none;
                filter: none;
                -ms-filter: none;
            }
            body * {
                visibility: hidden;
            }

            .print-table, .print-table * {
                visibility: visible;

            }
            .print-table {
                position: absolute;
                width: 100%;
                top: 0;
                left: 0;

                /* display: block; */
            }
            .no_print {
                display: none !important;
            }

            .quebraPagina{
                page-break-before: always;
            }

            /*  */
        }

        .select2-selection {
            height: 50px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            height: 50px!important;
        }

        .select2-selection__choice {
            font-size: 14px;
            margin: 2px !important;
            color: black;
        }
    </style>
@endpush

@section('conteudo')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Fluxo de caixa</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatorio</a></li>
                <li class="breadcrumb-item active">Fluxo de caixa</li>
            </ol>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <form action="javascript:void(0)" id="formRelatorio">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Data inicial</label>
                                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Data final</label>
                                            <input type="date" id="data_fim" name="data_fim" class="form-control" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Conta Caixa</label>
                                    <select class="form-control select2" style="width: 100%" name="contas[]" id="contas" multiple required>
                                        @foreach ($conta_caixa as $item)
                                            <option value="{{$item->id}}" selected>{{$item->descricao}}</option>
                                        @endforeach
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('contas')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('contas')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Formas de pagamento</label>
                                    <select name="formaPagamento" class="form-control selectfild2" style="width: 100%">
                                    <option value="">Todas Formas pagamento</option>
                                        @foreach ($formaPagamentos as $formaPagamento)
                                            <option value="{{ $formaPagamento }}">
                                                {{ App\ContaPagar::forma_pagamento_texto($formaPagamento) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Usuario baixa <span><i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Para pesquisar usuario especifico desmarque a opção todos e selecione um usuario"></i></span></label>
                                    <select class="form-control select2" style="width: 100%" name="usuarios[]" id="usuarios" multiple>
                                        <option value="todos" selected>Todos</option>
                                        {{-- @foreach ($procedimentos as $item)
                                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                                        @endforeach --}}
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('usuarios')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('usuarios')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>

                                </div>
                            </div>

                            {{-- <div class="col-md-8"></div> --}}
                            {{-- <div class="col-md-8"></div> --}}
                            {{-- <div class="col-md-2">
                                <div class="form-group imprimir" style="margin-top: 30px !important; float: right; width: 100%; display: none">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="imprimir()">Imprimir</button>
                                </div>
                            </div> --}}
                            <div class="col-md-12 acao">
                                <div class="form-groupn col-sm-3 text-right" style="margin-top: 30px !important; float: right;">
                                    <button type="submit" class="btn btn-info waves-effect btn-block waves-light">Pesquisar</button>
                                </div>

                                <div class="form-groupn imprimir col-sm-3" style="margin-top: 30px !important; float: right; display: none">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="imprimir()">Imprimir</button>
                                </div>

                                @can('habilidade_instituicao_sessao', 'cadastrar_movimentacoes')
                                    <div class="form-groupn imprimir col-sm-3" style="margin-top: 30px !important; float: right; display: none">
                                        <button type="button" class="btn waves-effect waves-light btn-block btn-circle btn-success geraMovimento" target="_blank"><i class="mdi mdi-clipboard-flow" aria-hidden="true"></i></button>
                                    </div>
                                @endcan


                            </div>
                        </div>
                    </form>

                    <div class="table-responsive print-table my-2" style="overflow-x: hidden;">
                        <div class="cabecalho" style="display: none;">
                            <div class="col-md-12 row align-items-center">
                                <img class="light-logo col-sm-2" src="@if ($instituicao->imagem){{ \Storage::cloud()->url($instituicao->imagem) }} @endif" alt="" style="height: 100px;"/>
                                <h3 class='lead col-sm-8'>{{$instituicao->nome}}</h3>
                                <label class="col-sm-2">{{date("d/m/Y H:i:s")}}</label>
                                <small class="text-muted col-sm-12 text-center"><b>endereço:</b> {{$instituicao->rua}} <b>Nº:</b> {{$instituicao->numero}} {{$instituicao->complemento}} <b>Bairro:</b> {{$instituicao->bairro}} <b>Cidade:</b> {{$instituicao->cidade}} <b>UF:</b> {{$instituicao->estado}}</small>
                            </div>

                            <h3 class="mt-3"><center>Fluxo de caixa</center></h3>

                            <hr class="hr-line-dashed">
                        </div>

                        <div class="tabela"></div>
                    </div>

                    <div id="modalGeraMovimento"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#usuarios").select2({
                // placeholder: "Pesquise por nome do paciente",
                allowClear: true,
                // minimumInputLength: 3,

                language: {
                    searching: function () {
                        return 'Buscando usuarios (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                },

                ajax: {
                    url:"{{route('instituicao.relatoriosFluxoCaixa.getUsuarios')}}",
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
                                text: `${item.nome}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
            });
        });

        $("#usuarios").on('change', function(){
            if($(this).val()[0] == 'todos'){
                var dados = $(this).val();

                const index = dados.indexOf('todos');
                if (index > -1) {
                    dados.splice(index, 1);
                }

                $(this).val(dados);
                $(this).change();
            }
        });
        
        function imprimir(){
            $(".cabecalho").css("display", "block");
            $("#tableScroll").removeClass("table-scroll table-wrapper-scroll");

            window.print();
            $(".cabecalho").css("display", "none");
            $("#tableScroll").addClass("table-scroll table-wrapper-scroll");
        }

        $('#formRelatorio').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            $.ajax("{{route('instituicao.relatoriosFluxoCaixa.tabela')}}", {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                    $(".geraMovimento").attr("disabled", true);
                },
                success: function (result) {
                    $(".tabela").html(result);
                    $(".imprimir").css('display', 'block')
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') ;
                    if($("#contas").val().length == 1){
                        $(".geraMovimento").attr("disabled", false);
                    }
                }
            })
        })


        $(".geraMovimento").on("click", function(e){
            geraMovimento()
        })

        function limpa_filtros(elemento){
            $("#"+elemento).find("option").attr("selected", false);
            $("#"+elemento).val('').trigger('change');
        }

        function seleciona_filtros(elemento){
            if(elemento == "usuarios"){
                $("#"+elemento).val([]);
                var dados = [];
                dados.push("todos")
                $("#"+elemento).val(dados)
                $("#"+elemento).trigger('change');
            }else{
                $("#"+elemento).val([]);
                var dados = [];
                $("#"+elemento).find("option").each(function(index, elem){
                    $(elem).attr("selected", true);
                    dados.push($(elem).val())
                })
                $("#"+elemento).val(dados)
                $("#"+elemento).trigger('change');
            }
        }


        // function seleciona_filtros(elemento){
        //     $("#"+elemento).val([]);
        //     var dados = [];
        //     $("#"+elemento).find("option").each(function(index, elem){
        //         $(elem).attr("selected", true);
        //         dados.push($(elem).val())
        //     })
        //     $("#"+elemento).val(dados)
        //     $("#"+elemento).trigger('change');

        // }

        $("#contas").on("change", function(){
            $(".imprimir").css('display', 'none')
        })

    </script>
@endpush
