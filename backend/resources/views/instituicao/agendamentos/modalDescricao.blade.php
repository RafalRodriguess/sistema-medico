<style>
  .desconto-descricao-group .btn{
    position: absolute;
    right: 0;
    height: 50%;
    padding: 0;
    width: 3em;
    text-align: center;
    line-height: 1;
    z-index: 999999;
  }

  .desconto-descricao-touchspin-up{
    border-radius: 0 4px 0 0;
    top: 0;
  }
  .desconto-descricao-touchspin-down{
    border-radius: 0 0 4px 0;
    bottom: 0;
  }
</style>

<div class="modal-header">
  <h4 class="modal-title" id="myLargeModalLabel">Agendamento</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>


<div class="row modal-body" id="AgendaCadastrarContainerDados">
  <div class="col-md-3 formulario-historico-agendamento">
      <h5 class="text-center">
          @if(count($usuarioAgendamentos) == 0)
            Nenhum agendamento registrado
          @elseif(count($usuarioAgendamentos) == 1 )
            1 agendamento registrado
          @else
            {{count($usuarioAgendamentos)}} agendamentos registrados
          @endif </h5>
    <div class="list-group white-bg scrollable">
      @foreach ( $usuarioAgendamentos as $uagendamento)
          <div class="list-group-item">
              <h5>{{\Carbon\Carbon::parse($uagendamento['data'])->format('d/m/Y H:i')}}</h5>
              <div class="small">
                    @if($uagendamento->instituicoesAgenda->prestadores)
                        <p title="{{($uagendamento->instituicoesAgenda->prestadores->especialidade) ? ucwords($uagendamento->instituicoesAgenda->prestadores->especialidade->descricao) : ""}} - {{ucwords($uagendamento->instituicoesAgenda->prestadores->prestador->nome)}}" class="noWrap">

                          <i class="ti-id-badge"></i> {{($uagendamento->instituicoesAgenda->prestadores->especialidade) ? ucwords($uagendamento->instituicoesAgenda->prestadores->especialidade->descricao) : ""}} {{ucwords($uagendamento->instituicoesAgenda->prestadores->prestador->nome)}}

                        </p>
                    @endif
                    <p>

                      <button class="btn @if( $uagendamento['status']=='pendente' )
                          agendamento status-1"> <span class="mdi mdi-account-alert icon-agenda"></span>
                      @elseif( $uagendamento['status']=='agendado')
                          agendamento status-2"> <span class="mdi mdi-account-convert icon-agenda"></span>
                      @elseif( $uagendamento['status']=='confirmado')
                          agendamento status-3"> <span class="mdi mdi-account-convert icon-agenda"></span>
                      @elseif($uagendamento['status']=='cancelado' )
                          agendamento status-4"> <span class="far fa-frown"></span>
                      @elseif( $uagendamento['status']=='finalizado')
                          agendamento status-5"> <span class="mdi mdi-checkbox-marked-circle-outline icon-agenda"></span>
                      @elseif( $uagendamento['status']=='ausente')
                          agendamento status-7"> <span class="mdi mdi-account-remove icon-agenda"></span>
                      @elseif( $uagendamento['status']=='em_atendimento')
                          agendamento status-8"> <span class="ti-id-badge"></span>
                          @php $uagendamento['status'] = "em consultório" @endphp
                      @elseif( $uagendamento['status']=='finalizado_medico')
                          agendamento status-9"> <span class="mdi mdi-checkbox-marked-circle-outline icon-agenda"></span>
                          @php $uagendamento['status'] = "finalizado consultório" @endphp
                      @elseif( $uagendamento['status']=='desistencia')
                          agendamento status-10"> <span class="mdi icon-agenda"></span>
                      @elseif( $uagendamento['status']=='excluir')
                          agendamento border"> <span class="mdi mdi-trash-can icon-agenda"></span>
                          @php $uagendamento['status'] = "Excluido" @endphp
                      @else
                        ">
                      @endif
                    {{ucwords($uagendamento['status'])}}</button>
                    </p>

                    @foreach ( $uagendamento->agendamentoProcedimento as $agendamentoProcedimento)
                      @if ($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed)
                        @if ($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->convenios)
                          @if ($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->procedimento)
                            <p><span class="ti-hand-stop"></span> {{strtoupper($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->convenios->nome)}} - {{strtoupper  ($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao)}}</p>
                          @endif
                        @endif
                      @endif
                    @endforeach

                </div>
          </div>
      @endforeach

    </div>
  </div>

  <div class="col-md-9 formulario-dados-agendamento">

    <ul class="nav nav-tabs customtab editarTabs" role="tablist">

      <li class="nav-item visualizarAgendamento">
        <a class="nav-link active tab-agendamento-visualizar" data-toggle="tab" href="#agendamento-visualizar" role="tab">
          <span class="hidden-xs-down">Agendamento</span>
        </a>
      </li>

      <li class="nav-item visualizarOpcionais  @if(!empty($agendamento['obs'])) border-danger border-top @endif" style="--bs-border-opacity: .2;">
        <a class="nav-link tab-opcionais" data-toggle="tab" href="#opcionais" role="tab">
          @if(!empty($agendamento['obs']))
            <i class="mdi mdi-comment-alert  text-danger"  data-toggle="tooltip" data-placement="right" title="" data-original-title="Observação preenchida"></i>
          @endif

          <span class="hidden-xs-down">Opcionais</span>
        </a>
      </li>

      <li class="nav-item visualizarAuditoria">
        <a class="nav-link tab-auditoria" data-toggle="tab" href="#auditoria" role="tab">
          <span class="hidden-xs-down">Auditoria</span>
        </a>
      </li>
      @can('habilidade_instituicao_sessao', 'visualizar_atendimento_paciente')
        <li class="nav-item visualizarAtendimentoPaciente  @if(count($agendamento->atendimentoPaciente)>0) border-danger border-top @endif">
          <a class="nav-link tab-atendimento-paciente" data-toggle="tab" href="#atendimento-paciente" role="tab">
            @if(count($agendamento->atendimentoPaciente)>0)
              <i class="mdi mdi-comment-alert  text-danger"  data-toggle="tooltip" data-placement="right" title="" data-original-title="Atendimento preenchido"></i>
            @endif
            <span class="hidden-xs-down">Atendimento</span>
          </a>
        </li>
      @endcan

    </ul>

    <div class="tab-content  tabsEditar">
      <div class="tab-pane p-20 active" id="agendamento-visualizar" role="tabpanel">
        <div class="agendamento-visualizar">

          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label>Num. Registro</label>
              <input type="text" readonly="" value="{{$agendamento->id}}" id="agendameto_id_modalDescricao" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="ipt_agendamento_dia">Data </label>
                <input type="text" name="dia" id="ipt_agendamento_dia" class="form-control" alt="date" autocomplete="off" value="{{\Carbon\Carbon::parse($agendamento['data'])->format('d/m/Y')}}" readonly="">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group is-async-input">
                <label for="ipt_agendamento_hora">Hora Inicio/Final</label>
                <div class="input-container">
                  <div class="row">
                    <div class="col-md-6">
                      <input type="text" name="hora" id="ipt_agendamento_hora" class="form-control" alt="time" autocomplete="off" value="@if ($agendamento->data_original){{\Carbon\Carbon::parse($agendamento['data_original'])->format('H:i')}} @else {{\Carbon\Carbon::parse($agendamento['data'])->format('H:i')}} @endif" readonly="">
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="hora_final" id="ipt_agendamento_hora_final" class="form-control" alt="time" autocomplete="off" value="@if ($agendamento->data_final_original){{($agendamento['data_final_original']) ? \Carbon\Carbon::parse($agendamento['data_final_original'])->format('H:i') : \Carbon\Carbon::parse($agendamento['data'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $duracao_atendimento))->format('H:i')}} @else {{($agendamento['data_final']) ? \Carbon\Carbon::parse($agendamento['data_final'])->format('H:i') : \Carbon\Carbon::parse($agendamento['data'])->add(\Carbon\CarbonInterval::createFromFormat('H:i:s', $duracao_atendimento))->format('H:i')}} @endif" readonly="">
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>


            <div class="col-md-2">
              <div class="form-group">
                <label for="ipt_agendamento_valor">Valor Total</label>
              <input type="text" name="valor" id="ipt_agendamento_valor" class="form-control" alt="money" value="R${{integerParaReal($agendamento->valor_total)}}" style="text-align: right;" readonly="">
              </div>
            </div>
            <div class="col-md-3" style="display: none">
              <div class="form-group">
                <label for="ipt_agendamento_valor">Pagamento</label>
                  <input type="text" name="valor" id="ipt_agendamento_valor" class="form-control"  value="@if ($agendamento->forma_pagamento == 'cartao_credito')
                    {{ucwords($agendamento->status_pagamento)}}
                  @elseif($agendamento->forma_pagamento == 'cartao_entrega')Cartão no dia
                  @elseif($agendamento->forma_pagamento == 'dinheiro')Dinheiro no dia
                  @endif
                  " style="text-align: right;" readonly="">
              </div>
            </div>
            {{-- <div style="align-self: flex-end;" class="col-md-3">
              <div class="form-group">
                  <button  class="btn @if( $uagendamento['status']=='pendente' )
                              agendamento status-1"> <span class="far fa-meh"></span>
                          @elseif( $uagendamento['status']=='agendado')
                              agendamento status-2"> <span class="far fa-smile"></span>
                          @elseif( $uagendamento['status']=='confirmado')
                              agendamento status-3"> <span class="fas fa-check-square"></span>
                          @elseif($uagendamento['status']=='cancelado' )
                              agendamento status-4"> <span class="far fa-frown"></span>
                          @endif
                          {{ucwords($uagendamento['status'])}}</button>
              </div>
            </div> --}}
          </div>
          {{-- <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="ipt_agendamento_dia">Dia </label>
                <input type="text" name="dia" id="ipt_agendamento_dia" class="form-control" alt="date" autocomplete="off" value="{{\Carbon\Carbon::parse($agendamento['data'])->format('d/m/Y')}}" readonly="">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group is-async-input">
                <label for="ipt_agendamento_hora">Hora </label>
                <div class="input-container">
                  <input type="text" name="hora" id="ipt_agendamento_hora" class="form-control" alt="time" autocomplete="off" value="{{\Carbon\Carbon::parse($agendamento['data'])->format('H:i')}}" readonly="">
                </div>
              </div>
            </div>

          </div> --}}
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                @if($agendamento->instituicoesAgenda->prestadores)
                  <input type="hidden" class="form-control" id="prestadores_id" name="prestadores_id" value="{{$agendamento->instituicoesAgenda->prestadores->prestador->id}}">
                  <label for="ipt_agendamento_agenda">Prestador</label>
                  <input type="text" class="form-control" readonly="" value="{{($agendamento->instituicoesAgenda->prestadores->especialidade) ? ucwords($agendamento->instituicoesAgenda->prestadores->especialidade->descricao) : ""}} - {{ucwords(!empty($agendamento->instituicoesAgenda->prestadores->nome) ? $agendamento->instituicoesAgenda->prestadores->nome : $agendamento->instituicoesAgenda->prestadores->prestador->nome)}}">
                @endif
              </div>
            </div>
          </div>


      <hr class="hr-line-dashed">

      <div class="row">
        <div class="col-md-6">
          <div class="form-group is-async-input">
            <label for="ipt_agendamento_paciente">Nome do Paciente</label>
          <input type="text" class="form-control" readonly="" value="{{($agendamento->pessoa) ? ucwords($agendamento->pessoa->nome) : ''}}">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="ipt_agendamento_paciente_data">Data nascimento</label>
            <input type="date" name="paciente_data" id="ipt_agendamento_paciente_data" class="form-control" autocomplete="off" value="{{($agendamento->pessoa) ? ucwords($agendamento->pessoa->nascimento) : ""}}" readonly="">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="ipt_agendamento_paciente_telefone">Telefone</label>
            <input type="text" name="paciente_telefone_1" id="ipt_agendamento_paciente_telefone" class="form-control" alt="phone" autocomplete="off" value="{{($agendamento->pessoa) ? ucwords($agendamento->pessoa->telefone1) : ""}}" readonly="">
          </div>
        </div>




      <hr class="hr-line-dashed">
        <ul class="nav nav-tabs customtab editarTabs" role="tablist">
          @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
            <li class="nav-item visualizarProcedimentos"> <a class="nav-link active tab-procedimento-visualizar" data-toggle="tab" href="#procedimento-visualizar" role="tab"><span class="hidden-sm-up"><i class="ti-home "></i></span> <span class="hidden-xs-down"><i class="ti-heart-broken"></i> Procedimentos</span></a> </li>
            <li class="nav-item visualizarPagamento"> <a class="nav-link tab-pagamento" data-toggle="tab" href="#pagamento" role="tab"><span class="hidden-sm-up"><i class="ti-money "></i></span> <span class="hidden-xs-down"><i class="ti-money"></i> Pagamentos</span></a> </li>
          @endcan
        </ul>

        <div class="tab-content tabsEditar">
          <div class="tab-pane p-20 active" id="procedimento-visualizar" role="tabpanel">
            <div class="procedimento-visualizar">
              <form id='form' action="">
                    {{-- @method('put') --}}
                @csrf
                <input name="agendamento" type="hidden" class="form-control" id="agendamento_descricao"  value="{{$agendamento->id}}">

                <div class="agendamento-procedimentos" id="agendamento_procedimentos">
                  <div class="lista-procedimentos" >
                    <div class="convenio_procedimentos_descricao row">
                      @include('instituicao.agendamentos.procedimentos_editar')
                      @if (\Gate::check('habilidade_instituicao_sessao', 'editar_procedimento_pagamanto'))
                        @if (\Gate::check('habilidade_instituicao_sessao', 'add_procedimento_agendamento_momento'))
                          <div class="form-group col-md-9 add-class" >
                            <span alt="default" class="add-convenio fas fa-plus-circle" style="cursor: pointer">
                                <a class="mytooltip" href="javascript:void(0)">
                                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar convenio procedimento"></i>
                                </a>
                            </span>
                          </div>
                        @else
                        
                        {{-- @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento') --}}
                          <div class="form-group col-md-9 add-class" >
                            @if (!empty($convenios))
                              @if ($agendamento->status!='finalizado')
                                @if ($agendamento->status!='agendado')
                                 @if ($agendamento->status!='finalizado_medico')
                                    @if ($agendamento->status!='em_atendimento')
                                      <span alt="default" class="add-convenio fas fa-plus-circle" style="cursor: pointer">
                                          <a class="mytooltip" href="javascript:void(0)">
                                              <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar convenio procedimento"></i>
                                          </a>
                                      </span>
                                    @endif
                                  @endif
                                @endif
                              @endif
                            @else
                              <h5>Não é possivel adicionar mais procedimentos ao agendamento</h5>
                            @endif
                          </div>
                        @endif
                        {{-- @endcan --}}
                      @else
                          @if ($agendamento->status!='finalizado')
                            @if ($agendamento->status!='agendado')
                              @if ($agendamento->status!='finalizado_medico')
                              @if ($agendamento->status!='em_atendimento')
                                <div class="form-group col-md-9 add-class" >
                                @if (!empty($convenios))
                                    <span alt="default" class="add-convenio fas fa-plus-circle" style="cursor: pointer">
                                        <a class="mytooltip" href="javascript:void(0)">
                                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar convenio procedimento"></i>
                                        </a>
                                    </span>
                                  @else
                                    <h5>Não é possivel adicionar mais procedimentos ao agendamento</h5>
                                  @endif
                                </div>

                              @endif
                              @endif
                            @endif
                          @endif
                      @endif
                      {{-- <div class="col-md-9"></div> --}}
                      @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
                        <div class="form-group col-md-3">
                            <label class="form-control-label">Total R$</label>
                            <input class="form-control" alt="decimal" type="text" readonly id="total_procedimentos_descricao" name="total_procedimentos_descricao">
                        </div>
                      @endcan
                    </div>

                  <div class="form-group text-right">
                    @can('habilidade_instituicao_sessao', 'add_procedimento_agendamento_momento')
                        @if ($agendamento->status=='finalizado' || $agendamento->status=='agendado' || $agendamento->status=='finalizado_medico' || $agendamento->status=='em_atendimento')
                          <button class="btn btn-outline-secondary waves-effect waves-light" type="submit"><span class="btn-label"><i class="fa fa-check"></i></span>Salvar</button>
                        @endif
                      @endcan
                    @if (\Gate::check('habilidade_instituicao_sessao', 'editar_procedimento_pagamanto'))
                      @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
                        @if ($agendamento->status!='finalizado')
                          @if ($agendamento->status!='agendado')
                            @if ($agendamento->status!='finalizado_medico')
                              @if ($agendamento->status!='em_atendimento')
                                <button class="btn btn-outline-secondary waves-effect waves-light" type="submit"><span class="btn-label"><i class="fa fa-check"></i></span>Salvar</button>
                              @endif
                            @endif
                          @endif
                        @endif
                      @endcan
                    @else
                      @if ($agendamento->status!='finalizado')
                        @if ($agendamento->status!='agendado')
                          @if ($agendamento->status!='finalizado_medico')
                          @if ($agendamento->status!='em_atendimento')
                            {{-- <button type="submit" class="btn btn-danger waves-effect waves-light">Editar</button>  --}}
                            <button class="btn btn-outline-secondary waves-effect waves-light" type="submit"><span class="btn-label"><i class="fa fa-check"></i></span>Salvar</button>
                          @endif
                          @endif
                        @endif
                      @endif
                    @endif
                  </div>

                </div>
              </form>
                  {{-- *KENNEDY  verificar o que é isso que icaro fez que buga
                @if(count($outrosAgendamentos) > 0) --}}
                  @if(isset($outros_Agendamento_verificar))
                    <hr class="hr-line-dashed">

                    <p><strong>Outros Procedimentos:</strong></p>

                    <div class="agendamento-procedimentos" id="agendamento_procedimentos">
                        <div class="lista-procedimentos" >
                        @foreach ( $outrosAgendamentos as  $outroAgendamento)


                            @foreach ( $outroAgendamento->agendamentoProcedimento as $index => $agendamentoProcedimento)
                                <div style="display: flex;">
                                    <div class="procedimento">
                                        <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label>Convênio</label>
                                            <input type="text" class="form-control" readonly="" value="{{strtoupper($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->convenios->nome)}}">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                            <label>Procedimento</label>
                                            <input type="text" class="form-control" readonly="" value="{{strtoupper($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao)}}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <label>
                                                Valor
                                            </label>
                                            <input type="text" class="form-control" readonly="" alt="money" value="R${{integerParaReal($agendamentoProcedimento->valor_atual)}}" style="text-align: right;">
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                </div>

                            @endforeach
                        @endforeach
                        </div>

                    </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="tab-pane p-20" id="pagamento" role="tabpanel">
              <div class="pagamento">
                <form id='formPagamento' action="">
                  <input type="text" hidden name="carteirinha_id_pagamento" id="carteirinha_id_pagamento">
                  <input type="text" hidden name="tipo_guia_pagamento" id="tipo_guia_pagamento">
                  <input type="text" hidden name="num_guia_convenio_pagamento" id="num_guia_convenio_pagamento">
                  <input type="text" hidden name="cod_aut_convenio_pagamento" id="cod_aut_convenio_pagamento">
                  @csrf
                  @include('instituicao.agendamentos.metodoPagamento')
                  <div class="form-group text-right">
                    @can('habilidade_instituicao_sessao', 'emitir_boleto')
                      <button class="btn btn-outline-secondary waves-effect waves-light emitir_boleto" type="button"><span class="btn-label"><i class="mdi mdi-barcode"></i></span>Emitir boletos</button>
                    @endcan
                    
                    @can('habilidade_instituicao_sessao', 'emitir_nota_fiscal')
                      <button class="btn btn-outline-secondary waves-effect waves-light emitir_nfe" type="button"><span class="btn-label"><i class="fa fa-money-bill-alt"></i></span>Emitir nota fiscal</button>
                    @endcan

                    @can('habilidade_instituicao_sessao', 'salvar_pagamento_agendamentos')
                      @if (\Gate::check('habilidade_instituicao_sessao', 'editar_procedimento_pagamanto'))
                        <button class="btn btn-outline-secondary waves-effect waves-light salvar_pagamento" type="button"><span class="btn-label"><i class="fa fa-check"></i></span>Salvar pagamento</button>
                      @else
                        @if ($agendamento->status!='finalizado')
                          {{-- @if (count($agendamento->contaReceber) == 0) --}}
                          @if ($agendamento->status!='agendado')
                            @if ($agendamento->status!='finalizado_medico')
                            @if ($agendamento->status!='em_atendimento')
                              {{-- <button type="button" class="btn btn-danger waves-effect waves-light salvar_pagamento">Salvar pagamento</button>  --}}
                              <button class="btn btn-outline-secondary waves-effect waves-light salvar_pagamento" type="button"><span class="btn-label"><i class="fa fa-check"></i></span>Salvar pagamento</button>
                            @endif
                            @endif
                          @endif
                        @endif
                      @endif
                    @endcan
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="tab-pane p-20" id="opcionais" role="tabpanel">
      <div class="opcionais">
        <div class="col-sm-12 pt-3">
          <input type="hidden" name="boleto_acompanhante" value="{{old('boleto_acompanhante', $agendamento['boleto_acompanhante'])}}" id="boleto_acompanhante">
          <div class="card shadow-none p-3 mb-0">
            <div class="form-check">
             {{-- {{ dd($agendamento)}} --}}
              <input type="checkbox" class="form-check-input" name="acompanhante" value="1" @if(old('acompanhante', $agendamento['acompanhante'])=="1") checked @endif id="acompanhanteCheckEdicao">
              <label class="form-check-label" for="acompanhanteCheckEdicao">Acompanhante</label>
            </div>

            <div class="col-sm-12 acompanhante_edicao" style="display: {{($agendamento['acompanhante'] == 1) ? 'block': 'none'}}">
              <div class='row py-2'>
                <div class="col-sm-4">
                  <div class="form-group @if($errors->has('acompanhante_relacao')) has-danger @endif">
                      <label class="form-control-label p-0 m-0">Relação/Parentesco</label>
                      <select name="acompanhante_relacao" id="acompanhante_relacao_edicao" class="form-control select2-simples @if($errors->has('acompanhante_relacao')) form-control-danger @endif">
                          <option selected disabled value="">Selecione</option>
                          @foreach ($referencia_relacoes as $relacao)
                              <option value="{{ $relacao }}" @if(old('acompanhante_relacao', $agendamento['acompanhante_relacao'])==$relacao) selected @endif>{{ $relacao }}</option>
                          @endforeach
                      </select>
                      @if($errors->has('acompanhante_relacao'))
                          <small class="form-text text-danger">{{ $errors->first('acompanhante_relacao') }}</small>
                      @endif
                  </div>
                </div>

                <div class="col-sm-8">
                  <div class="form-group @if($errors->has('acompanhante_nome')) has-danger @endif">
                      <label class="form-control-label p-0 m-0">Nome</label>
                      <input type="text" name="acompanhante_nome" id="acompanhante_nome_edicao" value="{{ old('acompanhante_nome', $agendamento['acompanhante_nome']) }}"
                          class="form-control campo @if($errors->has('acompanhante_nome')) form-control-danger @endif">
                      @if($errors->has('acompanhante_nome'))
                          <small class="form-text text-danger">{{ $errors->first('acompanhante_nome') }}</small>
                      @endif
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group @if($errors->has('acompanhante_telefone')) has-danger @endif">
                      <label class="form-control-label p-0 m-0">Telefone</label>
                      <input type="text" name="acompanhante_telefone" id="acompanhante_telefone_edicao" value="{{ old('acompanhante_telefone', $agendamento['acompanhante_telefone']) }}"
                          class="form-control campo telefone @if($errors->has('acompanhante_telefone')) form-control-danger @endif">
                      @if($errors->has('acompanhante_telefone'))
                          <small class="form-text text-danger">{{ $errors->first('acompanhante_telefone') }}</small>
                      @endif
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group @if($errors->has('cpf_acompanhante')) has-danger @endif">
                      <label class="form-control-label p-0 m-0">Telefone</label>
                      <input type="text" name="cpf_acompanhante" id="cpf_acompanhante_edicao" alt="cpf" value="{{ old('cpf_acompanhante', $agendamento['cpf_acompanhante']) }}"
                          class="form-control campo cpf @if($errors->has('cpf_acompanhante')) form-control-danger @endif">
                      @if($errors->has('cpf_acompanhante'))
                          <small class="form-text text-danger">{{ $errors->first('cpf_acompanhante') }}</small>
                      @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 my-3 guias_sancoop">
          
        </div>

        <div class="col-sm-12 my-3">
          <div class="card shadow-none p-3">
            <div class="col-sm -12">
              <div class="form-group @if($errors->has('compromisso_id')) has-danger @endif">
                <label class="form-control-label p-0 m-0">Etiquetas</label>
                <select class="form-control select2ModalDescricao" name="compromisso_id" id="compromisso_id" style="width: 100%">
                    <option value="">Selecione</option>
                    @foreach ($compromissos as $item)
                        <option value="{{$item->id}}" @if ($item->id == $agendamento->compromisso_id)
                            selected
                        @endif>{{$item->descricao}}</option>
                    @endforeach
                </select>
              </div>

              <div class="form-group @if($errors->has('obs')) has-danger @endif" style="@cannot('habilidade_instituicao_sessao', 'visualizar_obs_opcionais')
                display: none
            @endcannot">
                  <label class="form-control-label p-0 m-0">Observações</label>
                  <textarea rows='5' name="obs" id="obs" value="{{ old('obs') }}" class="form-control campo @if($errors->has('obs')) form-control-danger @endif">{{ old('obs', $agendamento['obs']) }}</textarea>

                      @if($errors->has('obs'))
                      <small class="form-text text-danger">{{ $errors->first('obs') }}</small>
                  @endif
              </div>

              <div class="form-group @if($errors->has('solicitante')) has-danger @endif">
                  <label class="form-control-label p-0 m-0">Prestador Solicitante</label>
                  <select class="form-control select2solicitante" name="solicitante_agenda" id="solicitante_agenda" style="width: 100%">
                    @if(!empty($solicitante))
                      <option value="{{$agendamento['solicitante_id']}}">{{$solicitante->nome}}</option>
                    @endif
                  </select>
              </div>


              {{-- INSTITUIÇÃO E PRESTADOR DEVEM ESTAR HABILITADO PARA REALIZAÇÃO DO TELEATENDIMENTO --}}
              @if($instituicao->telemedicina_integrado == 1 && $agendamento->instituicoesAgenda->prestadores->telemedicina_integrado == 1)

              <div class="form-group">
                <div class="form-check form-check-inline" style="">
                    <input type="checkbox" class="form-check-input medico-checkbox"
                        id="teleatendimento" name="teleatendimento" value="1" @if(old('teleatendimento', $agendamento->teleatendimento)=="1") checked @endif>
                    <label class="form-check-label" for="teleatendimento">Teleatendimento</label>
                </div>
              </div>

                @if(!empty($agendamento->teleatendimento_link_prestador) && !empty($agendamento->teleatendimento_link_paciente))

                <div class="form-group">
                  <a href="{{$agendamento->teleatendimento_link_prestador}}" target="_blank">
                  <button type="button" class="btn btn-success btn-circle" data-toggle="tooltip" title data-original-title="Link prestador">
                    <i class="ti-id-badge"></i>
                  </button>
                  </a>

                  <button type="button" class="btn btn-warning btn-circle" data-toggle="tooltip" title data-original-title="Link paciente" onclick="copia_link_paciente()">
                    <i class="ti-user"></i> 
                  </button> {{$agendamento->teleatendimento_link_paciente}}
                  {{-- <textarea class="link_paciente2" style="display: contents;">{{$agendamento->teleatendimento_link_paciente}}</textarea> --}}

               </div>

                @endif

              @endif


            </div>

            <button class="btn btn-primary waves-effect" type="button" onclick="salvaObs()">
               Salvar Obs
            </button>

          </div>
          <div class="form-group text-right pb-2" style="margin-top: 10px">
            {{-- <div class="col-md-4"> --}}
              <button type="button" class="btn btn-success waves-effect folhaSala" >Imprimir folha de sala</button>
            {{-- </div> --}}
            {{-- <div class="col-md-4"> --}}
              <button type="button" class="btn btn-success waves-effect termoConsentimento" >Imprimir termo de consentimento</button>
            {{-- </div> --}}
          </div>


        </div>
      </div>
    </div>

    <div class="tab-pane p-20" id="auditoria" role="tabpanel">
      <div class="audiotoria"></div>
    </div>

    <div class="tab-pane p-20" id="atendimento-paciente" role="tabpanel">
      <div class="atendimento-paciente"></div>
    </div>

  </div>

</div>

<div class="modal-footer" style="width: 100%">

  @if ($agendamento->pessoa)
    <a href="{{route('instituicao.pessoas.edit', [$agendamento->pessoa])}}" target="_blank">
      {{-- <button type="button" class="btn btn-primary waves-effect" >Ficha do paciente</button> --}}
      <button class="btn btn-primary waves-effect" type="button" style="background-color: #26c6da;border:#26c6da;color: #fff;">
        <span class="btn-label"><i class="ti-user"></i></span>Ficha do paciente
      </button>
    </a>
  @endif

  @if ($agendamento->status!='cancelado')
    @if ($agendamento->status!='finalizado')

      @if ($agendamento->status=='pendente' || $agendamento->status=='ausente' || $agendamento->status=='confirmado')

        <button class="btn btn-secondary waves-effect waves-light confirmar_agendamento" data-id="{{$agendamento->id}}" data-dismiss="modal" type="button" style="background-color: #009688;border:#009688;color: #fff;">
          <span class="btn-label"><i class="mdi mdi-account-check"></i></span>Confirmar
        </button>

        <button class="btn btn-secondary waves-effect waves-light ausente_agendamento" data-id="{{$agendamento->id}}" data-dismiss="modal" type="button" style="background-color: #727b84;border:#727b84;color: #fff;">
          <span class="btn-label"><i class="mdi mdi-account-remove"></i></span>Ausente
        </button>
      @endif

      @if ($agendamento->status!='finalizado')
        @if ($agendamento->status!='agendado')
        @if ($agendamento->status!='em_atendimento')
          @if ($agendamento->status!='finalizado_medico')
            {{-- <button type="button" class="btn btn-info waves-effect iniciar_atendimento" data-id="{{$agendamento->id}}" data-dismiss="modal">Iniciar atendimento</button> --}}
            <button class="btn btn-secondary waves-effect waves-light iniciar_atendimento" data-id="{{$agendamento->id}}" type="button" style="background-color: #ffcf8e;border:#ffcf8e;color: #877052;">
              <span class="btn-label"><i class="mdi mdi-account-convert"></i></span>Iniciar atendimento
            </button>
          @endif
        @endif
        @endif
      @endif


      @if ($agendamento->status=='agendado' || $agendamento->status=='em_atendimento' || $agendamento->status=='finalizado_medico')
      {{-- <button type="button" class="btn btn-success waves-effect finalizar_agendamento" data-id="{{$agendamento->id}}" data-dismiss="modal">Finalizar</button> --}}
      <button class="btn btn-secondary waves-effect waves-light finalizar_agendamento" data-id="{{$agendamento->id}}" data-dismiss="modal" type="button" style="background-color: #745af2;border:#745af2;color: #fff;">
        <span class="btn-label"><i class="mdi mdi-checkbox-marked-circle-outline"></i></span>Finalizar atendimento
      </button>
      @endif
      @if (count($agendamento->atendimento) > 0)
        @if ($agendamento->status=='agendado' || $agendamento->status=='em_atendimento')
          @if ($medico)
            @can('habilidade_instituicao_sessao', 'abrir_prontuario')
              <a href="{{ route('instituicao.agendamentos.prontuario', [$agendamento]) }}" target="_blank">
                {{-- <button type="button" class="btn btn-info waves-effect atendimento_button" data-id="{{$agendamento->id}}">Atender consultório</button> --}}
                <button class="btn btn-secondary waves-effect waves-light atendimento_button" data-id="{{$agendamento->id}}" type="button" style="background-color: #499b55;border:#499b55;color: #fff;">
                  <span class="btn-label"><i class="mdi mdi-clipboard-text"></i></span>Atender consultório
                </button>
              </a>
            @endcan
          @endif
        @endif
        @if ($agendamento->status == "finalizado_medico")
          @can('habilidade_instituicao_sessao', 'abrir_prontuario')
            @if ($medico)
              @can('habilidade_instituicao_sessao', 'abrir_prontuario')
                <a href="{{ route('instituicao.agendamentos.prontuario', [$agendamento]) }}" target="_blank">
                  <button type="button" class="btn btn-info waves-effect" data-id="{{$agendamento->id}}" >Prontuário</button>
                </a>
              @endcan
            @endif
          @endcan
        @endif
      @endif

      

      @if ($agendamento->status!='finalizado_medico')
      @if ($agendamento->status!='em_atendimento')

      <button type="button" class="btn btn-warning btn-circle cancelar" data-id="{{$agendamento->id}}" data-dismiss="modal" data-toggle="tooltip" title data-original-title="Desmarcar">
        <i class="mdi mdi-account-off"></i>
      </button>

      <button type="button" class="btn btn-circle text-white desistir" data-id="{{$agendamento->id}}" data-dismiss="modal" data-toggle="tooltip" title data-original-title="Desistência" style="background-color: #727b90">
        <i class="mdi mdi-account-remove"></i>
      </button>
      
      <button type="button" class="btn btn-danger btn-circle remover_agendamento" data-id="{{$agendamento->id}}" data-dismiss="modal" data-toggle="tooltip" title data-original-title="Excluir">
        <i class="ti-trash"></i>
      </button>

        {{-- <button type="button" class="btn btn-warning waves-effect remover_agendamento" data-id="{{$agendamento->id}}" data-dismiss="modal">Remover</button> --}}
        {{-- <button type="button" class="btn btn-danger waves-effect cancelar" data-id="{{$agendamento->id}}" data-dismiss="modal">Desmarcar</button> --}}
      @endif
      @endif




    @endif
  @else
    <button type="button" class="btn btn-primary waves-effect reativar_agendamento" data-id="{{$agendamento->id}}" data-dismiss="modal">Reativar agendamento</button>
  @endif
  @if ($agendamento->status!="pendente")
    @can('habilidade_instituicao_sessao', 'retorno_agendamento_pendente')
      <button type="button" class="btn btn-circle retornar_pendente" data-id="{{$agendamento->id}}" data-dismiss="modal" data-toggle="tooltip" title data-original-title="Retornar para pendente" style="background: #a236f3; border-color: #a236f3; color: #fff">
        <i class="mdi mdi-undo"></i>
      </button>
    @endcan
  @endif
  <button type="button" class="btn btn-success btn-circle" onclick="imprimir()" data-id="{{$agendamento->id}}" data-toggle="tooltip" title="" data-original-title="Imprimir">
    <i class="mdi mdi-printer"></i>
  </button>
  <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
</div>

<script>
  var quantidade_convenio = 0;
  var desconto_maximo_descricao = "{{$desconto_maximo_descricao}}";

  function imprimir(){
    var id_agendamento = $("#agendamento_descricao").val();

    $.ajax("{{route('instituicao.agendamentos.imprimeAgendamento', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', id_agendamento), {
        method: "POST",
        data: {
          "_token": "{{csrf_token()}}",
        },
        beforeSend: () => {
          $('.loading').css('display', 'block');
          $('.loading').find('.class-loading').addClass('loader')
          $(".print-div").css("display", "block");
        },
        success: function (result) {
          // $('#modalDescricao').modal('hide');
          $(".print-div").html(result);
          // $(".print-div").css("display", "block");
          window.print();
        },
        complete: () => {
          $('.loading').css('display', 'none');
          $('.loading').find('.class-loading').removeClass('loader')
          $(".print-div").css("display", "none");
        }
    });
  }

  $(".close").on("click", function(){
    $(".tem-arquvio").css('display', 'none');
    $(".sem-arquivo").css('display', 'block');
  })

  $(".refresh").on("click", function(){
    $(".tem-arquvio").css('display', 'block');
    $(".sem-arquivo").css('display', 'none');
  })

  function callIniciarAtendimento($id){
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
  
  $( document ).ready(function() {
    contaReceberCriada = '{{$contaReceberCriada}}'
    
    $(".select2ModalDescricao").select2()
    // verificaExigeCarteirinha()
    $(".dropifyUpload").dropify();
    
    $(".exibe").css('display', 'block');

    setTimeout(() => {
      $(".desc_juros_multa").setMask();
      $(".porcentagem_desconto").setMask();
      $(".desc_juros_multa").css('text-align', 'left');
    }, 500);

    quantidade_convenio = $('.convenio_procedimentos_descricao').find('.item-convenio').length;
    // $('input[type="checkbox"]').iCheck({
    //     checkboxClass: 'icheckbox_square',
    //     radioClass: 'iradio_square',
    // }).on('ifChecked', function(event){
    //     $('#button_estornar').attr('disabled',false);
    //     console.log("check de acompanhate aqui");
    // });

    // $('.toggle_estorno').click(function(){
    //     $('.estornos').toggle()
    //     $('input[type="checkbox"]').val()
    // })

    $("#acompanhanteCheckEdicao").on("change", function(){
      if($("#acompanhanteCheckEdicao").is(":checked")){
        $('.acompanhante_edicao').css('display', 'block');
      }else{
        $('.acompanhante_edicao').css('display', 'none');
        $("#acompanhante_relacao_edicao").val("");
        $("#acompanhante_nome_edicao").val("");
        $("#acompanhante_telefone_edicao").val("");
        $("#cpf_acompanhante_edicao").val("");
      }
    });

    $("form").submit(function(e){
            e.preventDefault()

            var formData = new FormData($(this)[0]);
            var id_agendamento = $("#agendamento_descricao").val()
            // console.log(formData.values())
            $.ajax("{{ route('instituicao.agendamentos.editarAgendamento', ['agendamento' => 'agendamento_id']) }}".replace('agendamento_id', id_agendamento), {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
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
                    callRenderPage();
                    carregarGuia();
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

            // $.ajax("{{ route('instituicao.agendamentos.estornarParcialmente') }}", {
            //     method: "POST",
            //     data: formData,
            //     processData: false,
            //     contentType: false,
            //     success: function (response) {

            //         $.toast({
            //             heading: response.title,
            //             text: response.text,
            //             position: 'top-right',
            //             loaderBg: '#ff6849',
            //             icon: response.icon,
            //             hideAfter: 3000,
            //             stack: 10
            //         });
            //     },
            //     error: function (response) {
            //         if(response.responseJSON.errors){
            //             Object.keys(response.responseJSON.errors).forEach(function(key) {
            //                 $.toast({
            //                     heading: 'Erro',
            //                     text: response.responseJSON.errors[key][0],
            //                     position: 'top-right',
            //                     loaderBg: '#ff6849',
            //                     icon: 'error',
            //                     hideAfter: 9000,
            //                     stack: 10
            //                 });

            //             });
            //         }
            //     }
            // })
    })

    valorAtual();

    $(".select2solicitante").select2({
      placeholder: "Pesquise por nome",
      allowClear: true,
      minimumInputLength: 3,
      tags: true,
      createTag: function (params) {
        var term = $.trim(params.term);

        return {
          id: term,
          text: term + ' (Novo Prestador)',
          newTag: true
        }
      },
      language: {
        searching: function () {
          return 'Buscando solicitante (aguarde antes de selecionar)…';
        },

        inputTooShort: function (input) {
          return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
        },
      },

      ajax: {
          url:"{{route('instituicao.agendamentos.getSolicitantes')}}",
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
                  text: item.nome,
                })),
              pagination: {
                  more: data.pagination.more
              }
            };
          },
          cache: true
      },

    }).on('select2:select', function (e) {
      var data = e.params.data;
      console.log(data);
      if(e.params.data.newTag){
        $("solicitante_agenda").val(e.params.id);
      }
    })

    // $(".select2carteirinha").select2({
    //   placeholder: "Pesquise por carteirinha",
    //   allowClear: true,
    //   // minimumInputLength: 3,
    //   language: {
    //     searching: function () {
    //       return 'Buscando carteirinha (aguarde antes de selecionar)…';
    //     },
        
    //     inputTooShort: function (input) {
    //       return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
    //     },
    //   },    
      
    //   ajax: {
    //       url:"{{route('instituicao.agendamentos.getCarteirinhas')}}",
    //       dataType: 'json',
    //       delay: 100,

    //       data: function (params) {
    //         return {
    //           q: params.term || '', // search term
    //           page: params.page || 1,
    //           pessoa: $("#paciente_id_desc").val()
    //         };
    //       },
    //       processResults: function (data, params) {
    //         params.page = params.page || 1;
    //         return {
    //           results: _.map(data.results, item => ({
    //               id: Number.parseInt(item.id),
    //               text: `${item.carteirinha} (${item.convenio[0].nome})`,
    //             })),
    //           pagination: {
    //               more: data.pagination.more
    //           }
    //         };
    //       },
    //       cache: true
    //   },

    // })

    carregarGuia();

  })

  $('.convenio_procedimentos_descricao').on('click', '.desconto-descricao-touchspin-up', function(e){
      var desc = retornaFormatoValor($(this).parents('.desconto_descricao_input').find('.desc_juros_multa').val());
      var element = $(this).parents('.desconto_descricao_input').find('.desc_juros_multa');
      if( desc < 0){
        
        desc = parseFloat(desc) * -1;
        element.val(desc.toFixed(2)).setMask()
      }else if(desc == 0){
        element.val('0').setMask()
      }
      $(".desc_juros_multa").css('text-align', 'left');
      calculaValorNovoDescricao(this);
  })
  
  $('.convenio_procedimentos_descricao').on('click', '.desconto-descricao-touchspin-down', function(e){
      var desc = retornaFormatoValor($(this).parents('.desconto_descricao_input').find('.desc_juros_multa').val());
      var element = $(this).parents('.desconto_descricao_input').find('.desc_juros_multa');
      if( desc > 0){
          var total = $(element).parents('.item-convenio').find('.valor_procedimento').attr('data-valor')
          desc = parseFloat(desc) * -1;
          var desconto_porcento = (desc*100)/total;

          if((desconto_porcento*-1) > desconto_maximo_descricao){
            Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo_descricao+"%", "error")
            $(element).val(0)
            desc = 0;
            $(element).parents('.item-convenio').find('.porcentagem_desconto').val(desc.toFixed(2)).setMask()
          }

          element.val(desc.toFixed(2)).setMask()
          
        }else if(desc == 0){
          element.val('-0').setMask()
        }
        $(".desc_juros_multa").css('text-align', 'left');
      calculaValorNovoDescricao(this);
  })

  function calculaValorNovoDescricaoPorcento(element){
    var total = $(element).parents('.item-convenio').find('.valor_procedimento').attr('data-valor')
    var desconto = $(element).val()

    if(desconto > desconto_maximo_descricao){
      Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo_descricao+"%", "error")
      $(element).val(0).setMask()
      desconto = 0;
    }

    var desconto_real = (total*desconto)/100;

    if(Math.sign(retornaFormatoValor($(element).parents('.item-convenio').find('.desc_juros_multa').val()) < 0) || retornaFormatoValor($(element).parents('.item-convenio').find('.desc_juros_multa').val()) == 0){
      $(element).parents('.item-convenio').find('.desc_juros_multa').val((desconto_real*-1).toFixed(2)).setMask()
    }else{
      $(element).parents('.item-convenio').find('.desc_juros_multa').val(desconto_real.toFixed(2)).setMask()
    }
    $(element).parents('.item-convenio').find('.desc_juros_multa').css('text-align', 'left')
    calculaValorNovoDescricao(element);
  }

  function calculaValorNovoDescricaoReal(element){
    var total = $(element).parents('.item-convenio').find('.valor_procedimento').attr('data-valor')
    var desconto = retornaFormatoValor($(element).val())

    var desconto_porcento = (desconto*100)/total;

    if((desconto_porcento*-1) > desconto_maximo_descricao){
      Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo_descricao+"%", "error")
      $(element).val(0)
      desconto = 0;
      desconto_porcento = 0;
    }

    $(element).parents('.item-convenio').find('.porcentagem_desconto').val(desconto_porcento.toFixed(2)).setMask()
    calculaValorNovoDescricao(element);
  }

  function calculaValorNovoDescricao(element){
    var valor = $(element).parents('.item-convenio').find('.valor_procedimento').attr('data-valor');
    var quantidade = $(element).parents('.item-convenio').find('.qtd_procedimento').val();
    var desconto = 0;
    if($(element).parents('.item-convenio').find('.desc_juros_multa').length > 0){
      desconto = retornaFormatoValor($(element).parents('.item-convenio').find('.desc_juros_multa').val());
    }

    var total = (parseFloat(valor)*quantidade) + parseFloat(desconto);
    $(element).parents('.item-convenio').find('.valor_procedimento').val(total.toFixed(2));
    $(element).parents('.item-convenio').find('.valor_procedimento').setMask();
    totalProcedimentos();
  }


  function carregarGuia(){
    var id_agendamento = $("#agendamento_descricao").val()
    $.ajax("{{ route('instituicao.agendamentos.getGias', ['agendamento' => 'agendamento_id']) }}".replace('agendamento_id', id_agendamento), {
      method: "GET",
      data: {"_token": "{{ csrf_token() }}"},
      // beforeSend: () => {
      //   $('.loading').css('display', 'block');
      //   $('.loading').find('.class-loading').addClass('loader')
      // },
      success: function (response) {
        // $.toast({
        //   heading: response.title,
        //   text: response.text,
        //   position: 'top-right',
        //   loaderBg: '#ff6849',
        //   icon: response.icon,
        //   hideAfter: 3000,
        //   stack: 10
        // });
        $('.guias_sancoop').html(response);

        $(".select2carteirinha").select2({
          placeholder: "Pesquise por carteirinha",
          allowClear: true,
          // minimumInputLength: 3,
          language: {
            searching: function () {
              return 'Buscando carteirinha (aguarde antes de selecionar)…';
            },
            
            inputTooShort: function (input) {
              return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
            },
          },    
          
          ajax: {
              url:"{{route('instituicao.agendamentos.getCarteirinhas')}}",
              dataType: 'json',
              delay: 100,

              data: function (params) {
                return {
                  q: params.term || '', // search term
                  page: params.page || 1,
                  pessoa: $("#paciente_id_desc").val()
                };
              },
              processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                  results: _.map(data.results, item => ({
                      id: Number.parseInt(item.id),
                      text: `${item.carteirinha} (${item.convenio[0].nome})`,
                    })),
                  pagination: {
                      more: data.pagination.more
                  }
                };
              },
              cache: true
          },

        })
      },
      // complete: () => {
      //   $('.loading').css('display', 'none');
      //   $('.loading').find('.class-loading').removeClass('loader')
      // }
    });
  }

  $(".atendimento_button").on('click', function(){
    $('#modalDescricao').modal('hide');
  })

  $(".cancelar-parcela-pagamento").on('click', function(e){
    e.preventDefault()
    var id_parcela = $(this).attr('data-id');
    var id_agendamento = $("#agendamento_descricao").val()
    Swal.fire({
        title: "Confirmar!",
        text: 'Deseja cancelar parcela ?',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: "Não, cancelar!",
        confirmButtonText: "Sim, confirmar!",
    }).then(function(result) {
        if(result.value){
          $.ajax("{{ route('instituicao.agendamentos.cancelarParcelaPagamento', ['agendamento' => 'agendamento_id', 'parcela' => 'parcela_id']) }}".replace('agendamento_id', id_agendamento).replace('parcela_id', id_parcela), {
            method: "POST",
            data: {"_token": "{{ csrf_token() }}"},
            beforeSend: () => {
              $('.loading').css('display', 'block');
              $('.loading').find('.class-loading').addClass('loader')
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
              $('#modalDescricao').modal('hide')
            },
            complete: () => {
              $('.loading').css('display', 'none');
              $('.loading').find('.class-loading').removeClass('loader')
            },
            error: function (response) {
              $('.loading').css('display', 'none');
              $('.loading').find('.class-loading').removeClass('loader')
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

      // console.log(formData.values())

    })

    $(".salvar_pagamento").on('click', function(e){
      e.preventDefault()
      var texto = "";
      var status = true;
      var agendamento_status = "{{$agendamento->status}}";
      if(retornaFormatoValor($(".pagamento").find('.diferenca_pagamento').val()) != 0){
        texto = 'Verifique a aba "Pagamentos" pois possui diferença de valores a receber ou deseja salvar mesmo assim ?';
      }else{
        texto = 'Deseja salvar o pagamento ?';
      }

      if(agendamento_status == "finalizado" || agendamento_status == "agendado" || agendamento_status == "finalizado_medico" ){
        status = false;
      }

      

      Swal.fire({
          title: "Confirmar!",
          text: texto,
          icon: "warning",
          showCancelButton: true,
          showDenyButton: status,
          confirmButtonColor: "#393ed9",
          cancelButtonText: "Cancelar",
          confirmButtonText: "Sim",
          denyButtonText: 'Sim e iniciar atendimento',
          denyButtonColor: '#c8a26f',
      }).then(function(result) {
          if(result.isConfirmed){
            salvarPagamento('salvar')
          }else if(result.isDenied){
            salvarPagamento('status')
          }
      })
      // console.log(formData.values())

    })

    function salvarPagamento(tipo){ ////VOLTAR AQUI ###################################################### #####################////
      $("#carteirinha_id_pagamento").val($("#carteirinha_id option:selected").val())
      $("#tipo_guia_pagamento").val($("#tipo_guia").val())
      $("#num_guia_convenio_pagamento").val($("#num_guia_convenio").val())
      $("#cod_aut_convenio_pagamento").val($("#cod_aut_convenio").val())
      var id_agendamento = $("#agendamento_descricao").val()
      var formData = new FormData($("#formPagamento")[0]);
      var url = "";

      // console.log($('#cod_aut_convenio').val())
      // if(exige_carteirinha && tipo == "status"){
      //     Swal.fire({
      //         title: "Selecione carteirinha!",
      //         text: 'O convênio exige uma carteirinha selecionada e o codigo de autorização preenchido!',
      //         icon: "error",
      //     })
      // }else{
        if(tipo == "salvar"){
          url = "{{ route('instituicao.agendamentos.salvarPagamento', ['agendamento' => 'agendamento_id']) }}".replace('agendamento_id', id_agendamento);
        }else{
          url = "{{ route('instituicao.agendamentos.salvarPagamentoStatus', ['agendamento' => 'agendamento_id']) }}".replace('agendamento_id', id_agendamento)
        }

        // if(contaReceberCriada == 1){
        //   callIniciarAtendimento("{{$agendamento->id}}")
        // }else{

          $.ajax(url, {
              method: "POST",
              data: formData,
              processData: false,
              contentType: false,
              beforeSend: () => {
                  $('.loading').css('display', 'block');
                  $('.loading').find('.class-loading').addClass('loader')
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
                  $('#modalDescricao').modal('hide')
              },
              complete: () => {
                  $('.loading').css('display', 'none');
                  $('.loading').find('.class-loading').removeClass('loader')
                  callRenderPage();
              },
              error: function (response) {
                  $('.loading').css('display', 'none');
                  $('.loading').find('.class-loading').removeClass('loader')
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
        // }

      // }
    }

    $(".mytooltip").tooltip();

  $('.convenio_procedimentos_descricao').on('click', '.add-convenio', function(){
      addConvenio();
  });

  $('.telefone').each(function(){
      $(this).setMask('(99) 99999-9999', {
          translation: { '9': { pattern: /[0-9]/, optional: false} }
      })
  });

  $(".selectfild2_convenio").select2();
  $(".valor_mask").setMask();

  function addConvenio(){
      quantidade_convenio++;

      $($('#item-convenio').html()).insertBefore(".add-class");

      $('.mask_item').setMask();
      $('.mask_item').removeClass('mask_item');
      $(".selectfild2").select2();

      $("[name^='convenio[#]']").each(function(index, element) {
          const name = $(element).attr('name');

          $(element).attr('name', name.replace('#',quantidade_convenio));
      })

      setTimeout(() => {
        $(".desc_juros_multa").setMask();
        $(".porcentagem_desconto").setMask();
        $(".desc_juros_multa").css('text-align', 'left');
      }, 500);
  }

  $('.convenio_procedimentos_descricao').on('click', '.item-convenio .remove-convenio', function(e){
      e.preventDefault()
      $(e.currentTarget).parents('.item-convenio').remove();
      totalProcedimentos()
      if ($('.convenio_procedimentos_descricao').find('.item-convenio').length == 0) {
          addConvenio();
      }

  });

  $('.tab-auditoria').on('click', function(e){
      e.preventDefault()

      var id = $("#agendamento_descricao").val()

      $.ajax({
        url: "{{route('instituicao.agendamentos.getAuditoria', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', id),
        type: "GET",
        data: {
          "_token": "{{ csrf_token() }}"
          },
        datatype: "json",
        processData: false,
        contentType: false,
        success: function(result) {
          if(result != null){
              $('#auditoria').html(result)
          }
        }
    });

  });

  function getProcedimentos(element){
    // verificaExigeCarteirinha()
    var id = $(element).val()
    var prestador_id =  $('#prestadores_id').val()

    $.ajax({
        url: "{{route('instituicao.agendamentos.getProcedimentos', ['convenio' => 'convenio_id', 'prestador' => 'prestador_id'])}}".replace('convenio_id', id).replace('prestador_id', prestador_id),
        type: "GET",
        data: {
          "_token": "{{ csrf_token() }}"
          },
        datatype: "json",
        processData: false,
        contentType: false,
        success: function(result) {
          if(result != null){
              procedimentos = result
              var options = $(element).parents(".item-convenio").find('.procedimentos');
              options.select2()
              $(element).parents(".item-convenio").find('.valor_procedimento').val(' ');
              $(element).parents(".item-convenio").find('.valor_procedimento').setMask();
              options.prop('disabled', false);
              options.find('option').filter(':not([value=""])').remove();
              $.each(procedimentos, function (key, value) {
                          // $('<option').val(value.id).text(value.Nome).appendTo(options);
                  options.append('<option value='+value.instituicao_procedimentos_convenios[0].pivot.id+' data-valor='+value.instituicao_procedimentos_convenios[0].pivot.valor+' data-exige-qtd='+value.procedimento.exige_quantidade+' data-cobrar='+value.procedimento.n_cobrar_agendamento+'>'+value.procedimento.descricao+'</option>')
                  //options += '<option value="' + key + '">' + value + '</option>';
              });
          }
        }
    });

  }

  function getValorProcedimento(element){
    valor = $('option:selected', element).attr('data-valor');

    var quantidade = $('option:selected', element).attr('data-exige-qtd');
    var cobrar = $('option:selected', element).attr('data-cobrar');
    
    if(quantidade == 'false'){
      $(element).parents(".item-convenio").find('.exige_quantidade').css('display', 'none');
    }else{
      $(element).parents(".item-convenio").find('.exige_quantidade').css('display', 'block');
    }

    if(cobrar == 'true'){
      valor = 0
    }

    $(element).parents(".item-convenio").find('.qtd_procedimento').val(1);
    $(element).parents(".item-convenio").find('.valor_procedimento').val(valor);
    $(element).parents(".item-convenio").find('.valor_procedimento').attr('data-valor', valor);
    $(element).parents(".item-convenio").find('.valor_procedimento').setMask();

    // totalProcedimentos()
    calculaValorNovoDescricao(element)

  }

  function getNovoValorDescricao(element){

    var valor_procedimento = retornaFormatoValor($(element).parents(".item-convenio").find('.procedimentos option:selected').attr('data-valor'))
    var quantidade_procedimento = $(element).val();

    var valor_novo = quantidade_procedimento * valor_procedimento;
    
    $(element).parents(".item-convenio").find('.valor_procedimento').val(valor_novo);
    $(element).parents(".item-convenio").find('.valor_procedimento').setMask();
    // totalProcedimentos();
    calculaValorNovoDescricao(element);
  }

  function totalProcedimentos(){

    var total_procedimentos_descricao = 0;

    $(".valor_procedimento").each(function(index, element) {
        var valor_procedimento = retornaFormatoValor($(element).val())
        total_procedimentos_descricao = parseFloat(valor_procedimento) + parseFloat(total_procedimentos_descricao);
    })

    $("#total_procedimentos_descricao").val(total_procedimentos_descricao.toFixed(2))
    $("#total_procedimentos_descricao").setMask()
    $('.pagamento').find(".total_a_pagar_pagamento").val(total_procedimentos_descricao.toFixed(2))
    $('.pagamento').find(".total_a_pagar_pagamento").setMask()

  }

  function retornaFormatoValor(valor){
    var novo = valor;
    novo = novo.replace('.','')
    novo = novo.replace(',','.')
    return novo;
  }

  function valorAtual(){
    var total_procedimentos_descricao = 0;

    $(".valor_atual").each(function(index, element) {
        var valor_procedimento = retornaFormatoValor($(element).val())
        total_procedimentos_descricao = parseFloat(valor_procedimento) + parseFloat(total_procedimentos_descricao);
    })

    $("#total_procedimentos_descricao").val(total_procedimentos_descricao.toFixed(2))
    $("#total_procedimentos_descricao").setMask()
    $('.pagamento').find(".total_a_pagar_pagamento").val(total_procedimentos_descricao.toFixed(2))
    $('.pagamento').find(".total_a_pagar_pagamento").setMask()
  }

  function salvaObs(){
    // console.log($("#solicitante_agenda").find("optin:selected"));
    obs = $("#obs").val();
    id_agendamento = $("#agendameto_id_modalDescricao").val()
    acompanhante = $("#acompanhanteCheckEdicao").val();
    acompanhante_relacao = $("#acompanhante_relacao_edicao").val();
    acompanhante_nome = $("#acompanhante_nome_edicao").val();
    acompanhante_telefone = $("#acompanhante_telefone_edicao").val();
    acompanhante_cpf = $("#cpf_acompanhante_edicao").val();
    compromisso_id = $("#compromisso_id").val();
    var solicitante = $("#solicitante_agenda").val();

    // console.log( $("#teleatendimento"));

    if($('#teleatendimento').is(':checked')){
      teleatendimento = 1;
    }else{
      teleatendimento = null;
    }


    $.ajax("{{route('instituicao.agendamentos.salvaObs', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', id_agendamento), {
        method: 'POST',
        data: {
          '_token': '{{csrf_token()}}',
          'obs': obs,
          'acompanhante' : acompanhante,
          'acompanhante_relacao': acompanhante_relacao,
          'acompanhante_nome': acompanhante_nome,
          'acompanhante_telefone': acompanhante_telefone,
          'cpf_acompanhante': acompanhante_cpf,
          'compromisso_id': compromisso_id,
          'solicitante_id': solicitante,
          'teleatendimento': teleatendimento,
        },
        beforeSend: () => {
            $('.loading').css('display', 'block');
            $('.loading').find('.class-loading').addClass('loader')
        },
        success: function (result) {
          $.toast({
            heading: result.title,
            text: result.text,
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: result.icon,
            hideAfter: 3000,
            stack: 10
        });


        },
        complete: () => {
            $('.loading').css('display', 'none');
            $('.loading').find('.class-loading').removeClass('loader')
        }
    });

    // console.log("aqui", obs, agendamento_id)
  }

  $(".emitir_nfe").on('click', function(){
    var agendamento_id = $("#agendameto_id_modalDescricao").val();
    console.log(agendamento_id);

    $.ajax("{{route('instituicao.notasFiscais.emitirNfe')}}", {
      method: "POST",
      data: {
        "_token": "{{csrf_token()}}",
        "agendamento_id": agendamento_id,
      },
      beforeSend: () => {
          $('.loading').css('display', 'block');
          $('.loading').find('.class-loading').addClass('loader')
      },
      success: function (result) {
        $('#modalEmitirNota .modal-content').html('');
        $('#modalEmitirNota .modal-content').html(result);
        $("#modalDescricao").modal('hide');
        $("#modalEmitirNota").modal('show');
        
        // $.toast({
        //   heading: result.title,
        //   text: result.text,
        //   position: 'top-right',
        //   loaderBg: '#ff6849',
        //   icon: result.icon,
        //   hideAfter: 3000,
        //   stack: 10
        // })
      },
      complete: () => {
        $('.loading').css('display', 'none');
        $('.loading').find('.class-loading').removeClass('loader')
      }
    })
  })

  $(".guias_sancoop").on('click', '.editar-carteirinha', function(e){
    e.preventDefault();
    var id_agendamento = $("#agendamento_descricao").val();

    var formData = new FormData($('#formGuia')[0]);

    $.ajax("{{route('instituicao.agendamentos.editarCarteirinha', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', id_agendamento), {
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      beforeSend: () => {
        $('.loading').css('display', 'block');
        $('.loading').find('.class-loading').addClass('loader')
      },
      success: function (result) {
        $.toast({
          heading: result.title,
          text: result.text,
          position: 'top-right',
          loaderBg: '#ff6849',
          icon: result.icon,
          hideAfter: 3000,
          stack: 10
        })
      },
      complete: () => {
          $('.loading').css('display', 'none');
          $('.loading').find('.class-loading').removeClass('loader')
      }
    })
  })

  $('.emitir_boleto').on('click', function(){
    agendamento_id = $('#agendameto_id_modalDescricao').val();

    url = "{{route('instituicao.agendamentos.geraBoelto', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', agendamento_id);
    
    if($('#acompanhanteCheckEdicao').is(':checked')){
      Swal.fire({
          title: "Confirmar!",
          text: "Deseja gerar boleto em nome do acompanhate ou paciente?",
          icon: "warning",
          showCancelButton: true,
          showDenyButton: true,
          confirmButtonColor: "#393ed9",
          cancelButtonText: "Cancelar",
          confirmButtonText: "Acompanhante",
          denyButtonText: 'Paciente',
          denyButtonColor: '#c8a26f',
      }).then(function(result) { 
        if(result.isConfirmed){
          window.open(url+"/?acompanhante=1", '_blank')
        }else if(result.isDenied){
          window.open(url, '_blank')
        }
      })
    }else{
      window.open(url, '_blank')
    }

    

    // url = "{{route('instituicao.agendamentos.geraBoelto', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', agendamento_id);

    // 

    // $.ajax("{{route('instituicao.agendamentos.geraBoelto', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', agendamento_id), {
    //   method: "POST",
    //   data: {
    //     "_token": "{{csrf_token()}}",
    //     "agendamento_id": agendamento_id
    //   },
    //   beforeSend: () => {
    //       $('.loading').css('display', 'block');
    //       $('.loading').find('.class-loading').addClass('loader')
    //   },
    //   success: function (result) {
    //     // console.log(result.length);
    //     // if(result.length > 0){
    //     //   $.toast({
    //     //     heading: result.title,
    //     //     text: result.text,
    //     //     position: 'top-right',
    //     //     loaderBg: '#ff6849',
    //     //     icon: result.icon,
    //     //     hideAfter: 3000,
    //     //     stack: 10
    //     //   })
    //     // }
    //   },
    //   complete: () => {
    //       $('.loading').css('display', 'none');
    //       $('.loading').find('.class-loading').removeClass('loader')
    //   }
    // })
  });

  // function verificaExigeCarteirinha(){
  //   var count = 0;
  //   if($("#carteirinha_id option:selected").val() == "" || $("#cod_aut_convenio").val() == ""){
  //     $(".item-convenio").each(function(index, element){
  //       var exige = $("input[name='convenio["+index+"][convenio_agenda]']").attr('data-exige-carteirinha');
        
  //       if(exige == 1){
  //         count = 1;
  //         exige_carteirinha = true;
  //       }
  //     })
      
  //     if(count == 0){
  //       exige_carteirinha = false;
  //     }
  //   }else{
  //     exige_carteirinha = false;
  //   }
  // }

  function uploadGuiaConvenio(){
    var formData = new FormData($('#formArquivoGuia')[0]);
    var id_agendamento = $("#agendamento_descricao").val()

    console.log(formData, id_agendamento);

    $.ajax("{{route('instituicao.agendamentos.uploadGuia', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', id_agendamento), {
        type: 'POST',
        data: formData,
        success: function (result) {
            // alert(data)
        },
        cache: false,
        contentType: false,
        processData: false,
        xhr: function() {  // Custom XMLHttpRequest
          var myXhr = $.ajaxSettings.xhr();
          if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
              myXhr.upload.addEventListener('progress', function () {
                  /* faz alguma coisa durante o progresso do upload */
              }, false);
          }
          return myXhr;
        }
    });
  }

  function atendimentoAtualizar(result)
  {
    $('.atendimento-paciente').html(result)            
    $('.button_tooltip').tooltip()      
  }

  $('.tab-atendimento-paciente').on('click', function(){
        var pessoa_id = "{{$agendamento->pessoa_id}}";
        if($('.atendimento-paciente').hasClass('carregado')){
            return
        }else{

            $('.atendimento-paciente').addClass('carregado')

            $.ajax({
                url: "{{route('instituicao.atendimentos_paciente.lista', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', pessoa_id),
                type: 'GET',
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },

                success: function(result) {
                    
                  atendimentoAtualizar(result);
                    
                },

                complete: () => {
                  $('.loading').css('display', 'none');
                  $('.loading').find('.class-loading').removeClass('loader')
                }

            });
        }
    })

    $(".atendimento-paciente").on('click', '.nova_atendimento', function(){
        $(".novo_atendimento").css('display', 'block')
        $(".lista_atendimento").css('display', 'none')
        callCriarAtendimento();
    })

    $(".atendimento-paciente").on('click', '.cancelar_atendimento', function(){
        $(".novo_atendimento").css('display', 'none')
        $(".lista_atendimento").css('display', 'block')
    })

    function callCriarAtendimento(){
      var pessoa_id = "{{$agendamento->pessoa_id}}";
        $.ajax({
            url: "{{route('instituicao.atendimentos_paciente.create', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', pessoa_id),
            type: 'GET',
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
                $(".novo_atendimento").html('')
            },

            success: function(result) {
                
                $(".novo_atendimento").html(result)
                
            },

            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader')
            }

        });
    }

    $(".atendimento-paciente").on('submit', "#form_atendimento_criar", function(e){
        e.preventDefault()

        var formData = new FormData($(this)[0]);
        var pessoa_id = "{{$agendamento->pessoa_id}}";
        var agendamento_id = "{{$agendamento->id}}";
        
        $.ajax("{{route('instituicao.atendimentos_paciente.storeAgendamento', ['pessoa' => 'pessoa_id', 'agendamento' => 'agendamento_id'])}}".replace('pessoa_id', pessoa_id).replace('agendamento_id', agendamento_id), {
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $(".atendimento-paciente").html('')
                $(".atendimento-paciente").addClass('loader')
                $('.loading').css('display', 'block');
            },
            success: function (result) {
                atendimentoAtualizar(result);
                $('.loading').css('display', 'none');
                $.toast({
                    heading: 'Sucesso',
                    text: 'Atendimento cadastrado com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3000,
                    stack: 10
                })
            },
            complete: () => {
                $(".atendimento-paciente").removeClass('loader')
            }
        })
    })
    
    $(".atendimento-paciente").on('submit', "#form_atendimento_editar", function(e){
        e.preventDefault()

        var formData = new FormData($(this)[0]);
        var id = $("#id_atendimento_paciente").val()
        var pessoa_id = "{{$agendamento->pessoa_id}}";
        
        $.ajax("{{route('instituicao.atendimentos_paciente.updateAgendamento', ['pessoa' => 'pessoa_id', 'atendimento_paciente' => 'atendimento_paciente_id'])}}".replace('pessoa_id', pessoa_id).replace('atendimento_paciente_id', id), {
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $(".atendimento-paciente").html('')
                $(".atendimento-paciente").addClass('loader')
                $('.loading').css('display', 'block');
            },
            success: function (result) {
                atendimentoAtualizar(result);
                $('.loading').css('display', 'none');
                $.toast({
                    heading: 'Sucesso',
                    text: 'Atendimento editado com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3000,
                    stack: 10
                })
            },
            complete: () => {
                $(".atendimento-paciente").removeClass('loader')
                
                
                
            }
        })
    })

    $(".retornar_pendente").on('click', function(e){
        e.stopPropagation();
        var agendamento = $(this).attr('data-id')
        Swal.fire({
            title: "Retornar!",
            text: 'Deseja retornar o agendamento para pendente ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                
                $.ajax("{{route('instituicao.agendamentos.retornaPendente', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', agendamento), {
                method: "POST",
                data: {
                    '_token': '{{csrf_token()}}'
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (response) {
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Agendamento retornado para pendente',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 10
                    })

                    callRenderPage()
                    callRenderSemanalPesquisa(response.data);
                    $('#modalDescricao').modal('hide')
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
              })
            }
          })
      })

      $(".desistir").on('click', function(e){
        e.stopPropagation();
        var agendamento = $(this).attr('data-id')
        Swal.fire({
            title: "Desistência!",
            html: 'O paciente desistiu do atendimento?<br><small style="color: #FF0000">Esta ação ira cancelar o finaceiro deste agendamento.</small>',
            icon: "warning",
            input: 'select',
            inputOptions: {
              nao_pode_esperar: "Paciente não pôde esperar para ser atendido",
              precisou_sair: "Precisou sair do atendimento por motivos pessoais",
              outro: "Outros"
            },
            inputValidator: (value) => {
              return new Promise((resolve) => {
                if(value !== ''){
                  resolve()
                } else {
                  resolve('Você precisa escolher um motivo')
                }                
              })
            },
            inputPlaceholder: 'Motivo de desistência',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.isConfirmed){
                
              console.log(result)

                $.ajax("{{route('instituicao.agendamentos.setDesistencia', ['agendamento' => 'agendamento_id'])}}".replace('agendamento_id', agendamento), {
                method: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'motivo_desistencia': result.value,
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (response) {
                    console.log(response)
                    
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Agendamento retornado para pendente',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 10
                    })

                    callRenderPage()
                    callRenderSemanalPesquisa(response.data);
                    $('#modalDescricao').modal('hide')
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
              })
            }
          })
      })


      /* TELEATENDIMENTO */

      function copia_link_paciente()
      {
        // const input = document.querySelector('.link_paciente');
        // navigator.clipboard.writeText(input.value).then(() => {
        //   alert('Copied to Clipboard')
        // })
      }

      $(".termoConsentimento").on('click', function(){
        geraModalModeloTermo('termo_consentimento')
      })

      $(".folhaSala").on('click', function(){
        geraModalModeloTermo('folha_sala')
      })

