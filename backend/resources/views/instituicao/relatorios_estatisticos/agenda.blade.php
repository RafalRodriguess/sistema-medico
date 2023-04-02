@extends('instituicao.layout')


@push('scripts')
    <!-- chartist chart -->
    <script src="{{ asset('material/assets/plugins/chartist-js/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}"></script>
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
    <script type="text/javascript" src="{{ asset('material/assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('material/assets/plugins/daterangepicker/daterangepicker.css')}}" />
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>

@endpush

@push('estilos')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-div, .print-div * {
                visibility: visible;
            }
            .print-div {
                position: fixed;
                left: 0;
                top: 0;
            }
        }
        
    </style>
@endpush

@section('conteudo')

    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Agenda</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Relatórios Estatísticos</a></li>
                <li class="breadcrumb-item active">Agenda</li>
            </ol>
        </div>
        
    </div>


    <div class="card">
        <div class="card-body ">
            {{-- <form action="{{ route('instituicao.sanguesDerivados.update', [$sangue_derivado]) }}" method="post"> --}}
                <form action="javascript:void(0)" id="formRelatorio">
                    @csrf
                    <div class="row">                        
                        <div class="d-flex m-t-10 justify-content-end">
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="form-group">
                                    <label>Período</label>
                                    <input type="hidden" name="start" id="dataStart">
                                    <input type="hidden" name="end" id="dataEnd">
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;border-radius: 0.25rem; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                            </div>
                        
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Convenios</label>
                                <select class="form-control select2" style="width: 100%" name="convenios" id="convenios">
                                    <option value="todos" selected>Todos</option>
                                    @foreach ($convenios as $item)
                                        <option value="{{$item->id}}">{{$item->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Procedimentos</label>
                                <select class="form-control select2" style="width: 100%" name="procedimentos" id="procedimentos">
                                    <option value="todos" selected>Todos</option>
                                    @foreach ($procedimentos as $item)
                                        <option value="{{$item->id}}">{{$item->descricao}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Profissionais</label>
                                <select class="form-control select2" style="width: 100%" name="profissionais" id="profissionais">
                                    <option value="todos" selected>Todos</option>
                                    @foreach ($profissionais as $item)
                                        <option value="{{$item->id}}">{{$item->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 acao">
                            <div class="form-group text-right" style="margin-top: 30px !important; float: right;">
                                <button type="submit" class="btn btn-info waves-effect waves-light m-r-10" >Pesquisar</button>
                            </div>
                            
                            <div class="col-md-2 imprimir" style="margin-top: 30px !important; float: right; display: none">
                                <div class="form-group">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="imprimir()">Imprimir</button>
                                </div>
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
        function imprimir(){
            window.print();
        }

        $(document).ready(function() {
            
        })

        $('#formRelatorio').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            $.ajax("{{route('instituicao.relatoriosEstatisticos.resultAgenda')}}", {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {
                    $("#chartGrid").html(result);
                    $(".imprimir").css('display', 'block')
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            })
        })

        function loadData(start, end) {
            $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            
            $('.sk-spinner').parent().addClass('sk-loading');

            periodo = {"start": start.format('YYYY-MM-DD'), "end": end.format('YYYY-MM-DD'),}

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
                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            },
            ranges: {
            'Hoje': [moment(), moment()],
            'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Ultimos 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
            'Este Mês': [moment().startOf('month'), moment().endOf('month')],
            'Último Mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, loadData);

        loadData(start, end);
    </script>
@endpush