<div>
    {{-- <form class="form-material m-t-40 row">
        <div class="form-group col-md-6 m-t-20">
            <input type="text" class="form-control form-control-line" value="col-md-6"> </div>
        <div class="form-group col-md-6 m-t-20">
            <input type="email" id="example-email2" name="example-email" class="form-control" placeholder="col-md-6"> </div>
        <div class="form-group col-md-4 m-t-20">
            <input type="text" class="form-control form-control-line" value="col-md-4"> </div>
        <div class="form-group col-md-4 m-t-20">
            <input type="email" id="example-email2" name="example-email" class="form-control" placeholder="col-md-4"> </div>
        <div class="form-group col-md-4 m-t-20">
            <input type="text" class="form-control" value="col-md-4"> </div>
        <div class="form-group col-md-3 m-t-20">
            <input type="text" class="form-control" placeholder="col-md-3"> </div>
        <div class="form-group col-md-3 m-t-20">
            <select class="form-control">
                <option>col-md-3</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
        </div>


    </form>
 --}}

    <div class="card-header no_print">
        <div wire:ignore class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">


            <div class="col-md-3" style="padding-left:0px;">
                <select id="setor" style="width: 100%" class="form-control">
                    <option value=0>Todos setores</option>
                    @foreach ($setores_instituicao as $setor_instituicao)
                        <option {{$setor_instituicao->id==$setor_id? 'selected': '' }}  value="{{ $setor_instituicao->id }}">{{ $setor_instituicao->descricao }}</option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-3" style="padding-left:0px;">
                <select id="prestador" style="width: 100%" class="form-control">
                    <option value=""></option>
                    @foreach ($especialidade as $especialidades)
                        <optgroup label="{{ $especialidades->descricao }}">
                            @foreach ($especialidades->prestadoresInstituicao as $prestadoresInstituicao)
                                
                                {{-- @if ($prestadoresInstituicao->ativo == 1) --}}
                                    <option {{$prestadoresInstituicao->id == $prestador_especialidade_id? 'selected': '' }}
                                        value="{{ $prestadoresInstituicao->id }}"
                                    >
                                        {{!empty($prestadoresInstituicao->nome) ? $prestadoresInstituicao->nome : $prestadoresInstituicao->prestador->nome }}
                                    </option>
                                {{-- @endif --}}
                            @endforeach
                        </optgroup>

                    @endforeach
                </select>
            </div>

            {{-- <div class="btn-group ">
                <select id="prestador" style="width: 100%" class="form-control">
                    <option value=""></option>
                    @foreach ($especialidade as $especialidades)
                        <optgroup label="{{ $especialidades->nome }}">
                            @foreach ($especialidades->prestadoresInstituicao as $prestadoresInstituicao)
                                <option {{$prestadoresInstituicao->id ==$prestador_especialidade_id? 'selected': '' }}  value="{{ $prestadoresInstituicao->id }}">{{ $prestadoresInstituicao->prestador->nome }}</option>
                            @endforeach
                        </optgroup>

                    @endforeach
                </select>
            </div> --}}

            <div class="col-md-3" style="padding-left:0px;">
                <select id="exame" style="width: 100%" class="form-control select2ProcedimentoPesquisa">
                    {{-- <optgroup label="Todos"> --}}
                        @if ($procedimento_instituicao_id == 0)
                            <option value="0" @if ("0" == $procedimento_instituicao_id)
                                selected
                            @endif>Todos os procedimentos</option>
                        @else
                            <option value="{{$procedimento_selected['id']}}" selected>{{$procedimento_selected['descricao']}}</option>
                        @endif
                    {{-- </optgroup> --}}
                    {{-- @foreach ($grupos as $grupo)
                        <optgroup label="{{ $grupo->nome }}">
                            @foreach ($grupo->procedimentos_instituicoes as $procedimentos)
                                <option {{$procedimentos->id ==$procedimento_instituicao_id? 'selected': '' }}  value="{{ $procedimentos->id }}">{{ $procedimentos->procedimento->descricao }}</option>
                            @endforeach
                        </optgroup>

                    @endforeach --}}
                </select>
            </div>

            <div class="col-md-3" style="padding-left:0px;">
                <select id="grupo" style="width: 100%" class="form-control">
                    <option value="0" @if ("0" == $grupo_id)
                            selected
                    @endif>Todos os grupos</option>
                    @foreach ($grupos_instituicao as $grupo_instituicao)
                        <option {{$grupo_instituicao->id==$grupo_id? 'selected': '' }}  value="{{ $grupo_instituicao->id }}">{{ $grupo_instituicao->nome }}</option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-3" style="padding-left:0px;padding-top:15px;">
                <select id="convenio" style="width: 100%" class="form-control">
                    <option value="0" @if ("0" == $convenio_id)
                            selected
                    @endif>Todos os convenios</option>
                    @foreach ($convenios_instituicao as $convenio_instituicao)
                        <option {{$convenio_instituicao->id==$convenio_id? 'selected': '' }}  value="{{ $convenio_instituicao->id }}">{{ $convenio_instituicao->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3" style="padding-left:0px;padding-top:15px;">
                <select id="faixa_idade" style="width: 100%" class="form-control">
                    <option value="0" @if (0 == $faixa_idade)
                        selected
                    @endif>Todas faixas etárias</option>
                    @foreach (App\InstituicoesAgenda::getFaixaEtaria() as $item)
                        <option value="{{ $item }}"
                            @if ($item == $faixa_idade)
                                selected
                            @endif
                        >{{ App\InstituicoesAgenda::getFaixaEtariaTexto($item) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3" style="padding-left:0px;padding-top:15px;">

                <div class="btn-group " role="group">
                    <button type="button" class="btn btn-default" data-action="toggle-datepicker" title="Escolher período" >
                        <i class="fa fa-fw fa-calendar"></i>
                    </button>
                    <button type="button" class="btn btn-default" data-change-agenda="previous"  title="Anterior">
                        <i class="mdi mdi-arrow-left-bold"></i>
                    </button>
                    <input type="text" class="datepicker form-control" id="data" readonly value="{{$data}}">
                    <button type="button" class="btn btn-default" data-change-agenda="next"  title="Próximo">
                        <i class="mdi mdi-arrow-right-bold"></i>
                    </button>
                </div>

            </div>


            <div class="col-md-3" style="padding-left:0px;padding-top:15px;">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: 1px solid #ced4da;">
                    <i class="fa fa-fw fa-filter"></i>
                </button>

                <div class="dropdown-menu">
                    {{-- <a href="#"  class="dropdown-item">
                        <input data-name="possui_disponivel" type="checkbox" id="md_checkbox_1" checked />
                        <label for="md_checkbox_1"> Possui horário disponivel</label>
                    </a>
                    <a href="#"  class="dropdown-item">
                        <input data-name="possui_agendado" type="checkbox" id="md_checkbox_2" checked />
                        <label for="md_checkbox_2"> Possui agendamento</label>
                    </a> --}}
                    <a href="javascript:void(0)"  class="dropdown-item">
                        <input class="checkboxAgendamentoPesquisa" data-name="horario_vazio" type="checkbox" id="md_checkbox_3" />
                        <label for="md_checkbox_3"> Exibir horários vazios passados</label>
                    </a>
                    <a href="javascript:void(0)"  class="dropdown-item">
                        <input class="checkboxAgendamentoPesquisa" data-name="horario_disponivel" type="checkbox" id="md_checkbox_4" onchange="verificaCheckBoxHorarios()" checked />
                        <label for="md_checkbox_4"> Exibir horários disponiveis</label>
                    </a>
                    <a href="javascript:void(0)"  class="dropdown-item">
                        <input class="checkboxAgendamentoPesquisa" data-name="horario_ausente" type="checkbox" id="md_checkbox_5" checked />
                        <label for="md_checkbox_5"> Exibir horários ausentes</label>
                    </a>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-default" wire:click="$emit('refreshComponent')" data-action="refresh" title="Atualizar">
                        <span class="mdi mdi-refresh"></span>
                    </button>
                    {{-- <button data-toggle="modal" data-target="#modalHelp" type="button" class="btn btn-default" title="Legenda">
                        <span class="fas fa-question"></span>
                    </button> --}}
                    {{-- <button type="button" class="btn btn-success" data-action="novo-agendamento"title="Agendar">
                        <span class="mdi mdi-calendar-plus"></span>
                    </button> --}}
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Dias de atendimento do profissional" style="border: 1px solid #ced4da;">
                        <i class="mdi mdi-calendar-question"></i>
                    </button>
                    <div class="dropdown-menu" id="dias_proficional"></div>
                </div>

                @if (count($this->usuario_prestador) == 0)
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary pesquisa_profissional_inativo" data-toggle="tooltip" data-placement="right" title="" data-original-title="Pesquisar agenda do profissional desativado" style="border: 1px solid #ced4da;">
                            <i class="mdi mdi-account-off"></i>
                        </button>
                    </div>
                @endif
                @if ($medico)
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary atender_paciente_avulso" data-toggle="tooltip" data-placement="right" title="" data-original-title="Atender paciente avulso" style="border: 1px solid #ced4da;">
                            <i class="ti-write"></i>
                        </button>
                    </div>
                @endif

                <div class="btn-group">
                    <button type="button" id="prof_dia_btn" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Profissionais que atendem nesta data" style="border: 1px solid #ced4da;">
                        <i class="mdi mdi-clipboard-account"></i>
                    </button>
                    <div class="dropdown-menu" id="profissionais_do_dia"></div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary horario_disponivel" data-horario="{{($hora_avulsa) ? $hora_avulsa : "08:00:00"}}" data-tipo="avulso" data-toggle="tooltip" data-placement="right" title="" data-original-title="Add agendamento avulso" style="border: 1px solid #ced4da;">
                        <i class="mdi mdi-calendar-plus"></i>
                    </button>
                </div>

                <div class="btn-group">
                    
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Legenda da agenda" style="border: 1px solid #ced4da;">
                        <i class="mdi mdi-information-outline"></i>
                    </button>
                    <div class="dropdown-menu" id="legenda_agenda">
                        <span data-toggle='tooltip' title='' data-original-title='Teste' class=''>
                            <div class="row">
                                <div class="col-md-2" style="background-color: #26c6da; color: #26c6da; height: 50%;">.</div>
                                <div class="col-md-9">Agendado</div>
                                <hr style="max-width: 100%;">    
                                <div class="col-md-2" style="background-color: #009688; color: #009688; height: 50%;">.</div>
                                <div class="col-md-9">Confirmado</div>  
                                <hr style="max-width: 100%;">                                          
                                <div class="col-md-2" style="background-color: #ffcf8e; color: #ffcf8e; height: 50%;">.</div>
                                <div class="col-md-9">Aguardando atendimento</div>
                                <hr style="max-width: 100%;">    
                                <div class="col-md-2" style="background-color: #8eff9e; color: #8eff9e; height: 50%;">.</div>
                                <div class="col-md-9">Em consultório</div>
                                <hr style="max-width: 100%;">    
                                <div class="col-md-2" style="background-color: #745af2; color: #745af2; height: 50%;">.</div>
                                <div class="col-md-9">Finalizado</div>
                                <hr style="max-width: 100%;">    
                                <div class="col-md-2" style="background-color: #78909C; color: #78909C; height: 50%;">.</div>
                                <div class="col-md-9">Desmarcado ou ausente</div>
                            </div>
                        </span>
                    </div>
                </div>
                @can('habilidade_instituicao_sessao', 'visualizar_agendamentos_lista_espera')
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary lista_espera" data-toggle="tooltip" data-placement="right" title="" data-original-title="Lista de espera" style="border: 1px solid #ced4da;">
                            <i class="mdi mdi-calendar-range"></i>
                        </button>
                    </div>
                @endcan

                <div class="btn-group" data-toggle="buttons" style="margin: 2px;">
                    <label class="btn btn-outline-secondary" style="border-color: #6c757d59!important;">
                        <input type="radio" id="exibirPeriodoAM" name="exibir_periodo" value="am">AM
                    </label>
                    <label class="btn btn-outline-secondary" style="border-color: #6c757d59!important;">
                        <input type="radio" id="exibirPeriodoPM" name="exibir_periodo" value="pm">PM
                    </label>
                    <label class="btn btn-outline-secondary active" style="border-color: #6c757d59!important;">
                        <input type="radio" id="exibirPeriodo24" name="exibir_periodo" value="24" checked>24Hrs
                    </label>
                </div>
            </div>

            {{-- <div class="btn-group rm-5" role="group">
                <button id='horario_disponivel' data-toggle="button"  type="button" aria-pressed="true" class="btn btn-secondary active"  title="Escolher período" >
                    <i class="fa fa-times text" aria-hidden="true"></i>
                    <i class="ti-check text-active" aria-hidden="true"></i>
                    <span class="text">Possui horario disponivel</span>
                    <span class="text-active">Possui horario disponivel</span>
                </button>
                <button id="horario_agendado" data-toggle="button" type="button" class="btn btn-secondary active"   aria-pressed="true" title="Anterior">
                    <i class="fa fa-times text" aria-hidden="true"></i>
                    <i class="ti-check text-active" aria-hidden="true"></i>
                    <span class="text">Possui agendamento</span>
                    <span class="text-active">Possui agendamento</span>
                </button>
            </div> --}}

            

        </div>
    </div>

    <div wire:poll.300000ms class="card-body no_print" >
        <input type="text" id="qtdAgendamentos" value="{{$qtdAgendamentos}}" style="display: none;">
        <div wire:loading.class.remove="hidden" class='hidden' wire:loading.class="ui ui-vazio" >
            {{-- <span style='font-size: 100px;' class="mdi mdi-refresh"></span> --}}

            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;" >
                <span class="sr-only">Carregando...</span>
            </div>
        </div>


        <div wire:loading.remove class="agenda_dia">
            @php
                $agendamentos_controle = clone $agendamentos;
                $max = (isset(json_decode($instituicao->config)->agendamentos->max_encaixe)) ? json_decode($instituicao->config)->agendamentos->max_encaixe : 0;
            @endphp

            @if($existeAgenda)
                @php
                    $totalAgenda = count($agendaDados);
                    $agendasTotal = 0;
                @endphp
                @for ($x = 0; $x < count($agendaDados); $x++)
                    @php
                        $agendasTotal++;
                    @endphp
                    @for ($i=\Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agendaDados[$x]['hora_inicio']); $i < \Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agendaDados[$x]['hora_intervalo']); $i->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agendaDados[$x]['duracao_atendimento'])))
                        @include('instituicao.agendamentos.agendamentoHorarios')
                    @endfor

                    @for($i=\Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agendaDados[$x]['hora_intervalo'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agendaDados[$x]['duracao_intervalo'])); $i < \Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agendaDados[$x]['hora_fim']); $i->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $agendaDados[$x]['duracao_atendimento'])) )
                        @include('instituicao.agendamentos.agendamentoHorarios')
                    @endfor

                    @if ($agendasTotal == $totalAgenda)
                        @php $agendamentos_count = 0; @endphp
                        @foreach ($agendamentos as $key => $agendamento)
                            {{-- {{dd(\Carbon\Carbon::parse($agendamento['data']), Carbon\Carbon::createFromFormat('d/m/Y', $data)->setTimeFromTimeString($agendaDados[$x]['hora_fim']))}} --}}

                            @include('instituicao.agendamentos.agendamentoForaHorario')
                            
                        @endforeach
                    @endif


                @endfor
            @else
                @if (count($agendamentos) > 0)
                    <div wire:loading.remove class="agenda_dia">
                        @php $agendamentos_count = 0; @endphp
                        @foreach ( $agendamentos as $key => $agendamento)
                            @include('instituicao.agendamentos.agendamentosDiaRemovido')
                        @endforeach
                    </div>
                @else
                    <div wire:loading.remove class="ui ui-vazio">
                        <span style='font-size: 100px;' class="mdi mdi-calendar-remove"></span>
                        <p class="lead">Não existem horários disponíveis para atendimento para este dia!</p>
                    </div>
                @endif
            @endif


        </div>

    </div>

    <div wire:ignore class="modal inmodal no_print" id="modalHorariosDisponiveis" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body" style="text-align: center;">
                    <h2>Defina o novo horário do atendimento</h2>

                    @can('habilidade_instituicao_sessao', 'remarcar_para_outro_prestador')
                        <select id="prestador_remarcar" style="width: 100%; margin-bottom: 10px;" class="form-control">
                            <option value=""></option>
                            @foreach ($especialidade as $especialidades)
                                <optgroup label="{{ $especialidades->descricao }}">
                                    @foreach ($especialidades->prestadoresInstituicao as $prestadoresInstituicao)
                                        {{-- @if ($prestadoresInstituicao->ativo == 1) --}}
                                            <option {{$prestadoresInstituicao->id ==$prestador_especialidade_id? 'selected': '' }}  value="{{ $prestadoresInstituicao->id }}">{{ $prestadoresInstituicao->prestador->nome }}</option>
                                        {{-- @endif --}}
                                    @endforeach
                                </optgroup>

                            @endforeach
                        </select>
                    @endcan

                    <input type="hidden" name="id"  value="">

                <div data-procedimento="{{$procedimento_instituicao_id}}" data-prestador="{{$prestador_especialidade_id}}" class='datepicker_modal mb-3' style="text-align: -webkit-center;">

                    </div>
                    <div class="horarios">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore class="modal inmodal no_print" id="modalProfissionalDesativado" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body" style="text-align: center;">
                    <h2>Seleciona o profissional que deseja</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <select id="prestador_desativado" style="width: 100%" class="form-control">
                                <option value=""></option>
                                @foreach ($especialidade as $especialidades)
                                    <optgroup label="{{ $especialidades->descricao }}">
                                        @foreach ($especialidades->prestadoresInstituicao as $prestadoresInstituicao)
                                            @if ($prestadoresInstituicao->ativo == 0)
                                                <option {{$prestadoresInstituicao->id ==$prestador_especialidade_id? 'selected': '' }}  value="{{ $prestadoresInstituicao->id }}">{{ $prestadoresInstituicao->prestador->nome }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12' style="margin-top: 15px">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-success waves-effect waves-light definir_prestador_inativo" data-dismiss="modal">Confirmar</button>
                                <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div wire:ignore class="modal inmodal no_print" id="modalAtenderPacienteAvulso" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body" style="text-align: center;">
                    <h2>Selecione o paciente</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <select id="paciente_avulso" style="width: 100%" class="form-control select2ModalPaciente">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class='col-md-12' style="margin-top: 15px">
                            <div class="form-group text-right">
                                <a class="modalAtenderAvulsoPaciente" href="" target="_blank"  style="text-decoration: none;color: unset; padding: 0px 3px;">
                                    <button type="button" class="btn btn-success waves-effect waves-light iniciar_atendimento_avulso_modal">Iniciar atendimento avulso</button>
                                </a>
                                <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore class="modal inmodal no_print" id="modalCancelarAgendamento" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body" style="text-align: center;">
                    <input type="hidden" name="horario"  value="">
                    <input type="hidden" name="id"  value="">
                    <h2>Confirmar o cancelamento do agendamento?</h2>
                    <p style="display: block;">(Opicional) Informe o motivo ao cliente:</p>
                    <input type="text" class="form-control" maxLength='255' id='motivo' name="motivo">
                    <div class='mt-4' >
                        <button data-dismiss="modal" class="btn btn-secondary cancel" tabindex="2" style="display: inline-block;">Não</button>
                        <button data-dismiss="modal" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore class="modal inmodal no_print" id="modalCancelarHorario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body" style="text-align: center;">
                    <input type="hidden" name="horario" id='value' value="">
                    <input type="hidden" name="agenda" id='value' value="">
                    <h2>Confirmar o cancelamento do horário?</h2>
                    <p style="display: block;">(Opicional) Informe o motivo:</p>
                    <input type="text" class="form-control" maxLength='255' id='motivo' name="motivo">
                    <div class='mt-4' >
                        <button data-dismiss="modal" class="btn btn-secondary cancel" tabindex="2" style="display: inline-block;">Não</button>
                        <button data-dismiss="modal" class="btn btn-success confirm" tabindex="1" style="display: inline-block;">Sim</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div wire:ignore class="modal inmodal no_print bs-example-modal-lg" id="modalListaEspera" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lista de espera</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <div class='mt-4' >
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore class="modal inmodal no_print" id="modalHelp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Legenda</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>


                </div>
                <div class="modal-body">
                    Para identificar a situação atual de determinado agendamento, diversos indicadores visuais são exibidos na sua tela:
                    <table class="table table-estado-agendamento">
                    <thead>
                        <tr>
                        <th>Ícone</th>
                        <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td style="background-color: #AED581; color: #555;">
                            <span class="mdi mdi-account-alert icon-agenda"></span>
                        </td>
                        <td>
                            <strong>Pendente:</strong> indica que o agendamento foi cadastrado no sistema, mas nenhuma ação
                            foi realizada sobre ele, como a confirmação do paciente ou da clínica.
                        </td>
                        </tr>
                        <tr>
                        <td style="background-color: #225f25; color: #fff;">
                            <span class="mdi mdi-account-convert icon-agenda"></span>
                        </td>
                        <td>
                            <strong>Agendado:</strong> indica que a clínica agendou o horário.
                        </td>
                        </tr>
                        {{-- <tr>
                        <td style="background-color: #009688; color: #fff;">
                            <span class="fas fa-check-square"></span>
                        </td>
                        <td>
                            <strong>Confirmado:</strong> indica que o paciente confirmou que estará presente no horário marcado
                            para a realização dos procedimentos.
                        </td>
                        </tr> --}}
                        <tr>
                        <td style="background-color: #78909C; color: #fff;">
                            <span class="far fa-frown"></span>
                        </td>
                        <td>
                            <strong>Cancelado:</strong> indica que o agendamento foi cancelado pela clínica ou paciente.
                        </td>
                        </tr>
                        {{-- <tr>
                        <td style="background-color: #FFEB3B; color: #555;">
                            <span class="fas fa-user-md"></span>
                        </td>
                        <td>
                            <strong>Paciente em atendimento:</strong> indica que o médico responsável pelo agendamento já
                            iniciou a realização dos procedimentos.
                        </td>
                        </tr>



                        <tr>
                        <td>
                            <span class="fas fa-user"></span>
                        </td>
                        <td>
                            <strong>Novo Paciente:</strong> indica que é um paciente novo na clínica, que está realizando atendimentos pela primeira vez.
                        </td>
                        </tr> --}}

                        <tr>
                        <td style="background-color: #EF6C00; color: #fff;">
                            <span class="mdi mdi-checkbox-marked-circle-outline icon-agenda"></span>
                        </td>
                        <td>
                            <strong>Procedimentos realizados:</strong> indica que o agendamento foi finalizado.
                        </td>
                        </tr>

                        <tr>
                        <td style="background-color: #cc0404; color: #fff;">
                            <span class="fas fa-exclamation text-warning"></span>
                        </td>
                        <td>
                            <strong>Alteração de Agenda:</strong> indica que o horario do agendamento está com problema devido a uma alteração na agenda do médico/procedimento e deve ser ajustado .
                        </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    

    <div class="print-div col-sm-12" style="display: none;"></div>

</div>
@push('estilos')
<style>
.agenda_horario_row.horario_disponivel_nao_exibir {
display: none!important;
}

.agendamento-registro.status-0 {
background-color:#cc0404;
border-color:#cc0404;
color: white;
}

.agendamento-registro.status-1 {
background-color: #26c6da;
border-color: #26c6da;
color: #fff;
}

.agendamento-registro.status-2    {
background-color: #ffcf8e;
border-color: #ffcf8e;
color: #fff;
}

.agendamento-registro.status-3 {
background-color: #009688;
border-color: #009688;
color: #fff;
}



.agendamento-registro.status-4 {
background-color: #78909C;
border-color: #78909C;
color: #fff;
}

.agendamento-registro.status-5 {
background-color: #745af2;
border-color: #745af2;
color: #fff;
}

.agendamento-registro.status-6 {
background-color: #ffcf8e;
border-color: #ffcf8e;
color: #fff;
}

.agendamento-registro.status-7 {
background-color: #899093;
border-color: #899093;
color: #fff;
}
.agendamento-registro.status-8 {
background-color: #8eff9e;
border-color: #8eff9e;
color: #fff
}
.agendamento-registro.status-9 {
background-color: #63dbae;
border-color: #63dbae;
color: #fff;
}

.agendamento-registro.status-10 {
background-color: #a58683;
border-color: #a58683;
color: #fff;
}

/* .agendamento-registro .btn:hover, .agendamento-registro .btn:focus {
box-shadow: none;
background-color: rgba(255, 255, 255, .43);
color: black;
} */

/* .agendamento-registro:hover{
box-shadow: none;
background-color: rgba(255, 255, 255, .43);
color: black;
} */

.icon-agenda{
font-size: 16px;
}

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
flex-basis: 30%;
}

