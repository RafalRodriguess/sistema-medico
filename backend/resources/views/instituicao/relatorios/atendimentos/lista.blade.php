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
        /* @media print {
            body * {
                visibility: hidden;
            }
            .print-table, .print-table * {
                visibility: visible;
            }

            .print-table {
                position: absolute;
                left: 0;
                top: 0;
            }

            .left-sidebar{
                display: none,
            }

            .topbar{
                display: none;
            }

        } */

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

            .left-sidebar{
                display: none,
            }

            .topbar{
                display: none;
            }

            .formRelatorioAtendimento{
                display: none;
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
            <h3 class="text-themecolor m-b-0 m-t-0">Atendimentos</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatorio</a></li>
                <li class="breadcrumb-item active">Atendimentos</li>
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
                    <form action="javascript:void(0)" class="formRelatorioAtendimento" id="formRelatorioAtendimento">
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
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Procedimentos <span><i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Para pesquisar procedimento especifico desmarque a opção todos e selecione um procedimento"></i></span></label>
                                    <select class="form-control select2" style="width: 100%" name="procedimentos[]" id="procedimentos" multiple>
                                        <option value="todos" selected>Todos</option>
                                        {{-- @foreach ($procedimentos as $item)
                                            <option value="{{$item->id}}">{{$item->descricao}}</option>
                                        @endforeach --}}
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('procedimentos')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('procedimentos')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>

                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Profissionais</label>
                                    <select class="form-control select2" style="width: 100%" name="profissionais[]" id="profissionais" multiple>
                                        @foreach ($profissionais as $item)
                                            <option value="{{$item->id}}" selected>{{$item->nome}}</option>
                                        @endforeach
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('profissionais')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('profissionais')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <span alt="default" class="add fas fa-plus-circle" style="cursor: pointer;">
                                        <a class="mytooltip" href="javascript:void(0)">
                                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Abrir mais filtros"></i>
                                        </a>
                                    </span>
                                    <span alt="default" class="remove fas fa-minus-circle" style="cursor: pointer; display: none">
                                        <a class="mytooltip" href="javascript:void(0)">
                                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Esconder filtros"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>

                            <div class="filtros col-md-12"  style="display: none">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Convenios</label>
                                            <select class="form-control select2" style="width: 100%" name="convenios[]" id="convenios" multiple>
                                                @foreach ($convenios as $item)
                                                    <option value="{{$item->id}}" selected>{{$item->nome}}</option>
                                                @endforeach
                                            </select>
                                            <span style="cursor: pointer" onclick="limpa_filtros('convenios')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                            <span style="cursor: pointer" onclick="seleciona_filtros('convenios')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Grupos</label>
                                            <select class="form-control select2" style="width: 100%" name="grupos[]" id="grupos" multiple>
                                                @foreach ($grupos as $item)
                                                    <option value="{{$item->id}}" selected>{{$item->nome}}</option>
                                                @endforeach
                                            </select>
                                            <span style="cursor: pointer" onclick="limpa_filtros('grupos')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                            <span style="cursor: pointer" onclick="seleciona_filtros('grupos')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Setores</label>
                                            <select class="form-control select2" style="width: 100%" name="setores[]" id="setores" multiple>
                                                @foreach ($setores as $item)
                                                    <option value="{{$item->id}}" selected>{{$item->descricao}}</option>
                                                @endforeach
                                            </select>
                                            <span style="cursor: pointer" onclick="limpa_filtros('setores')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                            <span style="cursor: pointer" onclick="seleciona_filtros('setores')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Solicitantes</label>
                                            <select class="form-control select2" style="width: 100%" name="solicitantes[]" id="solicitantes" multiple>
                                                @foreach ($solicitantes as $item)
                                                    <option value="{{$item->id}}">{{$item->nome}}</option>
                                                @endforeach
                                            </select>
                                            <span style="cursor: pointer" onclick="limpa_filtros('solicitantes')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                            <span style="cursor: pointer" onclick="seleciona_filtros('solicitantes')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-control select2" style="width: 100%" name="status[]" id="status" multiple>
                                        @foreach ($status as $item)
                                            <option value="{{$item}}" @if ($item == 'finalizado')
                                                selected
                                            @endif>{{$item}}</option>
                                        @endforeach
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('status')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('status')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Tipo relatório</label>
                                    <select name="tipo_relatorio" id="tipo_relatorio" class="form-control selectfild2" style="width: 100%">
                                        @if (!\Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_atendimento_somente_repasses'))
                                            <option value="atendimento">Atendimento</option>
                                            <option value="atendimento_valor">Atendimento com valor pago</option>
                                            <option value="detalhado">Repasse detalhado</option>
                                        @endif
                                        <option value="simples">Repasse simples</option>
                                        @if (!\Gate::check('habilidade_instituicao_sessao', 'visualizar_relatorio_atendimento_somente_repasses'))
                                            <option value="simples_valor">Repasse simples com valor</option>
                                            <option value="convenios">Convênios</option>
                                        @endif
                                            
                                    </select>


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
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">Pesquisar</button>
                                </div>

                                <div class="form-group text-right">
                                    

                                    <div class="col-md-2 imprimir" style=" float: right; width: 100%; display: none">
                                        <div class="form-group">
                                            <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="imprimir()">Imprimir</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3 financeiro" style=" float: right; width: 100%; display: none">
                                        <div class="form-group">
                                            <button type="button" class="btn waves-effect waves-light btn-block btn-danger gera_financeiro_modal">Gerar conta a pagar</button>
                                        </div>
                                    </div>
                                    @can('habilidade_instituicao_sessao', 'exporta_excel_relatorio_atendimento')
                                        <div class="col-md-1 btnExportExcel" style="float: right; width: 100%; display: none;">
                                            <a href="" id="btnExportExcel" target="_blank" class="btn btn-outline-secondary" style="border: 1px solid #ced4da;" data-toggle="tooltip" data-placement="top" title="Exportar para excel">
                                                <i class="fa fw fa-file-excel" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive print-table mb-2">
                        <div class="cabecalho" style="display: none;">
                            <div class="col-md-12 row align-items-center">
                                <img class="light-logo col-sm-2" src="@if ($instituicao->imagem){{ \Storage::cloud()->url($instituicao->imagem) }} @endif" alt="" style="height: 100px;"/>
                                <h3 class='lead col-sm-8'>{{$instituicao->nome}}</h3>
                                <label class="col-sm-2">{{date("d/m/Y H:i:s")}}</label>
                                <small class="text-muted col-sm-12 text-center"><b>endereço:</b> {{$instituicao->rua}} <b>Nº:</b> {{$instituicao->numero}} {{$instituicao->complemento}} <b>Bairro:</b> {{$instituicao->bairro}} <b>Cidade:</b> {{$instituicao->cidade}} <b>UF:</b> {{$instituicao->estado}}</small>
                            </div>

                            <h3 class="mt-3"><center>Relatório de atendimento <span class='texto_titulo'></span></center></h3>

                            <hr class="hr-line-dashed">
                        </div>

                        <div class="tabela"></div>
                    </div>

                    <div id="modalFinanceiro"></div>
                </div>
            </div>
            <!-- Column -->

            <!-- Column -->

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(".add").on('click', function() {
            $(".filtros").css('display', 'block');
            $(".add").css('display', 'none');
            $(".remove").css('display', 'block');
        })

        $(".remove").on('click', function() {
            $(".filtros").css('display', 'none');
            $(".remove").css('display', 'none');
            $(".add").css('display', 'block');
        })

        function imprimir(){
            $(".imprimir").attr('disabled', true);
            $(".texto_titulo").text($("#tipo_relatorio").val().replace("_", " "));
            $(".cabecalho").css("display", "block");
            window.print();
            $(".cabecalho").css("display", "none");
            setTimeout(function(){
                $(".imprimir").attr('disabled', false)
            }, 1000);
        }

        $(document).ready(function() {
            $("#procedimentos").select2({
                // placeholder: "Pesquise por nome do paciente",
                allowClear: true,
                // minimumInputLength: 3,

                language: {
                    searching: function () {
                        return 'Buscando procedimentos (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                },

                ajax: {
                    url:"{{route('instituicao.relatorioAtendimento.getProcedimentos')}}",
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
                                text: `${item.descricao}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
            });

            $('.acao').on('click', '.gera_financeiro_modal', function(){
                var formData = new FormData($('#formRelatorioAtendimento')[0])

                // var modal = 'modalVerFinanceiro';

                var data_inicio = $("#data_inicio").val();
                var data_fim = $("#data_fim").val();
                var convenios = $("#convenios").val();
                var procedimentos = $("#procedimentos").val();
                var profissionais = $("#profissionais").val();
                var status = $("#status").val();
                var grupos = $("#grupos").val();
                var setores = $("#setores").val();


                $.ajax({
                    url: "{{route('instituicao.relatorioAtendimento.verFinanceiro')}}",
                    type: "POST",
                    // data: formData,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'data_inicio': data_inicio,
                        'data_fim': data_fim,
                        'convenios': convenios,
                        'procedimentos': procedimentos,
                        'profissionais': profissionais,
                        'status': status,
                        'grupos': grupos,
                        'setores': setores,
                    },
                    // processData: false,
                    // contentType: false,
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function (result) {
                        if(result.icon == "error"){
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
                            $("#modalFinanceiro").html(result);
                            $("#modalFinanceiro").find("#modalVerFinanceiro").modal();
                        }
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader')
                        $('.mask_item').setMask();
                        $('.mask_item').removeClass('mask_item');
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
                });
            });

        })

        $('#formRelatorioAtendimento').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            $.ajax("{{route('instituicao.relatorioAtendimento.tabela')}}", {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {
                    $(".tabela").html(result);
                    setTimeout(function(){
                        $(".imprimir").css('display', 'block');
                        
                        

                        if($("#tipo_relatorio").val() == 'detalhado' || $("#tipo_relatorio").val() == 'simples'){
                            $(".financeiro").css('display', 'block');
                        }else{
                            $(".financeiro").css('display', 'none');
                        }
                    }, 1000);

                    $(".imprimir").css('display', 'block')

                    if($("#tipo_relatorio option:selected").val() == "convenios"){
                        
                        var formData = $('#formRelatorioAtendimento').serialize();

                        href = "{{route('instituicao.relatorioAtendimento.exportExcel')}}"+"/?"+formData;

                        $("#btnExportExcel").attr("href", href);
                        $(".btnExportExcel").css('display', 'block')
                    }else{
                        $(".btnExportExcel").css('display', 'none')
                        $("#btnExportExcel").attr("href", '');
                    }
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
        })

        function ativarClass(){
            // $(".table-responsive").find('.accordion').find('#demo-foo-row-toggler').footable()
        }

        function limpa_filtros(elemento){
            $("#"+elemento).find("option").attr("selected", false);
            $("#"+elemento).val('').trigger('change');
        }

        function seleciona_filtros(elemento){
            if(elemento == "procedimentos"){
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

        // $("#btnExportExcel").on('click', function(e){
        //     var formData = new FormData($("#formRelatorioAtendimento")[0]);
        //     $.ajax("{{route('instituicao.relatorioAtendimento.exportExcel')}}", {
        //         method: "POST",
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         beforeSend: () => {
        //             $('.loading').css('display', 'block');
        //             $('.loading').find('.class-loading').addClass('loader')
        //         },
        //         success: function (response) {
    
        //             $.toast({
        //                 heading: response.title,
        //                 text: response.text,
        //                 position: 'top-right',
        //                 loaderBg: '#ff6849',
        //                 icon: response.icon,
        //                 hideAfter: 3000,
        //                 stack: 10
        //             });
                    
        //         },
        //         complete: () => {
        //             $('.loading').css('display', 'none');
        //             $('.loading').find('.class-loading').removeClass('loader')
                    
        //         },
        //         error: function (response) {
        //             $('.loading').css('display', 'none');
        //             $('.loading').find('.class-loading').removeClass('loader')
        //         }
        //     })
        // })

    </script>
@endpush
