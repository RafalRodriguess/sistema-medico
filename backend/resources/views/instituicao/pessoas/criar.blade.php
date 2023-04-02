@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Pacientes',
        'breadcrumb' => [
            'Pacientes' => route('instituicao.pessoas.index'),
            'Novo',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.pessoas.store') }}" method="post" enctype="multipart/form-data" id="formPessoas">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('personalidade')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Personalidade <span class="text-danger">*</span></label>
                            <select name="personalidade"
                                class="form-control campo @if($errors->has('personalidade')) form-control-danger @endif">
                                <option selected disabled>Personalidade</option>
                                @foreach ($personalidades as $personalidade)
                                    <option value="{{ $personalidade }}" @if(old('personalidade', $dados_inicio['personalidade'] ?? null)==$personalidade) selected @endif>{{ App\Pessoa::getPersonalidadeTexto($personalidade) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('personalidade'))
                                <small class="form-text text-danger">{{ $errors->first('personalidade') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('tipo')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                            <select name="tipo"
                                class="form-control campo @if($errors->has('tipo')) form-control-danger @endif">
                                <option selected disabled>Tipos</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo }}" @if(old('tipo', $dados_inicio['tipo'] ?? 2)==$tipo) selected @endif>{{ App\Pessoa::getTipoTexto($tipo) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tipo'))
                                <small class="form-text text-danger">{{ $errors->first('tipo') }}</small>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="card shadow-none col-sm-12 m-0 p-0 mb-3" style="display: none" id="campos-fisico-juridico">

                    <div class="row m-0 mb-3 p-3 bg-light border-bottom">
                        <div class="col-sm-12">
                            <label class="form-control-label p-0 m-0" id="personalidade-selecionada"></label>
                        </div>
                    </div>

                    <div class="col-sm-12 m-0">

                        <div class="col-sm-12 p-0 m-0" style="display: none" id="campos-pessoa-fisica">

                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group cpf-campo @if($errors->has('cpf')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">CPF @if(!empty($campos_obg->cpf)) * @endif</label>
                                        <input type="text" name="cpf" alt="cpf" value="{{ old('cpf', $dados_inicio['cpf'] ?? null) }}"
                                            class="form-control campo @if($errors->has('cpf')) form-control-danger @endif">
                                        @if($errors->has('cpf'))
                                            <small class="form-text text-danger">{{ $errors->first('cpf') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group @if($errors->has('nome')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nome <span class="text-danger">*</span></label>
                                        <input type="text" name="nome" value="{{ old('nome', $dados_inicio['nome'] ?? null) }}" class="form-control campo @if($errors->has('nome')) form-control-danger @endif">
                                        @if($errors->has('nome'))
                                            <small class="form-text text-danger">{{ $errors->first('nome') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('nascimento')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Dt Nascimento @if(!empty($campos_obg->nascimento)) <span class="text-danger">*</span> @endif</label>
                                        <input type="date" name="nascimento" value="{{ old('nascimento') }}" class="form-control campo @if($errors->has('nascimento')) form-control-danger @endif" >
                                        @if($errors->has('nascimento'))
                                            <small class="form-text text-danger">{{ $errors->first('nascimento') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('telefone1')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 1 @if(!empty($campos_obg->telefone1)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="telefone1" value="{{ old('telefone1') }}"
                                            class="form-control campo telefone @if($errors->has('telefone1')) form-control-danger @endif">
                                        @if($errors->has('telefone1'))
                                            <small class="form-text text-danger">{{ $errors->first('telefone1') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('telefone2')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 2 @if(!empty($campos_obg->telefone2)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="telefone2" value="{{ old('telefone2') }}"
                                            class="form-control campo telefone @if($errors->has('telefone2')) form-control-danger @endif">
                                        @if($errors->has('telefone2'))
                                            <small class="form-text text-danger">{{ $errors->first('telefone2') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('telefone3')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 3 @if(!empty($campos_obg->telefone3)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="telefone3" value="{{ old('telefone3') }}"
                                            class="form-control campo telefone @if($errors->has('telefone3')) form-control-danger @endif">
                                        @if($errors->has('telefone3'))
                                            <small class="form-text text-danger">{{ $errors->first('telefone3') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('sexo')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Sexo @if(!empty($campos_obg->sexo)) <span class="text-danger">*</span> @endif</label>
                                        <select name="sexo" value="{{ old('sexo') }}" class="form-control campo @if($errors->has('sexo')) form-control-danger @endif">
                                            <option value="">Selecione um sexo</option>
                                            @foreach ($sexo as $item)

                                                <option value="{{ $item }}" @if(old('sexo')==$item) selected @endif>{{ App\Pessoa::getSexoTexto($item) }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('sexo'))
                                            <small class="form-text text-danger">{{ $errors->first('sexo') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group @if($errors->has('email')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Email</label>
                                        <input type="text" name="email" value="{{ old('email') }}"
                                            class="form-control campo @if($errors->has('email')) form-control-danger @endif">
                                        @if($errors->has('email'))
                                            <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('identidade')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Identidade</label>
                                        <input type="text" name="identidade" id="identidade" value="{{ old('identidade') }}" class="form-control campo @if($errors->has('identidade')) form-control-danger @endif">
                                        @if($errors->has('identidade'))
                                            <small class="form-text text-danger">{{ $errors->first('identidade') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('orgao_expedidor')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Orgão expedidor</label>
                                        <input type="text" name="orgao_expedidor" value="{{ old('orgao_expedidor') }}" class="form-control campo @if($errors->has('orgao_expedidor')) form-control-danger @endif">
                                        @if($errors->has('orgao_expedidor'))
                                            <small class="form-text text-danger">{{ $errors->first('orgao_expedidor') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('data_emissao')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Emissão</label>
                                        <input type="date" name="data_emissao" value="{{ old('data_emissao') }}" class="form-control campo @if($errors->has('data_emissao')) form-control-danger @endif" >
                                        @if($errors->has('data_emissao'))
                                            <small class="form-text text-danger">{{ $errors->first('data_emissao') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group @if($errors->has('nome_mae')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nome da mãe</label>
                                        <input type="text" name="nome_mae" value="{{ old('nome_mae', $dados_inicio['nome_mae'] ?? null) }}"
                                            class="form-control campo @if($errors->has('nome_mae')) form-control-danger @endif">
                                        @if($errors->has('nome_mae'))
                                            <small class="form-text text-danger">{{ $errors->first('nome_mae') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group @if($errors->has('nome_pai')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nome do pai</label>
                                        <input type="text" name="nome_pai" value="{{ old('nome_pai') }}"
                                            class="form-control campo @if($errors->has('nome_pai')) form-control-danger @endif">
                                        @if($errors->has('nome_pai'))
                                            <small class="form-text text-danger">{{ $errors->first('nome_pai') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('estado_civil')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Estado civil</label>
                                        <select name="estado_civil" value="{{ old('estado_civil') }}" class="form-control campo @if($errors->has('estado_civil')) form-control-danger @endif">
                                            <option value="">Selecione um estado civil</option>
                                            @foreach ($estado_civil as $item)
                                                <option value="{{ $item }}" @if(old('estado_civil')==$item) selected @endif>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('estado_civil'))
                                            <small class="form-text text-danger">{{ $errors->first('estado_civil') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('naturalidade')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Naturalidade</label>
                                        <input type="text" name="naturalidade" value="{{ old('naturalidade') }}"
                                            class="form-control campo @if($errors->has('naturalidade')) form-control-danger @endif">
                                        @if($errors->has('naturalidade'))
                                            <small class="form-text text-danger">{{ $errors->first('naturalidade') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('profissao')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Profissão</label>
                                        <input type="text" name="profissao" value="{{ old('profissao') }}"
                                            class="form-control campo @if($errors->has('profissao')) form-control-danger @endif">
                                        @if($errors->has('profissao'))
                                            <small class="form-text text-danger">{{ $errors->first('profissao') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('indicacao_descricao')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Indicação</label>
                                        <input type="text" name="indicacao_descricao" value="{{ old('indicacao_descricao') }}"
                                            class="form-control campo @if($errors->has('indicacao_descricao')) form-control-danger @endif">
                                        @if($errors->has('indicacao_descricao'))
                                            <small class="form-text text-danger">{{ $errors->first('indicacao_descricao') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('cep')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">CEP @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep') }}"
                                            class="form-control campo @if($errors->has('cep')) form-control-danger @endif">
                                        @if($errors->has('cep'))
                                            <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('estado')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Estado @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <select class="form-control campo select2-average @if($errors->has('estado')) form-control-danger  @endif" name="estado">
                                            <option value="">Selecione</option>
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

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Cidade @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input id="cidade" type="text" name="cidade" value="{{ old('cidade') }}"
                                            class="form-control campo @if($errors->has('cidade')) form-control-danger @endif">
                                        @if($errors->has('cidade'))
                                            <small class="form-text text-danger">{{ $errors->first('cidade') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Bairro @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input id="bairro" type="text" name="bairro" value="{{ old('bairro') }}"
                                            class="form-control campo @if($errors->has('bairro')) form-control-danger @endif">
                                        @if($errors->has('bairro'))
                                            <small class="form-text text-danger">{{ $errors->first('bairro') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('rua')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Rua @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="rua" id="rua" value="{{ old('rua') }}"
                                            class="form-control campo @if($errors->has('rua')) form-control-danger @endif">
                                        @if($errors->has('rua'))
                                            <small class="form-text text-danger">{{ $errors->first('rua') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('numero')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Numero @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="numero" id="numero" value="{{ old('numero') }}"
                                            class="form-control @if($errors->has('numero')) form-control-danger campo @endif">
                                        @if($errors->has('numero'))
                                            <small class="form-text text-danger">{{ $errors->first('numero') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('complemento')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Complemento</label>
                                        <input type="text" name="complemento" id="complemento" value="{{ old('complemento') }}"
                                            class="form-control @if($errors->has('complemento')) form-control-danger campo @endif">
                                        @if($errors->has('complemento'))
                                            <small class="form-text text-danger">{{ $errors->first('complemento') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 p-0 m-0" style="display: none" id="campos-pessoa-juridica">

                            <div class="row">

                                <div class="col-sm-2">
                                    <div class="form-group cnpj-campo @if($errors->has('cnpj')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">CNPJ <span class="text-danger">*</span></label>
                                        <input type="text" name="cnpj" alt="cnpj" value="{{ old('cnpj') }}"
                                            class="form-control campo @if($errors->has('cnpj')) form-control-danger @endif">
                                        @if($errors->has('cnpj'))
                                            <small class="form-text text-danger">{{ $errors->first('cnpj') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('nome_fantasia')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nome Fantasia<span class="text-danger">*</span></label>
                                        <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia') }}"
                                            class="form-control campo @if($errors->has('nome_fantasia')) form-control-danger @endif">
                                        @if($errors->has('nome_fantasia'))
                                            <small class="form-text text-danger">{{ $errors->first('nome_fantasia') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-7">
                                    <div class="form-group @if($errors->has('razao_social')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Razão Social<span class="text-danger">*</span></label>
                                        <input type="text" name="razao_social" value="{{ old('razao_social') }}"
                                            class="form-control campo @if($errors->has('razao_social')) form-control-danger @endif">
                                        @if($errors->has('razao_social'))
                                            <small class="form-text text-danger">{{ $errors->first('razao_social') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group site-campo @if($errors->has('site')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Site</label>
                                        <input type="text" name="site" value="{{ old('site') }}"
                                            class="form-control campo @if($errors->has('site')) form-control-danger @endif">
                                        @if($errors->has('site'))
                                            <small class="form-text text-danger">{{ $errors->first('site') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('banco')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Banco<span class="text-danger">*</span></label>
                                        <input type="text" name="banco" value="{{ old('banco') }}"
                                            class="form-control campo @if($errors->has('banco')) form-control-danger @endif">
                                        @if($errors->has('banco'))
                                            <small class="form-text text-danger">{{ $errors->first('banco') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('agencia')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Agencia<span class="text-danger">*</span></label>
                                        <input type="text" name="agencia" value="{{ old('agencia') }}"
                                            class="form-control campo @if($errors->has('agencia')) form-control-danger @endif">
                                        @if($errors->has('agencia'))
                                            <small class="form-text text-danger">{{ $errors->first('agencia') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('conta_corrente')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Conta Corrente<span class="text-danger">*</span></label>
                                        <input type="text" name="conta_corrente" value="{{ old('conta_corrente') }}"
                                            class="form-control campo @if($errors->has('conta_corrente')) form-control-danger @endif">
                                        @if($errors->has('conta_corrente'))
                                            <small class="form-text text-danger">{{ $errors->first('conta_corrente') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-sm-12 p-0 m-0">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group @if($errors->has('obs')) has-danger @endif">
                                <label class="form-control-label p-0 m-0">Obs</label>
                                <textarea rows='4' name="obs"
                                    class="form-control campo @if($errors->has('obs')) form-control-danger @endif">{{ old('obs') }}</textarea>
                                @if($errors->has('obs'))
                                    <small class="form-text text-danger">{{ $errors->first('obs') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="gerar_via_acompanhante" name="gerar_via_acompanhante" class="filled-in" @if (old('gerar_via_acompanhante') == 1)
                    checked
                    @endif value="1"/>
                    <label for="gerar_via_acompanhante">Gerar boleto ao nome do acompanhante<label>
                </div>

                <div class="col-sm-12 p-0 m-0">

                    <div class="card col-sm-12">

                        <div class="row mb-3">
                            <div class="col-sm-12 border-bottom bg-light p-3">
                                <label class="form-control-label p-0 m-0">Contato de Referencia / Acompanhate</label>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('referencia_relacao')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Relação/Parentesco</label>
                                    <select name="referencia_relacao"
                                        class="form-control campo select2-simples @if($errors->has('referencia_relacao')) form-control-danger @endif">
                                        <option selected disabled>Selecione</option>
                                        @foreach ($referencia_relacoes as $relacao)
                                            <option value="{{ $relacao }}" @if(old('referencia_relacao')==$relacao) selected @endif>{{ $relacao }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('referencia_relacao'))
                                        <small class="form-text text-danger">{{ $errors->first('referencia_relacao') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('referencia_nome')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome</label>
                                    <input type="text" name="referencia_nome" value="{{ old('referencia_nome') }}"
                                        class="form-control campo @if($errors->has('referencia_nome')) form-control-danger @endif">
                                    @if($errors->has('referencia_nome'))
                                        <small class="form-text text-danger">{{ $errors->first('referencia_nome') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('referencia_telefone')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Telefone</label>
                                    <input type="text" name="referencia_telefone" value="{{ old('referencia_telefone') }}"
                                        class="form-control campo telefone @if($errors->has('referencia_telefone')) form-control-danger @endif">
                                    @if($errors->has('referencia_telefone'))
                                        <small class="form-text text-danger">{{ $errors->first('referencia_telefone') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('referencia_documento')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Documento</label>
                                    <input type="text" name="referencia_documento" alt="cpf" value="{{ old('referencia_documento') }}"
                                        class="form-control campo @if($errors->has('referencia_documento')) form-control-danger @endif">
                                    @if($errors->has('referencia_documento'))
                                        <small class="form-text text-danger">{{ $errors->first('referencia_documento') }}</small>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                @can('habilidade_instituicao_sessao', 'cadastrar_documentos_pessoas')
                    <div class="col-sm-12 p-0 m-0">
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
                                                            <button type="button" id="adiciona-documento"
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
                    </div>
                @endcan
                
                @can('habilidade_instituicao_sessao', 'cadastrar_carteirinha')
                    <div class="col-sm-12 p-0 m-0">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card shadow-none bg-light">
                                    <div class="row d-flex justify-content-between p-2 m-0">
                                        <label class="form-control-label p-0 m-0">Carteirinhas</label>
                                        <button type="button" class="btn btn-success" id="adiciona-carteirinha">+</button>
                                    </div>
                                </div>
                                <div class="col-sm-12 p-0 m-0" id="carteirinha-lista">
                                    @if(old('carteirinha'))
                                        @for ($i = 0; $i < count(old('carteirinha')) ; $i ++)
                                            <div class="card shadow-none carteirinha-item p-0" id="{{ $i }}">
                                                <div class="row m-0 p-0">
                                                    <div class="col-sm-12 bg-light border-bottom">
                                                        <div class="row d-flex justify-content-between p-2 m-0">
                                                            <label class="form-control-label p-0 m-0">
                                                                <span class="title"></span>
                                                            </label>
                                                            <button type="button" id="adiciona-carteirinha"
                                                            onclick="javascript: $(this).parent().parent().parent().parent().remove();" class="btn btn-secondary" >x</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row p-2 m-0">
                                                    <div class="col-sm">
                                                        <div class="form-group">
                                                            <label class="form-control-label p-0 m-0">Convenios <span class="text-danger">*</span></label>
                                                            <select class="form-control select2" name="carteirinha[{{$i}}][convenio_id]" onchange="changeConvenioPlano(this)" required>
                                                                <option value='' selected disabled>Selecione o convenio</option>
                                                                @foreach ($convenios as $item)
                                                                    <option value="{{ $item->id }}" @if (old("carteirinha.{$i}.convenio_id") == $item->id)
                                                                        selected
                                                                    @endif>{{ $item->nome }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if($errors->has("carteirinha.{$i}.convenio_id"))
                                                                <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.convenio_id") }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                
                                                    <div class="col-sm">
                                                        <div class="form-group">
                                                            <label class="form-control-label p-0 m-0">Planos <span class="text-danger">*</span></label>
                                                            <select class="form-control select2" name="carteirinha[{{$i}}][plano_id]" id='carteirinha[{{$i}}][plano_id]' required>
                                                                <option value='' selected disabled>Selecione o plano</option>
                                                            </select>
                                                            @if($errors->has("carteirinha.{$i}.plano_id"))
                                                                <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.plano_id") }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-sm">
                                                        <label class="form-control-label p-0 m-0">Carteirinha <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="carteirinha[{{$i}}][carteirinha]" value="{{old("carteirinha.{$i}.carteirinha")}}" placeholder="Carteirinha" required>
                                                        @if($errors->has("carteirinha.{$i}.carteirinha"))
                                                            <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.carteirinha") }}</small>
                                                        @endif
                                                    </div>
                                
                                                    <div class="col-sm">
                                                        <label class="form-control-label p-0 m-0">validade <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" name="carteirinha[{{$i}}][validade]" value="{{old("carteirinha.{$i}.validade")}}" placeholder="Validade" required>
                                                        @if($errors->has("carteirinha.{$i}.validade"))
                                                            <small class="form-text text-danger">{{ $errors->first("carteirinha.{$i}.validade") }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                    <div class="add-class-carteirinha"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.pessoas.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" id="salvar" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')

    @can('habilidade_instituicao_sessao', 'cadastrar_documentos_pessoas')
        <script type="text/template" id="base-documento-item">
            <div class="card shadow-none documento-item p-0">
                <div class="row m-0 p-0">
                    <div class="col-sm-12 bg-light border-bottom">
                        <div class="row d-flex justify-content-between p-2 m-0">
                            <label class="form-control-label p-0 m-0">
                                <span class="title"></span>
                            </label>
                            <button type="button" id="adiciona-documento"
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
                                @foreach ($tipos_documentos as $tipo_documento)
                                    <option value="{{ $tipo_documento }}">{{ App\PessoaDocumento::getTipoTexto($tipo_documento) }}</option>
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
    @endcan

    <script type="text/template" id="base-carteirinha-item">
        <div class="card shadow-none carteirinha-item p-0">
            <div class="row m-0 p-0">
                <div class="col-sm-12 bg-light border-bottom">
                    <div class="row d-flex justify-content-between p-2 m-0">
                        <label class="form-control-label p-0 m-0">
                            <span class="title"></span>
                        </label>
                        <button type="button" id="adiciona-carteirinha"
                        onclick="javascript: $(this).parent().parent().parent().parent().remove();" class="btn btn-secondary" >x</button>
                    </div>
                </div>
            </div>
            <div class="row p-2 m-0">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="form-control-label p-0 m-0">Convenios <span class="text-danger">*</span></label>
                        <select class="form-control select2new" name="carteirinha[#][convenio_id]" onchange="changeConvenioPlano(this)" required>
                            <option value='' selected disabled>Selecione o convenio</option>
                            @foreach ($convenios as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label class="form-control-label p-0 m-0">Planos <span class="text-danger">*</span></label>
                        <select class="form-control select2new" name="carteirinha[#][plano_id]" required>
                            <option value='' selected disabled>Selecione o plano</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm">
                    <label class="form-control-label p-0 m-0">Carteirinha <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="carteirinha[#][carteirinha]" placeholder="Carteirinha" required>
                </div>

                <div class="col-sm">
                    <label class="form-control-label p-0 m-0">validade <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="carteirinha[#][validade]" placeholder="Validade" required>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="pessoa-nao-associada">
        <small class="form-text text-primary pessoa-nao-registrada pessoa-alerta">
            <i class="ti-check"></i> Disponível
        </small>
    </script>

    <script type="text/template" id="pessoa-associada">
        <small class="form-text text-danger pessoa-registrada pessoa-alerta">
            <i class="ti-close"></i> Proibido
        </small>
    </script>

    <script>
        var quantidade_carteirinha = 0;
        $(document).ready(function(){
            quantidade_carteirinha = $(".carteirinha-item").length
            getPlanos()
            function blockButtons() {
                $('button[id="salvar"]').prop('disabled', true);
                $('button[id="adiciona-documento"]').prop('disabled', true);
            }

            function desblockButtons() {
                $('button[id="salvar"]').prop('disabled', false);
                $('button[id="adiciona-documento"]').prop('disabled', false);
            }

            function requestPessoa(doc) {
                $(`input[name="${doc}"]`).on('change',function (e) {
                    if( ($(this).val()).length == 18 || ($(this).val()).length == 14 ) {
                        $.ajax({
                            url: '{{ route("instituicao.pessoas.getPessoa") }}',
                            method: 'POST', dataType: 'json',
                            data: { valor: $(this).val(), documento: doc, '_token': '{{ csrf_token() }}' },
                            success: function (response) {
                                console.log(response);
                                if (response.status==0) {
                                    /* Se a pessoa já estiver associada à esta instituição */
                                    $(`.${doc}-campo .pessoa-alerta`).remove();
                                    $(`.${doc}-campo`).append($($('#pessoa-associada').html()));
                                    blockButtons()
                                }
                                if (response.status==1) {
                                    /* Se a pessoa não estiver associada à esta instituição */
                                    $(`.${doc}-campo .pessoa-alerta`).remove();
                                    $(`.${doc}-campo`).append($($('#pessoa-nao-associada').html()));
                                    desblockButtons()
                                }
                            }
                        })
                    }
                });
            }

            function documentos() {
                let listaDocumentos = $('#documentos-lista');
                let ID = $('#documentos-lista .documento-item').length;
                let novoDocumento = $($('#base-documento-item').html());
                novoDocumento.attr('id', `documento_${ID}`);
                novoDocumento.find('.field').each(function(){
                    if($(this).hasClass('tipo')) $(this).attr('name', `documentos[${ID}][tipo]`);
                    if($(this).hasClass('arquivo')) {
                        $(this).attr('name', `documentos[${ID}][arquivo]`);
                    }
                    if($(this).hasClass('descricao')) $(this).attr('name', `documentos[${ID}][descricao]`);
                    $(this).on('change',function(){
                        $(this).next('.custom-file-label').html($(this).val());
                    });
                });
                novoDocumento.find('span.title').text(`Documento #${ID}`);
                listaDocumentos.append(novoDocumento);

                $('html, body').animate({
                    scrollTop: $(`#documento_${ID}`).offset().top
                }, 1000);
            }

            function personalidade() {
                let personalidade = $('select[name="personalidade"]').val();
                if(personalidade) {
                    $('#campos-fisico-juridico').show();
                    if(personalidade == 1) {
                        $('#campos-pessoa-juridica').hide();
                        $('#personalidade-selecionada').text('Pessoa Física');
                        $('#campos-pessoa-fisica').show();
                    }
                    if(personalidade == 2) {
                        $('#personalidade-selecionada').text('Pessoa Jurídica');
                        $('#campos-pessoa-fisica').show();
                        $('#campos-pessoa-juridica').show();
                    }
                }
            }

            $('.telefone').each(function(){
                $(this).setMask('(99) 99999-9999', {
                    translation: { '9': { pattern: /[0-9]/, optional: false} }
                })
            });

            $('input[name=identidade]').setMask('99.999.999-9', {
                translation: {'9': {pattern: /[0-9]/, optional: false}}
            })

            $('#adiciona-documento').on('click', function(){
                documentos();
            });

            $('select[name="personalidade"]').on('change', function(){
                personalidade();
            });

            personalidade();
            requestPessoa('cpf');
            requestPessoa('cnpj');


            $(".select2-simples").each(function(){
                $(this).select2({
                    tags: true
                });
            });

            $('.arquivo').each(function(){
                $(this).on('change',function(){
                    let fileName = $(this).val();
                    $(this).next('.custom-file-label').html(fileName);
                });
            });

            $('#formPessoas').on('submit', function(e){
                e.preventDefault()
                var formData = new FormData($(this)[0]);
                $.ajax("{{route('instituicao.pessoas.store')}}", {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: function (response) {
                        $.toast({
                            heading: response.title,
                            text: response.text,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: response.icon,
                            hideAfter: 3000,
                            stack: 10
                        });
                        if(response.icon=="success"){
                            window.location="{{ route('instituicao.pessoas.index') }}";
                        }
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') ;
                    }
                })
            })

        });

        $("#adiciona-carteirinha").on('click', function(){
            $($('#base-carteirinha-item').html()).insertBefore(".add-class-carteirinha");

            $('.select2new').select2();
            $('.select2new').removeClass('select2new');

            $("[name^='carteirinha[#]']").each(function(index, element) {
                const name = $(element).attr('name');

                $(element).attr('name', name.replace('#',quantidade_carteirinha));
            })

            quantidade_carteirinha++;
        })

        function changeConvenioPlano(element){
            var id = $('option:selected', element).val();
            var posicao = $(element).attr('name').split("").filter(n => (Number(n) || n == 0)).join("")
            getPlano(id, posicao)
        }

        function getPlanos(){
            for (let index = 0; index < quantidade_carteirinha; index++) {
                var id = $("[name^='carteirinha["+index+"][convenio_id]'").val()
                getPlano(id, index)
            }
        }

        function getPlano(id, posicao){
            if(id != ''){
                $.ajax({
                    url: "{{route('instituicao.carteirinhas.getPlanos', ['convenio_id' => 'Id'])}}".replace('Id', id),
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(retorno){

                        $("[name^='carteirinha["+posicao+"][plano_id]'").find('option').filter(':not([value=""])').remove();
                        for (i = 0; i < retorno.length; i++) {
                            $("[name^='carteirinha["+posicao+"][plano_id]'").append('<option value="'+ retorno[i].id +'">' + retorno[i].nome + '</option>');
                        }
                    }
                })
           }
        }
    </script>
@endpush