</script>

<script type="text/template" id="item-convenio">
  <div class="col-md-12 item-convenio">
      <div class="row">
          @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
            </div>
          @endcan
          <div class="form-group dados_parcela @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-6 @else col-md-4 @endif">
              <label class="form-control-label">Convenio:</span></label>
              <select name="convenio[#][convenio_agenda]" class="form-control selectfild2" style="width: 100%" onchange="getProcedimentos(this)">
                  <option value="">Selecione um convenio</option>
                  @foreach ($convenios as $item)
                      <option value="{{$item->id}}" data-exige-carteirinha={{($item->carteirinha_obg) ? 1 : 0}}>{{$item->nome}}</option>
                  @endforeach
              </select>
          </div>
          <div class="form-group @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-6 @else col-md-4 @endif">
              <label class="form-control-label">Procedimento *</span></label>
              <select name="convenio[#][procedimento_agenda]" class="form-control selectfild2 procedimentos" onchange="getValorProcedimento(this)" disabled>
                <option value="">Selecione um procedimento</option>
              </select>
          </div>
          <div class="form-group @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-4 @else col-md-2 @endif exige_quantidade">
              <label class="form-control-label">Qtd *</span></label>
              <input type="number" class="form-control qtd_procedimento" name="convenio[#][qtd_procedimento]" value='1' onchange="getNovoValorDescricao(this)">
          </div>
          @if ($instituicao->desconto_por_procedimento_agenda)
            @can('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')
              <div class="desconto_descricao_input col-md-4 @if($errors->has("convenio.0.desconto")) has-danger @endif">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-control-label">Desconto (%)</span></label>
                        <div class="input-group">
                            <input type="text" alt="porcentagem" class="form-control porcentagem_desconto" name="convenio[#][porcentagem_desconto]" placeholder="0.00" value="0.00" onchange="calculaValorNovoDescricaoPorcento(this)">
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-control-label">Desconto R$</span></label>
                        <div class="input-group">
                            <input type="text" alt="signed-decimal" class="form-control desc_juros_multa" data-bts-button-up-class="btn btn-secondary btn-outline down-button" data-bts-button-down-class="btn btn-secondary btn-outline up-button" name="convenio[#][desconto]" placeholder="-0,00" value="-0,00" onchange="calculaValorNovoDescricaoReal(this)">
                            <div class="input-group-append " >
                                    <div class="group-vertical-button desconto-descricao-group">
                                        <button type="button" class="btn btn-xs btn-secondary desconto-descricao-touchspin-up">
                                                <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-secondary desconto-descricao-touchspin-down">
                                                <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                  
              </div>
            @endcan
          @endif
          @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
            <div class="form-group @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-4 @else col-md-2 @endif">
                <label class="form-control-label">Valor R$ *</span></label>
                <input type="text" alt="decimal" class="form-control mask_item valor_procedimento" name="convenio[#][valor]"  onchange="totalCC(this)">
            </div>
          @endcan
      </div>
  </div>
</script>