.agendamento .agendamento-icone .fa,.agendamento .agendamento-icone .far,.agendamento .agendamento-icone .fas{
font-size: 20px;
vertical-align: middle;

}
.agendamento .agendamento-icone{
text-align: center;
flex-basis: 6%;
}

.agendamento .agendamento-paciente {
flex-basis: 50%;
}

.agendamento .agendamento_actions {
flex-basis: 21%;
text-align: right;
}

.agendamento.status-0 {
background-color:#cc0404;
border-color:#cc0404;
color: white;
}

.agendamento.status-1 {
background-color: #26c6da;
border-color: #26c6da;
color: #1b5c64;
}

.agendamento.status-2    {
background-color: #ffcf8e;
border-color: #ffcf8e;
color: #81653f;
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
background-color: #745af2;
border-color: #745af2;
color: #fff;
}

.agendamento.status-6 {
background-color: #ffcf8e;
border-color: #ffcf8e;
color: #877052;
}

.agendamento.status-7 {
background-color: #899093;
border-color: #899093;
color: #545c5e;
}
.agendamento.status-8 {
background-color: #8eff9e;
border-color: #8eff9e;
color: #285a2f;
}
.agendamento.status-9 {
background-color: #63dbae;
border-color: #63dbae;
color: #4db890;
}

