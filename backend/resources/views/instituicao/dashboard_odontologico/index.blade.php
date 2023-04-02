@extends('instituicao.layout')

@push('scripts')
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!-- chartist chart -->
    <script src="{{ asset('material/assets/plugins/chartist-js/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <!--c3 JavaScript -->
    <script src="{{ asset('material/assets/plugins/d3/d3.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/c3-master/c3.min.js') }}"></script>
    <!-- Vector map JavaScript -->
    <script src="{{ asset('material/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/vectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
    
    <script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('material/assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('material/assets/plugins/daterangepicker/daterangepicker.css')}}" />
    <script src="{{ asset('material/js/dashboard2.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>

    <link href="{{ asset('material/assets/plugins/bootstrap-table/dist/bootstrap-table.min.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('material/assets/plugins/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/bootstrap-table/dist/bootstrap-table.ints.js') }}"></script>
@endpush

@section('conteudo')
    <div class="row">
        <div class="col-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row page-titles">
        <div class="col-md-4 col-8 align-self-center">
            <h3 class="text-themecolor">Dashboard Odontológico</h3>
        </div>
        @can('habilidade_instituicao_sessao', 'dashboard_odontologico')
            <div class="col-md-8 col-4 align-self-center">
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
            </div>
        @endcan
    </div>

    @can('habilidade_instituicao_sessao', 'dashboard_odontologico')
    <div id="quantidades"></div>

    <div class="row">
        
        <div class="col-lg-4 col-md-12" id='chart_procedimentos'>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Procedimentos</h3>
                    <h6 class="card-subtitle">Estatística por procedimento</h6>
                    <div id="procedimentos" style="height:260px; width:100%;"></div>
                </div>
                <div>
                    <hr class="m-t-0 m-b-0">
                </div>
                <div class="card-body text-center">
                    <ul class="list-inline m-b-0" id="label_procedimentos"></ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12" id='chart_convenios'>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Convênios</h3>
                    <h6 class="card-subtitle">Estatística por convênios</h6>
                    <div id="convenios" style="height:260px; width:100%;"></div>
                </div>
                <div>
                    <hr class="m-t-0 m-b-0">
                </div>
                <div class="card-body text-center">
                    <ul class="list-inline m-b-0" id="label_convenios"></ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12" id='chart_grupos'>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Grupos</h3>
                    <h6 class="card-subtitle">Estatística por grupos</h6>
                    <div id="grupos" style="height:260px; width:100%;"></div>
                </div>
                <div>
                    <hr class="m-t-0 m-b-0">
                </div>
                <div class="card-body text-center">
                    <ul class="list-inline m-b-0" id="label_grupos"></ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12" id='procedimentos_realizados'>
            
        </div>
        <div class="col-lg-6 col-md-12" id='procedimentos_vendidos'>
            
        </div>
    </div>
    @endcan
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
        })

        function getQuantidade(periodo){
            $.ajax({
                url: "{{route('instituicao.dashboardOdontologico.getQuantidade')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end,
                },
                success: function(retorno){
                    $('#quantidades').html(retorno);
                }
                
            })
        }
        
        function getProcedimentosRealizados(periodo){
            $.ajax({
                url: "{{route('instituicao.dashboardOdontologico.getProcedimentosRealizados')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end,
                },
                success: function(retorno){
                    $('#procedimentos_realizados').html(retorno);
                }
                
            })
        }
        
        function getProcedimentosVendidos(periodo){
            $.ajax({
                url: "{{route('instituicao.dashboardOdontologico.getProcedimentosVendidos')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end,
                },
                success: function(retorno){
                    $('#procedimentos_vendidos').html(retorno);
                }
                
            })
        }

        function getProcedimentos(periodo){
            $.ajax({
                url: "{{route('instituicao.dashboardOdontologico.getProcedimentos')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end
                },
                success: function(retorno){
                    var dados = [];
                    var cores = [];
                    var label = "";
                    
                    for(var i = 0; i < retorno.length; i++ ){
                        dados[i] = [retorno[i].descricao, retorno[i].quantidade];
                        cores[i] = retorno[i].cor;

                        label = label+'<li><h6 class="text-muted text-info" style="color: '+retorno[i].cor+' !important;"><i class="fa fa-circle font-10 m-r-10 "></i>'+retorno[i].descricao+' ('+retorno[i].quantidade+')'+'</h6></li>'
                    }

                    var chart = c3.generate({
                        bindto: '#procedimentos',
                        data: {
                            columns: dados,
                            
                            type : 'donut',
                            onclick: function (d, i) {  },
                            onmouseover: function (d, i) {  },
                            onmouseout: function (d, i) {  }
                        },
                        donut: {
                            label: {
                                show: false
                                },
                            title: "Procedimentos",
                            width:20,
                            
                        },
                        
                        legend: {
                            hide: true
                            //or hide: 'data1'
                            //or hide: ['data1', 'data2']
                        },
                        color: {
                                pattern:  cores
                        }
                    });

                    $('#label_procedimentos').html(label);
                }
                
            })
        }
        
        function getConvenios(periodo){
            $.ajax({
                url: "{{route('instituicao.dashboardOdontologico.getConvenios')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end
                },
                success: function(retorno){
                    var dados = [];
                    var cores = [];
                    var label = "";
                    
                    for(var i = 0; i < retorno.length; i++ ){
                        dados[i] = [retorno[i].nome, retorno[i].quantidade];
                        cores[i] = retorno[i].cor;

                        label = label+'<li><h6 class="text-muted text-info" style="color: '+retorno[i].cor+' !important;"><i class="fa fa-circle font-10 m-r-10 "></i>'+retorno[i].nome+' ('+retorno[i].quantidade+')'+'</h6></li>'
                    }

                    var chart = c3.generate({
                        bindto: '#convenios',
                        data: {
                            columns: dados,
                            
                            type : 'donut',
                            onclick: function (d, i) {  },
                            onmouseover: function (d, i) {  },
                            onmouseout: function (d, i) {  }
                        },
                        donut: {
                            label: {
                                show: false
                                },
                            title: "Convenios",
                            width:20,
                            
                        },
                        
                        legend: {
                            hide: true
                            //or hide: 'data1'
                            //or hide: ['data1', 'data2']
                        },
                        color: {
                                pattern:  cores
                        }
                    });

                    $('#label_convenios').html(label);
                }
                
            })
        }
        
        function getGrupo(periodo){
            $.ajax({
                url: "{{route('instituicao.dashboardOdontologico.getGrupo')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end
                },
                success: function(retorno){
                    var dados = [];
                    var cores = [];
                    var label = "";
                    
                    for(var i = 0; i < retorno.length; i++ ){
                        dados[i] = [retorno[i].nome, retorno[i].quantidade];
                        cores[i] = retorno[i].cor;

                        label = label+'<li><h6 class="text-muted text-info" style="color: '+retorno[i].cor+' !important;"><i class="fa fa-circle font-10 m-r-10 "></i>'+retorno[i].nome+' ('+retorno[i].quantidade+')'+'</h6></li>'
                    }

                    var chart = c3.generate({
                        bindto: '#grupos',
                        data: {
                            columns: dados,
                            
                            type : 'donut',
                            onclick: function (d, i) {  },
                            onmouseover: function (d, i) {  },
                            onmouseout: function (d, i) {  }
                        },
                        donut: {
                            label: {
                                show: false
                                },
                            title: "Grupos",
                            width:20,
                            
                        },
                        
                        legend: {
                            hide: true
                            //or hide: 'data1'
                            //or hide: ['data1', 'data2']
                        },
                        color: {
                                pattern:  cores
                        }
                    });

                    $('#label_grupos').html(label);
                }
                
            })
        }
    
        // Dashboard datepicker
        
        var start = moment().startOf('month');
        var end = moment().endOf('month');

        function loadData(start, end) {
            $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            
            $('.sk-spinner').parent().addClass('sk-loading');

            periodo = {"start": start.format('YYYY-MM-DD'), "end": end.format('YYYY-MM-DD'),}

            getQuantidade(periodo);
            getProcedimentos(periodo);
            getConvenios(periodo);
            getProcedimentosRealizados(periodo);
            getProcedimentosVendidos(periodo);
            getGrupo(periodo);
            
        }

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
        // End Dashboard datepicker
    </script>
@endpush