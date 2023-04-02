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
                            <h3 class='text-themecolor m-b-0 m-t-0'>Atendimentos</h3>
                            <ol class='breadcrumb'>
                                <li class='breadcrumb-item'>Prontuario</li>
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
                            <div class='card' style="margin: 0px">
                                <input type="hidden" name="agendamento_id" id="agendamento_id" value="{{$agendamento->id}}">
                                <input type="hidden" name="paciente_id" id="paciente_id" value="{{$paciente->id}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-3 text-center">
                                                <a href="app-contact-detail.html"><img src="{{ asset('material/assets/images/default_photo.png') }}" alt="user" class="img-circle img-responsive"></a>
                                            </div>
                                            <div class="col-md-8 col-lg-9">
                                                <h3 class="box-title m-b-0">{{$paciente->nome}}</h3> 
                                                {{-- <small> <i class="fas fa-birthday-cake"></i> 27 anos</small> --}}
                                                {{-- <address>
                                                    Convenio: 
                                                    <br/>
                                                    <br/>
                                                    <abbr title="Phone">P:</abbr> (123) 456-7890
                                                </address> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                @if ($atendimento->status == 0)
                                                    <h3>Atendimento avulso</h3>
                                                @else
                                                    <small>Duração Atendimento</small>
                                                    <p><h3><span class="duracao_atendimento"></span><button type="button" class="btn btn-warning waves-effect finalizar_atendimento" data-id="{{$agendamento->id}}">Finalizar</button></h3></p>
                                                @endif
                                            </div>
                                            <div class="col-md-12 ">
                                                {{-- <h3 class="box-title m-b-0">{{$paciente->nome}}</h3> <small> <i class="fas fa-birthday-cake"></i> 27 anos</small> --}}
                                                {{-- <address>
                                                    Convenio: 
                                                    <br/>
                                                    <br/>
                                                    <abbr title="Phone">P:</abbr> (123) 456-7890
                                                </address> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                            <div class='card'>
                                <div class="row">
                                    <div class="col-md-12">
                                        
                                                <!-- Nav tabs -->
                                        <ul class="nav nav-tabs customtab editarTabs" role="tablist">
            
                                            <li class="nav-item visualizarResumo"> <a class="nav-link tab-resumo-paciente" data-toggle="tab" href="#resumo-paciente" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Resumo</span></a> </li>
                                            <li class="nav-item visualizarPacienteDados"> <a class="nav-link tab-paciente-dados" data-toggle="tab" href="#paciente-dados" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Paciente Dados</span></a> </li>
                                            <li class="nav-item visualizarProntuario"> <a class="nav-link tab-prontuario" data-toggle="tab" href="#prontuario" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Prontuário</span></a> </li>
                                            <li class="nav-item visualizarReceituario"> <a class="nav-link tab-receituario" data-toggle="tab" href="#receituario" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Receituário</span></a> </li>
                                            <li class="nav-item visualizarAtestado"> <a class="nav-link tab-atestado" data-toggle="tab" href="#atestado" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Atestado</span></a> </li>
                                            <li class="nav-item visualizarRelatorio"> <a class="nav-link tab-relatorio" data-toggle="tab" href="#relatorio" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Relatório</span></a> </li>
                                            <li class="nav-item visualizarExame"> <a class="nav-link tab-exame" data-toggle="tab" href="#exame" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Exame</span></a> </li>
                                            <li class="nav-item visualizarArquivo"> <a class="nav-link tab-arquivo" data-toggle="tab" href="#arquivo" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-card-details"></i> Arquivo</span></a> </li>
                                            
                                        </ul>
            
                                        <div class="tab-content tabcontent-border tabsEditar">
                                            <div class="tab-pane p-20" id="resumo-paciente" role="tabpanel">
                                                <div class="resumo-paciente">
                                                    
                                                </div>
                                            </div>
                                            <div class="tab-pane p-20" id="paciente-dados" role="tabpanel">
                                                <div class="paciente-dados">
                                                    
                                                </div>
                                            </div>
                                            <div class="tab-pane p-20" id="prontuario" role="tabpanel">
                                                <div class="prontuario">
                                                    
                                                </div>
                                            </div>
                                            <div class="tab-pane p-20" id="receituario" role="tabpanel">
                                                <div class="receituario">
                                                    
                                                </div>
                                            </div>
                                            <div class="tab-pane p-20" id="atestado" role="tabpanel">
                                                <div class="atestado">
                                                    
                                                </div>
                                            </div>
                                            <div class="tab-pane p-20" id="relatorio" role="tabpanel">
                                                <div class="relatorio">
                                                    
                                                </div>
                                            </div>
                                            <div class="tab-pane p-20" id="exame" role="tabpanel">
                                                <div class="exame">
                                                    
                                                </div>
                                            </div>
                                            <div class="tab-pane p-20" id="arquivo" role="tabpanel">
                                                <div class="arquivo">
                                                    
                                                </div>
                                            </div>
                                        </div>
                    
                                            
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            
                            <!-- Column -->
                            
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Right sidebar -->
                    <!-- ============================================================== -->
                     
@endsection

@push('scripts')
    <script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>

    <script>
        var time = "{{$atendimento->created_at}}";
        $(document).ready(function() {
            duracaoAtendimento();
        })

        function duracaoAtendimento(){
            var today = new Date();
            var tempo = new Date(time)
            var diff = moment.duration(moment(today).diff(moment(tempo)))

            var inteiro;
            var hora = "00";
            var minutos = "00";

            if(diff.asHours() > 0){
                inteiro = ""+diff.asHours()+""
                inteiro = inteiro.split('.')
                if(inteiro[0] < 10){
                    hora = "0"+inteiro[0];
                }else{
                    hora = inteiro[0];
                }
            }

            if(diff.asMinutes() > 0){
                inteiro = ""+diff.asMinutes()+""
                inteiro = inteiro.split('.')
                if(inteiro[0] > 60){
                    inteiro[0] = inteiro[0] - (60 * Math.floor(inteiro[0] / 60));
                }
                if(inteiro[0] < 10){
                    minutos = "0"+inteiro[0];
                }else{
                    minutos = inteiro[0];
                }
            }

            $(".duracao_atendimento").html(hora+":"+minutos+" hrs ")
            t = setTimeout(function() {
                duracaoAtendimento()
            }, 30000);
        }

        $('.finalizar_atendimento').on('click',function(e){
            $id = $(this).data('id');
            e.stopPropagation();
            Swal.fire({
                title: "Finalizar!",
                text: 'Deseja finalizar o atendimento ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    $.ajax("{{ route('instituicao.agendamentos.finalizar_atendimento') }}", {
                        method: "POST",
                        data: {id: $id, '_token': '{{csrf_token()}}'},
                        success: function (response) {

                            $.toast({
                                heading: response.title,
                                text: response.text,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: response.icon,
                                hideAfter: 3000,
                                stack: 10
                            });

                            window.close()

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
            })
            
        })

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO RESUMOS /////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $('.tab-resumo-paciente').on('click', function() {
            if($('.resumo-paciente').hasClass('carregado')){
                return
            }else{
                $('.resumo-paciente').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.resumo.pacienteResumo', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".resumo-paciente").html(result);
                        $('.loading').css('display', 'none');
                    },
                    complete: () => {
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO PACIENTE DADOS //////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-paciente-dados').on('click', function() {
            if($('.paciente-dados').hasClass('carregado')){
                return
            }else{
                $('.paciente-dados').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.prontuario.pacienteForm', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".paciente-dados").html(result);
                        $('.loading').css('display', 'none');
                    },
                    complete: () => {
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO PRONTUARIO DADOS ////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-prontuario').on('click', function() {
            if($('.prontuario').hasClass('carregado')){
                return
            }else{
                $('.prontuario').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.prontuario.prontuarioPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".prontuario").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })
        
        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO RECEITUÁRIO DADOS ///////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-receituario').on('click', function() {
            if($('.receituario').hasClass('carregado')){
                return
            }else{
                $('.receituario').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.receituario.receituarioPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".receituario").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })
        
        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO ATESTADO ////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-atestado').on('click', function() {
            if($('.atestado').hasClass('carregado')){
                return
            }else{
                $('.atestado').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.atestado.atestadoPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".atestado").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })
        
        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO RELATORIO ////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-relatorio').on('click', function() {
            if($('.relatorio').hasClass('carregado')){
                return
            }else{
                $('.relatorio').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.relatorio.relatorioPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".relatorio").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })
        
        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO EXAMES //////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-exame').on('click', function() {
            if($('.exame').hasClass('carregado')){
                return
            }else{
                $('.exame').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.exame.examePaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".exame").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })
        
        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO ARQUIVOS ////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-arquivo').on('click', function() {
            if($('.arquivo').hasClass('carregado')){
                return
            }else{
                $('.arquivo').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.arquivo.index', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".arquivo").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })
        
    </script>
@endpush