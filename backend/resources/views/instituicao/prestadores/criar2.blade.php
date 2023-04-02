@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Prestador',
        'breadcrumb' => [
            'Prestador' => route('instituicao.prestadores.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">

        <div class="card-body">
            <form action="{{ route('instituicao.prestadores.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('personalidade')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Personalidade asd<span class="text-danger">*</span></label>
                                <select name="personalidade" id="personalidade"
                                    class="form-control @if($errors->has('personalidade')) form-control-danger @endif">
                                    <option selected disabled value="0">Personalidade</option>
                                    <option value="1" id="personalidade_1" @if(old('personalidade')=="1") selected @endif>Pessoa Física</option>
                                    <option value="2" id="personalidade_2" @if(old('personalidade')=="2") selected @endif>Pessoa Juridica</option>
                                </select>
                                @if($errors->has('personalidade'))
                                    <small class="form-text text-danger">{{ $errors->first('personalidade') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('cpf')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">CPF <span class="text-danger">*</span></label>
                                <input type="text" name="cpf" alt="cpf" value="{{ old('cpf') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('cpf')) form-control-danger @endif">
                                @if($errors->has('cpf'))
                                    <small class="form-text text-danger">{{ $errors->first('cpf') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('cnpj')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">CNPJ  <span class="text-danger">*</span></label>
                                <input type="text" name="cnpj" alt="cnpj" value="{{ old('cnpj') }}"
                                    class="form-control field_personalidade_2 @if($errors->has('cnpj')) form-control-danger @endif">
                                @if($errors->has('cnpj'))
                                    <small class="form-text text-danger">{{ $errors->first('cnpj') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group @if($errors->has('nome')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Nome <span class="text-danger">*</span></label>
                                <input type="text" name="nome" value="{{ old('nome') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('nome')) form-control-danger @endif">
                                @if($errors->has('nome'))
                                    <small class="form-text text-danger">{{ $errors->first('nome') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group @if($errors->has('razao_social')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Razão Social  <span class="text-danger">*</span></label>
                                <input type="text" name="razao_social" value="{{ old('razao_social') }}"
                                    class="form-control field_personalidade_2 @if($errors->has('razao_social')) form-control-danger @endif">
                                @if($errors->has('razao_social'))
                                    <small class="form-text text-danger">{{ $errors->first('razao_social') }}</small>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('identidade')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">RG <span class="text-danger">*</span></label>
                                <input type="text" name="identidade"  value="{{ old('identidade') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('identidade')) form-control-danger @endif">
                                @if($errors->has('identidade'))
                                    <small class="form-text text-danger">{{ $errors->first('identidade') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('identidade_orgao_expedidor')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Orgão Expedidor <span class="text-danger">*</span></label>
                                <input type="text" name="identidade_orgao_expedidor"  value="{{ old('identidade_orgao_expedidor') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('identidade_orgao_expedidor')) form-control-danger @endif">
                                @if($errors->has('identidade_orgao_expedidor'))
                                <small class="form-text text-danger">{{ $errors->first('identidade_orgao_expedidor') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('identidade_uf')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">UF <span class="text-danger">*</span></label>
                                <select class="form-control field_personalidade_1 @if($errors->has('identidade_uf')) form-control-danger @endif" name="identidade_uf">
                                    <option selected disabled value="0">Selecione</option>
                                    <option value="AC" @if (old('identidade_uf') == 'AC')
                                        selected="selected"
                                    @endif>Acre</option>
                                    <option value="AL" @if (old('identidade_uf') == 'AL')
                                        selected="selected"
                                    @endif>Alagoas</option>
                                    <option value="AP" @if (old('identidade_uf') == 'AP')
                                        selected="selected"
                                    @endif>Amapá</option>
                                    <option value="AM" @if (old('identidade_uf') == 'AM')
                                        selected="selected"
                                    @endif>Amazonas</option>
                                    <option value="BA" @if (old('identidade_uf') == 'BA')
                                        selected="selected"
                                    @endif>Bahia</option>
                                    <option value="CE" @if (old('identidade_uf') == 'CE')
                                        selected="selected"
                                    @endif>Ceará</option>
                                    <option value="DF" @if (old('identidade_uf') == 'DF')
                                        selected="selected"
                                    @endif>Distrito Federal</option>
                                    <option value="GO" @if (old('identidade_uf') == 'GO')
                                        selected="selected"
                                    @endif>Goiás</option>
                                    <option value="ES" @if (old('identidade_uf') == 'ES')
                                        selected="selected"
                                    @endif>Espírito Santo</option>
                                    <option value="MA" @if (old('identidade_uf') == 'MA')
                                        selected="selected"
                                    @endif>Maranhão</option>
                                    <option value="MT" @if (old('identidade_uf') == 'MT')
                                        selected="selected"
                                    @endif>Mato Grosso</option>
                                    <option value="MS" @if (old('identidade_uf') == 'MS')
                                        selected="selected"
                                    @endif>Mato Grosso do Sul</option>
                                    <option value="MG" @if (old('identidade_uf') == 'MG')
                                        selected="selected"
                                    @endif>Minas Gerais</option>
                                    <option value="PA" @if (old('identidade_uf') == 'PA')
                                        selected="selected"
                                    @endif>Pará</option>
                                    <option value="PB" @if (old('identidade_uf') == 'PB')
                                        selected="selected"
                                    @endif>Paraiba</option>
                                    <option value="PR" @if (old('identidade_uf') == 'PR')
                                        selected="selected"
                                    @endif>Paraná</option>
                                    <option value="PE" @if (old('identidade_uf') == 'PE')
                                        selected="selected"
                                    @endif>Pernambuco</option>
                                    <option value="PI" @if (old('identidade_uf') == 'PI')
                                        selected="selected"
                                    @endif>Piauí­</option>
                                    <option value="RJ" @if (old('identidade_uf') == 'RJ')
                                        selected="selected"
                                    @endif>Rio de Janeiro</option>
                                    <option value="RN" @if (old('identidade_uf') == 'RN')
                                        selected="selected"
                                    @endif>Rio Grande do Norte</option>
                                    <option value="RS" @if (old('identidade_uf') == 'RS')
                                        selected="selected"
                                    @endif>Rio Grande do Sul</option>
                                    <option value="RO" @if (old('identidade_uf') == 'RO')
                                        selected="selected"
                                    @endif>Rondônia</option>
                                    <option value="RR" @if (old('identidade_uf') == 'RR')
                                        selected="selected"
                                    @endif>Roraima</option>
                                    <option value="SP" @if (old('identidade_uf') == 'SP')
                                        selected="selected"
                                    @endif>São Paulo</option>
                                    <option value="SC" @if (old('identidade_uf') == 'SC')
                                        selected="selected"
                                    @endif>Santa Catarina</option>
                                    <option value="SE" @if (old('identidade_uf') == 'SE')
                                        selected="selected"
                                    @endif>Sergipe</option>
                                    <option value="TO" @if (old('identidade_uf') == 'TO')
                                        selected="selected"
                                    @endif>Tocantins</option>
                                </select>
                                @if($errors->has('identidade_uf'))
                                    <small class="form-text text-danger">{{ $errors->first('identidade_uf') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('identidade_data_expedicao')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Data de Expedição <span class="text-danger">*</span></label>
                                <input type="date" name="identidade_data_expedicao" alt="date" value="{{ old('identidade_data_expedicao') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('identidade_data_expedicao')) form-control-danger @endif">
                                @if($errors->has('identidade_data_expedicao'))
                                    <small class="form-text text-danger">{{ $errors->first('identidade_data_expedicao') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('sexo')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Sexo <span class="text-danger">*</span></label>
                                <select name="sexo" id="sexo"
                                    class="form-control field_personalidade_1 @if($errors->has('sexo')) form-control-danger @endif">
                                    <option selected disabled value="0">Sexo</option>
                                    <option value="1" @if(old('sexo')=="1") selected @endif>Homem</option>
                                    <option value="2" @if(old('sexo')=="2") selected @endif>Mulher</option>
                                </select>
                                @if($errors->has('sexo'))
                                    <small class="form-text text-danger">{{ $errors->first('sexo') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('nascimento')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Data de Nascimento <span class="text-danger">*</span></label>
                                <input type="date" name="nascimento" alt="date" id="date" value="{{ old('nascimento') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('nascimento')) form-control-danger @endif">
                                @if($errors->has('nascimento'))
                                    <small class="form-text text-danger">{{ $errors->first('nascimento') }}</small>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group @if($errors->has('nome_da_mae')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Nome da Mãe <span class="text-danger">*</span></label>
                                <input type="text" name="nome_da_mae"  value="{{ old('nome_da_mae') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('nome_da_mae')) form-control-danger @endif">
                                @if($errors->has('nome_da_mae'))
                                    <small class="form-text text-danger">{{ $errors->first('nome_da_mae') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group @if($errors->has('nome_do_pai')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Nome do Pai <span class="text-danger">*</span></label>
                                <input type="text" name="nome_do_pai"  value="{{ old('nome_do_pai') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('nome_do_pai')) form-control-danger @endif">
                                @if($errors->has('nome_do_pai'))
                                    <small class="form-text text-danger">{{ $errors->first('nome_do_pai') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('nacionalidade')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Nacionalidade <span class="text-danger">*</span></label>
                                <input type="text" name="nacionalidade"  value="{{ old('nacionalidade') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('nacionalidade')) form-control-danger @endif">
                                @if($errors->has('nacionalidade'))
                                    <small class="form-text text-danger">{{ $errors->first('nacionalidade') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('naturalidade')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Naturalidade <span class="text-danger">*</span></label>
                                <input type="text" name="naturalidade"  value="{{ old('naturalidade') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('naturalidade')) form-control-danger @endif">
                                @if($errors->has('naturalidade'))
                                    <small class="form-text text-danger">{{ $errors->first('naturalidade') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('numero_cartao_sus')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Cartão SUS <span class="text-success">*</span></label>
                                <input type="text" name="numero_cartao_sus"  value="{{ old('numero_cartao_sus') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('numero_cartao_sus')) form-control-danger @endif">
                                @if($errors->has('numero_cartao_sus'))
                                    <small class="form-text text-danger">{{ $errors->first('numero_cartao_sus') }}</small>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('carga_horaria_mensal')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Carga Horária <span class="text-danger">*</span></label>
                                <input type="number" name="carga_horaria_mensal"  value="{{ old('carga_horaria_mensal') }}"
                                    class="form-control field_personalidade_1 @if($errors->has('carga_horaria_mensal')) form-control-danger @endif">
                                @if($errors->has('carga_horaria_mensal'))
                                    <small class="form-text text-danger">{{ $errors->first('carga_horaria_mensal') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group @if($errors->has('numero_cooperativa')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Atuação do Prestador <span class="text-danger">*</span></label>
                                <select name="tipo" class="form-control field_personalidade_1 @if($errors->has('tipo')) form-control-danger @endif">
                                    <option disabled selected>Selecione</option>
                                    <?php $tipos = App\InstituicoesPrestadores::getTipos(); ?>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo }}" @if(old('tipo')==$tipo) selected @endif>
                                            {{ App\InstituicoesPrestadores::getTipoTexto($tipo) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('tipo'))
                                    <small class="form-text text-danger">{{ $errors->first('tipo') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="form-group @if($errors->has('vinculos')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Vínculos <span class="text-danger">*</span></label>
                                <select name="vinculos[]" multiple style="width: 100%"
                                    class="form-control field_personalidade_1 multiplos-vinculos @if($errors->has('vinculos')) form-control-danger @endif">
                                    <?php $vinculos = App\InstituicoesPrestadores::getVinculos(); ?>
                                    @foreach($vinculos as $vinculo)
                                        <option value="{{ $vinculo }}" @if (old('vinculos.0'))
                                            @for ($i = 0; $i < count(old('vinculos')); $i++)
                                                @if ($vinculo == old("vinculos.{$i}"))
                                                    selected
                                                @endif
                                            @endfor
                                        @endif>
                                            {{ App\InstituicoesPrestadores::getVinculoTexto($vinculo) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('vinculos'))
                                    <small class="form-text text-danger">{{ $errors->first('vinculos') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('numero_cooperativa')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Cooperativa <span class="text-primary">*</span></label>
                                <input type="text" name="numero_cooperativa"  value="{{ old('numero_cooperativa') }}"
                                    class="form-control field_personalidade_1 numero_cooperativa @if($errors->has('numero_cooperativa')) form-control-danger @endif">
                                @if($errors->has('numero_cooperativa'))
                                    <small class="form-text text-danger">{{ $errors->first('numero_cooperativa') }}</small>
                                @endif
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-sm-3">
                            <div class="form-group @if($errors->has('pis')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">PIS <span class="text-primary">*</span></label>
                                <input type="text" name="pis"  value="{{ old('pis') }}"
                                    class="form-control field_personalidade_1 pis_pasep_nir_proe @if($errors->has('pis')) form-control-danger @endif">
                                @if($errors->has('pis'))
                                    <div class="form-control-feedback">{{ $errors->first('pis') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group @if($errors->has('pasep')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">PASEP <span class="text-primary">*</span></label>
                                <input type="text" name="pasep"  value="{{ old('pasep') }}"
                                    class="form-control field_personalidade_1 pis_pasep_nir_proe @if($errors->has('pasep')) form-control-danger @endif">
                                @if($errors->has('pasep'))
                                    <div class="form-control-feedback">{{ $errors->first('pasep') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group @if($errors->has('nir')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">NIR <span class="text-primary">*</span></label>
                                <input type="text" name="nir"  value="{{ old('nir') }}"
                                    class="form-control field_personalidade_1 pis_pasep_nir_proe @if($errors->has('nir')) form-control-danger @endif">
                                @if($errors->has('nir'))
                                    <div class="form-control-feedback">{{ $errors->first('nir') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group @if($errors->has('proe')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Proe <span class="text-primary">*</span></label>
                                <input type="text" name="proe"  value="{{ old('proe') }}"
                                    class="form-control field_personalidade_1 pis_pasep_nir_proe @if($errors->has('proe')) form-control-danger @endif">
                                @if($errors->has('proe'))
                                    <div class="form-control-feedback">{{ $errors->first('proe') }}</div>
                                @endif
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group @if($errors->has('numero_cooperativa')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Especialidades Médica <span class="text-danger">*</span></label>
                                <select class="form-control multiplas-especialidades" name="especialidades[]" multiple
                                    style="width: 100%">
                                    @foreach ($especialidades as $especialidade)
                                        <option value="{{ $especialidade->id }}"
                                            @if(old('especialidades'))
                                                @for ($i = 0; $i < count(old('especialidades')); $i++)
                                                    @if ($especialidade == old("especialidades.{$i}"))
                                                        selected
                                                    @endif
                                                @endfor
                                            @endif
                                        >{{ $especialidade->nome }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('especialidades'))
                                    <small class="form-text text-danger">{{ $errors->first('especialidades') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('tipo_conselho_id')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Conselhos <span class="text-danger">*</span></label>
                                <select class="form-control" name="tipo_conselho_id">
                                    <?php $tipos_conselhos = App\InstituicoesPrestadores::getTiposConselhos(); ?>
                                    <option selected disabled value="0">Selecione</option>
                                    @foreach ($tipos_conselhos as $tipo)
                                        <option value="{{ $tipo }}" @if(old('tipo_conselho_id')==$tipo) selected @endif>{{ App\InstituicoesPrestadores::getTipoConselhoTexto($tipo) }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('tipo_conselho_id'))
                                    <small class="form-text text-danger">{{ $errors->first('tipo_conselho_id') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="card shadow-none">
                                <div class="row p-0 m-0">
                                    <div class="col-sm-6 p-3">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input medico-checkbox"
                                                id="anestesistaCheck" name="anestesista" value="1" @if(old('anestesista')=="1") checked @endif>
                                            <label class="form-check-label" for="anestesistaCheck">Anestesista</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 p-3">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input medico-checkbox"
                                                id="auxiliarCheck" name="auxiliar" value="1" @if(old('auxiliar')=="1") checked @endif>
                                            <label class="form-check-label" for="auxiliarCheck">Auxiliar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('cep')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">CEP <span class="text-danger">*</span></label>
                                <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep') }}"
                                    class="form-control  @if($errors->has('cep')) form-control-danger @endif">
                                @if($errors->has('cep'))
                                    <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('estado')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Estado <span class="text-danger">*</span></label>
                                <select id="estado" class="form-control field_personalidade_2 @if($errors->has('estado')) form-control-danger @endif" name="estado">
                                    <option selected disabled value="0">Selecione</option>
                                    <option value="AC" @if (old('estado') == 'AC')
                                        selected="selected"
                                    @endif>Acre</option>
                                    <option value="AL" @if (old('estado') == 'AL')
                                        selected="selected"
                                    @endif>Alagoas</option>
                                    <option value="AP" @if (old('estado') == 'AP')
                                        selected="selected"
                                    @endif>Amapá</option>
                                    <option value="AM" @if (old('estado') == 'AM')
                                        selected="selected"
                                    @endif>Amazonas</option>
                                    <option value="BA" @if (old('estado') == 'BA')
                                        selected="selected"
                                    @endif>Bahia</option>
                                    <option value="CE" @if (old('estado') == 'CE')
                                        selected="selected"
                                    @endif>Ceará</option>
                                    <option value="DF" @if (old('estado') == 'DF')
                                        selected="selected"
                                    @endif>Distrito Federal</option>
                                    <option value="GO" @if (old('estado') == 'GO')
                                        selected="selected"
                                    @endif>Goiás</option>
                                    <option value="ES" @if (old('estado') == 'ES')
                                        selected="selected"
                                    @endif>Espírito Santo</option>
                                    <option value="MA" @if (old('estado') == 'MA')
                                        selected="selected"
                                    @endif>Maranhão</option>
                                    <option value="MT" @if (old('estado') == 'MT')
                                        selected="selected"
                                    @endif>Mato Grosso</option>
                                    <option value="MS" @if (old('estado') == 'MS')
                                        selected="selected"
                                    @endif>Mato Grosso do Sul</option>
                                    <option value="MG" @if (old('estado') == 'MG')
                                        selected="selected"
                                    @endif>Minas Gerais</option>
                                    <option value="PA" @if (old('estado') == 'PA')
                                        selected="selected"
                                    @endif>Pará</option>
                                    <option value="PB" @if (old('estado') == 'PB')
                                        selected="selected"
                                    @endif>Paraiba</option>
                                    <option value="PR" @if (old('estado') == 'PR')
                                        selected="selected"
                                    @endif>Paraná</option>
                                    <option value="PE" @if (old('estado') == 'PE')
                                        selected="selected"
                                    @endif>Pernambuco</option>
                                    <option value="PI" @if (old('estado') == 'PI')
                                        selected="selected"
                                    @endif>Piauí­</option>
                                    <option value="RJ" @if (old('estado') == 'RJ')
                                        selected="selected"
                                    @endif>Rio de Janeiro</option>
                                    <option value="RN" @if (old('estado') == 'RN')
                                        selected="selected"
                                    @endif>Rio Grande do Norte</option>
                                    <option value="RS" @if (old('estado') == 'RS')
                                        selected="selected"
                                    @endif>Rio Grande do Sul</option>
                                    <option value="RO" @if (old('estado') == 'RO')
                                        selected="selected"
                                    @endif>Rondônia</option>
                                    <option value="RR" @if (old('estado') == 'RR')
                                        selected="selected"
                                    @endif>Roraima</option>
                                    <option value="SP" @if (old('estado') == 'SP')
                                        selected="selected"
                                    @endif>São Paulo</option>
                                    <option value="SC" @if (old('estado') == 'SC')
                                        selected="selected"
                                    @endif>Santa Catarina</option>
                                    <option value="SE" @if (old('estado') == 'SE')
                                        selected="selected"
                                    @endif>Sergipe</option>
                                    <option value="TO" @if (old('estado') == 'TO')
                                        selected="selected"
                                    @endif>Tocantins</option>
                                </select>
                                @if($errors->has('estado'))
                                    <small class="form-text text-danger">{{ $errors->first('estado') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Cidade <span class="text-danger">*</span></label>
                                <input id="cidade" type="text" name="cidade" value="{{ old('cidade') }}"
                                    class="form-control field_personalidade_2 @if($errors->has('cidade')) form-control-danger @endif">
                                @if($errors->has('cidade'))
                                    <small class="form-text text-danger">{{ $errors->first('cidade') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Bairro<span class="text-danger">*</span></label>
                                <input id="bairro" type="text" name="bairro" value="{{ old('bairro') }}"
                                    class="form-control field_personalidade_2 @if($errors->has('bairro')) form-control-danger @endif">
                                @if($errors->has('bairro'))
                                    <small class="form-text text-danger">{{ $errors->first('bairro') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('rua')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Rua<span class="text-danger">*</span></label>
                                <input type="text" name="rua" id="rua" value="{{ old('rua') }}"
                                    class="form-control field_personalidade_2 @if($errors->has('rua')) form-control-danger @endif">
                                @if($errors->has('rua'))
                                    <small class="form-text text-danger">{{ $errors->first('rua') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group @if($errors->has('numero')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Número<span class="text-danger">*</span></label>
                                <input type="number" name="numero" id="numero" value="{{ old('numero') }}"
                                    class="form-control field_personalidade_2 @if($errors->has('numero')) form-control-danger @endif">
                                @if($errors->has('numero'))
                                    <small class="form-text text-danger">{{ $errors->first('numero') }}</small>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group @if($errors->has('nome_banco')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Banco <span class="text-danger">*</span></label>
                                <input type="text" name="nome_banco" value="{{ old('nome_banco') }}"
                                    class="form-control field_personalidade_2 @if($errors->has('nome_banco')) form-control-danger @endif">
                                @if($errors->has('nome_banco'))
                                    <small class="form-text text-danger">{{ $errors->first('nome_banco') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group @if($errors->has('agencia')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Agencia <span class="text-danger">*</span></label>
                                <input type="text" name="agencia" value="{{ old('agencia') }}"
                                    class="form-control field_personalidade_2 @if($errors->has('agencia')) form-control-danger @endif">
                                @if($errors->has('agencia'))
                                    <small class="form-text text-danger">{{ $errors->first('agencia') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group @if($errors->has('conta_bancaria')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Conta Bancaria <span class="text-danger">*</span></label>
                                <input type="text" name="conta_bancaria" value="{{ old('conta_bancaria') }}"
                                    class="form-control field_personalidade_2 @if($errors->has('conta_bancaria')) form-control-danger @endif">
                                @if($errors->has('conta_bancaria'))
                                    <small class="form-text text-danger">{{ $errors->first('conta_bancaria') }}</small>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card shadow-none">
                                <div class="row d-flex justify-content-between p-2 m-0">
                                    <label class="form-control-label p-0 m-0">Documentos</label>
                                    <button type="button" class="btn btn-success"               id="adiciona-documento">+</button>
                                </div>
                                <div class="row" id="documentos-lista">

                                    @if(old('documentos'))
                                        @for ($i = 0; $i < count(old('documentos')) ; $i ++)
                                            <div class="col-sm-12 p-0 m-0 documento-item" id="{{ $i }}">
                                                <div class="row p-0 m-0">
                                                    <div class="col-xl-3 p-1 m-0">
                                                        <div class="form-group">
                                                            <label class="form-control-label p-0 m-0">Tipo de Documento <span class="text-danger">*</span></label>
                                                            <select name="documentos[{{$i}}][tipo]" class="form-control tipo field">
                                                                <option selected disabled>Tipo</option>
                                                                <?php $tipos_documentos_prestadores = App\DocumentoPrestador::getTiposDocumentos(); ?>
                                                                @foreach ($tipos_documentos_prestadores as $tipo_documento_prestador)
                                                                    <option value="{{ $tipo_documento_prestador }}"
                                                                        @if(old("documentos.{$i}.tipo")==$tipo_documento_prestador)
                                                                            selected
                                                                        @endif>{{ App\DocumentoPrestador::getTipoDocumentoTexto($tipo_documento_prestador) }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if($errors->get("documentos.{$i}.tipo"))
                                                                <small class="form-text text-danger">{{ $errors->first("documentos.{$i}.tipo") }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 p-1 m-0">
                                                        <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control descricao field" name="documentos[{{$i}}][descricao]"
                                                        @if (old("documentos.{$i}.descricao"))
                                                            value='{{ old("documentos.{$i}.descricao") }}'
                                                        @endif>
                                                        @if($errors->get("documentos.{$i}.descricao"))
                                                            <small class="form-text text-danger">{{ $errors->first("documentos.{$i}.descricao") }}</small>
                                                        @endif
                                                    </div>
                                                    <div class="col-xl-4 p-1 m-0">
                                                        <label class="form-control-label p-0 m-0">Arquivo<span class="text-danger">*</span></label>
                                                        <input type="file" class="form-control-file arquivo field" name="documentos[{{$i}}][arquivo]">
                                                        @if($errors->get("documentos.{$i}.arquivo"))
                                                            <small class="form-text text-danger">{{ $errors->first("documentos.{$i}.arquivo") }}</small>
                                                        @endif
                                                    </div>
                                                    <div class="col-xl-1 d-flex p-1 m-0">
                                                        <div class="row col-sm-12 d-flex justify-content-end align-self-center p-0 m-0">
                                                            <button type="button" class="btn btn-danger remover field"
                                                            onclick="document.querySelector('#documentos-lista').removeChild(this.parentElement.parentElement.parentElement.parentElement)">-</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="form-group text-right">
                    <a href="{{ route('instituicao.prestadores.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button id='submit' type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>




@endsection



@push('scripts');

    <script type="text/template" id="prestador-disponivel-message">
        <small class="form-text text-success prestador-alert-message">
            <i class="ti-check"></i> Disponível
        </small>
    </script>

    <script type="text/template" id="prestador-indisponivel-message">
        <small class="form-text text-danger prestador-alert-message">
            <i class="ti-close"></i> Proibido
        </small>
    </script>

    <script type="text/template" id="prestador-permitido-message">
        <small class="form-text text-primary prestador-alert-message">
            <i class="ti-alert"></i> Permitido
        </small>
    </script>



    <script type="text/template" id="base-documento-item">
        <div class="col-sm-12 p-0 m-0 documento-item">
            <div class="row p-0 m-0">
                <div class="col-xl-3 p-1 m-0">
                    <div class="form-group">
                        <label class="form-control-label p-0 m-0">Tipo de Documento <span class="text-danger">*</span></label>
                        <select class="form-control tipo field">
                            <option selected disabled>Tipo</option>
                            <?php $tipos_documentos_prestadores = App\DocumentoPrestador::getTiposDocumentos(); ?>
                            @foreach ($tipos_documentos_prestadores as $tipo_documento_prestador)
                                <option value="{{ $tipo_documento_prestador }}">{{ App\DocumentoPrestador::getTipoDocumentoTexto($tipo_documento_prestador) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 p-1 m-0">
                    <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                    <input type="text" class="form-control descricao field">
                </div>
                <div class="col-xl-4 p-1 m-0">
                    <label class="form-control-label p-0 m-0">Arquivo<span class="text-danger">*</span></label>
                    <input type="file" class="form-control-file arquivo field">
                </div>
                <div class="col-xl-1 d-flex p-1 m-0">
                    <div class="row col-sm-12 d-flex justify-content-end align-self-center p-0 m-0">
                        <button type="button" class="btn btn-danger remover field"
                        onclick="document.querySelector('#documentos-lista').removeChild(this.parentElement.parentElement.parentElement.parentElement)">-</button>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script>
        $( document ).ready(function() {

            function removePreviousAlertMessage(campoInput){
                let previousAlertMessage = document.querySelector('.prestador-alert-message');
                if(previousAlertMessage) campoInput.removeChild(previousAlertMessage);
            }

            function insertAlertMessage(alert_id, campo){
                let campoInput = document.querySelector(`.${campo}-campo`);
                removePreviousAlertMessage(campoInput);
                let alert = $($(`#${alert_id}`).html())[0];
                console.log(alert)
                campoInput.appendChild(alert)
            }

            function InserirCamposPessoaFisica(){
                let camposFisicoJuridico = document.querySelector('#campos-fisico-juridico');
                let formPessoaFisica = $($('#form-pessoa-fisica').html())[0];
                if(formPessoaFisica) camposFisicoJuridico.appendChild(formPessoaFisica);
            }

            function RemoverCamposPessoaFisica(){
                let camposFisicoJuridico = document.querySelector('#campos-fisico-juridico');
                let formPessoaFisica = camposFisicoJuridico.querySelector('#fields_personalidade_1');
                if(formPessoaFisica) camposFisicoJuridico.removeChild(formPessoaFisica)
            }

            function InserirCamposPessoaJuridica(){
                let camposFisicoJuridico = document.querySelector('#campos-fisico-juridico');
                let formPessoaJuridica = $($('#form-pessoa-juridica').html())[0];
                if(formPessoaJuridica) camposFisicoJuridico.appendChild(formPessoaJuridica);
            }

            function RemoverCamposPessoaJuridica(){
                let camposFisicoJuridico = document.querySelector('#campos-fisico-juridico');
                let formPessoaJuridica = camposFisicoJuridico.querySelector('#fields_personalidade_2');
                if(formPessoaJuridica) camposFisicoJuridico.removeChild(formPessoaJuridica)
            }


            function personalidade(){
                let personalidade = $('[name="personalidade"]').val();
                if(personalidade==1){
                    RemoverCamposPessoaJuridica();

                    tipo();

                    $('input[name="cpf"]').on('change',function(e){
                        if( ($(this).val()).length == 14 ) {
                            $.ajax({
                                url: '{{route("instituicao.getprestador")}}',
                                method: 'POST', dataType: 'json',
                                data: {cpf :$(this).val(), '_token': '{{csrf_token()}}'},
                                success: function (response) {
                                    if (response.status==0) {
                                        /* Se o prestador já estiver regitrado
                                            e asssociado à esta instituicao */
                                        insertAlertMessage('prestador-indisponivel-message', 'cpf');
                                        limparCamposPF();
                                    }
                                    if (response.status==1) {
                                        /* O prestador já está registrado mas não está
                                            assossiado à essa instituicao */
                                        insertAlertMessage('prestador-permitido-message', 'cpf');
                                        limparCamposPF();
                                        preencherCampos(response.data);
                                    }
                                    if (response.status==2) {
                                        /* O prestador ainda não está registrado */
                                        insertAlertMessage('prestador-disponivel-message', 'cpf');
                                        limparCamposPF();
                                    }
                                }
                            })
                        }
                    });
                }
                if(personalidade==2){
                    InserirCamposPessoaJuridica();

                    $('input[name="cnpj"]').on('change',function (e) {
                        if( ($(this).val()).length == 18 ) {
                            $.ajax({
                                url: '{{route("instituicao.getprestador")}}',
                                method: 'POST', dataType: 'json',
                                data: {cnpj :$(this).val(), '_token': '{{csrf_token()}}'},
                                success: function (response) {
                                    console.log(response.data)
                                    if (response.status==0) {
                                        // O prestador já está registrado e assossiado
                                        // à essa instituicao
                                        insertAlertMessage('prestador-indisponivel-message', 'cnpj');
                                        limparCamposPJ();
                                    }
                                    if (response.status==1) {
                                        // O prestador já está registrado mas não está
                                        // assossiado à essa instituicao
                                        insertAlertMessage('prestador-permitido-message', 'cnpj');
                                        limparCamposPJ();
                                        preencherCampos(response.data);
                                    }
                                    if (response.status==2) {
                                        // O prestador ainda não está registrado
                                        insertAlertMessage('prestador-disponivel-message', 'cnpj');
                                        limparCamposPJ();
                                    }
                                }
                            })
                        }
                    });
                }
            }

            function documentos(){
                function hasClass(elemento, classe) {
                    return (' ' + elemento.className + ' ').indexOf(' ' + classe + ' ') > -1;
                }
                document.querySelector('#adiciona-documento').addEventListener('click', ()=>{
                    let lista_documentos = document.querySelector('#documentos-lista')
                    let id = lista_documentos.querySelectorAll('.documento-item').length
                    let new_documento = $($('#base-documento-item').html())[0]
                    new_documento.setAttribute('id', `${id}`)
                    new_documento.querySelectorAll('.field').forEach((field)=>{
                        if(hasClass(field, 'tipo')) field.name = `documentos[${id}][tipo]`
                        if(hasClass(field, 'arquivo')) field.name = `documentos[${id}][arquivo]`
                        if(hasClass(field, 'descricao')) field.name = `documentos[${id}][descricao]`
                    })
                    lista_documentos.appendChild(new_documento)
                    console.log(new_documento)
                })
            }

            function insertMedicoCampos()
            {

            }

            function tipo(){
                let value = $('[name="tipo"]').val()
                if(value==2){

                    let medicoCampos = $($('#campos-tipo-medico').html())[0];
                    // document.querySelector('#medico_fields_two').style.display = 'block'
                    console.log(medicoCampos)
                    $('#campo-medico-options')[0].appendChild(medicoCampos);
                    $('.multiplas-especialidades').select2();
                }
                if(value!=2){
                    let especialidadesMedicasOne = document.querySelector('#medico_fields_one')
                    let especialidades = document.querySelector('.multiplas-especialidades')
                    especialidades.value = null
                    let especialidades_options = especialidades.querySelectorAll('option')
                    especialidades_options.forEach((option)=>{
                        if(option.selected) option.selected = false
                    })

                    especialidadesMedicasOne.style.display = 'none'


                    let especialidadesMedicasTwo = document.querySelector('#medico_fields_two')
                    let conselhos = especialidadesMedicasTwo.querySelector('.conselhos_options')
                    let checks = especialidadesMedicasTwo.querySelectorAll('.medico-checkbox')
                    checks.forEach((check)=>{
                        check.checked = false
                    })
                    let conselhos_options = conselhos.querySelectorAll('option')
                    conselhos_options.forEach((option)=>{
                        if(option.value=='0') option.selected = true
                    })
                    especialidadesMedicasTwo.style.display = 'none'
                }

            }

            function vinculo(){

                let values = $('[name="vinculos[]"]').val()
                if(values){
                    console.log(values)
                    if(values.includes('2') || values.includes('3')){
                        let pisPasepNirProeFields = document.querySelector('#pis_pasep_nir_proe')
                        pisPasepNirProeFields.style.display = 'block'
                    }
                    if(!values.includes('2') && !values.includes('3')){
                        let pisPasepNirProeFields = document.querySelector('#pis_pasep_nir_proe')
                        let fields = pisPasepNirProeFields.querySelectorAll('.pis_pasep_nir_proe')
                        fields.forEach((field)=>{
                            field.value = null
                        })
                        pisPasepNirProeFields.style.display = 'none'
                    }
                    if(values.includes('1')){
                        let numeroCooperativaField = document.querySelector('#numero_cooperativa')
                        numeroCooperativaField.style.display = 'block'
                    }
                    if(!values.includes('1')){
                        let numeroCooperativaField = document.querySelector('#numero_cooperativa')
                        let fields = numeroCooperativaField.querySelectorAll('.numero_cooperativa')
                        fields.forEach((field)=>{
                            field.value = null
                        })
                        numeroCooperativaField.style.display = 'none'
                    }
                }
            }

            $('.multiplas-especialidades').select2();
            $('.multiplos-vinculos').select2();

            $('[name="tipo"]').on('change', function(){
                tipo();
            });
            $('[name="vinculos[]"]').on('change', function(){
                vinculo();
            });
            $('input[name=identidade]').setMask('99.999.999-9', {
                translation: { '9': { pattern: /[0-9]/, optional: false } }
            })
            $('input[name=numero_cartao_sus]').setMask('999 9999 9999 9999', {
                translation: { '9': { pattern: /[0-9]/, optional: false } }
            })
            $('[name="personalidade"]').on('change', function() {
                personalidade();
            });


            personalidade();

            tipo();

            vinculo();

            documentos();




            function preencherCampos(data){
                for(var campo in data ){
                    if(data[campo]!==null){
                        let campoField = null;
                        campoField = $(`input[name="${campo}"]`);
                        if (!campoField[0]) {
                            let campoFieldSelect = null;
                            campoFieldSelect = $(`select[name="${campo}"]`);
                            if (campoFieldSelect[0]) {
                                campoFieldOptions = $(`select[name="${campo}"] option`);
                                campoFieldOptions
                                    .filter(`option[value="${data[campo]}"]`)
                                    .attr('selected', true);
                            }
                        }
                        if (campoField[0]) {
                            campoField.val(data[campo]);
                            campoField.prop("readonly", true);
                        }
                    }
                }
            }

            function limparCamposPJ(){
                $('input[name="nome"]').prop("readonly", false);
                $('input[name="razao_social"]').prop("readonly", false);
                $('input[name="nome_banco"]').prop("readonly", false);
                $('input[name="agencia"]').prop("readonly", false);
                $('input[name="conta_bancaria"]').prop("readonly", false);
                $('input[name="cep"]').prop("readonly", false);
                $('input[name="cidade"]').prop("readonly", false);
                $('input[name="bairro"]').prop("readonly", false);
                $('input[name="rua"]').prop("readonly", false);
                $('input[name="numero"]').prop("readonly", false);
                if($('select[name="estado"]').attr('readonly', false)){
                    $('select[name="estado"] option')
                        .filter(`[disabled="disabled"]`)
                        .attr('disabled', false);
                }
                $('input[name="ativo"]').prop("disabled", false);
                $('input[name="ativo"]').prop("checked", false);
            }

            function limparCamposPF(data){
                $('input[name="nome"]').prop("readonly", false);
                $('input[name="nascimento"]').prop("readonly", false);
                $('input[name="identidade"]').prop("readonly", false);
                $('input[name="identidade_orgao_expedidor"]').prop("readonly", false);
                $('input[name="identidade_data_expedicao"]').prop("readonly", false);
                $('input[name="nome_da_mae"]').prop("readonly", false);
                $('input[name="nome_do_pai"]').prop("readonly", false);
                $('input[name="numero_cartao_sus"]').prop("readonly", false);
                $('input[name="nacionalidade"]').prop("readonly", false);
                $('input[name="naturalidade"]').prop("readonly", false);
                $('input[name="carga_horaria_mensal"]').prop("readonly", false);
                document.querySelectorAll('select[name="vinculos[]"] option')
                    .forEach((opt)=>{
                        opt.selected = false
                    })
                $('select[name="vinculos[]"]').change()
                $('select[name="vinculos[]"]').attr('readonly', false)
                $('select[name="vinculos[]"] option').attr('disabled', false)
                $('input[name="pis"]').prop("readonly", false);
                $('input[name="pasep"]').prop("readonly", false);
                $('input[name="nir"]').prop("readonly", false);
                $('input[name="proe"]').prop("readonly", false);
                $('input[name="numero_cooperativa"]').prop("readonly", false);
                document.querySelectorAll('select[name="especialidades[]"] option')
                    .forEach((opt)=>{
                        opt.selected = false
                    })
                $('select[name="especialidades[]"]').change()
                $('select[name="especialidades[]"]').attr('readonly', false)
                $('select[name="especialidades[]"] option').attr('disabled', false)
                $('select[name="conselho_id"]').attr('readonly', false)
                $('select[name="conselho_id"] option').attr('disabled', false)
                $('input[name="anestesista"]').prop("checked", false)
                $('input[name="anestesista"]').prop("disabled", false);
                $('input[name="auxiliar"]').prop("checked", false)
                $('input[name="auxiliar"]').prop("disabled", false);
                document.querySelectorAll('select[name="tipo"] option')
                    .forEach((opt)=>{
                        opt.selected = false
                    })
                $('select[name="tipo"]').change()
                $('select[name="tipo"]').attr('readonly', false)
                $('select[name="tipo"] option').attr('disabled', false)
                document.querySelectorAll('select[name="identidade_uf"] option')
                    .forEach((opt)=>{
                        opt.selected = false
                    })
                $('select[name="identidade_uf"]').attr('readonly', false)
                $('select[name="identidade_uf"] option').attr('disabled', false)
                document.querySelectorAll('select[name="sexo"] option')
                    .forEach((opt)=>{
                        opt.selected = false
                    })
                $('select[name="sexo"]').attr('readonly', false)
                $('select[name="sexo"] option').attr('disabled', false)
                $('input[name="ativo"]').prop("checked", false)
                $('input[name="ativo"]').prop("disabled", false);
            }



        })
    </script>
@endpush
