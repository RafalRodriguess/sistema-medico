@csrf

<div class="row">

    <div class="form-group col-md-6 {{ $errors->has('descricao') ? 'has-danger' : '' }}">
        <label class="form-control-label" for="descricao">
            Descrição <span class="text-danger">*</span>
        </label>
        <input type="text" name="descricao" id="descricao"
            class="form-control {{ $errors->has('descricao') ? 'form-control-danger' : '' }}"
            value="{{ old('descricao') ?? ($atividades_medica->descricao ?? '') }}">
        @if ($errors->has('descricao'))
            <span class="text-danger">{{ $errors->first('descricao') }}</span>
        @endif
    </div>

    <div class="form-group col-md-2 {{ $errors->has('ordem_apresentacao') ? 'has-danger' : '' }}">
        <label class="form-control-label" for="ordem_apresentacao">
            Ordem Apres. <span class="text-danger">*</span>
        </label>
        <input type="number" name="ordem_apresentacao" id="ordem_apresentacao"
            class="form-control {{ $errors->has('ordem_apresentacao') ? 'form-control-danger' : '' }}"
            value="{{ old('ordem_apresentacao') ?? ($atividades_medica->ordem_apresentacao ?? '') }}">
        @if ($errors->has('ordem_apresentacao'))
            <span class="text-danger">{{ $errors->first('ordem_apresentacao') }}</span>
        @endif
    </div>

    <div class="form-group col-md-4 {{ $errors->has('tipo_funcao') ? 'has-danger' : '' }}">
        <label class="form-control-label" for="tipo_funcao">
            Tipo de função <span class="text-danger">*</span>
        </label>
        <select name="tipo_funcao" id="tipo_funcao"
            class="form-control {{ $errors->has('tipo_funcao') ? 'form-control-danger' : '' }}">
            <option value="">Selecione...</option>
            @foreach ($tipos as $tipo)
                <option value="{{ $tipo }}"
                    {{ old('tipo_funcao') == $tipo || $atividades_medica->tipo_funcao == $tipo ? 'selected' : '' }}>
                    {{ $tipo }}
                </option>
            @endforeach
        </select>
        @if ($errors->has('tipo_funcao'))
            <span class="text-danger">{{ $errors->first('tipo_funcao') }}</span>
        @endif
    </div>

</div>
