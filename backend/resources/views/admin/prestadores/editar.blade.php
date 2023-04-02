@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Prestador #{$prestadore->id} {$prestadore->nome}",
        'breadcrumb' => [
            'Prestadores' => route('prestadores.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">
        <div class="card-body">
            <form action="{{ route('prestadores.update', [$prestadore]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group @if($errors->has('personalidade')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Personalidade <span class="text-danger">*</span></label>
                            <select name="personalidade"
                                class="form-control campo @if($errors->has('personalidade')) form-control-danger @endif">
                                {{-- Verifica a personalidade a partir dos vinculos --}}
                                @php
                                    $personalidade = ($prestadore->prestadorVinculos()->where('vinculo_id', '=', 5)->exists()) ? 1 : 2;
                                @endphp
                                <option selected disabled value="0">Personalidade</option>
                                <option value="1" @if ($personalidade=='1')
                                    selected
                                @endif @if(old('personalidade')=='1') selected @endif>Pessoa Física</option>
                                <option value="2" @if ($personalidade=='2')
                                    selected
                                @endif @if(old('personalidade')=='2') selected @endif>Pessoa Juridica</option>
                            </select>
                            @if($errors->has('personalidade'))
                                <small class="form-text text-danger">{{ $errors->first('personalidade') }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row" id="campos-fisico-juridico">
                    <div class="col-sm-12" id="form-pessoa-fisica"  style="display: none;">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group cpf-campo @if($errors->has('cpf')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">CPF <span class="text-danger">*</span></label>
                                    <input type="text" name="cpf" data-prev="{{ $prestadore->cpf }}" alt="cpf" value="{{ old('cpf', $prestadore->cpf) }}"
                                        class="form-control campo @if($errors->has('cpf')) form-control-danger @endif">
                                    @if($errors->has('cpf'))
                                        <small class="form-text text-danger">{{ $errors->first('cpf') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" value="{{ old('nome', $prestadore->nome) }}"
                                        class="form-control campo @if($errors->has('nome')) form-control-danger @endif">
                                    @if($errors->has('nome'))
                                        <small class="form-text text-danger">{{ $errors->first('nome') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('nascimento')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Data de Nascimento <span class="text-danger">*</span></label>
                                    <input type="text" name="nascimento" alt="date" id="date" value="{{ old('nascimento', $prestadore->nascimento) }}"
                                        class="form-control campo @if($errors->has('nascimento')) form-control-danger @endif">
                                    @if($errors->has('nascimento'))
                                        <small class="form-text text-danger">{{ $errors->first('nascimento') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('sexo')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Sexo <span class="text-danger">*</span></label>
                                    <select name="sexo" id="sexo"
                                        class="form-control campo @if($errors->has('sexo')) form-control-danger @endif">
                                        <option selected disabled hidden>Selecione</option>
                                        @foreach ($opcoes_sexo as $id => $opcao)
                                            <option value="{{ $id }}" @if(old('sexo', $prestadore->sexo) == $id) selected="selected" @endif>{{ $opcao }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('sexo'))
                                        <small class="form-text text-danger">{{ $errors->first('sexo') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('identidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">RG <span class="text-danger">*</span></label>
                                    <input type="text" name="identidade"  value="{{ old('identidade', $prestadore->identidade) }}"
                                        class="form-control campo @if($errors->has('identidade')) form-control-danger @endif">
                                    @if($errors->has('identidade'))
                                        <small class="form-text text-danger">{{ $errors->first('identidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('identidade_orgao_expedidor')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Orgão Expedidor <span class="text-danger">*</span></label>
                                    <input type="text" name="identidade_orgao_expedidor"  value="{{ old('identidade_orgao_expedidor', $prestadore->identidade_orgao_expedidor) }}"
                                        class="form-control campo @if($errors->has('identidade_orgao_expedidor')) form-control-danger @endif">
                                    @if($errors->has('identidade_orgao_expedidor'))
                                    <small class="form-text text-danger">{{ $errors->first('identidade_orgao_expedidor') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('identidade_uf')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">UF <span class="text-danger">*</span></label>
                                    <select class="form-control campo
                                        @if($errors->has('identidade_uf')) form-control-danger @endif" name="identidade_uf">
                                        <option selected disabled >Selecione</option>
                                        <option value="AC" @if ($prestadore->identidade_uf=='AC')
                                            selected
                                        @endif @if (old('identidade_uf') == 'AC')
                                            selected="selected"
                                        @endif>Acre</option>
                                        <option value="AL" @if ($prestadore->identidade_uf=='AL')
                                            selected
                                        @endif @if (old('identidade_uf') == 'AL')
                                            selected="selected"
                                        @endif>Alagoas</option>
                                        <option value="AP" @if ($prestadore->identidade_uf=='AP')
                                            selected
                                        @endif @if (old('identidade_uf') == 'AP')
                                            selected="selected"
                                        @endif>Amapá</option>
                                        <option value="AM" @if ($prestadore->identidade_uf=='AM')
                                            selected
                                        @endif @if (old('identidade_uf') == 'AM')
                                            selected="selected"
                                        @endif>Amazonas</option>
                                        <option value="BA" @if ($prestadore->identidade_uf=='BA')
                                            selected
                                        @endif @if (old('identidade_uf') == 'BA')
                                            selected="selected"
                                        @endif>Bahia</option>
                                        <option value="CE" @if ($prestadore->identidade_uf=='CE')
                                            selected
                                        @endif @if (old('identidade_uf') == 'CE')
                                            selected="selected"
                                        @endif>Ceará</option>
                                        <option value="DF" @if ($prestadore->identidade_uf=='DF')
                                            selected
                                        @endif @if (old('identidade_uf') == 'DF')
                                            selected="selected"
                                        @endif>Distrito Federal</option>
                                        <option value="GO" @if ($prestadore->identidade_uf=='GO')
                                            selected
                                        @endif @if (old('identidade_uf') == 'GO')
                                            selected="selected"
                                        @endif>Goiás</option>
                                        <option value="ES" @if ($prestadore->identidade_uf=='ES')
                                            selected
                                        @endif @if (old('identidade_uf') == 'ES')
                                            selected="selected"
                                        @endif>Espírito Santo</option>
                                        <option value="MA" @if ($prestadore->identidade_uf=='MA')
                                            selected
                                        @endif @if (old('identidade_uf') == 'MA')
                                            selected="selected"
                                        @endif>Maranhão</option>
                                        <option value="MT" @if ($prestadore->identidade_uf=='MT')
                                            selected
                                        @endif @if (old('identidade_uf') == 'MT')
                                            selected="selected"
                                        @endif>Mato Grosso</option>
                                        <option value="MS" @if ($prestadore->identidade_uf=='MS')
                                            selected
                                        @endif @if (old('identidade_uf') == 'MS')
                                            selected="selected"
                                        @endif>Mato Grosso do Sul</option>
                                        <option value="MG" @if ($prestadore->identidade_uf=='MG')
                                            selected
                                        @endif @if (old('identidade_uf') == 'MG')
                                            selected="selected"
                                        @endif>Minas Gerais</option>
                                        <option value="PA" @if ($prestadore->identidade_uf=='PA')
                                            selected
                                        @endif @if (old('identidade_uf') == 'PA')
                                            selected="selected"
                                        @endif>Pará</option>
                                        <option value="PB" @if ($prestadore->identidade_uf=='PB')
                                            selected
                                        @endif @if (old('identidade_uf') == 'PB')
                                            selected="selected"
                                        @endif>Paraiba</option>
                                        <option value="PR" @if ($prestadore->identidade_uf=='PR')
                                            selected
                                        @endif @if (old('identidade_uf') == 'PR')
                                            selected="selected"
                                        @endif>Paraná</option>
                                        <option value="PE" @if ($prestadore->identidade_uf=='PE')
                                            selected
                                        @endif @if (old('identidade_uf') == 'PE')
                                            selected="selected"
                                        @endif>Pernambuco</option>
                                        <option value="PI" @if ($prestadore->identidade_uf=='PI')
                                            selected
                                        @endif @if (old('identidade_uf') == 'PI')
                                            selected="selected"
                                        @endif>Piauí­</option>
                                        <option value="RJ" @if ($prestadore->identidade_uf=='RJ')
                                            selected
                                        @endif @if (old('identidade_uf') == 'RJ')
                                            selected="selected"
                                        @endif>Rio de Janeiro</option>
                                        <option value="RN" @if ($prestadore->identidade_uf=='RN')
                                            selected
                                        @endif @if (old('identidade_uf') == 'RN')
                                            selected="selected"
                                        @endif>Rio Grande do Norte</option>
                                        <option value="RS" @if ($prestadore->identidade_uf=='RS')
                                            selected
                                        @endif @if (old('identidade_uf') == 'RS')
                                            selected="selected"
                                        @endif>Rio Grande do Sul</option>
                                        <option value="RO" @if ($prestadore->identidade_uf=='RO')
                                            selected
                                        @endif @if (old('identidade_uf') == 'RO')
                                            selected="selected"
                                        @endif>Rondônia</option>
                                        <option value="RR" @if ($prestadore->identidade_uf=='RR')
                                            selected
                                        @endif @if (old('identidade_uf') == 'RR')
                                            selected="selected"
                                        @endif>Roraima</option>
                                        <option value="SP" @if ($prestadore->identidade_uf=='SP')
                                            selected
                                        @endif @if (old('identidade_uf') == 'SP')
                                            selected="selected"
                                        @endif>São Paulo</option>
                                        <option value="SC" @if ($prestadore->identidade_uf=='SC')
                                            selected
                                        @endif @if (old('identidade_uf') == 'SC')
                                            selected="selected"
                                        @endif>Santa Catarina</option>
                                        <option value="SE" @if ($prestadore->identidade_uf=='SE')
                                            selected
                                        @endif @if (old('identidade_uf') == 'SE')
                                            selected="selected"
                                        @endif>Sergipe</option>
                                        <option value="TO" @if ($prestadore->identidade_uf=='TO')
                                            selected
                                        @endif @if (old('identidade_uf') == 'TO')
                                            selected="selected"
                                        @endif>Tocantins</option>
                                    </select>
                                    @if($errors->has('identidade_uf'))
                                        <small class="form-text text-danger">{{ $errors->first('identidade_uf') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('identidade_data_expedicao')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Data de Expedição <span class="text-danger">*</span></label>
                                    <input type="text" name="identidade_data_expedicao" alt="date" value="{{ old('identidade_data_expedicao', $prestadore->identidade_data_expedicao) }}"
                                        class="form-control campo @if($errors->has('identidade_data_expedicao')) form-control-danger @endif">
                                    @if($errors->has('identidade_data_expedicao'))
                                        <small class="form-text text-danger">{{ $errors->first('identidade_data_expedicao') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('nome_da_mae')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome da Mãe <span class="text-danger">*</span></label>
                                    <input type="text" name="nome_da_mae"  value="{{ old('nome_da_mae', $prestadore->nome_da_mae) }}"
                                        class="form-control campo @if($errors->has('nome_da_mae')) form-control-danger @endif">
                                    @if($errors->has('nome_da_mae'))
                                        <small class="form-text text-danger">{{ $errors->first('nome_da_mae') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('nome_do_pai')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome do Pai <span class="text-danger">*</span></label>
                                    <input type="text" name="nome_do_pai"  value="{{ old('nome_do_pai', $prestadore->nome_do_pai) }}"
                                        class="form-control campo @if($errors->has('nome_do_pai')) form-control-danger @endif">
                                    @if($errors->has('nome_do_pai'))
                                        <small class="form-text text-danger">{{ $errors->first('nome_do_pai') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('numero_cartao_sus')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Cartão SUS <span class="text-success">*</span></label>
                                    <input type="text" name="numero_cartao_sus"  value="{{ old('numero_cartao_sus', $prestadore->numero_cartao_sus) }}"
                                        class="form-control campo @if($errors->has('numero_cartao_sus')) form-control-danger @endif">
                                    @if($errors->has('numero_cartao_sus'))
                                        <small class="form-text text-danger">{{ $errors->first('numero_cartao_sus') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('nacionalidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nacionalidade <span class="text-danger">*</span></label>
                                    <input type="text" name="nacionalidade"  value="{{ old('nacionalidade', $prestadore->nacionalidade) }}"
                                        class="form-control campo @if($errors->has('nacionalidade')) form-control-danger @endif">
                                    @if($errors->has('nacionalidade'))
                                        <small class="form-text text-danger">{{ $errors->first('nacionalidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('naturalidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Naturalidade <span class="text-danger">*</span></label>
                                    <input type="text" name="naturalidade"  value="{{ old('naturalidade', $prestadore->naturalidade) }}"
                                        class="form-control campo @if($errors->has('naturalidade')) form-control-danger @endif">
                                    @if($errors->has('naturalidade'))
                                        <small class="form-text text-danger">{{ $errors->first('naturalidade') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12" id="form-pessoa-juridica" style="display: none;">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group cnpj-campo @if($errors->has('cnpj')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">CNPJ  <span class="text-danger">*</span></label>
                                    <input type="text" data-prev="{{ $prestadore->cnpj }}" name="cnpj" alt="cnpj" value="{{ old('cnpj', $prestadore->cnpj) }}"
                                        class="form-control campo @if($errors->has('cnpj')) form-control-danger @endif">
                                    @if($errors->has('cnpj'))
                                        <small class="form-text text-danger">{{ $errors->first('cnpj') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('razao_social')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Razão Social <span class="text-danger">*</span></label>
                                    <input type="text" name="razao_social" value="{{ old('razao_social', $prestadore->razao_social) }}"
                                        class="form-control campo @if($errors->has('razao_social')) form-control-danger @endif">
                                    @if($errors->has('razao_social'))
                                        <small class="form-text text-danger">{{ $errors->first('razao_social') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('cep')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">CEP <span class="text-danger">*</span></label>
                                    <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep', $prestadore->cep) }}"
                                        class="form-control campo @if($errors->has('cep')) form-control-danger @endif">
                                    @if($errors->has('cep'))
                                        <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('estado')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Estado <span class="text-danger">*</span></label>
                                    <select id="estado" class="form-control @if($errors->has('estado')) form-control-danger @endif" name="estado">
                                        <option selected disabled value="0">Selecione</option>
                                        <option value="AC" @if ($prestadore->estado=='AC')
                                            selected
                                        @endif @if (old('estado') == 'AC')
                                            selected="selected"
                                        @endif>Acre</option>
                                        <option value="AL" @if ($prestadore->estado=='AL')
                                            selected
                                        @endif @if (old('estado') == 'AL')
                                            selected="selected"
                                        @endif>Alagoas</option>
                                        <option value="AP" @if ($prestadore->estado=='AP')
                                            selected
                                        @endif @if (old('estado') == 'AP')
                                            selected="selected"
                                        @endif>Amapá</option>
                                        <option value="AM" @if ($prestadore->estado=='AM')
                                            selected
                                        @endif @if (old('estado') == 'AM')
                                            selected="selected"
                                        @endif>Amazonas</option>
                                        <option value="BA" @if ($prestadore->estado=='BA')
                                            selected
                                        @endif @if (old('estado') == 'BA')
                                            selected="selected"
                                        @endif>Bahia</option>
                                        <option value="CE" @if ($prestadore->estado=='CE')
                                            selected
                                        @endif @if (old('estado') == 'CE')
                                            selected="selected"
                                        @endif>Ceará</option>
                                        <option value="DF" @if ($prestadore->estado=='DF')
                                            selected
                                        @endif @if (old('estado') == 'DF')
                                            selected="selected"
                                        @endif>Distrito Federal</option>
                                        <option value="GO" @if ($prestadore->estado=='GO')
                                            selected
                                        @endif @if (old('estado') == 'GO')
                                            selected="selected"
                                        @endif>Goiás</option>
                                        <option value="ES" @if ($prestadore->estado=='ES')
                                            selected
                                        @endif @if (old('estado') == 'ES')
                                            selected="selected"
                                        @endif>Espírito Santo</option>
                                        <option value="MA" @if ($prestadore->estado=='MA')
                                            selected
                                        @endif @if (old('estado') == 'MA')
                                            selected="selected"
                                        @endif>Maranhão</option>
                                        <option value="MT" @if ($prestadore->estado=='MT')
                                            selected
                                        @endif @if (old('estado') == 'MT')
                                            selected="selected"
                                        @endif>Mato Grosso</option>
                                        <option value="MS" @if ($prestadore->estado=='MS')
                                            selected
                                        @endif @if (old('estado') == 'MS')
                                            selected="selected"
                                        @endif>Mato Grosso do Sul</option>
                                        <option value="MG" @if ($prestadore->estado=='MG')
                                            selected
                                        @endif @if (old('estado') == 'MG')
                                            selected="selected"
                                        @endif>Minas Gerais</option>
                                        <option value="PA" @if ($prestadore->estado=='PA')
                                            selected
                                        @endif @if (old('estado') == 'PA')
                                            selected="selected"
                                        @endif>Pará</option>
                                        <option value="PB" @if ($prestadore->estado=='PB')
                                            selected
                                        @endif @if (old('estado') == 'PB')
                                            selected="selected"
                                        @endif>Paraiba</option>
                                        <option value="PR" @if ($prestadore->estado=='PR')
                                            selected
                                        @endif @if (old('estado') == 'PR')
                                            selected="selected"
                                        @endif>Paraná</option>
                                        <option value="PE" @if ($prestadore->estado=='PE')
                                            selected
                                        @endif @if (old('estado') == 'PE')
                                            selected="selected"
                                        @endif>Pernambuco</option>
                                        <option value="PI" @if ($prestadore->estado=='PI')
                                            selected
                                        @endif @if (old('estado') == 'PI')
                                            selected="selected"
                                        @endif>Piauí­</option>
                                        <option value="RJ" @if ($prestadore->estado=='RJ')
                                            selected
                                        @endif @if (old('estado') == 'RJ')
                                            selected="selected"
                                        @endif>Rio de Janeiro</option>
                                        <option value="RN" @if ($prestadore->estado=='RN')
                                            selected
                                        @endif @if (old('estado') == 'RN')
                                            selected="selected"
                                        @endif>Rio Grande do Norte</option>
                                        <option value="RS" @if ($prestadore->estado=='RS')
                                            selected
                                        @endif @if (old('estado') == 'RS')
                                            selected="selected"
                                        @endif>Rio Grande do Sul</option>
                                        <option value="RO" @if ($prestadore->estado=='RO')
                                            selected
                                        @endif @if (old('estado') == 'RO')
                                            selected="selected"
                                        @endif>Rondônia</option>
                                        <option value="RR" @if ($prestadore->estado=='RR')
                                            selected
                                        @endif @if (old('estado') == 'RR')
                                            selected="selected"
                                        @endif>Roraima</option>
                                        <option value="SP" @if ($prestadore->estado=='SP')
                                            selected
                                        @endif @if (old('estado') == 'SP')
                                            selected="selected"
                                        @endif>São Paulo</option>
                                        <option value="SC" @if ($prestadore->estado=='SC')
                                            selected
                                        @endif @if (old('estado') == 'SC')
                                            selected="selected"
                                        @endif>Santa Catarina</option>
                                        <option value="SE" @if ($prestadore->estado=='SE')
                                            selected
                                        @endif @if (old('estado') == 'SE')
                                            selected="selected"
                                        @endif>Sergipe</option>
                                        <option value="TO" @if ($prestadore->estado=='TO')
                                            selected
                                        @endif @if (old('estado') == 'TO')
                                            selected="selected"
                                        @endif>Tocantins</option>
                                    </select>
                                    @if($errors->has('estado'))
                                        <small class="form-text text-danger">{{ $errors->first('estado') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Cidade <span class="text-danger">*</span></label>
                                    <input id="cidade" type="text" name="cidade" value="{{ old('cidade', $prestadore->cidade) }}"
                                        class="form-control campo @if($errors->has('cidade')) form-control-danger @endif">
                                    @if($errors->has('cidade'))
                                        <small class="form-text text-danger">{{ $errors->first('cidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Bairro<span class="text-danger">*</span></label>
                                    <input id="bairro" type="text" name="bairro" value="{{ old('bairro', $prestadore->bairro) }}"
                                        class="form-control campo @if($errors->has('bairro')) form-control-danger @endif">
                                    @if($errors->has('bairro'))
                                        <small class="form-text text-danger">{{ $errors->first('bairro') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('rua')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Rua<span class="text-danger">*</span></label>
                                    <input type="text" name="rua" id="rua" value="{{ old('rua', $prestadore->rua) }}"
                                        class="form-control campo @if($errors->has('rua')) form-control-danger @endif">
                                    @if($errors->has('rua'))
                                        <small class="form-text text-danger">{{ $errors->first('rua') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('numero')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Numero<span class="text-danger">*</span></label>
                                    <input type="number" name="numero" id="numero" value="{{ old('numero', $prestadore->numero) }}"
                                        class="form-control @if($errors->has('numero')) form-control-danger campo @endif">
                                    @if($errors->has('numero'))
                                        <small class="form-text text-danger">{{ $errors->first('numero') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('prestadores.index') }}">
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

    <script>
        $( document ).ready(function() {

            function insertAlertMessage(alert_id, campo){
                let campoInput = $(`.${campo}-campo`)[0];
                $(`.${campo}-campo .prestador-alert-message`).remove();
                let alert = $($(`#${alert_id}`).html())[0];
                campoInput.appendChild(alert)
            }

            function removeAlertMessage(campo) {
                $(`.${campo}-campo .prestador-alert-message`).remove();
            }

            function personalidade(){
                let value = $('select[name="personalidade"]').val();
                if(value==1){
                    $('#campos-fisico-juridico #form-pessoa-juridica').hide();
                    $('#campos-fisico-juridico #form-pessoa-fisica').show();
                    requestDocmento('cpf');
                }
                if(value==2){
                    $('#campos-fisico-juridico #form-pessoa-fisica').show();
                    $('#campos-fisico-juridico #form-pessoa-juridica').show();
                    requestDocmento('cpf');
                    requestDocmento('cnpj');
                }
            }

            function requestDocmento(doc) {
                console.log(doc)
                $(`input[name="${doc}"]`).on('change',function (e) {
                    if( ($(this).val()).length == 18 || ($(this).val()).length == 14 ) {
                        $.ajax({
                            url: '{{route("getprestador")}}',
                            method: 'POST', dataType: 'json',
                            data: { valor: $(this).val(), documento: doc, '_token': '{{csrf_token()}}' },
                            success: function (response) {
                                if (response.status==0) {
                                    /* Se já houver prestador registrado com esse cpf/cnpj */
                                    if(response.data[doc]===$(`input[name="${doc}"]`).data('prev')) {
                                        removeAlertMessage(doc);
                                        desblockButtons();
                                        return;
                                    };
                                    insertAlertMessage('prestador-indisponivel-message', doc);
                                    blockButtons();
                                }
                                if (response.status==1) {
                                    /* O não houver prestador registrado com esse cpf/cnpj */
                                    insertAlertMessage('prestador-permitido-message', doc);
                                    desblockButtons();
                                }
                            }
                        })
                    }
                });
            }

            function blockButtons(){
                $('#submit').prop('disabled', true);
                $('#adiciona-documento').prop('disabled', true);
            }

            function desblockButtons(){
                $('#submit').prop('disabled', false);
                $('#adiciona-documento').prop('disabled', false);
            }

            $('select[name="personalidade"]').on('change', function() {
                personalidade();
            });

            $('input[name=identidade]').setMask('99.999.999-9', {
                translation: {'9': {pattern: /[0-9]/, optional: false}}
            })

            $('input[name=numero_cartao_sus]').setMask('999 9999 9999 9999', {
                translation: { '9': { pattern: /[0-9]/, optional: false} }
            })

            $(`.arquivo`).each(function(){
                $(this).on('change',function(){
                    var fileName = $(this).val();
                    $(this).next('.custom-file-label').html(fileName);
                });
            });

            $('form').submit(function(e){

                $(this).find('div[style*="display: none"]').each(function(){
                    $(this).find('input').each(function(){
                        $(this).prop('disabled', true);
                    });
                    $(this).find('select').each(function(){
                        $(this).prop('disabled', true);
                    });
                });

            });

            personalidade();

        })
    </script>
@endpush




