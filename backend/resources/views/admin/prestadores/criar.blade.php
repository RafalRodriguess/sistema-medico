
@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Prestador',
        'breadcrumb' => [
            'Prestador' => route('prestadores.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">

        <div class="card-body">
            <form action="{{ route('prestadores.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group @if($errors->has('personalidade')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Personalidade <span class="text-danger">*</span></label>
                            <select name="personalidade"
                                class="form-control campo @if($errors->has('personalidade')) form-control-danger @endif" id="personalidade">
                                <option selected disabled value="0">Personalidade</option>
                                <option value="1" @if(old('personalidade')=='1') selected @endif>Pessoa Física</option>
                                <option value="2" @if(old('personalidade')=='2') selected @endif>Pessoa Juridica</option>
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
                                    <input type="text" name="cpf" alt="cpf" value="{{ old('cpf') }}"
                                        class="form-control campo @if($errors->has('cpf')) form-control-danger @endif">
                                    @if($errors->has('cpf'))
                                        <small class="form-text text-danger">{{ $errors->first('cpf') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" value="{{ old('nome') }}"
                                        class="form-control campo @if($errors->has('nome')) form-control-danger @endif">
                                    @if($errors->has('nome'))
                                        <small class="form-text text-danger">{{ $errors->first('nome') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('nascimento')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Data de Nascimento <span class="text-danger">*</span></label>
                                    <input type="text" name="nascimento" alt="date" id="date" value="{{ old('nascimento') }}"
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
                                            <option value="{{ $id }}" @if(old('sexo', -1) == $id) selected="selected" @endif>{{ $opcao }}</option>
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
                                    <input type="text" name="identidade"  value="{{ old('identidade') }}"
                                        class="form-control campo @if($errors->has('identidade')) form-control-danger @endif">
                                    @if($errors->has('identidade'))
                                        <small class="form-text text-danger">{{ $errors->first('identidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('identidade_orgao_expedidor')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Orgão Expedidor <span class="text-danger">*</span></label>
                                    <input type="text" name="identidade_orgao_expedidor"  value="{{ old('identidade_orgao_expedidor') }}"
                                        class="form-control campo @if($errors->has('identidade_orgao_expedidor')) form-control-danger @endif">
                                    @if($errors->has('identidade_orgao_expedidor'))
                                    <small class="form-text text-danger">{{ $errors->first('identidade_orgao_expedidor') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('identidade_uf')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">UF <span class="text-danger">*</span></label>
                                    <select class="form-control campo @if($errors->has('identidade_uf')) form-control-danger @endif" name="identidade_uf">
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
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('identidade_data_expedicao')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Data de Expedição <span class="text-danger">*</span></label>
                                    <input type="text" name="identidade_data_expedicao" alt="date" value="{{ old('identidade_data_expedicao') }}"
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
                                    <input type="text" name="nome_da_mae"  value="{{ old('nome_da_mae') }}"
                                        class="form-control campo @if($errors->has('nome_da_mae')) form-control-danger @endif">
                                    @if($errors->has('nome_da_mae'))
                                        <small class="form-text text-danger">{{ $errors->first('nome_da_mae') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('nome_do_pai')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome do Pai <span class="text-danger">*</span></label>
                                    <input type="text" name="nome_do_pai"  value="{{ old('nome_do_pai') }}"
                                        class="form-control campo @if($errors->has('nome_do_pai')) form-control-danger @endif">
                                    @if($errors->has('nome_do_pai'))
                                        <small class="form-text text-danger">{{ $errors->first('nome_do_pai') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('numero_cartao_sus')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Cartão SUS <span class="text-success">*</span></label>
                                    <input type="text" name="numero_cartao_sus"  value="{{ old('numero_cartao_sus') }}"
                                        class="form-control campo @if($errors->has('numero_cartao_sus')) form-control-danger @endif">
                                    @if($errors->has('numero_cartao_sus'))
                                        <small class="form-text text-danger">{{ $errors->first('numero_cartao_sus') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('nacionalidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nacionalidade <span class="text-danger">*</span></label>
                                    <input type="text" name="nacionalidade"  value="{{ old('nacionalidade') }}"
                                        class="form-control campo @if($errors->has('nacionalidade')) form-control-danger @endif">
                                    @if($errors->has('nacionalidade'))
                                        <small class="form-text text-danger">{{ $errors->first('nacionalidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('naturalidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Naturalidade <span class="text-danger">*</span></label>
                                    <input type="text" name="naturalidade"  value="{{ old('naturalidade') }}"
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
                                    <input type="text" name="cnpj" alt="cnpj" value="{{ old('cnpj') }}"
                                        class="form-control campo @if($errors->has('cnpj')) form-control-danger @endif">
                                    @if($errors->has('cnpj'))
                                        <small class="form-text text-danger">{{ $errors->first('cnpj') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('razao_social')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Razão Social <span class="text-danger">*</span></label>
                                    <input type="text" name="razao_social" value="{{ old('razao_social') }}"
                                        class="form-control campo @if($errors->has('razao_social')) form-control-danger @endif">
                                    @if($errors->has('razao_social'))
                                        <small class="form-text text-danger">{{ $errors->first('razao_social') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('cep')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">CEP <span class="text-danger">*</span></label>
                                    <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep') }}"
                                        class="form-control campo @if($errors->has('cep')) form-control-danger @endif">
                                    @if($errors->has('cep'))
                                        <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('estado')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Estado <span class="text-danger">*</span></label>
                                    <select id="estado" class="form-control campo @if($errors->has('estado')) form-control-danger @endif" name="estado">
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
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Cidade <span class="text-danger">*</span></label>
                                    <input id="cidade" type="text" name="cidade" value="{{ old('cidade') }}"
                                        class="form-control campo @if($errors->has('cidade')) form-control-danger @endif">
                                    @if($errors->has('cidade'))
                                        <small class="form-text text-danger">{{ $errors->first('cidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Bairro<span class="text-danger">*</span></label>
                                    <input id="bairro" type="text" name="bairro" value="{{ old('bairro') }}"
                                        class="form-control campo @if($errors->has('bairro')) form-control-danger @endif">
                                    @if($errors->has('bairro'))
                                        <small class="form-text text-danger">{{ $errors->first('bairro') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('rua')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Rua<span class="text-danger">*</span></label>
                                    <input type="text" name="rua" id="rua" value="{{ old('rua') }}"
                                        class="form-control campo @if($errors->has('rua')) form-control-danger @endif">
                                    @if($errors->has('rua'))
                                        <small class="form-text text-danger">{{ $errors->first('rua') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('numero')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Numero<span class="text-danger">*</span></label>
                                    <input type="number" name="numero" id="numero" value="{{ old('numero') }}"
                                        class="form-control @if($errors->has('numero')) form-control-danger campo @endif">
                                    @if($errors->has('numero'))
                                        <small class="form-text text-danger">{{ $errors->first('numero') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow-none bg-light">
                            <div class="row d-flex justify-content-between p-2 m-0">
                                <label class="form-control-label p-0 m-0">Documentos</label>
                                <button type="button" class="btn btn-success"               id="adiciona-documento">+</button>
                            </div>
                        </div>
                        <div class="col-sm-12 p-0 m-0" id="documentos-lista">

                            @if(old('documentos'))
                                @for ($i = 0; $i < count(old('documentos')) ; $i ++)

                                    <div class="card shadow-none documento-item p-0" id="{{ $i }}">
                                        <div class="row m-0 p-0">
                                            <div class="col-sm-12 bg-light border-bottom">
                                                <div class="row d-flex justify-content-between p-2 m-0">
                                                    <label class="form-control-label p-0 m-0">
                                                        <span class="title">Documento #{{$i}}</span>
                                                    </label>
                                                    <button type="button"
                                                    onclick="javascript: $(this).parent().parent().parent().parent().remove();" class="btn btn-secondary" >x</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-2 m-0">
                                            <div class="col-sm-2">
                                                <div class="form-group @if($errors->has("documentos.{$i}.tipo")) has-danger @endif">
                                                    <label class="form-control-label p-0 m-0">Tipo de Documento <span class="text-danger">*</span></label>
                                                    <select class="form-control tipo field " name="documentos[{{$i}}][tipo]" >
                                                        <option selected disabled>Tipo</option>
                                                        <?php $tipos_documentos_prestadores = App\DocumentoPrestador::getTiposDocumentos(); ?>
                                                        @foreach ($tipos_documentos_prestadores as $tipo_documento_prestador)
                                                            <option @if(old("documentos.{$i}.tipo")==$tipo_documento_prestador)
                                                            selected
                                                        @endif value="{{ $tipo_documento_prestador }}">{{ App\DocumentoPrestador::getTipoDocumentoTexto($tipo_documento_prestador) }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->get("documentos.{$i}.tipo"))
                                                        <small class="form-text text-danger">{{ $errors->first("documentos.{$i}.tipo") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3 @if($errors->has("documentos.{$i}.descricao")) has-danger @endif">
                                                <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                                                <input type="text" value='{{ old("documentos.{$i}.descricao") }}' name="documentos[{{$i}}][descricao]" class="form-control descricao field">
                                                @if($errors->get("documentos.{$i}.descricao"))
                                                    <small class="form-text text-danger">{{ $errors->first("documentos.{$i}.descricao") }}</small>
                                                @endif
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="form-group @if($errors->has("documentos.{$i}.arquivo")) has-danger @endif">
                                                    <label class="p-0 m-0">Arquivo <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" name="documentos[{{$i}}][arquivo]" class="form-control custom-file-input arquivo field">
                                                        <label class="custom-file-label">Selecione o Arquivo</label>
                                                    </div>
                                                    @if($errors->get("documentos.{$i}.arquivo"))
                                                        <small class="form-text text-danger">{{ $errors->first("documentos.{$i}.arquivo") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif
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

    <script type="text/template" id="base-documento-item">
        <div class="card shadow-none documento-item p-0">

            <div class="row m-0 p-0">
                <div class="col-sm-12 bg-light border-bottom">
                    <div class="row d-flex justify-content-between p-2 m-0">
                        <label class="form-control-label p-0 m-0">
                            <span class="title"></span>
                        </label>
                        <button type="button"
                        onclick="javascript: $(this).parent().parent().parent().parent().remove();" class="btn btn-secondary" >x</button>
                    </div>
                </div>
            </div>

            <div class="row p-2 m-0">

                <div class="col-sm-2">
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

                <div class="col-sm-3">
                    <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                    <input type="text" class="form-control descricao field">
                </div>

                <div class="col-sm-7">
                    <div class="form-group">
                        <label class="p-0 m-0">Arquivo <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="form-control custom-file-input arquivo field">
                            <label class="custom-file-label">Selecione o Arquivo</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </script>

    <script>
        $( document ).ready(function() {

            function insertAlertMessage(alert_id, campo){
                let campoInput = $(`.${campo}-campo`)[0];
                $(`.${campo}-campo .prestador-alert-message`).remove();
                let alert = $($(`#${alert_id}`).html())[0];
                campoInput.appendChild(alert)
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

            function documentos(){
                function hasClass(elemento, classe) {
                    return (' ' + elemento.className + ' ').indexOf(' ' + classe + ' ') > -1;
                }
                document.querySelector('#adiciona-documento').addEventListener('click', ()=>{
                    let lista_documentos = document.querySelector('#documentos-lista')
                    let id = lista_documentos.querySelectorAll('.documento-item').length
                    let new_documento = $($('#base-documento-item').html())[0]
                    new_documento.setAttribute('id', `${id}`);
                    let timestamp = new Date().getTime();
                    let newID = `arquivo_${timestamp}`;
                    new_documento.querySelectorAll('.field').forEach((field)=>{
                        if(hasClass(field, 'tipo')) field.name = `documentos[${id}][tipo]`
                        if(hasClass(field, 'arquivo')) {
                            field.name = `documentos[${id}][arquivo]`;
                            field.id = newID;
                        }
                        if(hasClass(field, 'descricao')) field.name = `documentos[${id}][descricao]`
                    })
                    new_documento.querySelector('span.title').textContent = `Documento #${id}`;
                    lista_documentos.appendChild(new_documento);
                    $(`#${newID}`).on('change',function(){
                        var fileName = $(this).val();
                        $(this).next('.custom-file-label').html(fileName);
                    });
                })
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

            personalidade();
            documentos();

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

        })
    </script>
@endpush




