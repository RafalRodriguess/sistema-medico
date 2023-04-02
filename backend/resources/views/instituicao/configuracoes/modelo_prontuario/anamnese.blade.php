<div class="row">
    <div class="form-group col-md-6">
        <label class="form-control-label">Queixa principal: </label>
        <textarea name="queixa_principal" class="form-control" cols="5" rows="4">@if (array_key_exists('queixa_principal', $modelo['prontuario'])){{$modelo['prontuario']['queixa_principal']}}@endif</textarea>
    </div>
    <div class="form-group col-md-6">
        <label class="form-control-label">H.M.A: </label>
        <textarea name="h_m_a" class="form-control" cols="5" rows="4">@if (array_key_exists('h_m_a', $modelo['prontuario'])){{$modelo['prontuario']['h_m_a']}}@endif</textarea>
    </div>
    <div class="form-group col-md-6">
        <label class="form-control-label">H.P: </label>
        <textarea name="h_p" class="form-control" cols="5" rows="4">@if (array_key_exists('h_p', $modelo['prontuario'])){{$modelo['prontuario']['h_p']}}@endif</textarea>
    </div>
    <div class="form-group col-md-6">
        <label class="form-control-label">H.F: </label>
        <textarea name="h_f" class="form-control" cols="5" rows="4">@if (array_key_exists('h_f', $modelo['prontuario'])){{$modelo['prontuario']['h_f']}}@endif</textarea>
    </div>
    <div class="form-group col-md-6">
        <label class="form-control-label">Hipótese diagnôstica: </label>
        <textarea name="hipotese_diagnostica" class="form-control" cols="5" rows="4">@if (array_key_exists('hipotese_diagnostica', $modelo['prontuario'])){{$modelo['prontuario']['hipotese_diagnostica']}}@endif</textarea>
    </div>
    <div class="form-group col-md-6">
        <label class="form-control-label">Exame fisico: </label>
        <textarea name="exame_fisico" class="form-control" cols="5" rows="4">@if (array_key_exists('exame_fisico', $modelo['prontuario'])){{$modelo['prontuario']['exame_fisico']}}@endif</textarea>
    </div>
    <div class="form-group col-md-6">
        <label class="form-control-label">Conduta: </label>
        <textarea name="conduta" class="form-control" cols="5" rows="4">@if (array_key_exists('conduta', $modelo['prontuario'])){{$modelo['prontuario']['conduta']}}@endif</textarea>
    </div>
    <div class="form-group col-md-6">
        <label class="form-control-label">Observações: </label>
        <textarea name="obs" class="form-control" cols="5" rows="4">@if (array_key_exists('obs', $modelo['prontuario'])){{$modelo['prontuario']['obs']}}@endif</textarea>
    </div>
    <div class="form-group col-md-12">
        <label class="form-control-label">CID:</label>
        <select class="form-control select2Cid" name="cid" style="width: 100%">
            <option value=""></option>
            @foreach ($cids as $item)            
                <option value="{{$item->id}}" @if (array_key_exists('cid', $modelo['prontuario']))
                    @if ($modelo['prontuario']['cid']['id'] == $item->id)
                        selected
                    @endif
                @endif>{{$item->descricao}}</option>
            @endforeach
        </select>
    </div>
</div>