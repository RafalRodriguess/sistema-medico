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
            <h3 class="text-themecolor">Bem vindo {{ request()->user('instituicao')->nome }}</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Painel Atendimento Ambulatorial</li>
            </ol>
        </div>
        @can('habilidade_instituicao_sessao', 'visualizar_dashboard')
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
                            
                            <!--<select class="form-control" name="periodo" id="periodo">
                                <option value="">Hoje</option>
                                <option value="">Últimos 30 dias</option>
                            </select>-->
                        </div>
                    </div>
                    {{-- <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                        <div class="form-group">
                            <label>Especialidade</label>
                            <select class="form-control" name="especialidade_id" id="especialidade_id">
                                <option value="">Todas especialidades</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                        <div class="form-group">
                            <label>Profissional</label>
                            <select class="form-control" name="medico_id" id="medico_id">
                                <option value="">Todos profissionais</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
        @endcan
    </div>

    @can('habilidade_instituicao_sessao', 'visualizar_dashboard')
    <div id="quantidades"></div>

    <div class="row">
        <div class="col-lg-4 col-md-12" id='chart_atendimento'>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Atendimento</h3>
                    <h6 class="card-subtitle">Situações de atendimento</h6>
                    <div id="atendimento" style="height:260px; width:100%;"></div>
                </div>
                <div>
                    <hr class="m-t-0 m-b-0">
                </div>
                <div class="card-body text-center ">
                    <ul class="list-inline m-b-0">
                        <li>
                            <h6 class="text-muted text-info" style="color: #ffcf8e !important;"><i class="fa fa-circle font-10 m-r-10 "></i>Em atendimento</h6> </li>
                        <li>
                            <h6 class="text-muted  text-primary" style="color: #745af2 !important;"><i class="fa fa-circle font-10 m-r-10"></i>Atendidos</h6> </li>
                        <li>
                            <h6 class="text-muted  text-success" style="color: #b9b9bf !important;"><i class="fa fa-circle font-10 m-r-10"></i>Ausentes</h6> </li>
                            <li>
                                <h6 class="text-muted  text-success" style="color: #fc4b6c !important;"><i class="fa fa-circle font-10 m-r-10"></i>Desmarcados</h6> </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12" id='chart_paciente'>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Pacientes</h3>
                    <h6 class="card-subtitle">Novos X Recorrentes</h6>
                    <div id="pacientes" style="height:260px; width:100%;"></div>
                </div>
                <div>
                    <hr class="m-t-0 m-b-0">
                </div>
                <div class="card-body text-center ">
                    <ul class="list-inline m-b-0">
                        <li>
                            <h6 class="text-muted text-info" style="color: #26c6da !important;"><i class="fa fa-circle font-10 m-r-10 "></i>Novos</h6> </li>
                        <li>
                            <h6 class="text-muted  text-primary" style="color: #1e88e5 !important;"><i class="fa fa-circle font-10 m-r-10"></i>Recorrentes</h6> </li>
            
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12" id='chart_convenio'>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Convênios</h3>
                    <h6 class="card-subtitle">Estatística por convênio</h6>
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
    </div>
    @endcan
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            getEspecialidades()
            getMedicos()
        })
        
        function getEspecialidades(){
            $.ajax({
                url: "{{route('instituicao.dashboard.getEspecialidades')}}",
                success: function(retorno){
                    $('#especialidade_id').find('option').filter(':not([value=""])').remove();

                    for(var i = 0; i < retorno.length; i++ ){
                        $('#especialidade_id').append('<option value="'+retorno[i].id+'"">'+retorno[i].descricao+'</option>')
                       
                    }
                }
                
            })
        }

        function getMedicos(){
            $.ajax({
                url: "{{route('instituicao.dashboard.getMedicos')}}",
                success: function(retorno){
                    
                    $('#medico_id').find('option').filter(':not([value=""])').remove();

                    for(var i = 0; i < retorno.length; i++ ){
                        $('#medico_id').append('<option value="'+retorno[i].id+'"">'+retorno[i].nome+'</option>')
                       
                    }
                }
                
            })
        }

        $('#medico_id').on('change', function(){
            loadData(start, end)
        })

        function getAgendamentos(periodo){
            $.ajax({
               
                
                url: "{{route('instituicao.dashboard.getAgendamentos')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end,
                    // "especialidade_id": $('#especialidade_id').val(),
                    "medico_id": $('#medico_id').val()
                },
                success: function(retorno){
                    $('#quantidades').html(retorno);
                }
                
            })
        }

        function getAtendimentos(periodo){
            $.ajax({
                url: "{{route('instituicao.dashboard.getAtendimentos')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end,
                    // "especialidade_id": $('#especialidade_id').val(),
                    "medico_id": $('#medico_id').val()
                },
                success: function(retorno){
                    // $('#chart_atendimento').html(retorno);

                    var chart = c3.generate({
                        bindto: '#atendimento',
                        data: {
                            columns: [
                                ['Em atendimento', retorno.em_atendimento],
                                ['Atendidos', retorno.atendidos],
                                ['Ausentes', retorno.ausentes],
                                ['Desmarcados', retorno.desmarcados],
                            ],
                            
                            type : 'donut',
                            onclick: function (d, i) { console.log("onclick", d, i); },
                            onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                            onmouseout: function (d, i) { console.log("onmouseout", d, i); }
                        },
                        donut: {
                            label: {
                                show: false
                            },
                            title: "Pacientes",
                            width:20,
                            
                        },
                        
                        legend: {
                        hide: true
                        //or hide: 'data1'
                        //or hide: ['data1', 'data2']
                        },
                        color: {
                            pattern: ['#ffcf8e', '#745af2', '#b9b9bf', '#fc4b6c']
                        }
                    });
                }
                
            })
        }
        
        
        function getPacientes(periodo){
            $.ajax({
                url: "{{route('instituicao.dashboard.getPacientes')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end,
                    "especialidade_id": $('#especialidade_id').val(),
                    "medico_id": $('#medico_id').val()
                },
                success: function(retorno){
                    // $('#chart_paciente').html(retorno);
                    var chart = c3.generate({
                        bindto: '#pacientes',
                        data: {
                            columns: [
                                ['Novos', retorno.novos],
                                ['Recorrentes', retorno.recorrentes],
                            ],
                            
                            type : 'donut',
                            onclick: function (d, i) { console.log("onclick", d, i); },
                            onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                            onmouseout: function (d, i) { console.log("onmouseout", d, i); }
                        },
                        donut: {
                            label: {
                                show: false
                            },
                            title: "Pacientes",
                            width:20,
                            
                        },
                        
                        legend: {
                        hide: true
                        //or hide: 'data1'
                        //or hide: ['data1', 'data2']
                        },
                        color: {
                            pattern: ['#26c6da', '#1e88e5']
                        }
                    });
                }
                
            })
        }

        function getConvenios(periodo){
            $.ajax({
                url: "{{route('instituicao.dashboard.getConvenios')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "start": periodo.start,
                    "end": periodo.end,
                    "especialidade_id": $('#especialidade_id').val(),
                    "medico_id": $('#medico_id').val()
                },
                success: function(retorno){
                    var dados = [];
                    var cores = [];
                    var label = "";
                    for(var i = 0; i < retorno.length; i++ ){
                        dados[i] = [retorno[i].nome, retorno[i].total];
                        cores[i] = retorno[i].cor;

                        label = label+'<li><h6 class="text-muted text-info" style="color: '+retorno[i].cor+' !important;"><i class="fa fa-circle font-10 m-r-10 "></i>'+retorno[i].nome+'</h6></li>'
                    }

                    var chart = c3.generate({
                        bindto: '#convenios',
                        data: {
                            columns: dados,
                            
                            type : 'donut',
                            onclick: function (d, i) { console.log("onclick", d, i); },
                            onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                            onmouseout: function (d, i) { console.log("onmouseout", d, i); }
                        },
                        donut: {
                            label: {
                                show: false
                                },
                            title: "Convênios",
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
    
        // Dashboard datepicker
        
        var start = moment().startOf('month');
        var end = moment().endOf('month');

        function loadData(start, end) {
            $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            
            $('.sk-spinner').parent().addClass('sk-loading');

            periodo = {"start": start.format('YYYY-MM-DD'), "end": end.format('YYYY-MM-DD'),}

            getAgendamentos(periodo);
            getAtendimentos(periodo);
            getPacientes(periodo);
            getConvenios(periodo);
            
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