

<div class="col-sm-2 p-0 m-0">
    <div class="form-group mr-2 ml-2 @if($errors->has('carga_horaria_mensal')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Carga horária <span class="text-danger">*</span></label>
        <input type="number" name="carga_horaria_mensal"  value="{{ old('carga_horaria_mensal') }}"
            class="form-control field_personalidade_1 @if($errors->has('carga_horaria_mensal')) form-control-danger @endif">
        @if($errors->has('carga_horaria_mensal'))
            <small class="form-text text-danger">{{ $errors->first('carga_horaria_mensal') }}</small>
        @endif
    </div>
</div>

<div class="col-sm-4 p-0 m-0">
    <div class="form-group mr-2 ml-2 @if($errors->has('vinculos')) has-danger @endif">
        <label class="form-control-label p-0 m-0">Vínculo <span class="text-danger">*</span></label>
        <select name="vinculos[]" multiple style="width: 100%"
            class="form-control field_personalidade_1 multiplos-vinculos @if($errors->has('vinculos')) form-control-danger @endif">
            <?php $vinculos = App\Prestador::getVinculos(); ?>
            @foreach($vinculos as $vinculo)
                <option value="{{ $vinculo }}" @if (old('vinculos.0'))
                    @for ($i = 0; $i < count(old('vinculos')); $i++)
                        @if ($vinculo == old("vinculos.{$i}"))
                            selected
                        @endif
                    @endfor
                @endif>
                    {{ App\Prestador::getVinculoTexto($vinculo) }}
                </option>
            @endforeach
        </select>
        @if($errors->has('vinculos'))
            <small class="form-text text-danger">{{ $errors->first('vinculos') }}</small>
        @endif
    </div>
</div>


<div class="col-sm-12 p-0 m-0 collapse" id="pis_pasep_nir_proe">
    <div class="row col-sm-12 p-0 m-0">
        <div class="col-sm-3 p-0 m-0">
            <div class="form-group mr-2 ml-2 @if($errors->has('pis')) has-danger @endif">
                <label class="form-control-label p-0 m-0">PIS <span class="text-primary">*</span></label>
                <input type="text" name="pis"  value="{{ old('pis') }}"
                    class="form-control field_personalidade_1 pis_pasep_nir_proe @if($errors->has('pis')) form-control-danger @endif">
                @if($errors->has('pis'))
                    <div class="form-control-feedback">{{ $errors->first('pis') }}</div>
                @endif
            </div>
        </div>
        <div class="col-sm-3 p-0 m-0">
            <div class="form-group mr-2 ml-2 @if($errors->has('pasep')) has-danger @endif">
                <label class="form-control-label p-0 m-0">PASEP <span class="text-primary">*</span></label>
                <input type="text" name="pasep"  value="{{ old('pasep') }}"
                    class="form-control field_personalidade_1 pis_pasep_nir_proe @if($errors->has('pasep')) form-control-danger @endif">
                @if($errors->has('pasep'))
                    <div class="form-control-feedback">{{ $errors->first('pasep') }}</div>
                @endif
            </div>
        </div>
        <div class="col-sm-3 p-0 m-0">
            <div class="form-group mr-2 ml-2 @if($errors->has('nir')) has-danger @endif">
                <label class="form-control-label p-0 m-0">NIR <span class="text-primary">*</span></label>
                <input type="text" name="nir"  value="{{ old('nir') }}"
                    class="form-control field_personalidade_1 pis_pasep_nir_proe @if($errors->has('nir')) form-control-danger @endif">
                @if($errors->has('nir'))
                    <div class="form-control-feedback">{{ $errors->first('nir') }}</div>
                @endif
            </div>
        </div>
        <div class="col-sm-3 p-0 m-0">
            <div class="form-group mr-2 ml-2 @if($errors->has('proe')) has-danger @endif">
                <label class="form-control-label p-0 m-0">Proe <span class="text-primary">*</span></label>
                <input type="text" name="proe"  value="{{ old('proe') }}"
                    class="form-control field_personalidade_1 pis_pasep_nir_proe @if($errors->has('proe')) form-control-danger @endif">
                @if($errors->has('proe'))
                    <div class="form-control-feedback">{{ $errors->first('proe') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="col-sm-12 p-0 m-0 collapse" id="numero_cooperativa">
    <div class="row col-sm-12 p-0 m-0">
        <div class="col-sm-3 p-0 m-0">
            <div class="form-group mr-2 ml-2 @if($errors->has('numero_cooperativa')) has-danger @endif">
                <label class="form-control-label p-0 m-0">Número da Cooperativa <span class="text-danger">*</span></label>
                <input type="text" name="numero_cooperativa"  value="{{ old('numero_cooperativa') }}"
                    class="form-control field_personalidade_1 numero_cooperativa @if($errors->has('numero_cooperativa')) form-control-danger @endif">
                @if($errors->has('numero_cooperativa'))
                    <small class="form-text text-danger">{{ $errors->first('numero_cooperativa') }}</small>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="row col-sm-12 p-0 m-0">
    <div class="col-sm-3 p-0 m-0">
        <div class="form-group mr-2 ml-2 @if($errors->has('numero_cooperativa')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Atuação do Prestador <span class="text-danger">*</span></label>
            <select name="tipo" class="form-control field_personalidade_1 @if($errors->has('tipo')) form-control-danger @endif">
                <option disabled selected>Selecione</option>
                <?php $tipos = App\Prestador::getTipos(); ?>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo }}" @if(old('tipo')==$tipo) selected @endif>
                        {{ App\Prestador::getTipoTexto($tipo) }}
                    </option>
                @endforeach
            </select>
            @if($errors->has('tipo'))
                <small class="form-text text-danger">{{ $errors->first('tipo') }}</small>
            @endif
        </div>
    </div>
    <div class="col-sm-9 p-0 m-0 collapse" id="medico_fields_one">
        <div class="form-group col-sm-12 mr-2 ml-2 @if($errors->has('numero_cooperativa')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Especialidades Médica <span class="text-danger">*</span></label>
            <select class="form-control field_personalidade_1 multiplas-especialidades" name="especialidades[]" multiple
                style="width: 100%">
                <?php $especialidades = App\Prestador::getEspecialidades(); ?>
                @foreach ($especialidades as $especialidade)
                    <option value="{{ $especialidade }}"
                        @if(old('especialidades'))
                            @for ($i = 0; $i < count(old('especialidades')); $i++)
                                @if ($especialidade == old("especialidades.{$i}"))
                                    selected
                                @endif
                            @endfor
                        @endif
                    >{{ App\Prestador::getEspecialidadeTexto($especialidade) }}</option>
                @endforeach
            </select>
            @if($errors->has('especialidades'))
                <small class="form-text text-danger">{{ $errors->first('especialidades') }}</small>
            @endif
        </div>
    </div>
