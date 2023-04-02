
@php($oldCc = old('cc') ?: [])
@if (empty($oldCc))
    @foreach ($agendamento->agendamentoProcedimento as $index => $agendamentoProcedimento)
        <div class="col-md-12 item-convenio">
            <div class="row">
                
                @if (\Gate::check('habilidade_instituicao_sessao', 'remover_procedimento_agendamento_momento'))
                    {{-- @can('habilidade_instituicao_sessao', 'remover_procedimento_agendamento_momento') --}}
                        <div class="col-md-12">
                            <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
                        </div>
                    {{-- @endcan --}}
                @else
                    @if ($agendamento->status!='finalizado') 
                        @if ($agendamento->status!='agendado')
                            @if ($agendamento->status!='finalizado_medico') 
                                @if ($agendamento->status!='em_atendimento')
                                    <div class="col-md-12">
                                        <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
                                    </div>
                                @endif
                            @endif
                        @endif
                    @endif
                @endif
                <div style="display: flex;">
                    <div class="procedimento">
                        <div class="row col-md-12">
                            <div class="@if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-6 @else col-md-4 @endif">
                                <div class="form-group">
                                <label>Convênio</label>
                                <input type="text" name="convenio[{{$index}}][convenio_agenda]" data-exige-carteirinha={{($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->convenios->carteirinha_obg) ? 1 : 0}} class="form-control" readonly=""  value="{{strtoupper($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->convenios->nome)}}">
                                </div>
                            </div>
                            <div class="@if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-6 @else col-md-4 @endif">
                                <div class="form-group">
                                <label>Procedimento</label>
                                <input type="hidden" name="convenio[{{$index}}][procedimento_agenda]" class="form-control" readonly="" value="{{strtoupper($agendamentoProcedimento->procedimentos_instituicoes_convenios_id)}}">
                                <input type="text" class="form-control" readonly="" value="{{strtoupper($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao)}}">
                                </div>
                            </div>
                            
                            <div class="@if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-4 @else col-md-2 @endif" @if ($agendamentoProcedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->exige_quantidade == false) style="display: none" @endif>
                                <div class="form-group">
                                <label>
                                    Qtd
                                </label>
                                <input type="number" name="convenio[{{$index}}][qtd_procedimento]" class="form-control qtd_procedimento" readonly="" alt="decimal" value="{{$agendamentoProcedimento->qtd_procedimento}}" style="text-align: right;">
                                </div>
                            </div>
                            @if ($instituicao->desconto_por_procedimento_agenda)    
                                @can('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')
                                    <div class="form-group desconto_input col-md-4 @if($errors->has("convenio.0.desconto")) has-danger @endif">
                                        <label class="form-control-label">Desconto R$ *</span></label>
                                        <div class="input-group">
                                            <input type="text" alt="signed-decimal" class="form-control desc_juros_multa " data-bts-button-up-class="btn btn-secondary btn-outline down-button" data-bts-button-down-class="btn btn-secondary btn-outline up-button" name="convenio[0][desconto]" placeholder="-0,00" value="{{integerParaReal($agendamentoProcedimento->desconto)}}" readonly="">
                                        </div>
                                    </div>
                                @endcan
                            @endif
                            
                            @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
                                <div class="@if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-4 @else col-md-2 @endif">
                                    <div class="form-group">
                                    <label>
                                        Valor R$
                                    </label>
                                    <input type="text" name="convenio[{{$index}}][valor]" class="form-control valor_atual valor_procedimento" readonly="" alt="decimal" value="{{integerParaReal($agendamentoProcedimento->valor_atual)}}" style="text-align: right;">
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
    
                    {{-- @if ($agendamento->forma_pagamento == 'cartao_credito')
                      <div class="form-group estornos" style="padding-left: 10px;text-align:center;display:none;">
                        @if(!$agendamentoProcedimento->estornado)
                        <label for=""> Estornar</label>
                        <input class="form-control" name="procedimentos[]" value="{{$agendamentoProcedimento->id}}" type="checkbox" />
                        @else
                        <label for=""> Estornado</label>
                        <div class="align-self-center round-primary"><i class="mdi mdi-check "></i></div>
                        @endif
                      </div>
                    @endif --}}
                </div>
            </div>
        </div>
    @endforeach

@else

    @for($i = 0, $max = count($oldCc); $i < $max; $i++)
        <div class="col-md-12 item-convenio">
            <div class="row">
                @can('habilidade_instituicao_sessao', 'remover_procedimento_agendamento_momento')
                    <div class="col-md-12">
                        <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
                    </div>
                @endcan
                <div class="form-group dados_parcela col-md-4 @if($errors->has("convenio.{$i}.convenio_agenda")) has-danger @endif">
                    <label class="form-control-label">Convenio:</span></label>
                    <select name="convenio[{{$i}}][convenio_agenda]" class="form-control selectfild2_convenio @if($errors->has("convenio.{$i}.convenio_agenda")) form-control-danger @endif" style="width: 100%" onchange="getProcedimentos(this)">
                        <option value="">Selecione um Convenio</option>
                        @foreach ($convenios as $item)
                            <option value="{{$item->id}}" data-exige-carteirinha={{($item->carteirinha_obg) ? 1 : 0}} @if (old("convenio.{$i}.convenio_agenda") == $item->id)
                                selected="selected"
                            @endif>{{$item->nome}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("convenio.{$i}.convenio_agenda"))
                        <div class="form-control-feedback">{{ $errors->first("convenio.{$i}.convenio_agenda") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4 @if($errors->has("convenio.{$i}.procedimento_agenda")) has-danger @endif">
                    <label class="form-control-label">Procedimento *</span></label>
                    <select name="convenio[{{$i}}][procedimento_agenda]" id="convenio[{{$i}}][procedimento_agenda]" class="form-control selectfild2_convenio procedimentos" onchange="getValorProcedimento(this)" disabled style="width: 100%">
                        <option value="">Selecione um procedimento</option>
                    </select>
                    @if($errors->has("convenio.{$i}.procedimento_agenda"))
                        <div class="form-control-feedback">{{ $errors->first("convenio.{$i}.procedimento_agenda") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-2 exige_quantidade @if($errors->has("convenio.{$i}.qtd_procedimento")) has-danger @endif">
                    <label class="form-control-label">Qtd</span></label>
                    <input type="number" class="form-control qtd_procedimento @if($errors->has("convenio.{$i}.qtd_procedimento")) form-control-danger @endif" name="convenio[{{$i}}][qtd_procedimento]" value="{{old("convenio.{$i}.qtd_procedimento")}}" onchange="getNovoValorDescricao(this)">
                    @if($errors->has("convenio.{$i}.qtd_procedimento"))
                        <div class="form-control-feedback">{{ $errors->first("convenio.{$i}.qtd_procedimento") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-2 @if($errors->has("convenio.{$i}.valor")) has-danger @endif">
                    <label class="form-control-label">Valor R$ *</span></label>
                    <input type="text" alt="decimal" class="form-control valor_mask valor_procedimento @if($errors->has("convenio.{$i}.valor")) form-control-danger @endif" name="convenio[{{$i}}][valor]" id="convenio[{{$i}}][valor]" value="{{old("convenio.{$i}.valor")}}" readonly>
                    @if($errors->has("convenio.{$i}.valor"))
                        <div class="form-control-feedback">{{ $errors->first("convenio.{$i}.valor") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endfor
@endif