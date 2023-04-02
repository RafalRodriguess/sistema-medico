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

            .left-sidebar{
                display: none,
            }

            .topbar{
                display: none;
            }

            .formRelatorio{
                display: none;
            }
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
            <h3 class="text-themecolor m-b-0 m-t-0">Sancoop</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatório</a></li>
                <li class="breadcrumb-item active">Sancoop</li>
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
                    <form action="javascript:void(0)" class="formRelatorio" id="formRelatorio">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Data de:</label>
                                    <select class="form-control" style="width: 100%" name="data_de">
                                        <option value="envio">Data de envio</option>
                                        <option value="atendimento">Data de atendimento</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
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
                                    <label class="form-label">Tipo Guia</label>
                                    <select class="form-control" style="width: 100%" name="tipo_guia">
                                        <option value="">Todas</option>
                                        <option value="consulta">Consulta</option>
                                        <option value="sadt">SADT</option>
                                    </select>
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
                                    <label class="form-label">Status</label>
                                    <select class="form-control select2" style="width: 100%" name="status[]" id="status" multiple>
                                        @foreach ($status as $key => $item)
                                            <option value="{{$key}}" selected >{{$item}}</option>
                                        @endforeach
                                    </select>
                                    <span style="cursor: pointer" onclick="limpa_filtros('status')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Limpar filtros"><i class="fa fa-trash"></i> </span>
                                    <span style="cursor: pointer" onclick="seleciona_filtros('status')" class="help" data-toggle="tooltip" data-placement="top" data-original-title="Selecionar todos os filtros"><i class="fa fa-reply-all"></i> </span>
                                </div>
                            </div>

                            <div class="col-md-12 acao">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">Pesquisar</button>
                                </div>

                                <div class="form-group text-right">
                                    <div class="col-md-2 imprimir" style=" float: right; width: 100%; display: none">
                                        <div class="form-group">
                                            <button type="button" id="btnExportPdf" class="btn waves-effect waves-light btn-block btn-success" >Imprimir</button>
                                        </div>
                                    </div>

                                    <div class="col-md-1 imprimir" style="float: right; width: 100%; display: none;">
                                        <a href="" id="btnExportExcel" target="_blank" class="btn btn-outline-secondary" style="border: 1px solid #ced4da;" data-toggle="tooltip" data-placement="top" title="Exportar para excel">
                                            <i class="fa fw fa-file-excel" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive print-table mb-2">
                        <div class="tabela"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
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
        });

        $('#formRelatorio').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            $.ajax("{{route('instituicao.relatoriosSancoop.tabela')}}", {
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
                    $(".imprimir").css('display', 'block')
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
            })
        })

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

        $('#btnExportExcel').on('click', function(e){
            e.preventDefault()
            // var formData = new FormData($('#formRelatorio')[0]);
            formData = $('#formRelatorio').serialize();
            
            window.open("{{route('instituicao.relatoriosSancoop.exportExcel')}}"+"/?"+formData, "_blank");

        });

        $('#btnExportPdf').on('click', function(e){
            e.preventDefault()
            // var formData = new FormData($('#formRelatorio')[0]);
            formData = $('#formRelatorio').serialize();
            
            window.open("{{route('instituicao.relatoriosSancoop.exportPdf')}}"+"/?"+formData, "_blank");

        });



    </script>
@endpush
