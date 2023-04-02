@php($itens = old('itens') ?: [])
@if (empty($itens))
    <div class="col-md-12 itens_procedimentos_row">
        <div class="row">
           <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
            </div>

            <div class="form-group col-md-4 @if($errors->has("itens.0.convenio")) has-danger @endif">
                <label class="form-control-label">Convenio:</span></label>
                <select name="itens[0][convenio]" class="form-control selectfild2 convenio @if($errors->has("itens.0.convenio")) form-control-danger @endif" style="width: 100%" onchange="getProcedimentos(this)">
                    <option value="">Selecione um convenio</option>
                    @foreach ($convenios as $item)
                        <option value="{{$item->id}}" @if (old("itens.0.convenio") == $item->id)
                            selected="selected"
                        @endif>{{$item->nome}}</option>
                    @endforeach
                </select>
                @if($errors->has("itens.0.convenio"))
                    <div class="form-control-feedback">{{ $errors->first("itens.0.convenio") }}</div>
                @endif
            </div>
            <div class="form-group col-md-4 @if($errors->has("itens.0.procedimento")) has-danger @endif">
                <label class="form-control-label">Procedimento * </label>
                <select name="itens[0][procedimento]" id="itens[0][procedimento]" class="form-control selectfild2 procedimentos" onchange="getValorProcedimento(this)" disabled style="width: 100%">
                    <option value="">Selecione um procedimento</option>
                </select>
                @if($errors->has("itens.0.procedimento"))
                    <div class="form-control-feedback">{{ $errors->first("itens.0.procedimento") }}</div>
                @endif
            </div>
            <div class="form-group col-md-2 exige_quantidade @if($errors->has("itens.0.qtd_procedimento")) has-danger @endif">
                <label class="form-control-label">Qtd</span></label>
                <input type="number" class="form-control qtd_procedimentor @if($errors->has("itens.0.qtd_procedimento")) form-control-danger @endif" name="itens[0][qtd_procedimento]" id="itens[0][qtd_procedimento]" value="{{old("itens.0.qtd_procedimento", 1)}}" onchange="getNovoValor(this)">
                @if($errors->has("itens.0.qtd_procedimento"))
                    <div class="form-control-feedback">{{ $errors->first("itens.0.qtd_procedimento") }}</div>
                @endif
            </div>
            <div class="form-group col-md-2 @if($errors->has("itens.0.valor")) has-danger @endif">
                <label class="form-control-label">Valor R$ *</span></label>
                <input type="text" alt="decimal" class="form-control valor_mask valor_procedimento @if($errors->has("itens.0.valor")) form-control-danger @endif" name="itens[0][valor]" id="itens[0][valor]" value="{{old("itens.0.valor")}}" readonly>
                @if($errors->has("itens.0.valor"))
                    <div class="form-control-feedback">{{ $errors->first("itens.0.valor") }}</div>
                @endif
            </div>
        </div>
    </div>

@else

    @for($i = 0, $max = count($itens); $i < $max; $i++)
        <div class="col-md-12 itens_procedimentos_row">
            <div class="row">                
                <div class="col-md-12">
                    <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
                </div>
                <div class="form-group dados_parcela col-md-4 @if($errors->has("itens.{$i}.convenio")) has-danger @endif">
                    <label class="form-control-label">Convênio:</span></label>
                    <select name="itens[{{$i}}][convenio]" class="form-control selectfild2 @if($errors->has("itens.{$i}.convenio")) form-control-danger @endif" style="width: 100%" onchange="getProcedimentos(this)">
                        <option value="">Selecione um Convênio</option>
                        @foreach ($convenios as $item)
                            <option value="{{$item->id}}" @if (old("itens.{$i}.convenio") == $item->id)
                                selected="selected"
                            @endif>{{$item->nome}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("itens.{$i}.convenio"))
                        <div class="form-control-feedback">{{ $errors->first("itens.{$i}.convenio") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-4 @if($errors->has("itens.{$i}.procedimento")) has-danger @endif">
                    <label class="form-control-label">Procedimento *</label>
                    <select name="itens[{{$i}}][procedimento]" id="itens[{{$i}}][procedimento]" class="form-control selectfild2 procedimentos" onchange="getValorProcedimento(this)" disabled style="width: 100%">
                        <option value="">Selecione um procedimento</option>
                    </select>
                    @if($errors->has("itens.{$i}.procedimento"))
                        <div class="form-control-feedback">{{ $errors->first("itens.{$i}.procedimento") }}</div>
                    @endif
                </div>
                <div class="form-group col-md-2 exige_quantidade @if($errors->has("itens.{$i}.qtd_procedimento")) has-danger @endif">
                    <label class="form-control-label">Qtd</span></label>
                    <input type="number" class="form-control qtd_procedimentor @if($errors->has("itens.{$i}.qtd_procedimento")) form-control-danger @endif" name="itens[{{$i}}][qtd_procedimento]" value="{{old("itens.{$i}.qtd_procedimento")}}" onchange="getNovoValor(this)">
                    @if($errors->has("itens.{$i}.qtd_procedimento"))
                        <div class="form-control-feedback">{{ $errors->first("itens.{$i}.qtd_procedimento") }}</div>
                    @endif
                </div>
                
                <div class="form-group col-md-2 @if($errors->has("itens.{$i}.valor")) has-danger @endif">
                    <label class="form-control-label">Valor *</span></label>
                    <input type="text" alt="decimal" class="form-control valor_mask valor_procedimento @if($errors->has("itens.{$i}.valor")) form-control-danger @endif" name="itens[{{$i}}][valor]" id="itens[{{$i}}][valor]" value="{{old("itens.{$i}.valor")}}" readonly>
                    @if($errors->has("itens.{$i}.valor"))
                        <div class="form-control-feedback">{{ $errors->first("itens.{$i}.valor") }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endfor
@endif