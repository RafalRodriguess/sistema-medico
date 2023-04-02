<div class="">
    <div class="form-group col-md-12">
<h5>Procedimentos:</h5>
    </div>
</div>
@php($oldCc = old('cc') ?: [])
@if (empty($oldCc))
    <div class="col-md-12 item-convenio-agendar">
        <div class="row">
            @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
                </div>
            @endcan
            <div class="form-group dados_parcela @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-6 @else col-md-4 @endif @if($errors->has("convenio.0.convenio_agenda")) has-danger @endif">
                <label class="form-control-label">Convenio:</span></label>
                <select id="convenio_paciente_carteirinha" name="convenio[0][convenio_agenda]" class="form-control selectfild2_convenio @if($errors->has("convenio.0.convenio_agenda")) form-control-danger @endif" style="width: 100%" onchange="getProcedimentos(this)">
                    <option value="">Selecione um convenio</option>
                    @if (count($convenios) > 0)
                        @foreach ($convenios as $item)
                            <option value="{{$item->id}}" @if (old("convenio.0.convenio_agenda") == $item->id)
                                selected="selected"
                            @endif>{{$item->nome}}</option>
                        @endforeach
                    @endif
                </select>
                @if($errors->has("convenio.0.convenio_agenda"))
                    <div class="form-control-feedback">{{ $errors->first("convenio.0.convenio_agenda") }}</div>
                @endif
            </div>
            <div class="form-group @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-6 @else col-md-4 @endif @if($errors->has("convenio.0.procedimento_agenda")) has-danger @endif">
                <label class="form-control-label">Procedimento * <span class="mdi mdi-plus-circle-multiple-outline btnSelectProcedimentos" data-convenio="" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Selecionar varios procedimentos para o convenio selecionado"></span></label>
                <select name="convenio[0][procedimento_agenda]" id="convenio[0][procedimento_agenda]" class="form-control selectfild2_convenio procedimentos" onchange="getValorProcedimentoAgendar(this)" disabled style="width: 100%">
                    <option value="">Selecione um procedimento</option>
                </select>
                @if($errors->has("convenio.0.procedimento_agenda"))
                    <div class="form-control-feedback">{{ $errors->first("convenio.0.procedimento_agenda") }}</div>
                @endif
            </div>
            <div class="form-group @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-4 @else col-md-2 @endif exige_quantidade_agendar @if($errors->has("convenio.0.qtd_procedimento")) has-danger @endif">
                <label class="form-control-label">Qtd</span></label>
                <input type="number" class="form-control qtd_procedimento_agendar @if($errors->has("convenio.0.qtd_procedimento")) form-control-danger @endif" name="convenio[0][qtd_procedimento]" id="convenio[0][qtd_procedimento]" value="{{old("convenio.0.qtd_procedimento", 1)}}" onchange="getNovoValorAgendar(this)">
                @if($errors->has("convenio.0.qtd_procedimento"))
                    <div class="form-control-feedback">{{ $errors->first("convenio.0.qtd_procedimento") }}</div>
                @endif
            </div>
            @if ($instituicao->desconto_por_procedimento_agenda)    
                @can('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')
                    <div class="desconto_input col-md-4 @if($errors->has("convenio.0.desconto")) has-danger @endif">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">Desconto (%)</span></label>
                                <div class="input-group">
                                    <input type="text" alt="porcentagem" class="form-control porcentagem_desconto" name="convenio[0][porcentagem_desconto]" placeholder="0.00" value="0.00" onchange="calculaValorNovoPorcento(this)">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-control-label">Desconto R$</span></label>
                                <div class="input-group">
                                    <input type="text" alt="signed-decimal" class="form-control desc_juros_multa" data-bts-button-up-class="btn btn-secondary btn-outline down-button" data-bts-button-down-class="btn btn-secondary btn-outline up-button" name="convenio[0][desconto]" placeholder="-0,00" value="-0,00" onchange="calculaValorNovoReal(this)">
                                    <div class="input-group-append " >
                                        {{-- <span class="input-group-text">.00</span> style="display: block;" style="right: 0; height: 100%; z-index: 11;" --}}
                                            <div class="group-vertical-button desconto-group">
                                                <button type="button" class="btn btn-xs btn-secondary desconto-touchspin-up">
                                                        <i class="fas fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-secondary desconto-touchspin-down">
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
                <div class="form-group @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-4 @else col-md-2 @endif @if($errors->has("convenio.0.valor")) has-danger @endif">
                    <label class="form-control-label">Valor R$ *</span></label>
                    <input type="text" alt="decimal" class="form-control valor_mask valor_procedimento_agendar_inserir @if($errors->has("convenio.0.valor")) form-control-danger @endif" name="convenio[0][valor]" id="convenio[0][valor]" value="{{old("convenio.0.valor")}}" readonly>
                    @if($errors->has("convenio.0.valor"))
                        <div class="form-control-feedback">{{ $errors->first("convenio.0.valor") }}</div>
                    @endif
                </div>
            @endcan
        </div>
    </div>

