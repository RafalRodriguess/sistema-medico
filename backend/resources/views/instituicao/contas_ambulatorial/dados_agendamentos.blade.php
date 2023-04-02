<div style="text-align: center"><h4>Registro Ambulatorial - Individual</h4></div>
<div class="row ">
    <div class="col-md-2 form-group @if($errors->has('agendamento_id')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Agendamento</label>
        <input type="text" name="agendamento_id" value="{{ old('agendamento_id', $agendamento->id) }}"
        class="form-control @if($errors->has('agendamento_id')) form-control-danger @endif" disabled>
        @if($errors->has('agendamento_id'))
            <small class="form-control-feedback">{{ $errors->first('agendamento_id') }}</small>
        @endif
    </div>
    <div class="col-md-5 form-group @if($errors->has('pessoa_id')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Paciente</label>
        <input type="text" name="pessoa_id" value="{{ old('pessoa_id', $agendamento->pessoa->nome) }}"
        class="form-control @if($errors->has('pessoa_id')) form-control-danger @endif" disabled>
        @if($errors->has('pessoa_id'))
            <small class="form-control-feedback">{{ $errors->first('pessoa_id') }}</small>
        @endif
    </div>
    <div class="col-md-5 form-group @if($errors->has('prestador_id')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Prestador</label>
        <input type="text" name="prestador_id" value="{{ old('prestador_id', $agendamento->instituicoesAgenda->prestadores->prestador->nome) }}"
        class="form-control @if($errors->has('prestador_id')) form-control-danger @endif" disabled>
        @if($errors->has('prestador_id'))
            <small class="form-control-feedback">{{ $errors->first('prestador_id') }}</small>
        @endif
    </div>
    <div class="col-md-3 form-group @if($errors->has('data')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Data/Hora atendimento</label>
        <input type="text" name="data" value="{{ old('data', date('d/m/Y H:i', strtotime($agendamento->data))) }}"
        class="form-control @if($errors->has('data')) form-control-danger @endif" disabled>
        @if($errors->has('data'))
            <small class="form-control-feedback">{{ $errors->first('data') }}</small>
        @endif
    </div>
    <div class="col-md-3 form-group @if($errors->has('convenio_id')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Convenio</label>
        <input type="text" name="convenio_id" value="{{ old('convenio_id', $agendamento->carteirinha->convenio[0]->nome) }}"
        class="form-control @if($errors->has('convenio_id')) form-control-danger @endif" disabled>
        @if($errors->has('convenio_id'))
            <small class="form-control-feedback">{{ $errors->first('convenio_id') }}</small>
        @endif
    </div>
    <div class="col-md-3 form-group @if($errors->has('plano_id')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Plano</label>
        <input type="text" name="plano_id" value="{{ old('plano_id', $agendamento->carteirinha->planoUnico->nome) }}"
        class="form-control @if($errors->has('plano_id')) form-control-danger @endif" disabled>
        @if($errors->has('plano_id'))
            <small class="form-control-feedback">{{ $errors->first('plano_id') }}</small>
        @endif
    </div>
    <div class="col-md-3 form-group @if($errors->has('regra_cobranca_id')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Regra</label>
        <input type="text" name="regra_cobranca_id" value="{{ old('regra_cobranca_id', $agendamento->carteirinha->planoUnico->regraCobranca->descricao) }}"
        class="form-control @if($errors->has('regra_cobranca_id')) form-control-danger @endif" disabled>
        @if($errors->has('regra_cobranca_id'))
            <small class="form-control-feedback">{{ $errors->first('regra_cobranca_id') }}</small>
        @endif
    </div>
</div>
<hr>
<div style="text-align: center"><h4>Itens do Registro Ambulatorial</h4></div>
<div class="itens_ambulatorial_class">
    @foreach ($procedimentos_atendimento->procedimento as $key => $item)    
        <div class="row">
            <div class="col-md-2 form-group @if($errors->has("proc.{$key}.grupo")) has-danger @endif">
                <label class="form-control-label p-0 m-0">Grupo</label>
                <input type="text" name="proc[{{$key}}][grupo]" value="{{ old("proc.{$key}.grupo", $item->procedimentoInstituicaoId->grupoProcedimento->nome) }}"
                class="form-control @if($errors->has("proc.{$key}.grupo")) form-control-danger @endif" readonly>
                @if($errors->has("proc.{$key}.grupo"))
                    <small class="form-control-feedback">{{ $errors->first("proc.{$key}.grupo") }}</small>
                @endif
            </div>
            <div class="col-md-2 form-group @if($errors->has("proc.{$key}.procedimento_id")) has-danger @endif">
                <label class="form-control-label p-0 m-0">Procedimento</label>
                <input type="text" name="proc[{{$key}}][procedimento_id]" value="{{ old("proc.{$key}.procedimento_id", $item->descricao) }}"
                class="form-control @if($errors->has("proc.{$key}.procedimento_id")) form-control-danger @endif" readonly>
                @if($errors->has("proc.{$key}.procedimento_id"))
                    <small class="form-control-feedback">{{ $errors->first("proc.{$key}.procedimento_id") }}</small>
                @endif
            </div>
            <div class="col-md-1 form-group @if($errors->has("proc.{$key}.quantidade")) has-danger @endif">
                <label class="form-control-label p-0 m-0">Qtd</label>
                <input type="number" name="proc[{{$key}}][quantidade]" value="{{ old("proc.{$key}.quantidade", $item->pivot->quantidade) }}"
                class="form-control @if($errors->has("proc.{$key}.quantidade")) form-control-danger @endif" readonly>
                @if($errors->has("proc.{$key}.quantidade"))
                    <small class="form-control-feedback">{{ $errors->first("proc.{$key}.quantidade") }}</small>
                @endif
            </div>
            <div class="col-md-1 form-group @if($errors->has("proc.{$key}.porcento_proc")) has-danger @endif">
                <label class="form-control-label p-0 m-0">% Proc.</label>
                <input type="text" alt="porcentagem" name="proc[{{$key}}][porcento_proc]" value="@if ($item->porcento_proc)
                    {{ old("proc.{$key}.porcento_proc", $item->porcento_proc) }}
                @else
                    {{ old("proc.{$key}.porcento_proc", 10000) }}
                @endif"
                class="form-control setMask @if($errors->has("proc.{$key}.porcento_proc")) form-control-danger @endif">
                @if($errors->has("proc.{$key}.porcento_proc"))
                    <small class="form-control-feedback">{{ $errors->first("proc.{$key}.porcento_proc") }}</small>
                @endif
            </div>
            <div class="col-md-1 form-group @if($errors->has("proc.{$key}.porcento_pago")) has-danger @endif">
                <label class="form-control-label p-0 m-0">$ Pago</label>
                <input type="text" alt="porcentagem" name="proc[{{$key}}][porcento_pago]" value="@if ($item->porcento_pago)
                    {{ old("proc.{$key}.porcento_pago", $item->porcento_pago) }}
                @else
                    {{ old("proc.{$key}.porcento_pago", 10000) }}
                @endif"
                class="form-control setMask @if($errors->has("proc.{$key}.porcento_pago")) form-control-danger @endif">
                @if($errors->has("proc.{$key}.porcento_pago"))
                    <small class="form-control-feedback">{{ $errors->first("proc.{$key}.porcento_pago") }}</small>
                @endif
            </div>
        </div>
    @endforeach
</div>

<script>
    $(document).ready(function(){
        $(".setMask").setMask()
    })
</script>
