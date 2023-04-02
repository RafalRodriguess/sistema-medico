@extends('instituicao.layout')


@push('scripts')
    <!-- chartist chart -->
    <script src="{{ asset('material/assets/plugins/chartist-js/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}">
    </script>
    <!--c3 JavaScript -->
    <script src="{{ asset('material/assets/plugins/d3/d3.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/c3-master/c3.min.js') }}"></script>
    <!-- jQuery peity -->
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>

    <!-- Vector map JavaScript -->
    <script src="{{ asset('material/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/vectormap/jquery-jvectormap-us-aea-en.js') }}"></script>

    <script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('material/assets/plugins/daterangepicker/daterangepicker.js') }}">
    </script>
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
@endpush

@push('estilos')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('material/assets/plugins/daterangepicker/daterangepicker.css') }}" />
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .print-div,
            .print-div * {
                visibility: visible;
            }

            .context-buttons,
            .post-tooltip {
                display: none;
            }

            .print-div {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            * {
                text-decoration: none !important;
            }

            .collapse {
                display: block !important;
            }

            .result-header {
                font-weight: bold;
                color: #444d51;
                padding: 0;
            }

            .card {
                border: unset !important;
            }

            .hide-print {
                display: none !important;
            }

            .print-div .card-body {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .print-div .result-item {
                border: unset !important;
                border-top: 1px solid #dee2e6 !important;
                margin-bottom: 2em;
            }
        }

        .select2-selection {
            height: 50px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            height: 50px !important;
        }

        .select2-selection__choice {
            font-size: 14px;
            margin: 2px !important;
            color: black;
        }

        .select2-selection.select2-selection--single {
            height: max-content !important;
        }

        .result-subitem,
        .result-header {
            display: flex;
            justify-content: space-between;
        }

        .result-header {
            padding: 0 1rem;
        }

        .result-subitem {
            border-bottom: 1px solid lightgrey;
            padding: 0.5em 0.25em;
            color: #67757c;
        }

        .result-subitem:last-child {
            border: unset;
        }

        .result-subitem>* {
            width: 16%;
            min-width: 100px;
            text-align: center;
            padding: 0 0.25em;
        }

        .result-expand {
            overflow: auto;
        }

        .print-div a * {
            color: #67757c;
        }

        .card-body .result-item {
            border-bottom: unset !important;
        }

        .card-body .result-item:last-child {
            border-bottom: 1px solid #dee2e6 !important;
        }
    </style>
@endpush

@section('conteudo')
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Estoque</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatórios Estatísticos</a></li>
                <li class="breadcrumb-item active">Estoque</li>
            </ol>
        </div>

    </div>


    <div class="card">
        <div class="card-body ">
            {{-- <form action="{{ route('instituicao.relatoriosEstoque.result') }}" method="POST"> --}}
            <form action="javascript:void(0)" id="formRelatorio" method="POST" target="_blank">
                @csrf
                <div class="row">
                    <div class="input-group col-12">
                        <div class="col">
                            <div class="form-group">
                                <label>Período</label>
                                <input type="hidden" name="start" id="dataStart">
                                <input type="hidden" name="end" id="dataEnd">
                                <div id="reportrange"
                                    style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;border-radius: 0.25rem; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label">Tipo de resultados</label>
                                <select id="tipo-resultado-select" class="form-control select2" style="width: 100%">
                                    <option value="1">Entradas de
                                        estoque</option>
                                    <option value="2">Saídas de estoque
                                    </option>
                                    <option value="3">Posição de estoque
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md" id="tipo-saida-container" style="display: none">
                            <div class="form-group">
                                <label class="form-label">Tipo de saída de estoque</label>
                                <select name="destino-saida-estoque" class="form-control">
                                    @foreach ($tipos_saida as $id => $tipo)
                                        <option value="{{ $id }}">{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label">Centros de custo
                                    <span data-toggle="tooltip" data-placement="right" title=""
                                        data-original-title="Escolha um ou mais centros de custo para gerar um relatório sobre eles, ou deixe vazio para fazer sobre todos"><i
                                            class="fas fa-question-circle"></i></span>
                                </label>
                                <select class="form-control" style="width: 100%" name="centros_custos[]"
                                    id="centros_custo_select" multiple>
                                    @foreach ($centros_custos as $centro)
                                        <option value="{{ $centro->id }}">{{ $centro->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="input-group col-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Estoques
                                    <span data-toggle="tooltip" data-placement="right" title=""
                                        data-original-title="Escolha um ou mais estoques para gerar um relatório sobre eles, ou deixe vazio para fazer sobre todos"><i
                                            class="fas fa-question-circle"></i></span>
                                </label>
                                <select class="form-control" style="width: 100%" name="estoques[]" id="estoques_select"
                                    multiple>
                                    @foreach ($estoques as $estoque)
                                        <option value="{{ $estoque->id }}">{{ $estoque->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Produtos
                                    <span data-toggle="tooltip" data-placement="right" title=""
                                        data-original-title="Escolha um ou mais produtos para gerar um relatório sobre eles, ou deixe vazio para fazer sobre todos"><i
                                            class="fas fa-question-circle"></i></span>
                                </label>
                                <select class="form-control" style="width: 100%" name="produtos[]" id="produtos_select"
                                    multiple>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 acao d-flex justify-content-end">
                        <div class="imprimir form-group mr-2" style="display: none">
                            <button type="submit" class="btn waves-effect waves-light btn-block btn-success">Gerar arquivo</button>
                        </div>
                        <div class="imprimir form-group mr-2" style="display: none">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-success"
                                onclick="imprimir()">Imprimir</button>
                        </div>
                        <div class="form-group text-right">
                            <button type="button" id="button-pesquisar" class="btn btn-info waves-effect waves-light m-r-10">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div id="chartGrid" class="print-div"></div>
@endsection

@push('scripts')
    <script>
        /**
         * Rotas utilizadas para cada tipo de relatorio
         * Infelizmente o tempo é curto portanto para reutilizar o máximo possível essa será a forma de identificar as rotas
         * de pesquisa e geração de artigo respectivamente para cada tipo de relatorio 1 => entrada, 2 => saida e 3 => posicao
         * */
        const rotas = {
            1: [
                "{{ route('instituicao.relatoriosEstoque.entradas') }}",
                "{{ route('instituicao.relatoriosEstoque.arquivo-entradas') }}"
            ],
            2: [
                "{{ route('instituicao.relatoriosEstoque.saidas') }}",
                "{{ route('instituicao.relatoriosEstoque.arquivo-saidas') }}"
            ],
            3: [
                "{{ route('instituicao.relatoriosEstoque.posicao') }}",
                "{{ route('instituicao.relatoriosEstoque.arquivo-posicao') }}"
            ]
        };

        $(document).ready(function() {
            $('#centros_custo_select').select2({
                placeholder: 'Todos os centros de custo',
                multiple: true,
                allowClear: true
            });
            $('#estoques_select').select2({
                placeholder: 'Todos os estoques',
                multiple: true,
                allowClear: true
            });
            $('#produtos_select').select2({
                placeholder: "Busque o produto",
                multiple: true,
                allowClear: true,
                ajax: {
                    url: "{{ route('instituicao.ajax.buscar-produtos') }}",
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            search: params.term,
                            '_token': '{{ csrf_token() }}',
                            paginate: true
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.results, function(obj) {
                                return {
                                    id: obj.id,
                                    text: `#${obj.id} - ${obj.descricao} [un: ${obj.unidade.descricao}]`
                                }
                            }),
                            pagination: {
                                more: data.pagination.more
                            }
                        }
                    }
                },
                language: {
                    searching: function() {
                        return 'Buscando ...';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                },
                escapeMarkup: function(m) {
                    return m;
                }
            });

            if ($('#tipo-resultado-select').val() == 2) {
                $('#tipo-saida-container').show();
            }
            $('#tipo-resultado-select').on('select2:select', (e) => {
                if ($(e.target).val() == 2) {
                    $('#tipo-saida-container').show();
                } else {
                    $('#tipo-saida-container').hide();
                }
            });

            $('#button-pesquisar').on('click', function(e) {
                e.preventDefault()
                var formData = new FormData($('#formRelatorio')[0]);
                $.ajax(rotas[$('#tipo-resultado-select').val()][0], {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader');
                    },
                    success: (result) => {
                        $("#chartGrid").html(result);
                        $(".imprimir").css('display', 'block');
                        $('.post-tooltip').tooltip();
                        $('#expand-all-button').on('click', (e) => {
                            $('.result-item .collapse').addClass('show');
                        });
                        $('#collapse-all-button').on('click', (e) => {
                            $('.result-item .collapse').removeClass('show');
                        });
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader');
                    }
                })
            });

            $('#formRelatorio').on('submit', e => {
                $(e.target).attr('action', rotas[$('#tipo-resultado-select').val()][1]);
            });
        });

        function imprimir() {
            window.print();
        }


        function loadData(start, end) {
            $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));

            $('.sk-spinner').parent().addClass('sk-loading');

            periodo = {
                "start": start.format('YYYY-MM-DD'),
                "end": end.format('YYYY-MM-DD'),
            }

            $("#dataStart").val(periodo.start).change();
            $("#dataEnd").val(periodo.end).change();

        }

        var start = moment().startOf('month');
        var end = moment().endOf('month');

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            opens: "left",
            locale: {
                "format": "DD/MM/YYYY",
                "applyLabel": "OK",
                "cancelLabel": "Cancelar",
                "fromLabel": "Início",
                "toLabel": "Fim",
                "customRangeLabel": "Definir datas",
                "daysOfWeek": [
                    "Dom",
                    "Seg",
                    "Ter",
                    "Qua",
                    "Qui",
                    "Sex",
                    "Sáb"
                ],
                "monthNames": [
                    "Janeiro",
                    "Fevereiro",
                    "Março",
                    "Abril",
                    "Maio",
                    "Junho",
                    "Julho",
                    "Agosto",
                    "Setembro",
                    "Outubro",
                    "Novembro",
                    "Dezembro"
                ],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov',
                    'Dez'
                ],
            },
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Ultimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mês': [moment().startOf('month'), moment().endOf('month')],
                'Último Mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            }
        }, loadData);

        loadData(start, end);
    </script>
@endpush
