@extends('instituicao.layout')

@push('scripts')
    <!-- jQuery peity -->
    <script src='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}'></script>
    <script src='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}'></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src='{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}'></script>
@endpush

@push('estilos')
    <link href='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}' rel='stylesheet'>
@endpush

@section('conteudo')
          
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class='row page-titles'>
            <div class='col-md-5 col-8 align-self-center'>
                <h3 class='text-themecolor m-b-0 m-t-0'>Agendamentos Centro Cirúrgicos</h3>
                <ol class='breadcrumb'>
                    <li class='breadcrumb-item'><a href='javascript:void(0)'>Agendamentos Centro Cirúrgicos</a></li>
                    {{-- <li class='breadcrumb-item active'>medicamentoss</li> --}}
                </ol>
            </div>
            
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class='row'>
            <div class='col-12'>
                <!-- Column -->
                <div class='card'>
                    <div class='card-body'>
                        <div class="row">
                            <div class=" col-md-3 form-group">
                                <select class="form-control centro_cirurgico" name="centro_cirurgico" id="centro_cirurgico" placeholder="Centro cirúrgicos">
                                    <option></option>
                                    @foreach ($centro_cirurgicos as $key => $item)
                                        <option value="{{ $item->id }}" @if ($key == 0)
                                            selected
                                        @endif>{{ $item->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" col-md-3 form-group">
                                <div class="btn-group " role="group">
                                    <button type="button" class="btn btn-default" data-action="toggle-datepicker" title="Escolher período" >
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </button>
                                    <button type="button" class="btn btn-default" data-change-agenda="previous"  title="Anterior">
                                        <i class="mdi mdi-arrow-left-bold"></i>
                                    </button>
                                    <input type="text" class="datepicker form-control" value="{{date('d/m/Y')}}">
                                    <button type="button" class="btn btn-default" data-change-agenda="next"  title="Próximo">
                                        <i class="mdi mdi-arrow-right-bold"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button type="button" class="btn btn-info waves-effect novo_agendamento">Novo agendamento</button>
                            </div>
                            
                        </div>
                        <hr>
                        <div class="agenda_dia">
                            <div class="loading" style="width: 100%; text-align: center">
                                <div class="spinner-border" role="status" style="width: 5rem; height: 5rem;">
                                </div>
                            </div>
                            <div class="agenda_horario_row">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                
                <!-- Column -->
                
            </div>
        </div>   
        
        <div id="modal_visualizar"></div>

        <div id="modalEscolhaEstoque" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        Escolha um estoque
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="agendamentoIdEstoque" id='agendamentoIdEstoque' class="agendamentoIdEstoque">
                        <select name="estoque_id_escolha" id="estoque_id_escolha" class="select2" style="width: 100%">
                            @foreach ($estoques as $item)
                                <option value="{{$item->id}}">{{$item->descricao}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal" >Fechar</button>
                        <button type="button" class="btn btn-success waves-effect gerarEstoqueFinal" >Gerar</button>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('estilos')
    <style>
        .scrollable {
            overflow-y: scroll;
            margin-bottom: 10px;
            max-height: 600px;
        }

        .noWrap{
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .agendamento .btn{

        background-color: inherit;
        color: inherit;
        border: inherit;

        }
        /* .agendamento .btn:hover, .agendamento .btn:focus, .agendamento .btn:active {
            box-shadow: none;
            background-color: rgba(255, 255, 255, .5) !important;
            color: #fff;
        } */

        .agendamento .btn:hover, .agendamento .btn:focus {
            box-shadow: none;
            background-color: rgba(255, 255, 255, .43);
        }

        .agendamento .agendamento_col {
            padding: 5px;
            font-size: 12px;
            padding-top: 0;
        padding-bottom: 0;
        }

        .agendamento .agendamento-procedimentos {
            flex-basis: 40%;
        }

        .agendamento .agendamento-icone .fa,.agendamento .agendamento-icone .far,.agendamento .agendamento-icone .fas{
            font-size: 20px;
            vertical-align: middle;

        }
        .agendamento .agendamento-icone{
            text-align: center;
            flex-basis: 15%;
        }

        .agendamento .agendamento-paciente {
            flex-basis: 40%;
        }

        .agendamento .agendamento_actions {
            flex-basis: 15%;
            text-align: right;
        }

        .agendamento.status-0 {
            background-color:#cc0404;
            border-color:#cc0404;
            color: white;
        }

        .agendamento.status-1 {
            background-color: #8fe32f;
            border-color: #8fe32f;
        }

        .agendamento.status-2    {
            background-color: #26c6da;
            border-color: #26c6da;
            color: #1b5c64;
        }

        .agendamento.status-3 {
            background-color: #009688;
            border-color: #009688;
            color: #fff;
        }



        .agendamento.status-4 {
            background-color: #78909C;
            border-color: #78909C;
            color: #fff;
        }

        .agendamento.status-5 {
            background-color: #ffcf8e;
            border-color: #ffcf8e;
            color: #fff;
        }

        .agendamento.status-6 {
            background-color: #4399a0;
            border-color: #4399a0;
            color: #fff;
        }
        
        .agendamento.status-7 {
            background-color: #745af2;
            border-color: #745af2;
            color: #fff;
        }


        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            border-top: 1px solid #e7eaec;
            line-height: 1.42857;
            padding: 8px;
            vertical-align: top;
        }
        .table-estado-agendamento td:first-child {
        width: 45px;
        font-size: 2em;
        text-align: center;
        vertical-align: middle;
        }
        .table-estado-agendamento td:last-child {
            font-size: 13px;
            line-height: 1.5;
        }
            .hidden{
                display: none;
            }
        .ui-vazio .fa {
            font-size: 96px;
        }
            .btn-group label {
                color: #000 !important;
                margin-bottom: 0px;
            }
            .form-control:disabled, .form-control[readonly] {
                background-color: #fff;
                opacity: 1;
            }

        .datepicker{
            border-radius: 0px;
            text-align: center;
        }

        .datepicker:focus{
                border-color : #ced4da;
                box-shadow: none;
        }

        .btn-default {
            color: inherit;
            background: white;
            border: 1px solid #ced4da;
        }
        .btn-default:hover, .btn-default:focus,  .open .dropdown-toggle.btn-default {
            color: inherit;
            border: 1px solid #d2d2d2;
            box-shadow: none;
        }


        .btn-default:hover:focus{
            background: inherit;
        }

        .btn-default:hover{

        background-color: #e6e6e6;
        }


        .btn-default:active, .btn-default.active, .open .dropdown-toggle.btn-default {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15) inset;
            background-color: inherit;
        }

        .card .card-header {
            background: #ffffff;
            border-bottom: 1px solid #0000001a;
        }

        .ui {
            padding-top: 100px;
            padding-bottom: 100px;
            text-align: center !important;
        }

        .agenda_horario_row{
            display:flex;
            padding-top: 2.5px;
            padding-bottom: 2.5px;

        }

        .agenda_horario_row + .agenda_horario_row {
            border-top: 1px solid #dfdfdf;
        }

        .agenda_horario_row .agenda_horario.horario_passado {
            color: #ccc;
        }
        .agenda_horario_row .agenda_horario {
            flex: 0 0 45px;
            align-self: center;
            line-height: 30px;
            vertical-align: middle;
            text-align: center;
        }

        .agenda_horario_row .agendamento {
            display: flex;
            max-width: 100%;
            border-radius: 5px;
            margin-bottom: 2px;
        }



        .agenda_eventos {
            flex-grow: 1;
        }

        .agendamento {
            border-radius: 5px;
            margin-bottom: 2px;
        }

        .agendamento .agendamento_col {
            vertical-align: middle;
            font-size: 12px;
            line-height: 35px;
        }

        .agendamento .agendamento_texto {
            padding-left: 30px;
            flex-basis: 85%;
        }

        .agendamento.agendamento_empty {
            background-color: #eaeaea;
            color: #aaa;
        }

        .agendamento.agendamento_past {
            background-color: #f9f9f9;
            color: #aaa;
        }




        .agendamento_texto{
            font-style: italic;
        }

        .agendamento.agendamento_intervalo {
            background-color: #616161;
            border-color: #616161;
            color: #fff;
        }

        .agenda_horario_row.is_current {
            border-radius: 5px;
            background-color: rgba(47, 64, 80, .15);
        }
        
        .loader,
        .loader:after {
            border-radius: 50%;
            width: 10em;
            height: 10em;
        }
        .loader {
            margin: 60px auto;
            font-size: 10px;
            position: relative;
            text-indent: -9999em;
            border-top: 1.1em solid rgba(3,3,3, 0.2);
            border-right: 1.1em solid rgba(3,3,3, 0.2);
            border-bottom: 1.1em solid rgba(3,3,3, 0.2);
            border-left: 1.1em solid #030303;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation: load8 1.1s infinite linear;
            animation: load8 1.1s infinite linear;
            }
            @-webkit-keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
            }
            @keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>    

@endpush

@push('scripts')
    <script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('material/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>

    <script>

        setInterval(function(){
            getCentroCirurgico();
        }, 50000);

        $(document).ready(function(){
            getCentroCirurgico();
            $(".centro_cirurgico").select2({
                placeholder: "Centro cirúrgico"
            }).on('select2:select', function(e){
                e.stopPropagation()
                getCentroCirurgico()
            });

            $(".datepicker").datepicker({
                closeText: 'Fechar',
                prevText: '<Anterior',
                nextText: 'Próximo>',
                currentText: 'Hoje',
                monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
                'Jul','Ago','Set','Out','Nov','Dez'],
                dayNames: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
                dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
                dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 0,
                onSelect:function(){
                    getCentroCirurgico()
                }
            })

            $(".agenda_dia").find('.loading').prop('hidden', 'true');
        })

        $('body').on('click','[data-action="toggle-datepicker"]',function(e){
             $(".datepicker").datepicker('show')
        })

        $('body').on('click','[data-change-agenda]',function(e){
            switch ($(e.currentTarget).data('changeAgenda')) {
                case 'previous':
                    previousPeriodo();
                    break;
                case 'next':
                    nextPeriodo();
                    break;
            }
            e.stopPropagation();
        })

        previousPeriodo = () => {
            date = new Date($(".datepicker").datepicker( "getDate"))
            date.setDate(date.getDate() - 1);
            $(".datepicker").datepicker( "setDate", date );
            getCentroCirurgico()
        }

        nextPeriodo = () => {
            date = new Date($(".datepicker").datepicker( "getDate"))
            date.setDate(date.getDate() + 1);
            $(".datepicker").datepicker( "setDate", date );
            getCentroCirurgico()
        }
        

        function getCentroCirurgico()
        {
            $(".agenda_dia").find('.loading').prop('hidden', false);
            var centro_cirurgico = $("#centro_cirurgico option:selected").val()
            $.ajax("{{ route('instituicao.agendamentoCentroCirurgico.getAgenda') }}", {
                method: "POST",
                data: {
                    data: $('.datepicker').val(), 
                    centro_cirurgico:  centro_cirurgico,
                    '_token': '{{csrf_token()}}'
                },
                beforeSend: () => {
                    $(".agenda_horario_row").html("");
                },
                success: function (response) {
                    if(response != ""){
                        $(".agenda_horario_row").html(response)
                        $('[data-toggle="tooltip"]').tooltip()
                        $(".novo_agendamento").prop('disabled', false);
                    }else{
                        $(".agenda_horario_row").html('Sem agenda para o dia')
                        $(".novo_agendamento").prop('disabled', true);
                    }
                },
                complete: () => {
                    $(".agenda_dia").find('.loading').prop('hidden', 'true');
                }
            })
            
        }

        $(".novo_agendamento").on('click', function(e) {
            e.stopPropagation()
            e.stopImmediatePropagation();
            $("#modal_visualizar").html("");
            var centro_cirurgico = $("#centro_cirurgico option:selected").val();
            if(centro_cirurgico != ""){
                var data = $('.datepicker').val();

                var url = "{{ route('instituicao.agendamentoCentroCirurgico.novaAgenda') }}";
                var data = {
                    centro_cirurgico: centro_cirurgico,
                    data: data,
                    '_token': '{{csrf_token()}}'
                };
                var modal = 'modalNovoAgenda';
                
                $('#loading').removeClass('loading-off');
                $('#modal_visualizar').load(url, data, function(resposta, status) {
                    $('#' + modal).modal();
                    $('#loading').addClass('loading-off');
                });
            }else{
                $.toast({
                    heading: "Error",
                    text: "Selecione um centro cirúrgico!",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: "error",
                    hideAfter: 3000,
                    stack: 10
                });
            }
        })

        $(".gerarEstoqueFinal").on('click', function(e){
            e.preventDefault()
            e.stopPropagation()
            e.stopImmediatePropagation()
            gerarEstoqueSalvar()
        })

        function gerarEstoqueSalvar(){
            var agendamentoId = $("#agendamentoIdEstoque").val()
            $.ajax("{{ route('instituicao.agendamentoCentroCirurgico.gerarEstoque', ['agendamento' => 'agendamentoId']) }}".replace('agendamentoId', agendamentoId), {
                method: "POST",
                data: {
                    'estoque_id': $("#estoque_id_escolha").val(),
                    '_token': '{{csrf_token()}}'
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (response) {
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Saida de estoque gerada com sucesso',
                        position: 'top-right',
                        loaderBg: '#23626b',
                        icon: 'success',
                        hideAfter: 9000,
                        stack: 10
                    });

                    $('#modalEscolhaEstoque').modal('hide')
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
        }
    </script>
@endpush