.agendamento.status-10 {
background-color: #a58683;
border-color: #a58683;
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
flex-basis: 95%;
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

@media print {
    body * {
        visibility: hidden;
    }
    .print-div, .print-div * {
        visibility: visible;
    }
    .print-div {
        position: absolute;
        widows: 100%;
        left: 0;
        top: 0;

    }
    .no_print {
        display: none !important;
    }
}

.dropdown-menu {
    max-height: 320px;
    overflow-y: auto;
}
.Highlighted{
    background : Green !important;
}
.Highlighted a{
    /* border-color: green!important; */

   /* background-image :none !important; */
   /* color: White !important; */
   /* font-weight:bold !important; */
   /* font-size: 12pt; */
}
#modalHorariosDisponiveis .select2{
    margin-bottom: 10px
}
</style>
@endpush
@push('scripts');

<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
<script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>

<script>
var contaReceberCriada = false;
$('[data-toggle="tooltip"]').tooltip()

document.addEventListener("DOMContentLoaded", () => {
    window.livewire.hook('afterDomUpdate', () => {
        $('[data-toggle="tooltip"]').tooltip()
        if($(".agenda_horario_row").find(".horario_disponivel").hasClass('horario_disponivel_nao_exibir')){
            $(".agenda_horario_row").find(".horario_disponivel_nao_exibir").parents(".agenda_horario_row").css('display', 'none');
        }
        if($(".agenda_horario_row").find(".vazio_passado").hasClass('horario_disponivel_nao_exibir')){
            $(".agenda_horario_row").find(".horario_disponivel_nao_exibir").parents(".agenda_horario_row").css('display', 'none');
        }

        setTimeout(() => {
            if($(".agenda_horario_row").find(".agenda_eventos_row_horario").hasClass('horario_cancelado_nao_exibir')){  
                $(".agenda_horario_row").find(".horario_cancelado_nao_exibir").parents(".agenda_horario_row").css('display', 'none');
            }
        }, 0);

        toobar();
    })
});

