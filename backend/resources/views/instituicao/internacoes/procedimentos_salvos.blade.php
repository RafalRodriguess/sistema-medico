
    @foreach($procedimentos as $key => $value)
        <div class="col-md-12 itens_procedimentos_row row">
            
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
            </div>
            <div class="form-group dados_parcela col-md-4">
                <label class="form-control-label">Convênio:</span></label>
                <input type="hidden" readonly name="itens[{{$key}}][convenio]" value="{{$value->convenios_id}}" />
                <input type="text" readonly readonly class="form-control item-convenio" value="{{$value->convenios->nome}}" />
            </div>
            <div class="form-group col-md-4">
                <label class="form-control-label">Procedimento</label>
                <input type="hidden" readonly name="itens[{{$key}}][procedimento]" value="{{$value->id}}" />
                <input type="text" readonly class="form-control " value="{{$value->procedimentoInstituicao->procedimento->descricao}}" />
            </div>
            
            <div class="form-group col-md-2 exige_quantidade">
                <label class="form-control-label">Qtd</span></label>
                <input type="text" readonly class="form-control qtd_procedimentor" name="itens[{{$key}}][qtd_procedimento]" value="{{$value->pivot->quantidade_procedimento}}" {{-- onchange="getNovoValor(this)"--}}> 
            </div>
            
            <div class="form-group col-md-2">
                <label class="form-control-label">Valor *</span></label>
                <input type="text" readonly alt="decimal" class="form-control valor_mask valor_procedimento" name="itens[{{$key}}][valor]" id="itens[{{$key}}][valor]" value="{{$value->pivot->valor}}" readonly>
            </div>
        </div>
 
    @endforeach