@else

    @for($i = 0, $max = count($oldCc); $i < $max; $i++)
        <div class="col-md-12 item-convenio-agendar">
            {{-- <div class="row"> --}}
                @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
                    <div class="col-md-12">
                        <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
                    </div>
                @endcan
                <div class="form-group dados_parcela col-md-6 @if($errors->has("convenio.{$i}.convenio_agenda")) has-danger @endif">
                    <label class="form-control-label">Convênio:</span></label>
                    <select name="convenio[{{$i}}][convenio_agenda]" class="form-control selectfild2_convenio @if($errors->has("convenio.{$i}.convenio_agenda")) form-control-danger @endif" style="width: 100%" onchange="getProcedimentos(this)">
                        <option value="">Selecione um Convênio</option>
                        @foreach ($convenios as $item)
                            <option value="{{$item->id}}" @if (old("convenio.{$i}.convenio_agenda") == $item->id)
                                selected="selected"
                            @endif>{{$item->nome}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("convenio.{$i}.convenio_agenda"))
                        <div class="form-control-feedback">{{ $errors->first("convenio.{$i}.convenio_agenda") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6 @if($errors->has("convenio.{$i}.procedimento_agenda")) has-danger @endif">
                    <label class="form-control-label">Procedimento * <span class="mdi mdi-plus-circle-multiple-outline btnSelectProcedimentos" data-convenio="" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Selecionar varios procedimentos para o convenio selecionado"></span></label>
                    <select name="convenio[{{$i}}][procedimento_agenda]" id="convenio[{{$i}}][procedimento_agenda]" class="form-control selectfild2_convenio procedimentos" onchange="getValorProcedimentoAgendar(this)" disabled style="width: 100%">
                        <option value="">Selecione um procedimento</option>
                    </select>
                    @if($errors->has("convenio.{$i}.procedimento_agenda"))
                        <div class="form-control-feedback">{{ $errors->first("convenio.{$i}.procedimento_agenda") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4 exige_quantidade_agendar @if($errors->has("convenio.{$i}.qtd_procedimento")) has-danger @endif">
                    <label class="form-control-label">Qtd</span></label>
                    <input type="number" class="form-control qtd_procedimento_agendar @if($errors->has("convenio.{$i}.qtd_procedimento")) form-control-danger @endif" name="convenio[{{$i}}][qtd_procedimento]" value="{{old("convenio.{$i}.qtd_procedimento")}}" onchange="getNovoValorAgendar(this)">
                    @if($errors->has("convenio.{$i}.qtd_procedimento"))
                        <div class="form-control-feedback">{{ $errors->first("convenio.{$i}.qtd_procedimento") }}</div>
                    @endif
                </div>
                @can('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')
                    <div class="form-group desconto_input col-md-4 @if($errors->has("convenio.{$i}.desconto")) has-danger @endif">
                        <label class="form-control-label">Desconto R$ *</span></label>
                        <div class="input-group">
                            <input type="text" alt="signed-decimal" class="form-control desc_juros_multa " data-bts-button-up-class="btn btn-secondary btn-outline down-button" data-bts-button-down-class="btn btn-secondary btn-outline up-button" name="convenio[{{$i}}][desconto]" placeholder="-0,00" value="{{(old("convenio.{$i}.desconto")) ? old("convenio.{$i}.desconto") : '-0,00'}}" onchange="calculaValorNovo(this)">
                            <div class="input-group-append " >
                                {{-- <span class="input-group-text">.00</span> style="display: block;" style="right: 0; height: 100%; z-index: 11;" --}}
                                    <div class="group-vertical-button desconto-group">
                                        <button type="button" class="btn btn-xs btn-secondary desconto-touchspin-up">
                                                <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-secondary desconto-touchspin-down">
                                                <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
                    <div class="form-group col-md-4 @if($errors->has("convenio.{$i}.valor")) has-danger @endif">
                        <label class="form-control-label">Valor *</span></label>
                        <input type="text" alt="decimal" class="form-control valor_mask valor_procedimento_agendar_inserir @if($errors->has("convenio.{$i}.valor")) form-control-danger @endif" name="convenio[{{$i}}][valor]" id="convenio[{{$i}}][valor]" value="{{old("convenio.{$i}.valor")}}" readonly>
                        @if($errors->has("convenio.{$i}.valor"))
                            <div class="form-control-feedback">{{ $errors->first("convenio.{$i}.valor") }}</div>
                        @endif
                    </div>
                @endcan
            {{-- </div> --}}
        </div>
    @endfor
@endif