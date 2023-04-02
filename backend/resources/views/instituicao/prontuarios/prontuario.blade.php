@extends('instituicao.layout')


@push('scripts')
    <!-- jQuery peity -->
    <script src='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.jquery.js') }}'></script>
    <script src='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw-init.js') }}'></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src='{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
@endpush

@push('estilos')
    <link href='{{ asset('material/assets/plugins/tablesaw-master/dist/tablesaw.css') }}' rel='stylesheet'>
    <style>
        a[disabled="disabled"] {
            pointer-events: none;
        }
    </style>
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
                            <div class='card'>
                                @if ($agendamento)
                                    <input type="hidden" name="agendamento_id" id="agendamento_id" value="{{$agendamento->id}}">
                                @endif
                                <input type="hidden" name="paciente_id" id="paciente_id" value="{{$paciente->id}}">

                                <div class="row">

                                    <div class="col-lg-2 col-md-2">
                                        <div class="card" style="height: 100%;border-radius: 0px;">
                                            <div class="card-body " style="flex: 0">
                                                <center class=""> 

                                                    {{-- Teleatendimento --}}
                                                    @if (isset($agendamento))
                                                        @if ($agendamento->teleatendimento == 1 && !empty($agendamento->teleatendimento_link_prestador))
                                                        <div style="margin-bottom: 10px;">
                                                            <a href="{{$agendamento->teleatendimento_link_prestador}}" target="_blank">
                                                            <button type="button" class="btn btn-success btn-circle" data-toggle="tooltip" title="" data-original-title="Abrir Teleatendimento">
                                                                <i class="mdi mdi-laptop-mac"></i>
                                                            </button>
                                                            </a>
                                                        </div>
                                                        @endif
                                                    @endif
                                                    {{-- Fim Teleatendimento --}}

                                                    <img src="{{ asset('material/assets/images/avatar.png') }}" class="img-circle" width="120" />
                                                    <h4 class="card-title m-t-10" style="font-size: 16px;">{{$paciente->nome}}</h4>
                                                    <h6 class="card-subtitle">
                                                        @if (!empty($agendamento))
                                                            @if(!empty($internacao))
                                                                Atendimento de internação
                                                            @elseif ($atendimento->status == 0)
                                                                Atendimento avulso
                                                            @else
                                                                Duração do Atendimento
                                                                <span class="duracao_atendimento"></span>
                                                                <br>
                                                                <span class="convenio_atendimento">Convenio: {{$agendamento->agendamentoProcedimentoTashed[0]->procedimentoInstituicaoConvenioTrashed->convenios->nome}}</span>
                                                                <br>
                                                                <br>
                                                                <button type="button" class="btn btn-warning waves-effect finalizar_atendimento" data-id="{{$agendamento->id}}">Finalizar</button>
                                                            @endif
                                                        @endif
                                                    </h6>
                                                    <div class="row text-center justify-content-md-center">
                                                        <div class="col-4"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="Atendimentos" title="Atendimentos" class="link"><i class="mdi mdi-checkbox-marked-circle-outline"></i> <font class="font-medium">{{$agendamentoAtendidos}}</font></a></div>
                                                        <div class="col-4"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="Faltas" title="Faltas" class="link"><i class="mdi mdi-account-remove"></i> <font class="font-medium">{{$agendamentoAusentes}}</font></a></div>
                                                    </div>

                                                    <div class="row text-center justify-content-md-center">
                                                        <div class="">
                                                            <label class="form-control-label p-0 m-0">
                                                                <i class="mdi mdi-alert"></i>
                                                                IMPORTANTE!
                                                            </label>
                                                            <textarea rows='4' name="obs" id="obs_consultorio" class="form-control">{{ $paciente->obs_consultorio }}</textarea>
                                                            
                                                            <button type="button" class="btn btn-danger waves-effect salvar_obs" data-id="{{$paciente->id}}"><i class="mdi mdi-check"></i> Salvar</button>
                                                        </div>
                                                    </div>
                                                </center>
                                            </div>
                                            
                                            <hr>

                                            <div class="card-body"> <small class="text-muted">Data de Nascimento </small>
                                                @if ($idade)
                                                <h6>{{date('d/m/Y', strtotime($paciente->nascimento))}} - {{$idade}} anos</h6>
                                                @endif <small class="text-muted p-t-30 db">Telefone</small>
                                                <h6>{{$paciente->telefone1}}</h6> 
                                                <small class="text-muted p-t-30 db">Endereço</small>
                                                <h6>
                                                    @if (!empty($paciente->rua))
                                                        {{$paciente->rua}}
                                                    @endif
                                                    @if (!empty($paciente->numero))
                                                        , {{$paciente->numero}}
                                                    @endif
                                                    @if (!empty($paciente->bairro))
                                                        , {{$paciente->bairro}}
                                                    @endif
                                                    @if (!empty($paciente->cidade))
                                                        - {{$paciente->cidade}}
                                                    @endif
                                                    @if (!empty($paciente->estado))
                                                         / {{$paciente->estado}}</h6>
                                                    @endif
                                                </h6>
                                              
                                                
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-10 col-md-10">
                                        
                                                <!-- Nav tabs -->
                                        <ul class="nav nav-tabs customtab editarTabs" role="tablist">
                                            {{-- active show --}}
                                            @can('habilidade_instituicao_sessao', 'visualizar_resumo')
                                                <li class="nav-item visualizarResumo"> <a class="nav-link tab-resumo-paciente" data-toggle="tab" href="#resumo-paciente" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-account-network"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-network"></i> Resumo <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                            @endcan

                                            <li class="nav-item visualizarPacienteDados"> <a class="nav-link tab-paciente-dados" data-toggle="tab" href="#paciente-dados" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-account-box-outline"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-account-box-outline"></i> Ficha <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>

                                            @can('habilidade_instituicao_sessao', 'visualizar_odontologico')
                                                <li class="nav-item visualizarOdontologico"> <a class="nav-link tab-odontologico" data-toggle="tab" href="#odontologico" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-guitar-pick-outline"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-guitar-pick-outline"></i> Odontológico <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                            @endcan
                                            @if ($agendamento)
                                                @can('habilidade_instituicao_sessao', 'visualizar_prontuario')
                                                    <li class="nav-item visualizarProntuario"> <a class="nav-link tab-prontuario" data-toggle="tab" href="#prontuario" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-bookmark-plus-outline"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-bookmark-plus-outline"></i> Prontuário <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan
                                                @can('habilidade_instituicao_sessao', 'visualizar_refracao')
                                                    <li class="nav-item visualizarRefracao"> <a class="nav-link tab-refracao" data-toggle="tab" href="#refracao" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-glasses"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-glasses"></i> Refração <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan   
                                                @can('habilidade_instituicao_sessao', 'visualizar_receituario')
                                                    <li class="nav-item visualizarReceituario"> <a class="nav-link tab-receituario" data-toggle="tab" href="#receituario" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-pill"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-pill"></i> Receituário <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan   
                                                @if (\Gate::check('habilidade_instituicao_sessao', 'prescrever_receituario_memed'))
                                                    <li class="nav-item visualizarReceituarioMemed"> <a class="nav-link tab-receituario-memed" data-toggle="tab" href="#receituario-memed" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-pill"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-pill"></i> Receituario Eletrônico <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endif                                                
                                                
                                                @can('habilidade_instituicao_sessao', 'visualizar_atestado')
                                                    <li class="nav-item visualizarAtestado"> <a class="nav-link tab-atestado" data-toggle="tab" href="#atestado" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-clipboard-outline"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-clipboard-outline"></i> Atestado <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan   
                                                @can('habilidade_instituicao_sessao', 'visualizar_relatorio')
                                                    <li class="nav-item visualizarRelatorio"> <a class="nav-link tab-relatorio" data-toggle="tab" href="#relatorio" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-image-filter"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-image-filter"></i> Relatório <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan   
                                                @can('habilidade_instituicao_sessao', 'visualizar_exame')
                                                    <li class="nav-item visualizarExame"> <a class="nav-link tab-exame" data-toggle="tab" href="#exame" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-library-plus"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-library-plus"></i> Exame <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan   
                                                @can('habilidade_instituicao_sessao', 'visualizar_encaminhamento')
                                                    <li class="nav-item visualizarEncaminhamento"> <a class="nav-link tab-encaminhamento" data-toggle="tab" href="#encaminhamento" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-ambulance"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-ambulance"></i> Encaminhamento <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan 
                                                @can('habilidade_instituicao_sessao', 'visualizar_laudo')
                                                    <li class="nav-item visualizarLaudo"> <a class="nav-link tab-laudo" data-toggle="tab" href="#laudo" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-clipboard-text"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-clipboard-text"></i> Laudo <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan
                                                @can('habilidade_instituicao_sessao', 'visualizar_avaliacao')
                                                    <li class="nav-item visualizarAvaliacao"> <a class="nav-link tab-avaliacao" data-toggle="tab" href="#avaliacao" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-clipboard-text"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-clipboard-text"></i> Avaliação <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan
                                                @can('habilidade_instituicao_sessao', 'visualizar_solicitacao_estoque')
                                                    <li class="nav-item visualizarSolicitacaoEstoque"> <a class="nav-link tab-soliciracao-estoque" data-toggle="tab" href="#solicitacaoEstoque" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-cart-outline"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-clipboard-text"></i> Solicitação de estoque <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan
                                                @can('habilidade_instituicao_sessao', 'visualizar_conclusao')
                                                    <li class="nav-item visualizarConclusao"> <a class="nav-link tab-conclusao" data-toggle="tab" href="#conclusao" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-file-check"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-file-check"></i> Conclusão <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                                @endcan  
                                            @endif
                                            @can('habilidade_instituicao_sessao', 'visualizar_arquivo')
                                                <li class="nav-item visualizarArquivo"> <a class="nav-link tab-arquivo" data-toggle="tab" href="#arquivo" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-file-multiple"></i></span> <span class="hidden-xs-down"><i class="mdi mdi-file-multiple"></i> Arquivo <i class="alert_icon" style="color: #d98025;"></i></span></a> </li>
                                            @endcan   
                                            
                                        </ul>
            
                                        <div class="tab-content tabcontent-border tabsEditar">
                                            {{-- active show --}}
                                            @can('habilidade_instituicao_sessao', 'visualizar_resumo')
                                                <div class="tab-pane p-20 " id="resumo-paciente" role="tabpanel">
                                                    <div class="resumo-paciente">
                                                        
                                                    </div>
                                                </div>
                                            @endcan
                                            <div class="tab-pane p-20" id="paciente-dados" role="tabpanel">
                                                <div class="paciente-dados">
                                                    
                                                </div>
                                            </div>

                                            @can('habilidade_instituicao_sessao', 'visualizar_prontuario')
                                            <div class="tab-pane p-20" id="prontuario" role="tabpanel">
                                                <div class="prontuario">
                                                    
                                                </div>
                                            </div>
                                            @endcan
                                            
                                            <div class="tab-pane p-20" id="odontologico" role="tabpanel">
                                                <div class="odontologico">
                                                    
                                                </div>
                                            </div>

                                            @can('habilidade_instituicao_sessao', 'visualizar_refracao')
                                            <div class="tab-pane p-20" id="refracao" role="tabpanel">
                                                <div class="refracao">
                                                    
                                                </div>
                                            </div>
                                            @endcan

                                            @can('habilidade_instituicao_sessao', 'visualizar_receituario')
                                            <div class="tab-pane p-20" id="receituario" role="tabpanel">
                                                <div class="receituario">
                                                    
                                                </div>
                                            </div>
                                            @endcan

                                            <div class="tab-pane p-20" id="receituario-memed" role="tabpanel">
                                                <div class="receituario-memed">
                                                    
                                                </div>
                                            </div>


                                            @can('habilidade_instituicao_sessao', 'visualizar_atestado')
                                            <div class="tab-pane p-20" id="atestado" role="tabpanel">
                                                <div class="atestado">
                                                    
                                                </div>
                                            </div>
                                            @endcan

                                            @can('habilidade_instituicao_sessao', 'visualizar_relatorio')
                                            <div class="tab-pane p-20" id="relatorio" role="tabpanel">
                                                <div class="relatorio">
                                                    
                                                </div>
                                            </div>
                                            @endcan

                                            @can('habilidade_instituicao_sessao', 'visualizar_exame')
                                            <div class="tab-pane p-20" id="exame" role="tabpanel">
                                                <div class="exame">
                                                    
                                                </div>
                                            </div>
                                            @endcan

                                            @can('habilidade_instituicao_sessao', 'visualizar_arquivo')
                                            <div class="tab-pane p-20" id="arquivo" role="tabpanel">
                                                <div class="arquivo">
                                                    
                                                </div>
                                            </div>
                                            @endcan
                                            @can('habilidade_instituicao_sessao', 'visualizar_encaminhamento')
                                            <div class="tab-pane p-20" id="encaminhamento" role="tabpanel">
                                                <div class="encaminhamento">
                                                    
                                                </div>
                                            </div>
                                            @endcan
                                            @can('habilidade_instituicao_sessao', 'visualizar_laudo')
                                            <div class="tab-pane p-20" id="laudo" role="tabpanel">
                                                <div class="laudo">
                                                    
                                                </div>
                                            </div>
                                            @endcan
                                            @can('habilidade_instituicao_sessao', 'visualizar_avaliacao')
                                            <div class="tab-pane p-20" id="avaliacao" role="tabpanel">
                                                <div class="avaliacao">
                                                    
                                                </div>
                                            </div>
                                            @endcan
                                            @can('habilidade_instituicao_sessao', 'visualizar_solicitacao_estoque')
                                            <div class="tab-pane p-20" id="solicitacaoEstoque" role="tabpanel">
                                                <div class="solicitacaoEstoque">
                                                    
                                                </div>
                                            </div>
                                            @endcan
                                            @can('habilidade_instituicao_sessao', 'visualizar_conclusao')
                                            <div class="tab-pane p-20" id="conclusao" role="tabpanel">
                                                <div class="conclusao">
                                                    
                                                </div>
                                            </div>
                                            @endcan
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
        var alertNaoSalvo = false;
        var time = "{{$atendimento->created_at}}";
        $(document).ready(function() {
            duracaoAtendimento();
            if($(".customtab").find('.visualizarResumo').length > 0){
                carregaResumo();
                $(".tab-resumo-paciente").addClass('active show');
                $("#resumo-paciente").addClass('active show');
            }else{
                carregaDadosPaciente();
                $(".tab-paciente-dados").addClass('active show');
                $("#paciente-dados").addClass('active show');
            }
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
            autoSave();
            // e.stopPropagation();
            // if(alertNaoSalvo){
            //     Swal.fire({
            //         title: "Atenção!",
            //         text: 'Algumas alterações não foram salvas, deseja prosseguir mesmo assim ?',
            //         icon: "warning",
            //         showCancelButton: true,
            //         confirmButtonColor: "#DD6B55",
            //         cancelButtonText: "Não, cancelar!",
            //         confirmButtonText: "Sim, descarta alterações!",
            //     }).then(function(result) {
            //         if(result.value){
            //             finalizarAtendimento($id)
            //         }
            //     })
            // }else{
            //     finalizarAtendimento($id)
            // }
            finalizarAtendimento($id)
        })

        function finalizarAtendimento($id){
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
        }

        $('.salvar_obs').on('click',function(e){
            $id = $(this).data('id');
            e.stopPropagation();
            
            $.ajax("{{ route('instituicao.agendamentos.salvaObsConsultorio') }}", {
                method: "POST",
                data: {
                    id: $id,
                    '_token': '{{csrf_token()}}',
                    'obs_consultorio': $("#obs_consultorio").val()
                },
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

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO RESUMOS /////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
        
        $('.tab-resumo-paciente').on('click', function() {
            if($('.resumo-paciente').hasClass('carregado')){
                return
            }else{
                carregaResumo()
            }
        })

        function carregaResumo(){
            $(".resumo-paciente").html('');
            $('.resumo-paciente').addClass('carregado')

            paciente_id = $("#paciente_id").val();
            agendamento_id = $("#agendamento_id").val();

            $.ajax({
                url: "{{route('agendamento.resumo.pacienteResumo', ['paciente' => 'paciente_id'])}}".replace('paciente_id', paciente_id),
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

        function carregaResumoPag(){
            $('.resumo-paciente').removeClass('carregado')
        }

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO PACIENTE DADOS //////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-paciente-dados').on('click', function() {
            if($('.paciente-dados').hasClass('carregado')){
                return
            }else{
                carregaDadosPaciente()
            }
        })

        function carregaDadosPaciente(){
            $('.paciente-dados').addClass('carregado')

            paciente_id = $("#paciente_id").val();
            agendamento_id = $("#agendamento_id").val();

            $.ajax({
                url: "{{route('agendamento.prontuario.pacienteForm', ['paciente' => 'paciente_id'])}}".replace('paciente_id', paciente_id),
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

        //receiturario Memed
        function receituarioMemed(){
            if($('.receituario-memed').hasClass('carregado')){
                return
            }else{
                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.receituario_memed.receituarioPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                        $(".receituario-memed").html("");
                    },
                    success: function(result) {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') ;
                        // console.log(result)
                        if(result.icon == "error"){
                            console.log("aqui");
                            $.toast({
                                heading: result.title,
                                text: result.text,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: result.icon,
                                hideAfter: 3000,
                                stack: 10
                            });
                            $('.receituario-memed').removeClass('carregado')
                        }else{
                            $('.receituario-memed').addClass('carregado')
                            $(".receituario-memed").html(result);
                        }  
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        }

        $('.tab-receituario-memed').on('click', function() {
            receituarioMemed();
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
                    url: "{{route('agendamento.arquivo.index', ['paciente' => 'paciente_id'])}}".replace('paciente_id', paciente_id),
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

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO REFRAÇÃO ////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-refracao').on('click', function() {
            if($('.refracao').hasClass('carregado')){
                return
            }else{
                $('.refracao').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.refracao.refracaoPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".refracao").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })
        
        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO ODONTOLOGICO ////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-odontologico').on('click', function() {
            if($('.odontologico').hasClass('carregado')){
                return
            }else{
                $('.odontologico').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();
                if(!agendamento_id){
                    agendamento_id = null;
                }

                $.ajax({
                    url: "{{route('agendamento.odontologico.odontologicoPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".odontologico").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO ENCAMINHAMENTO //////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-encaminhamento').on('click', function() {
            if($('.encaminhamento').hasClass('carregado')){
                return
            }else{
                $('.encaminhamento').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.encaminhamento.encaminhamentoPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".encaminhamento").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO LAUDO ///////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-laudo').on('click', function() {
            if($('.laudo').hasClass('carregado')){
                return
            }else{
                $('.laudo').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.laudo.laudoPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".laudo").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO AVALIACAO ///////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-avaliacao').on('click', function() {
            if($('.avaliacao').hasClass('carregado')){
                return
            }else{
                $('.avaliacao').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.internacoes.avaliacao.index', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".avaliacao").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO SOLICITAÇÃO DE ESTOQUE //////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-soliciracao-estoque').on('click', function() {
            if($('.solicitacaoEstoque').hasClass('carregado')){
                return
            }else{
                $('.solicitacaoEstoque').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.internacoes.estoque', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".solicitacaoEstoque").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })

        ////////////////////////////////////////////////////////////////////////////////////////
        /////////SEÇÃO CONCLUSÃO ///////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////

        $('.tab-conclusao').on('click', function() {
            if($('.conclusao').hasClass('carregado')){
                return
            }else{
                $('.conclusao').addClass('carregado')

                paciente_id = $("#paciente_id").val();
                agendamento_id = $("#agendamento_id").val();

                $.ajax({
                    url: "{{route('agendamento.conclusao.conclusaoPaciente', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
                    type: 'get',
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function(result) {
                        $(".conclusao").html(result);
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }

                });
            }
        })

        //EXTRAS
        function addIcon(local){
            if(!$("."+local).find('.alert_icon').hasClass("mdi-alert-circle-outline")){
                $("."+local).find('.alert_icon').addClass('mdi mdi-alert-circle-outline');
                $("."+local).css('background', '#f8ac59');
                alertNaoSalvo = true;
            }
        }
        
        function removeIcon(local){
            $("."+local).find('.alert_icon').removeClass('mdi mdi-alert-circle-outline');
            $("."+local).css('background', '#fff');
            if($('.mdi-alert-circle-outline').length == 0){
                alertNaoSalvo = false;
            }
        }

        //Auto save Troca Guia
        function autoSave(){
            $(".nav-item").find('.alert_icon').each((index,element) => {
                if($(element).hasClass('mdi mdi-alert-circle-outline')){
                    $(element).parents('.nav-item').each((item, el) => {
                        if($(el).hasClass('visualizarProntuario')){
                            salvarProntuarioAuto();
                        }else if($(el).hasClass('visualizarReceituario')){
                            if($(".form-receituario-livre").hasClass('active')){
                                salvarReceituarioAuto('formReceituarioLivre');
                            }else{
                                salvarReceituarioAuto('formReceituario');
                            }
                        }else if($(el).hasClass('visualizarAtestado')){
                            salvarAtestadoAuto();
                        }else if($(el).hasClass('visualizarRelatorio')){
                            salvarRelatorioAuto();
                        }else if($(el).hasClass('visualizarExame')){
                            salvarExameAuto();
                        }else if($(el).hasClass('visualizarEncaminhamento')){
                            salvarEncaminhamentoAuto();
                        }else if($(el).hasClass('visualizarLaudo')){
                            salvarLaudoAuto();
                        }else if($(el).hasClass('visualizarConclusao')){
                            salvarConclusaoAuto();
                        }else if($(el).hasClass('visualizarRefracao')){
                            salvarRefracaoAuto();
                        }
                    })
                }
            })
        }
        
        
        $(".nav-link").on('click', function(){
            autoSave();
        })

        $(window).on("unload", function(e) {
            autoSave();
        });
        
    </script>
    
    <script language=javascript type="text/javascript">
        function newPopup(url){
            window.open(url, 'prontuário', 'width=1024, height=860')
        }
    </script>

    {{-- <script id="js-memed" type="text/javascript"
        src="https://integrations.memed.com.br/modulos/plataforma.sinapse-prescricao/build/sinapse-prescricao.min.js"
        data-token="TOKEN_DO_USUARIO_OBTIDO_NO_CADASTRO_VIA_API"
        data-container="div-memed">
    </script> --}}
@endpush