function callRenderPage(){
    $('[data-action="refresh"]').click();
    $('.list-livewire').find('[data-action="refresh-count"]').click();
}

function callRenderPageSemanal($prestador){
    @this.set('prestador_especialidade_id', $prestador);
    $('[data-action="refresh"]').click();
    $('.list-livewire').find('[data-action="refresh-count"]').click();
}

function callRenderSemanalPesquisa(data){
    if($("#semanal-click").hasClass('carregado')){
        callRenderSemanal(data);
    }
}

    // $('.exibir_prestadores').on('click', function (e) {

    // });

$(".pesquisa_profissional_inativo").on('click', function(){
    $("#modalProfissionalDesativado").modal('show')
})
$(".atender_paciente_avulso").on('click', function(){
    $("#modalAtenderPacienteAvulso").modal('show')
})
var exige_carteirinha = false;

$(".iniciar_atendimento_avulso_modal").on('click', function(){
    $("#modalAtenderPacienteAvulso").modal('hide')
})

var SelectedDates = {};
var SelectedDatesRemarcar = {};

$( document ).ready(function() {
    $(".select2ModalPaciente").select2({
        placeholder: "Pesquise por nome ou cpf",
        allowClear: true,
        minimumInputLength: 3,
        language: {
        searching: function () {
            return 'Buscando paciente (aguarde antes de selecionar)…';
        },
        
        inputTooShort: function (input) {
            return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
        },
        },    
        
        ajax: {
            url:"{{route('instituicao.agendamentos.getPacientes')}}",
            dataType: 'json',
            delay: 100,

            data: function (params) {
            return {
                q: params.term || '', // search term
                page: params.page || 1
            };
            },
            processResults: function (data, params) {
            params.page = params.page || 1;
            return {
                results: _.map(data.results, item => ({
                    id: Number.parseInt(item.id),
                    text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''} ${(item.telefone1) ? '- ('+item.telefone1+')': ''}`,
                })),
                pagination: {
                    more: data.pagination.more
                }
            };
            },
            cache: true
        },

    }).on('select2:select', function(e){
        var data = e.params.data;
        var pessoa_id = data.id;
        $(".modalAtenderAvulsoPaciente").attr('href', "{{ route('instituicao.pessoas.abrirProntuario', ['pessoa' => 'pessoa_id']) }}".replace('pessoa_id', pessoa_id))
    })

    $("#prestador_remarcar").select2()
    
    getDataAtendimentos();
    // SelectedDates[new Date('2022-08-19 00:00:00')] = new Date('2022-08-19 00:00:00');
    // SelectedDates[new Date('2022-08-18 00:00:00')] = new Date('2022-08-18 00:00:00');
    // SelectedDates[new Date('2022-09-17 00:00:00')] = new Date('2022-09-17 00:00:00');
    // console.log(SelectedDates);

    $('[data-toggle="tooltip"]').tooltip()

    @this.on('reset_icheck', function(){
        // $('input[data-name="horario_vazio"]').iCheck('check');
        // $('input[data-name="horario_disponivel"]').iCheck('check');
        // $('input[data-name="horario_ausente"]').iCheck('check');
        // verificaCheckBoxHorarios();
        verificaExibirPeriodo();
    })

    // verificaCheckBoxHorarios();
    verificaExibirPeriodo();

    toobar();

    // $('body').on('click','[data-action="refresh"]',function(e){
    //     e.stopPropagation();
    //     e.stopImmediatePropagation();
    //     console.log('aqui')
    //     callRenderPage()
    // })

    if($(".agenda_horario_row").find(".horario_disponivel").hasClass('horario_disponivel_nao_exibir')){
        $(".agenda_horario_row").find(".horario_disponivel_nao_exibir").parents(".agenda_horario_row").css('display', 'none');
    }

    if($(".agenda_horario_row").find(".vazio_passado").hasClass('horario_disponivel_nao_exibir')){
        $(".agenda_horario_row").find(".horario_disponivel_nao_exibir").parents(".agenda_horario_row").css('display', 'none');
    }

    if($(".agenda_horario_row").find(".agenda_eventos_row_horario").hasClass('horario_cancelado_nao_exibir')){
        $(".agenda_horario_row").find(".horario_cancelado_nao_exibir").parents(".agenda_horario_row").css('display', 'none');
    }

    getDiasPrestador()

    /* MODAL INSERINDO AGENDA*/

    // $('body').on('click','add_agendamento_avulso', function(e){
    //     if($('#prestador').val() == ""){
    //         Swal.fire({
    //             title: "Prestador!",
    //             text: 'Selecione um prestador',
    //             icon: "warning",
    //             confirmButtonColor: "#DD6B55",
    //             confirmButtonText: "Ok!",
    //         })
    //     }else{
        
    //         console.log('clicou')
    //     }
    // }

    $('body').on('click','.horario_disponivel',function(e){

        $('#modalInserirAgenda .modal-content').html('');
        
        if($('#prestador').val() == ""){
            Swal.fire({
                title: "Prestador!",
                text: 'Selecione um prestador',
                icon: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ok!",
            })
        }else{
            // console.log($(this).data('agendamento'))

            var dataAtual = new Date();
            // var dia = dataAtual.getDate();
            // var mes = (dataAtual.getMonth() + 1);
            // var ano = dataAtual.getFullYear();
            // var horas = dataAtual.getHours();
            // var minutos = dataAtual.getMinutes();

            horario_atual = dataAtual.getHours()+":"+dataAtual.getMinutes();

            // console.log(horario_atual, $(this).attr('data-horario'))

            $.ajax("{{ route('instituicao.agendamentos.modalInserirAgenda') }}", {
                    method: "POST",
                    data: {
                        data: $('.datepicker').val(),
                        horario:  $(this).attr('data-horario'),
                        tipo:  $(this).attr('data-tipo'),
                        id:  $(this).attr('data-agenda'),
                        paciente_lista:  $(this).attr('data-paciente'),
                        lista_id:  $(this).attr('data-lista'),
                        prestador_especialidade_id: $('#prestador').val(),
                        '_token': '{{csrf_token()}}'
                    },
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function (response) {
                        $('#modalDescricao .modal-content').html('');
                        if(response.icon == "error"){
                            $.toast({
                                heading: response.title,
                                text: response.text,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: response.icon,
                                hideAfter: 3000,
                                stack: 10
                            });
                        }else{
                            
                            $('#modalInserirAgenda .modal-content').html(response);
                            $('#modalInserirAgenda').modal('show')
                        }
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader')
                    },
            })
        }
    })


    /* MODAL VISUAZLIANDO DADOS */

    $('body').on('click','.agendamento.clickable',function(e){

        $('#modalDescricao .modal-content').html('');
        // console.log($(this).data('agendamento'))
        $.ajax("{{ route('instituicao.agendamentos.modalDescricao') }}", {
                method: "POST",
                data: {agendamento_id: $(this).attr('data-agendamento'), '_token': '{{csrf_token()}}'},
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (response) {
                    $('#modalInserirAgenda .modal-content').html('');
                    $('#modalDescricao .modal-content').html(response);
                    $('#modalDescricao').modal('show')
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                },
        })
    })

    $('#modalCancelarAgendamento .confirm').on('click',function(e){
        $motivo = $('#modalCancelarAgendamento').find('input[name="motivo"]').val();
        $id = $('#modalCancelarAgendamento').find('input[name="id"]').val();

        $.ajax("{{ route('instituicao.agendamentos.cancelar_agendamento') }}", {
                method: "POST",
                data: {id: $id, motivo : $motivo, '_token': '{{csrf_token()}}'},

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

                    if(response.icon=='success'){

                        callRenderPage()
                        callRenderSemanalPesquisa(response.data);
                    }

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
    });


    $('#modalCancelarHorario .confirm').on('click',function(e){
        $motivo = $('#modalCancelarHorario').find('input[name="motivo"]').val();
        $horario = $('#modalCancelarHorario').find('input[name="horario"]').val();
        $agenda = $('#modalCancelarHorario').find('input[name="agenda"]').val();

        $.ajax("{{ route('instituicao.agendamentos.cancelar_horario') }}", {
                method: "POST",
                data: {agenda: $agenda, horario: $horario, motivo : $motivo, '_token': '{{csrf_token()}}'},

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

                    if(response.icon=='success'){

                        callRenderPage()
                        callRenderSemanalPesquisa(response.data);
                    }

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
    });


    $('body').on('click', '.reativar_horario',function(e){
        $id_agendamento = $(this).data("agendamento");

        // console.log($id_agendamento);

        $.ajax("{{ route('instituicao.agendamentos.reativarHorario', ['agendamento' => 'agendamento_id']) }}".replace('agendamento_id', $id_agendamento), {
            method: "POST",
            data: {'_token': '{{csrf_token()}}'},

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

                if(response.icon=='success'){

                    callRenderPage()
                    callRenderSemanalPesquisa(response.data);
                }

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
    });

    $('body').on('click','.remarcar',function(e){
        SelectedDatesRemarcar = SelectedDates;
        e.stopPropagation();
        $(".datepicker_modal").datepicker({
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
            minDate: 0,
            beforeShowDay: function(date) {
                var Highlight = SelectedDatesRemarcar[date];
                // console.log(date)
                // console.log(Highlight)
                // console.log(SelectedDates)
                if (Highlight) {
                    return [true, "Highlighted", ''];
                }
                else {
                    return [true, '', ''];
                }
            },
            onSelect:function(){
                $('.datepicker_modal').data("data", this.value)
                construirModal()
            }

        })
        $('.ui-datepicker').addClass('notranslate');
        $('.datepicker_modal').data("data", moment($(".datepicker_modal").datepicker("getDate")).format('D/MM/YYYY') )
        // $(".datepicker_modal").datepicker("setDate", $('.datepicker_modal').data('data'))
        $('#modalHorariosDisponiveis').find('input[name="id"]').val($(this).attr('data-id'))
        $('#modalHorariosDisponiveis').modal('show');
        var $prestador_id = $("#prestador option:selected").val();
        if($("#prestador_remarcar option:selected").val() == ""){
            $("#prestador_remarcar").val($prestador_id).change();
        }
        
        construirModal()

        // construidModal($agenda, $agendamentos);
    })

    $('body').on('click','button.remarcar_horario',function(e){
        $id = $('#modalHorariosDisponiveis').find('input[name="id"]').val();
        $data = $(this).attr('data-horario');
        $idAgenda = $(this).attr('data-id');
        var $prestador_id = $("#prestador option:selected").val();
        if($("#prestador_remarcar").length > 0){
            $prestador_id = $("#prestador_remarcar option:selected").val();
        }

        Swal.fire({
            title: "Remarcar!",
            text: 'Deseja remarcar o agendamento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax("{{ route('instituicao.agendamentos.alterar_horario') }}", {
                    method: "POST",
                    data: {id: $id, idAgenda: $idAgenda, data: $data, '_token': '{{csrf_token()}}', prestador_id: $prestador_id},

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

                        if(response.icon=='success'){

                            callRenderPage()
                            callRenderSemanalPesquisa(response.data);
                        }

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


    function construirModal(){
        $('#modalHorariosDisponiveis .modal-body .horarios').html('');
        var $prestador_id = $("#prestador option:selected").val();
        if($("#prestador_remarcar").length > 0){
            $prestador_id = $("#prestador_remarcar option:selected").val();
        }

        $.ajax("{{ route('instituicao.agendamentos.modalRemarcar') }}", {
                method: "POST",
                data: {procedimento_instituicao_id: $('.datepicker_modal').data('procedimento'), prestador_especialidade_id: $prestador_id, data :$('.datepicker_modal').data('data'), '_token': '{{csrf_token()}}'},
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (response) {

                    $('#modalHorariosDisponiveis .modal-body .horarios').html(response);

                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
        })
    }

    $('body').on('click','.cancelar',function(e){
        e.stopPropagation();
        $('#modalCancelarAgendamento').find('input[name="id"]').val($(this).data('id'))
        $('#modalCancelarAgendamento').find('input[name="motivo"]').val("")
        $('#modalCancelarAgendamento').modal('show');
    })

    $('body').on('click','.finalizar_agendamento',function(e){
        $id = $(this).data('id');
        e.stopPropagation();
        Swal.fire({
            title: "Finalizar!",
            text: 'Deseja finalizar o agendamento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax("{{ route('instituicao.agendamentos.finalizar_agendamento') }}", {
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

                        if(response.icon=='success'){

                            callRenderPage()
                            callRenderSemanalPesquisa(response.data);
                        }

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

    $('body').on('click','.noModal',function(e){
        e.stopPropagation();
    });

    $('body').on('click','.confirmar_agendamento',function(e){
        $id = $(this).data('id');
        e.stopPropagation();
        Swal.fire({
            title: "Confirmar!",
            text: 'Deseja confirmar o agendamento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                // var formData = $("#formPagamento").serializeArray()
                $.ajax("{{ route('instituicao.agendamentos.confirmar_agendamento') }}", {
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

                        if(response.icon=='success'){
                            
                            callRenderPage()
                            callRenderSemanalPesquisa(response.data);
                        }

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

    $('body').on('click','.remover_agendamento',function(e){
        $id = $(this).data('id');
        e.stopPropagation();
        Swal.fire({
            title: "Remover!",
            text: 'Deseja remover o agendamento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax("{{ route('instituicao.agendamentos.remover_agendamento') }}", {
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

                        if(response.icon=='success'){

                            callRenderPage()
                            callRenderSemanalPesquisa(response.data);
                        }

                    },
                    error: function (response) {
                        if(response.responseJSON.errors){
                            Object.keys(response.responseJSON.errors).forEach(function(key) {
                                $.toast({
                                    heading: 'Erro',
                                    text: response.responseJSON.errors[key][0],
                                    position: 'top-right',
                                    loaderBg: '#ff6ausente849',
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

    $('body').on('click','.ausente_agendamento',function(e){
        $id = $(this).data('id');
        e.stopPropagation();
        Swal.fire({
            title: "Ausente!",
            text: 'Paciente não compareceu ao atendimento ?',
            icon: "warning",
            html:"<form><div class='form-group'><label>Motivo</label><input type='text' id='motivo_ausente' name='motivo_ausente' class='form-control' placeholder='Digite o motivo da ausência'></div>",
            preConfirm: () => {
                const motivo = Swal.getPopup().querySelector('#motivo_ausente').value
                return {motivo}
            },
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            // console.log(result, result.motivo)
            
            if(result.value){
                $.ajax("{{ route('instituicao.agendamentos.ausente_agendamento') }}", {
                    method: "POST",
                    data: {
                        id: $id,
                        motivo: result.value.motivo,
                        '_token': '{{csrf_token()}}',                        
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

                        if(response.icon=='success'){
                            callRenderPage()
                            callRenderSemanalPesquisa(response.data);
                        }

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

    $('body').on('click','.reativar_agendamento',function(e){
        $id = $(this).data('id');
        e.stopPropagation();
        Swal.fire({
            title: "Reativar!",
            text: 'Deseja reativar o agendamento ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax("{{ route('instituicao.agendamentos.reativar_agendamento') }}", {
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

                        if(response.icon=='success'){

                            callRenderPage()
                            callRenderSemanalPesquisa(response.data);
                        }

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

    $('body').on('click','.iniciar_atendimento',function(e){
        $id = $(this).attr('data-id');
        e.stopPropagation();
        $(".editar-carteirinha").click();
        // if(exige_carteirinha){
        //     Swal.fire({
        //         title: "Selecione carteirinha!",
        //         text: 'O convênio exige uma carteirinha selecionada e o codigo de autorização preenchido!',
        //         icon: "error",
        //     })
        // }else{

            if(retornaFormatoValor($(".pagamento").find('.diferenca_pagamento').val()) != 0){
                Swal.fire({
                    title: "Valores a receber!",
                    text: 'Preencha a aba "Pagamentos" para iniciar o atendimento!',
                    icon: "warning",
                })
    
            }else{    
                Swal.fire({
                    title: "Iniciar atendimento!",
                    text: 'Deseja iniciar o atendimento ?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não, cancelar!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if(result.value){
                        $('#modalDescricao').modal('hide')
                        $.ajax("{{ route('instituicao.agendamentos.iniciar_atendimento') }}", {
                            method: "POST",
                            data: {
                                id: $id,
                                '_token': '{{csrf_token()}}',
                                carteirinha_id: $("#carteirinha_id option:selected").val(),
                                tipo_guia: $("#tipo_guia").val(),
                                num_guia_convenio: $("#num_guia_convenio").val(),
                                cod_aut_convenio: $("#cod_aut_convenio").val()
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
    
                                if(response.icon=='success'){
    
                                    callRenderPage()
                                    callRenderSemanalPesquisa(response.data);
                                }
    
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
        // }
    })

    $('body').on('click','.cancelar_horario',function(e){
        $('#modalCancelarHorario').find('input[name="horario"]').val($(this).attr('data-horario'))
        $('#modalCancelarHorario').find('input[name="agenda"]').val($(this).attr('data-agenda'))
        $('#modalCancelarHorario').find('input[name="motivo"]').val("")
        $('#modalCancelarHorario').modal('show');
    })

    $('.dropdown-item').on('click',function(e){
        $(this).find('input').iCheck('toggle')
        e.stopPropagation()
    })

    $(".select2ProcedimentoPesquisa").select2({
        placeholder: "Pesquise por procedimento",
        allowClear: true,
        minimumInputLength: 3,
        language: {
        searching: function () {
            return 'Buscando procedimentos (aguarde antes de selecionar)…';
        },

        inputTooShort: function (input) {
            return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
        },
        },

        ajax: {
            url:"{{route('instituicao.agendamentos.getProcedimentoPesquisa')}}",
            dataType: 'json',
            delay: 100,

            data: function (params) {
            return {
                q: params.term || '', // search term
                page: params.page || 1
            };
            },
            processResults: function (data, params) {
            params.page = params.page || 1;

            // console.log(data.results)
            return {
                results: _.map(data.results, item => ({
                    id: Number.parseInt(item.procedimento_instituicao[0].id),
                    text: `${item.descricao}`,
                })),
                pagination: {
                    more: data.pagination.more
                }
            };
            },
            cache: true
        },

    }).on('select2:select', function (e) {
        var data = $('#exame').select2("val");
        var dados = {
            id: data,
            descricao: $('#exame option:selected').text()
        };
        // dados['descricao'] =
        @this.set('procedimento_instituicao_id', data);
        @this.set('procedimento_selected', dados);
    }).on('select2:unselect', function(e){
        var data = 0;
        var dados = {
            id: 0,
            descricao: 'Todos os procedimentos'
        };
        // dados['descricao'] =
        @this.set('procedimento_instituicao_id', data);
        @this.set('procedimento_selected', dados);
    })

    // $('#exame').select2({
    //     placeholder:"Selecione um procedimento",
    //     templateSelection: formatState2
    // });

    $('#grupo').select2({
        placeholder:"Selecione um grupo/especialidade",
    });

    $('#prestador').select2({
        placeholder:"Selecione um profissional",
        // templateSelection: formatState
    });

    $('#prestador_desativado').select2({
        placeholder:"Selecione um profissional",
        // templateSelection: formatState
    });

    $('#setor').select2({
        placeholder:"Selecione um setor",
        // templateSelection: formatState
    });

    $('#convenio').select2({
        placeholder:"Selecione um convênio",
        // templateSelection: formatState
    });

    $('#faixa_idade').select2({
        placeholder:"Selecione uma faixa de idade",
        // templateSelection: formatState
    });

    function formatState (item) {
        if (!item.id) {
            return 'Selecione um médico';
        }
        opt = $(item.element);
        og = opt.closest('optgroup').attr('label');
        return $('<span><strong>'+og+'</strong>'+' : '+item.text+'</span>');
    };

    function formatState2 (item) {
        if (!item.id) {
            return 'Selecione um procedimento';
        }
        opt = $(item.element);
        og = opt.closest('optgroup').attr('label');
        return $('<span><strong>'+og+'</strong>'+' : '+item.text+'</span>');
    };

    // $('#exame').on('select2:select', function (e) {
    //     var data = $('#exame').select2("val");
    //     // $('#prestador').val(null).trigger('change');
    //     // $('#grupo').val(null).trigger('change');
    //     @this.set('procedimento_instituicao_id', data);
    //     // @this.set('prestador_especialidade_id', '');
    //     // @this.set('grupo_id', '');
    // });

    $('#prestador').on('select2:select', function (e) {
        $("#prestador_desativado").val("").change();
        var data = $('#prestador').select2("val");
        // $('#exame').val(null).trigger('change');
        // $('#grupo').val(null).trigger('change');
        @this.set('prestador_especialidade_id', data);
        // @this.set('procedimento_instituicao_id', '');
        // @this.set('grupo_id', '');
        getDataAtendimentos()
    });

    function getDataAtendimentos(){
        if($('#prestador option:selected').val()){
            // console.log($('#prestador option:selected').val())
            $.ajax("{{ route('instituicao.agendamentos.getDiasAtendimentoPrestador') }}", {
                method: "POST",
                data: {
                    'prestador_id': $('#prestador option:selected').val(),
                    '_token': '{{csrf_token()}}'
                },
                success: function (response) {
                    // console.log(response);
                    SelectedDates = {};
                    for (let index = 0; index < response.length; index++) {
                        const element = response[index];
                        SelectedDates[new Date(element)] = new Date(element);
                    }
                    // var SelectedDates = {};
                    // SelectedDates[new Date('2022-08-18 00:00:00')] = new Date('2022-08-18 00:00:00');
                    // SelectedDates[new Date('2022-09-17 00:00:00')] = new Date('2022-09-17 00:00:00');
                }
            })
        }
    }

    $("#prestador_remarcar").on('change', function(){
        if($('#prestador_remarcar option:selected').val()){
            $.ajax("{{ route('instituicao.agendamentos.getDiasAtendimentoPrestador') }}", {
                method: "POST",
                data: {
                    'prestador_id': $('#prestador_remarcar option:selected').val(),
                    '_token': '{{csrf_token()}}'
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (response) {
                    // console.log(response);
                    SelectedDatesRemarcar = {};
                    for (let index = 0; index < response.length; index++) {
                        const element = response[index];
                        SelectedDatesRemarcar[new Date(element)] = new Date(element);
                    }
                    $(".datepicker_modal").datepicker("refresh");
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                    // var SelectedDates = {};
                    // SelectedDates[new Date('2022-08-18 00:00:00')] = new Date('2022-08-18 00:00:00');
                    // SelectedDates[new Date('2022-09-17 00:00:00')] = new Date('2022-09-17 00:00:00');
                },
                complete: () => {
                    construirModal();
                }
            })
        }
    })

    $('#prestador_desativado').on('select2:select', function (e) {
        $("#prestador").val("").change();
        var data = $('#prestador_desativado').select2("val");
        // $('#exame').val(null).trigger('change');
        // $('#grupo').val(null).trigger('change');
        @this.set('prestador_especialidade_id', data);
        // @this.set('procedimento_instituicao_id', '');
        // @this.set('grupo_id', '');
    });

    $('#setor').on('select2:select', function (e) {
        var data = $('#setor').select2("val");
        // $('#exame').val(null).trigger('change');
        // $('#grupo').val(null).trigger('change');
        @this.set('setor_id', data);
        // @this.set('procedimento_instituicao_id', '');
        // @this.set('grupo_id', '');
    });


    $('#convenio').on('select2:select', function (e) {
        var data = $('#convenio').select2("val");
        // $('#exame').val(null).trigger('change');
        // $('#grupo').val(null).trigger('change');
        @this.set('convenio_id', data);
        // @this.set('procedimento_instituicao_id', '');
        // @this.set('grupo_id', '');
    });


    $('#faixa_idade').on('select2:select', function (e) {
        var data = $('#faixa_idade').select2("val");
        // $('#exame').val(null).trigger('change');
        // $('#grupo').val(null).trigger('change');
        @this.set('faixa_idade', data);
        // @this.set('procedimento_instituicao_id', '');
        // @this.set('grupo_id', '');
    });

    $('#grupo').on('select2:select', function (e) {
        var data = $('#grupo').select2("val");
        // $('#exame').val(null).trigger('change');
        // $('#prestador').val(null).trigger('change');
        @this.set('grupo_id', data);
        // @this.set('prestador_especialidade_id', '');
        // @this.set('procedimento_instituicao_id', '');
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
            $(this).attr('value', this.value);
            @this.set('data',  this.value);
        },
        beforeShowDay: function(date) {
            var Highlight = SelectedDates[date];
            // console.log(date)
            // console.log(Highlight)
            // console.log(SelectedDates)
            if (Highlight) {
                return [true, "Highlighted", ''];
            }
            else {
                return [true, '', ''];
            }
        }
    })

    $('.ui-datepicker').addClass('notranslate');

    previousPeriodo = () => {
        date = new Date($(".datepicker").datepicker( "getDate"))
        date.setDate(date.getDate() - 1);
        $(".datepicker").datepicker( "setDate", date );
        @this.set('data', date.toLocaleDateString("pt-BR"));
    }

    nextPeriodo = () => {
        date = new Date($(".datepicker").datepicker( "getDate"))
        date.setDate(date.getDate() + 1);
        $(".datepicker").datepicker( "setDate", date );
        @this.set('data', date.toLocaleDateString("pt-BR"));
    }

    $('body').on('click','[data-action="toggle-datepicker"]',function(e){
        $(".datepicker").datepicker('show')
    })

    // $('#horario_disponivel').on('click',function(e){
    //     @this.set('horario_disponivel', !$(e.currentTarget).hasClass('active'));
    // })


    // $('#horario_agendado').on('click',function(e){

    //     @this.set('horario_agendado', !$(e.currentTarget).hasClass('active'));
    // })



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

    $("#prestador").on('change', function(){
        getDiasPrestador();
        $("#semanal-click").removeClass('carregado');
        $("#agenda-semanal").html('');
    })

    getProfissionaisDia()
})

    // $('#md_checkbox_4').on('change', function(){
    //     console.log('aqui1')
    //     verificaCheckBoxHorarios()
    // })
    // $('input[data-name="horario_disponivel"]').on('ifChanged', function(){
    //     console.log('aqui1')
    //     verificaCheckBoxHorarios()
    // })
    // $('input[data-name="horario_ausente"]').on('ifChanged', function(){
    //     console.log('aqui1')
    //     verificaCheckBoxHorarios()
    // })

    function verificaCheckBoxHorarios(tipo = null){
        // if($('input[type="checkbox"]').data('name')=='horario_vazio'){
            
        if(tipo == null){
            if(!$('input[data-name="horario_vazio"]').is(":checked")){
                $('.vazio_passado').closest('.agenda_horario_row').css('display','none')
            }else{
                $('.vazio_passado').closest('.agenda_horario_row').css('display','flex')
            }
        // }else if($('input[type="checkbox"]').data('name')=='horario_disponivel'){
            if(!$('input[data-name="horario_disponivel"]').is(":checked")){
                $('.horario_disponivel').closest('.agenda_horario_row').css('display','none')
            }else{
                $('.horario_disponivel').closest('.agenda_horario_row').css('display','flex')
            }
        // }else if($('input[type="checkbox"]').data('name')=='horario_ausente'){
            if(!$('input[data-name="horario_ausente"]').is(":checked")){
                $('.horario_cancelado').closest('.agenda_horario_row').css('display','none')
            }else{
                $('.horario_cancelado').closest('.agenda_horario_row').css('display','flex')
            }
        // }
        }else{
            if(!$('input[data-name="horario_vazio"]').is(":checked")){
                $('.vazio_passado').closest('.agenda_horario_row.class_'+tipo).css('display','none')
            }else{
                $('.vazio_passado').closest('.agenda_horario_row.class_'+tipo).css('display','flex')
            }
        // }else if($('input[type="checkbox"]').data('name')=='horario_disponivel'){
            if(!$('input[data-name="horario_disponivel"]').is(":checked")){
                $('.horario_disponivel').closest('.agenda_horario_row.class_'+tipo).css('display','none')
            }else{
                $('.horario_disponivel').closest('.agenda_horario_row.class_'+tipo).css('display','flex')
            }
        // }else if($('input[type="checkbox"]').data('name')=='horario_ausente'){
            if(!$('input[data-name="horario_ausente"]').is(":checked")){
                $('.horario_cancelado').closest('.agenda_horario_row.class_'+tipo).css('display','none')
            }else{
                $('.horario_cancelado').closest('.agenda_horario_row.class_'+tipo).css('display','flex')
            }
        }
    }

    $("input[name='exibir_periodo']").on('change', function(){
        verificaExibirPeriodo()
    })

    function verificaExibirPeriodo(){
        if($("input[name='exibir_periodo']:checked").val() == "am"){
            $(".class_am").css('display', 'flex');
            $(".class_pm").css('display', 'none');
            verificaCheckBoxHorarios('am')  
        }
        if($("input[name='exibir_periodo']:checked").val() == "pm"){
            $(".class_pm").css('display', 'flex');
            $(".class_am").css('display', 'none');
            verificaCheckBoxHorarios('pm')  
        }
        if($("input[name='exibir_periodo']:checked").val() == "24"){
            $(".class_am").css('display', 'flex');
            $(".class_pm").css('display', 'flex');
            verificaCheckBoxHorarios(null)  
        }

    }

    function getDiasPrestador(){
        $('[data-toggle="tooltip"]').tooltip()
        $('#dias_proficional').html('');
        var id = $("#prestador").val()
        $.ajax("{{ route('instituicao.agendamentos.getDiasPrestador') }}", {
            method: "POST",
            data: {
                'prestador_id': id ,
                '_token': '{{csrf_token()}}'
            },
            success: function (response) {

                texto = "<div class='col-sm-12'><center>"
                if(!response.dias_semana.length > 0){
                    texto = texto + "<h4>Dias continuos</h4><hr>...<hr>"
                }else{
                    texto = texto + "<h4>Dias continuos</h4><hr>"
                    for(i=0; i < response.dias_semana.length; i++){
                        texto = texto + response.dias_semana[i].dia
                        if(response.dias_semana[i].obs  ){
                            texto = texto + "<span data-toggle='tooltip' title='' data-original-title='"+response.dias_semana[i].obs+"' class='mdi mdi-comment'></span>"
                        }

                        texto = texto + "<hr>"
                    }
                }

                if(!response.dias_unicos.length > 0){
                    texto = texto + "<h4>Dias Unicos</h4><hr>...<hr>"
                }else{
                    texto = texto + "<h4>Dias Unicos</h4><hr>"
                    for(i=0; i < response.dias_unicos.length; i++){
                        texto = texto + response.dias_unicos[i].date
                        if(response.dias_unicos[i].obs_unico){
                            texto = texto + "<span data-toggle='tooltip' title='' data-original-title='"+response.dias_unicos[i].obs_unico+"' class='mdi mdi-comment'></span>"
                        }
                        texto = texto +  "<hr>";
                    }
                }

                texto = texto + "</center></div>";
                $('#dias_proficional').html(texto);
            }
        })

    }

    // $('.list-pesquisa').find("#qtdAgendamentos").on('change', function(){
    $("#qtdAgendamentos").on('change', function(){
        toobar();
    });

    function toobar(){
        var elemento = $('.list-pesquisa').find('#toobar-pesquisa');
        var json_status = $('#qtdAgendamentos').val();
        $.ajax("{{ route('instituicao.agendamentos.setToobar') }}", {
            method: "POST",
            data: {
                'dados': json_status ,
                '_token': '{{csrf_token()}}'
            },
            success: function (response) {
                $(elemento).html(response);
            }
        })
    }

    $("#prof_dia_btn").on('click', function(){
        getProfissionaisDia();
    })

    function getProfissionaisDia(){
        data = $("#data").val();
        setor_id = $("#setor").val();

        $.ajax("{{ route('instituicao.agendamentos.getProfissionaisDia') }}", {
            method: "GET",
            data: {
                'data': data ,
                'setor_id': setor_id,
                '_token': '{{csrf_token()}}'
            },
            beforeSend: () => {
                $("#profissionais_do_dia").html('<center><div class="spinner-border" role="status"><span class="visually-hidden"></span></div></center>');
            },
            success: function (response) {
                $("#profissionais_do_dia").html(response);
            }
        })
    }

    $("#profissionais_do_dia").on('click', '.profissional', function(e){
        $("#prestador").val($(this).data('id')).change();
        @this.set('prestador_especialidade_id', $(this).data('id'));
    });

    $(".lista_espera").on('click', function(e){
        var prestadorId = $("#prestador").val();
        $.ajax("{{route('instituicao.agendamentosListaEspera.listaEsperaAgenda', ['prestadorInst' => 'prestadorId'])}}".replace('prestadorId', prestadorId), {
        method: "GET",
        data: {
            "_token": "{{csrf_token()}}"
        },
        beforeSend: () => {
            $('.loading').css('display', 'block');
            $('.loading').find('.class-loading').addClass('loader')
        },
        success: function (result) {
            $('#modalEmitirNota .modal-content .modal-body').html('');
            $('#modalListaEspera .modal-content .modal-body').html(result);
            
            $("#modalListaEspera").modal('show');
        },
        complete: () => {
            
            $('.loading').css('display', 'none');
            $('.loading').find('.class-loading').removeClass('loader')
        }
        })
    })

    $('#modalListaEspera .modal-content .modal-body .scrolling-pagination').jscroll({
        loadingHtml: '<div class="spinner-border text-secondary" role="status"><span class="sr-only">Loading...</span></div>',
        autoTrigger: true,
        padding: 0,
        nextSelector: '.pagination li.active + li a',
        contentSelector: 'div.scrolling-pagination',
        callback: function() {
            $('ul.pagination').remove();
        }
    });

</script>
@endpush