</div>


<div class="row col-sm-12 p-0 m-0">

    <div class="col-sm-12 p-2 m-0 collapse" id="medico_fields_two">
        <div class="row col-sm-12 p-0 m-0">
            <div class="col-sm-4 p-0 m-0">
                <label class="form-control-label p-0 m-0">Conselhos <span class="text-danger">*</span></label>
                <select class="form-control field_personalidade_1 conselhos_options" name="conselho_id">
                    <?php $tipos_conselhos = App\Prestador::getTiposConselhos(); ?>
                    <option selected disabled value="0">Conselhos</option>
                    @foreach ($tipos_conselhos as $tipo)
                        <option value="{{ $tipo }}" @if(old('conselho_id')=="$tipo") selected @endif>{{ App\Prestador::getTipoConselhoTexto($tipo) }}</option>
                    @endforeach
                </select>
                @if($errors->has('conselho_id'))
                    <small class="form-text text-danger">{{ $errors->first('conselho_id') }}</small>
                @endif
            </div>
            <div class="col-sm-8 p-4 m-0">
                <div class="card col-sm-12 p-2 m-0">
                    <div class="row p-0 m-0">
                        <div class="col-sm-6 p-0 m-0">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input medico-checkbox field_personalidade_1"
                                    id="anestesistaCheck" name="anestesista" value="1" @if(old('anestesista')=="1") checked @endif>
                                <label class="form-check-label" for="anestesistaCheck">Anestesista</label>
                            </div>
                        </div>
                        <div class="col-sm-6 p-0 m-0">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input medico-checkbox field_personalidade_1"
                                    id="auxiliarCheck" name="auxiliar" value="1" @if(old('auxiliar')=="1") checked @endif>
                                <label class="form-check-label" for="auxiliarCheck">Auxiliar</label>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>











<div class="row col-sm-12 p-0 m-0">
    <div class="col-sm-4 p-0 m-0">
        <div class="form-group mr-2 ml-2 @if($errors->has('nome_banco')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Banco <span class="text-danger">*</span></label>
            <input type="text" name="nome_banco" value="{{ old('nome_banco') }}"
                class="form-control field_personalidade_2 @if($errors->has('nome_banco')) form-control-danger @endif">
            @if($errors->has('nome_banco'))
                <small class="form-text text-danger">{{ $errors->first('nome_banco') }}</small>
            @endif
        </div>
    </div>
    <div class="col-sm-4 p-0 m-0">
        <div class="form-group mr-2 ml-2 @if($errors->has('agencia')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Agencia <span class="text-danger">*</span></label>
            <input type="text" name="agencia" value="{{ old('agencia') }}"
                class="form-control field_personalidade_2 @if($errors->has('agencia')) form-control-danger @endif">
            @if($errors->has('agencia'))
                <small class="form-text text-danger">{{ $errors->first('agencia') }}</small>
            @endif
        </div>
    </div>
    <div class="col-sm-4 p-0 m-0">
        <div class="form-group mr-2 ml-2 @if($errors->has('conta_bancaria')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Conta Bancaria <span class="text-danger">*</span></label>
            <input type="text" name="conta_bancaria" value="{{ old('conta_bancaria') }}"
                class="form-control field_personalidade_2 @if($errors->has('conta_bancaria')) form-control-danger @endif">
            @if($errors->has('conta_bancaria'))
                <small class="form-text text-danger">{{ $errors->first('conta_bancaria') }}</small>
            @endif
        </div>
    </div>
</div>










<div class="col-sm-6 p-4 m-0">
    <div class="form-check mr-2 ml-2">
        <input type="checkbox" class="form-check-input" name="ativo" value="1" @if(old('ativo')=="1") checked @endif id="ativoCheck">
        <label class="form-check-label" for="ativoCheck">Ativo</label>
    </div>
</div>
