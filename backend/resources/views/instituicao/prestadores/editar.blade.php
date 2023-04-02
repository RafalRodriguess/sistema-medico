@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Editar Prestador',
        'breadcrumb' => [
            'Prestadores' => route('instituicao.prestadores.index'),
            'Atualizar',
        ],
    ])
    @endcomponent

    <div class="card col-sm-12">
        <div class="card-body">
            <form action="{{ route('instituicao.prestadores.update', [$prestador]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group @if($errors->has('vinculos')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Vínculo <span class="text-danger">*</span></label>
                            <select name="vinculos[]" multiple style="width: 100%"
                                class="form-control campo multiplos-vinculos @if($errors->has('vinculos')) form-control-danger @endif">
                                <?php $vinculos = App\InstituicoesPrestadores::getVinculos(); ?>
                                @foreach($vinculos as $vinculo)
                                    <option value="{{ $vinculo }}" @if(in_array($vinculo, $prestador_instituicao[0]->vinculos)) selected @endif>
                                        {{ App\InstituicoesPrestadores::getVinculoTexto($vinculo) }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('vinculos'))
                                <small class="form-text text-danger">{{ $errors->first('vinculos') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card shadow-none p-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input campo" name="ativo" value="1" @if ($prestador->prestadoresInstituicoesLocal[0]->ativo=="1")
                                    checked
                                @endif @if(old('ativo')=="1") checked @endif id="ativoCheck">
                                <label class="form-check-label" for="ativoCheck">Ativo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="campos-fisico-juridico">
                    <div class="col-sm-12" id="form-pessoa-fisica"  style="display: none;">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group cpf-campo @if($errors->has('cpf')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">CPF <span class="text-danger">*</span></label>
                                    <input type="text" name="cpf" alt="cpf" value="{{ old('cpf', $prestador->cpf) }}"
                                        class="form-control campo @if($errors->has('cpf')) form-control-danger @endif">
                                    @if($errors->has('cpf'))
                                        <small class="form-text text-danger">{{ $errors->first('cpf') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" value="{{ old('nome', !empty($prestador->prestadoresInstituicoesLocal[0]->nome) ? $prestador->prestadoresInstituicoesLocal[0]->nome : $prestador->nome) }}"
                                        class="form-control campo @if($errors->has('nome')) form-control-danger @endif">
                                    @if($errors->has('nome'))
                                        <small class="form-text text-danger">{{ $errors->first('nome') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('nascimento')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Data de Nascimento <span class="text-danger">*</span></label>
                                    <input type="date" name="nascimento" alt="date" id="date" value="{{ old('nascimento', $prestador->nascimento) }}"
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
                                        <option selected value="0">Sexo</option>
                                        <option value="1" @if(old('sexo', $prestador->sexo)=="1") selected @endif>Masculino</option>
                                        <option value="2" @if(old('sexo', $prestador->sexo)=="2") selected @endif>Feminino</option>
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
                                    <label class="form-control-label p-0 m-0">RG</label>
                                    <input type="text" name="identidade" value="{{ old('identidade', $prestador->identidade) }}"
                                        class="form-control campo @if($errors->has('identidade')) form-control-danger @endif">
                                    @if($errors->has('identidade'))
                                        <small class="form-text text-danger">{{ $errors->first('identidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('identidade_orgao_expedidor')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Orgão Expedidor</label>
                                    <input type="text" name="identidade_orgao_expedidor"  value="{{ old('identidade_orgao_expedidor', $prestador->identidade_orgao_expedidor) }}"
                                        class="form-control campo @if($errors->has('identidade_orgao_expedidor')) form-control-danger @endif">
                                    @if($errors->has('identidade_orgao_expedidor'))
                                    <small class="form-text text-danger">{{ $errors->first('identidade_orgao_expedidor') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('identidade_uf')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">UF</label>
                                    <select class="form-control campo @if($errors->has('identidade_uf')) form-control-danger @endif" name="identidade_uf">
                                        <option selected value="0">Selecione</option>
                                        <option value="AC" @if ($prestador->identidade_uf == 'AC')
                                            selected
                                        @endif>Acre</option>
                                        <option value="AL" @if ($prestador->identidade_uf == 'AL')
                                            selected
                                        @endif>Alagoas</option>
                                        <option value="AP" @if ($prestador->identidade_uf == 'AP')
                                            selected
                                        @endif>Amapá</option>
                                        <option value="AM" @if ($prestador->identidade_uf == 'AM')
                                            selected
                                        @endif>Amazonas</option>
                                        <option value="BA" @if ($prestador->identidade_uf == 'BA')
                                            selected
                                        @endif>Bahia</option>
                                        <option value="CE" @if ($prestador->identidade_uf == 'CE')
                                            selected
                                        @endif>Ceará</option>
                                        <option value="DF" @if ($prestador->identidade_uf == 'DF')
                                            selected
                                        @endif>Distrito Federal</option>
                                        <option value="GO" @if ($prestador->identidade_uf == 'GO')
                                            selected
                                        @endif>Goiás</option>
                                        <option value="ES" @if ($prestador->identidade_uf == 'ES')
                                            selected
                                        @endif>Espírito Santo</option>
                                        <option value="MA" @if ($prestador->identidade_uf == 'MA')
                                            selected
                                        @endif>Maranhão</option>
                                        <option value="MT" @if ($prestador->identidade_uf == 'MT')
                                            selected
                                        @endif>Mato Grosso</option>
                                        <option value="MS" @if ($prestador->identidade_uf == 'MS')
                                            selected
                                        @endif>Mato Grosso do Sul</option>
                                        <option value="MG" @if ($prestador->identidade_uf == 'MG')
                                            selected
                                        @endif>Minas Gerais</option>
                                        <option value="PA" @if ($prestador->identidade_uf == 'PA')
                                            selected
                                        @endif>Pará</option>
                                        <option value="PB" @if ($prestador->identidade_uf == 'PB')
                                            selected
                                        @endif>Paraiba</option>
                                        <option value="PR" @if ($prestador->identidade_uf == 'PR')
                                            selected
                                        @endif>Paraná</option>
                                        <option value="PE" @if ($prestador->identidade_uf == 'PE')
                                            selected
                                        @endif>Pernambuco</option>
                                        <option value="PI" @if ($prestador->identidade_uf == 'PI')
                                            selected
                                        @endif>Piauí­</option>
                                        <option value="RJ" @if ($prestador->identidade_uf == 'RJ')
                                            selected
                                        @endif>Rio de Janeiro</option>
                                        <option value="RN" @if ($prestador->identidade_uf == 'RN')
                                            selected
                                        @endif>Rio Grande do Norte</option>
                                        <option value="RS" @if ($prestador->identidade_uf == 'RS')
                                            selected
                                        @endif>Rio Grande do Sul</option>
                                        <option value="RO" @if ($prestador->identidade_uf == 'RO')
                                            selected
                                        @endif>Rondônia</option>
                                        <option value="RR" @if ($prestador->identidade_uf == 'RR')
                                            selected
                                        @endif>Roraima</option>
                                        <option value="SP" @if ($prestador->identidade_uf == 'SP')
                                            selected
                                        @endif>São Paulo</option>
                                        <option value="SC" @if ($prestador->identidade_uf == 'SC')
                                            selected
                                        @endif>Santa Catarina</option>
                                        <option value="SE" @if ($prestador->identidade_uf == 'SE')
                                            selected
                                        @endif>Sergipe</option>
                                        <option value="TO" @if ($prestador->identidade_uf == 'TO')
                                            selected
                                        @endif>Tocantins</option>
                                    </select>
                                    @if($errors->has('identidade_uf'))
                                        <small class="form-text text-danger">{{ $errors->first('identidade_uf') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('identidade_data_expedicao')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Data de Expedição</label>
                                    <input type="text" name="identidade_data_expedicao" alt="date" value="{{ old('identidade_data_expedicao', $prestador->identidade_data_expedicao) }}"
                                        class="form-control campo @if($errors->has('identidade_data_expedicao')) form-control-danger @endif">
                                    @if($errors->has('identidade_data_expedicao'))
                                        <small class="form-text text-danger">{{ $errors->first('identidade_data_expedicao') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group @if($errors->has('telefone')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Telefone <span class="text-danger">*</span></label>
                                    <input type="text" name="telefone" value="{{ old('telefone', $prestador->prestadoresInstituicoesLocal[0]->telefone) }}"
                                        class="form-control campo telefone @if($errors->has('telefone')) form-control-danger @endif">
                                    @if($errors->has('telefone'))
                                        <div class="form-control-feedback">{{ $errors->first('telefone') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group @if($errors->has('telefone2')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Telefone 2</label>
                                    <input type="text" name="telefone2" value="{{ old('telefone2', $prestador->prestadoresInstituicoesLocal[0]->telefone2) }}"
                                        class="form-control campo telefone @if($errors->has('telefone2')) form-control-danger @endif">
                                    @if($errors->has('telefone2'))
                                        <div class="form-control-feedback">{{ $errors->first('telefone2') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('numero_cartao_sus')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Cartão SUS</label>
                                    <input type="text" name="numero_cartao_sus"  value="{{ old('numero_cartao_sus',$prestador->numero_cartao_sus) }}"
                                        class="form-control campo @if($errors->has('numero_cartao_sus')) form-control-danger @endif">
                                    @if($errors->has('numero_cartao_sus'))
                                        <small class="form-text text-danger">{{ $errors->first('numero_cartao_sus') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('email')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Email <span class="text-danger">*</span></label>
                                    <input type="text" name="email"  value="{{ old('email',$prestador->email) }}"
                                        class="form-control campo @if($errors->has('email')) form-control-danger @endif">
                                    @if($errors->has('email'))
                                        <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group @if($errors->has('nome_da_mae')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome da Mãe</label>
                                    <input type="text" name="nome_da_mae"  value="{{ old('nome_da_mae', $prestador->nome_da_mae) }}"
                                        class="form-control campo @if($errors->has('nome_da_mae')) form-control-danger @endif">
                                    @if($errors->has('nome_da_mae'))
                                        <small class="form-text text-danger">{{ $errors->first('nome_da_mae') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group @if($errors->has('nome_do_pai')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome do Pai</label>
                                    <input type="text" name="nome_do_pai"  value="{{ old('nome_do_pai',$prestador->nome_do_pai) }}"
                                        class="form-control campo @if($errors->has('nome_do_pai')) form-control-danger @endif">
                                    @if($errors->has('nome_do_pai'))
                                        <small class="form-text text-danger">{{ $errors->first('nome_do_pai') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('nacionalidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nacionalidade</label>
                                    <input type="text" name="nacionalidade"  value="{{ old('nacionalidade', $prestador->nacionalidade) }}"
                                        class="form-control campo @if($errors->has('nacionalidade')) form-control-danger @endif">
                                    @if($errors->has('nacionalidade'))
                                        <small class="form-text text-danger">{{ $errors->first('nacionalidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('naturalidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Naturalidade</label>
                                    <input type="text" name="naturalidade"  value="{{ old('naturalidade', $prestador->naturalidade) }}"
                                        class="form-control campo @if($errors->has('naturalidade')) form-control-danger @endif">
                                    @if($errors->has('naturalidade'))
                                        <small class="form-text text-danger">{{ $errors->first('naturalidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('carga_horaria_mensal')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Carga</label>
                                    <input type="number" name="carga_horaria_mensal"  value="{{ old('carga_horaria_mensal', $prestador->prestadoresInstituicoesLocal[0]->carga_horaria_mensal) }}"
                                        class="form-control campo @if($errors->has('carga_horaria_mensal')) form-control-danger @endif">
                                    @if($errors->has('carga_horaria_mensal'))
                                        <small class="form-text text-danger">{{ $errors->first('carga_horaria_mensal') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('tipo')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Atuação do Prestador <span class="text-danger">*</span></label>
                                    <select name="tipo" class="form-control campo @if($errors->has('tipo')) form-control-danger @endif">
                                        <option disabled selected>Selecione</option>
                                        <?php $tipos = App\InstituicoesPrestadores::getTipos(); ?>
                                        @foreach($tipos as $tipo)
                                            <option value="{{ $tipo }}" @if($prestador->prestadoresInstituicoesLocal[0]->tipo==$tipo) selected @endif>
                                                {{ App\InstituicoesPrestadores::getTipoTexto($tipo) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('tipo'))
                                        <small class="form-text text-danger">{{ $errors->first('tipo') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('instituicao_usuario_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Usuario vinculado</label>
                                    <select name="instituicao_usuario_id" class="form-control select2 @if($errors->has('instituicao_usuario_id')) form-control-danger @endif" style="width: 100%">
                                        <option value="">Nenhum</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}" @if(old('instituicao_usuario_id', $prestador->prestadoresInstituicoesLocal[0]->instituicao_usuario_id) == $usuario->id) selected @endif>
                                                {{ $usuario->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('instituicao_usuario_id'))
                                        <small class="form-text text-danger">{{ $errors->first('instituicao_usuario_id') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-sm-3">
                                <div class="form-check form-check-inline" style="margin-top: 20px;">
                                    <input type="checkbox" class="form-check-input medico-checkbox "
                                        id="exibir_dataCheck" name="exibir_data" value="1" @if(old('exibir_data', $prestador->prestadoresInstituicoesLocal[0]->exibir_data)=="1") checked @endif>
                                    <label class="form-check-label" for="exibir_dataCheck">Exibir data prontuario</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-check form-check-inline" style="margin-top: 20px;">
                                    <input type="checkbox" class="form-check-input medico-checkbox "
                                        id="exibir_titulo_pacienteCheck" name="exibir_titulo_paciente" value="1" @if(old('exibir_titulo_paciente', $prestador->prestadoresInstituicoesLocal[0]->exibir_titulo_paciente)=="1") checked @endif>
                                    <label class="form-check-label" for="exibir_titulo_pacienteCheck">Exibir titulo e nome paciente prontuarios</label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-check form-check-inline" style="margin-top: 20px;">
                                    <input type="checkbox" class="form-check-input medico-checkbox "
                                        id="whatsapp_enviar_confirm_agenda_check" name="whatsapp_enviar_confirm_agenda" value="1" @if(old('whatsapp_enviar_confirm_agenda', $prestador->prestadoresInstituicoesLocal[0]->whatsapp_enviar_confirm_agenda)=="1") checked @endif>
                                    <label class="form-check-label" for="whatsapp_enviar_confirm_agenda_check">whatsapp enviar confirmação de agenda</label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-check form-check-inline" style="margin-top: 20px;">
                                    <input type="checkbox" class="form-check-input medico-checkbox "
                                        id="whatsapp_receber_agenda_check" name="whatsapp_receber_agenda" value="1" @if(old('whatsapp_receber_agenda', $prestador->prestadoresInstituicoesLocal[0]->whatsapp_receber_agenda)=="1") checked @endif>
                                    <label class="form-check-label" for="whatsapp_receber_agenda_check">whatsapp receber agenda diária</label>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="display: none;" id="campos-funcionario">
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('pis')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">PIS <span class="text-primary">*</span></label>
                                    <input type="text" name="pis"  value="{{ old('pis', $prestador->prestadoresInstituicoesLocal[0]->pis) }}"
                                        class="form-control campo @if($errors->has('pis')) form-control-danger @endif">
                                    @if($errors->has('pis'))
                                        <div class="form-control-feedback">{{ $errors->first('pis') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('pasep')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">PASEP <span class="text-primary">*</span></label>
                                    <input type="text" name="pasep"  value="{{ old('pasep', $prestador->prestadoresInstituicoesLocal[0]->pasep) }}"
                                        class="form-control campo @if($errors->has('pasep')) form-control-danger @endif">
                                    @if($errors->has('pasep'))
                                        <div class="form-control-feedback">{{ $errors->first('pasep') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('nir')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">NIR <span class="text-primary">*</span></label>
                                    <input type="text" name="nir"  value="{{ old('nir', $prestador->prestadoresInstituicoesLocal[0]->nir) }}"
                                        class="form-control campo @if($errors->has('nir')) form-control-danger @endif">
                                    @if($errors->has('nir'))
                                        <div class="form-control-feedback">{{ $errors->first('nir') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('proe')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Proe <span class="text-primary">*</span></label>
                                    <input type="text" name="proe"  value="{{ old('proe', $prestador->prestadoresInstituicoesLocal[0]->proe) }}"
                                        class="form-control campo @if($errors->has('proe')) form-control-danger @endif">
                                    @if($errors->has('proe'))
                                        <div class="form-control-feedback">{{ $errors->first('proe') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display: none;" id="numero-cooperativa">
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('numero_cooperativa')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Número da Cooperativa <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_cooperativa"  value="{{ old('numero_cooperativa', $prestador->prestadoresInstituicoesLocal[0]->numero_cooperativa) }}"
                                        class="form-control campo numero_cooperativa @if($errors->has('numero_cooperativa')) form-control-danger @endif">
                                    @if($errors->has('numero_cooperativa'))
                                        <small class="form-text text-danger">{{ $errors->first('numero_cooperativa') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 p-0">
                            <div class="card shadow-none p-3">
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group @if($errors->has('tipo_conselho_id')) has-danger @endif">
                                            <label class="form-control-label p-0 m-0">Conselhos</label>
                                            <select style="width: 100%" class="form-control campo conselhos_options" name="tipo_conselho_id">
                                                <?php $tipos_conselhos = App\InstituicoesPrestadores::getTiposConselhos(); ?>
                                                <option selected value="">Nenhum</option>
                                                @foreach ($tipos_conselhos as $tipo)
                                                    <option value="{{ $tipo }}" @if(old('tipo_conselho_id', $prestador->prestadoresInstituicoesLocal[0]->tipo_conselho_id)==$tipo) selected @endif>{{ App\InstituicoesPrestadores::getTipoConselhoTexto($tipo) }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('tipo_conselho_id'))
                                                <small class="form-text text-danger">{{ $errors->first('tipo_conselho_id') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group @if($errors->has('conselho_uf')) has-danger @endif">
                                            <label class="form-control-label p-0 m-0">UF</label>
                                            <select class="form-control campo conselhos_options" name="conselho_uf">
                                                <option selected value="">Selecione</option>
                                                <option value="AC" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'AC')
                                                    selected="selected"
                                                @endif>Acre</option>
                                                <option value="AL" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'AL')
                                                    selected="selected"
                                                @endif>Alagoas</option>
                                                <option value="AP" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'AP')
                                                    selected="selected"
                                                @endif>Amapá</option>
                                                <option value="AM" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'AM')
                                                    selected="selected"
                                                @endif>Amazonas</option>
                                                <option value="BA" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'BA')
                                                    selected="selected"
                                                @endif>Bahia</option>
                                                <option value="CE" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'CE')
                                                    selected="selected"
                                                @endif>Ceará</option>
                                                <option value="DF" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'DF')
                                                    selected="selected"
                                                @endif>Distrito Federal</option>
                                                <option value="GO" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'GO')
                                                    selected="selected"
                                                @endif>Goiás</option>
                                                <option value="ES" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'ES')
                                                    selected="selected"
                                                @endif>Espírito Santo</option>
                                                <option value="MA" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'MA')
                                                    selected="selected"
                                                @endif>Maranhão</option>
                                                <option value="MT" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'MT')
                                                    selected="selected"
                                                @endif>Mato Grosso</option>
                                                <option value="MS" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'MS')
                                                    selected="selected"
                                                @endif>Mato Grosso do Sul</option>
                                                <option value="MG" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'MG')
                                                    selected="selected"
                                                @endif>Minas Gerais</option>
                                                <option value="PA" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'PA')
                                                    selected="selected"
                                                @endif>Pará</option>
                                                <option value="PB" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'PB')
                                                    selected="selected"
                                                @endif>Paraiba</option>
                                                <option value="PR" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'PR')
                                                    selected="selected"
                                                @endif>Paraná</option>
                                                <option value="PE" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'PE')
                                                    selected="selected"
                                                @endif>Pernambuco</option>
                                                <option value="PI" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'PI')
                                                    selected="selected"
                                                @endif>Piauí­</option>
                                                <option value="RJ" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'RJ')
                                                    selected="selected"
                                                @endif>Rio de Janeiro</option>
                                                <option value="RN" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'RN')
                                                    selected="selected"
                                                @endif>Rio Grande do Norte</option>
                                                <option value="RS" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'RS')
                                                    selected="selected"
                                                @endif>Rio Grande do Sul</option>
                                                <option value="RO" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'RO')
                                                    selected="selected"
                                                @endif>Rondônia</option>
                                                <option value="RR" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'RR')
                                                    selected="selected"
                                                @endif>Roraima</option>
                                                <option value="SP" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'SP')
                                                    selected="selected"
                                                @endif>São Paulo</option>
                                                <option value="SC" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'SC')
                                                    selected="selected"
                                                @endif>Santa Catarina</option>
                                                <option value="SE" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'SE')
                                                    selected="selected"
                                                @endif>Sergipe</option>
                                                <option value="TO" @if (old('conselho_uf', $prestador->prestadoresInstituicoesLocal[0]->conselho_uf) == 'TO')
                                                    selected="selected"
                                                @endif>Tocantins</option>
                                            </select>
                                            @if($errors->has('conselho_uf'))
                                                <small class="form-text text-danger">{{ $errors->first('conselho_uf') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display: none;" id="campos-medico">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group @if($errors->has('crm')) has-danger @endif">
                                            <label class="form-control-label p-0 m-0">CRM </label>
                                            <input type="text" name="crm"  value="{{ old('crm', $prestador->prestadoresInstituicoesLocal[0]->crm) }}"
                                                class="form-control campo @if($errors->has('crm')) form-control-danger @endif">
                                            @if($errors->has('crm'))
                                                <div class="form-control-feedback">{{ $errors->first('crm') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group @if($errors->has('especialidades')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Especialidades Médica <span class="text-danger">*</span></label>
                                    <select class="form-control campo multiplas-especialidades" name="especialidades[]" multiple
                                        style="width: 100%">
                                        @foreach ($especialidades as $item)
                                            <option value="{{ $item->id }}"
                                                @if($especialidade)
                                                    @for ($i = 0; $i < count($especialidade); $i++)
                                                        @if ($item->id == $especialidade[$i]->id)
                                                            selected
                                                        @endif
                                                    @endfor
                                                @endif
                                            >{{ $item->descricao }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('especialidades'))
                                        <small class="form-text text-danger">{{ $errors->first('especialidades') }}</small>
                                    @endif
                                </div>

                                <div class="form-group @if($errors->has('especializacoes')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Especializações</label>
                                    <select id="especializacoes-select-" class="form-control select2 campo multiplas-especializacoes select2" name="especializacoes[][especializacoes_id]" multiple
                                        style="width: 100%">
                                       <option disabled value="">Selecione</option>
                                       @foreach ($especializacoes as $item)
                                            <option value="{{ $item->id }}" {{ (in_array($item->id, $especializacoes_escolhidas)) ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('especializacoes'))
                                        <small class="form-text text-danger">{{ $errors->first('especializacoes') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="card shadow-none">
                                    <div class="row p-3 m-0">
                                        <div class="col-sm-6">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input campo medico-checkbox "
                                                    id="anestesistaCheck" name="anestesista" value="1" @if($prestador->prestadoresInstituicoesLocal[0]->anestesista=="1") checked @endif>
                                                <label class="form-check-label" for="anestesistaCheck">Anestesista</label>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input campo medico-checkbox "
                                                    id="auxiliarCheck" name="auxiliar" value="1" @if($prestador->prestadoresInstituicoesLocal[0]->auxiliar=="1") checked @endif>
                                                <label class="form-check-label" for="auxiliarCheck">Auxiliar</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label p-0 m-0">Tipo prontuário principal:</label>
                                        <select class="form-control" name="tipo_prontuario" style="width: 100%">
                                            <option value="livre" {{ ($prestador->prestadoresInstituicoesLocal[0]->tipo_prontuario == "livre") ? 'selected' : '' }}>Livre</option>
                                            <option value="padrao" {{ ($prestador->prestadoresInstituicoesLocal[0]->tipo_prontuario == "padrao") ? 'selected' : '' }}>Padrão</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label p-0 m-0">Tipo resumo:</label>
                                        <select class="form-control" name="resumo_tipo" style="width: 100%">
                                            <option value="1" {{ ($prestador->prestadoresInstituicoesLocal[0]->resumo_tipo == "1") ? 'selected' : '' }}>Fechado</option>
                                            <option value="2" {{ ($prestador->prestadoresInstituicoesLocal[0]->resumo_tipo == "2") ? 'selected' : '' }}>Aberto</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12" id="form-pessoa-juridica" style="display: none;">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group cnpj-campo @if($errors->has('cnpj')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">CNPJ</label>
                                    <input type="text" name="cnpj" alt="cnpj" value="{{ old('cnpj', $prestador->cnpj) }}"
                                        class="form-control campo @if($errors->has('cnpj')) form-control-danger @endif">
                                    @if($errors->has('cnpj'))
                                        <small class="form-text text-danger">{{ $errors->first('cnpj') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('razao_social')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Razão Social <span class="text-danger">*</span></label>
                                    <input type="text" name="razao_social" value="{{ old('razao_social', $prestador->razao_social) }}"
                                        class="form-control campo @if($errors->has('razao_social')) form-control-danger @endif">
                                    @if($errors->has('razao_social'))
                                        <small class="form-text text-danger">{{ $errors->first('razao_social') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('nome_banco')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Banco</label>
                                    <input type="text" name="nome_banco" value="{{ old('nome_banco', $prestador->prestadoresInstituicoesLocal[0]->nome_banco) }}"
                                        class="form-control campo @if($errors->has('nome_banco')) form-control-danger @endif">
                                    @if($errors->has('nome_banco'))
                                        <small class="form-text text-danger">{{ $errors->first('nome_banco') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('agencia')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Agencia</label>
                                    <input type="text" name="agencia" value="{{ old('agencia', $prestador->prestadoresInstituicoesLocal[0]->agencia) }}"
                                        class="form-control campo @if($errors->has('agencia')) form-control-danger @endif">
                                    @if($errors->has('agencia'))
                                        <small class="form-text text-danger">{{ $errors->first('agencia') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('conta_bancaria')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Conta Bancaria</label>
                                    <input type="text" name="conta_bancaria" value="{{ old('conta_bancaria', $prestador->prestadoresInstituicoesLocal[0]->conta_bancaria) }}"
                                        class="form-control campo @if($errors->has('conta_bancaria')) form-control-danger @endif">
                                    @if($errors->has('conta_bancaria'))
                                        <small class="form-text text-danger">{{ $errors->first('conta_bancaria') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('cep')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">CEP</label>
                                    <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep', $prestador->cep) }}"
                                        class="form-control campo @if($errors->has('cep')) form-control-danger @endif">
                                    @if($errors->has('cep'))
                                        <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('estado')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Estado</label>
                                    <select id="estado" class="form-control campo @if($errors->has('estado')) form-control-danger @endif" name="estado">
                                        <option selected disabled value="0">Selecione</option>
                                        <option value="AC" if @if ($prestador->estado == 'AC') selected @endif>Acre</option>
                                        <option value="AL" @if ($prestador->estado == 'AL') selected @endif>Alagoas</option>
                                        <option value="AP" @if ($prestador->estado == 'AP') selected @endif>Amapá</option>
                                        <option value="AM" @if ($prestador->estado == 'AM') selected @endif>Amazonas</option>
                                        <option value="BA" @if ($prestador->estado == 'BA') selected @endif>Bahia</option>
                                        <option value="CE" @if ($prestador->estado == 'CE') selected @endif>Ceará</option>
                                        <option value="DF" @if ($prestador->estado == 'DF')
                                            selected
                                        @endif>Distrito Federal</option>
                                        <option value="GO" @if ($prestador->estado == 'GO')
                                            selected
                                        @endif>Goiás</option>
                                        <option value="ES" @if ($prestador->estado == 'ES')
                                            selected
                                        @endif>Espírito Santo</option>
                                        <option value="MA" @if ($prestador->estado == 'MA')
                                            selected
                                        @endif>Maranhão</option>
                                        <option value="MT" @if ($prestador->estado == 'MT')
                                            selected
                                        @endif>Mato Grosso</option>
                                        <option value="MS" @if ($prestador->estado == 'MS')
                                            selected
                                        @endif>Mato Grosso do Sul</option>
                                        <option value="MG" @if ($prestador->estado == 'MG')
                                            selected
                                        @endif>Minas Gerais</option>
                                        <option value="PA" @if ($prestador->estado == 'PA')
                                            selected
                                        @endif>Pará</option>
                                        <option value="PB" @if ($prestador->estado == 'PB')
                                            selected
                                        @endif>Paraiba</option>
                                        <option value="PR" @if ($prestador->estado == 'PR')
                                            selected
                                        @endif>Paraná</option>
                                        <option value="PE" @if ($prestador->estado == 'PE')
                                            selected
                                        @endif>Pernambuco</option>
                                        <option value="PI" @if ($prestador->estado == 'PI')
                                            selected
                                        @endif>Piauí­</option>
                                        <option value="RJ" @if ($prestador->estado == 'RJ')
                                            selected
                                        @endif>Rio de Janeiro</option>
                                        <option value="RN" @if ($prestador->estado == 'RN')
                                            selected
                                        @endif>Rio Grande do Norte</option>
                                        <option value="RS" @if ($prestador->estado == 'RS')
                                            selected
                                        @endif>Rio Grande do Sul</option>
                                        <option value="RO" @if ($prestador->estado == 'RO')
                                            selected
                                        @endif>Rondônia</option>
                                        <option value="RR" @if ($prestador->estado == 'RR')
                                            selected
                                        @endif>Roraima</option>
                                        <option value="SP" @if ($prestador->estado == 'SP')
                                            selected
                                        @endif>São Paulo</option>
                                        <option value="SC" @if ($prestador->estado == 'SC')
                                            selected
                                        @endif>Santa Catarina</option>
                                        <option value="SE" @if ($prestador->estado == 'SE')
                                            selected
                                        @endif>Sergipe</option>
                                        <option value="TO" @if ($prestador->estado == 'TO')
                                            selected
                                        @endif>Tocantins</option>
                                    </select>
                                    @if($errors->has('estado'))
                                        <small class="form-text text-danger">{{ $errors->first('estado') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Cidade</label>
                                    <input id="cidade" type="text" name="cidade" value="{{ old('cidade', $prestador->cidade) }}"
                                        class="form-control campo @if($errors->has('cidade')) form-control-danger @endif">
                                    @if($errors->has('cidade'))
                                        <small class="form-text text-danger">{{ $errors->first('cidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Bairro</label>
                                    <input id="bairro" type="text" name="bairro" value="{{ old('bairro', $prestador->bairro) }}"
                                        class="form-control campo @if($errors->has('bairro')) form-control-danger @endif">
                                    @if($errors->has('bairro'))
                                        <small class="form-text text-danger">{{ $errors->first('bairro') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('rua')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Rua</label>
                                    <input type="text" name="rua" id="rua" value="{{ old('rua', $prestador->rua) }}"
                                        class="form-control campo @if($errors->has('rua')) form-control-danger @endif">
                                    @if($errors->has('rua'))
                                        <small class="form-text text-danger">{{ $errors->first('rua') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('numero')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Numero</label>
                                    <input type="text" name="numero" id="numero" value="{{ old('numero', $prestador->numero) }}"
                                        class="form-control @if($errors->has('numero')) form-control-danger campo @endif">
                                    @if($errors->has('numero'))
                                        <small class="form-text text-danger">{{ $errors->first('numero') }}</small>
                                    @endif
                                </div>
                            </div>


                            @if($instituicao->possui_faturamento_sancoop == 1)
                            <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-control-label p-0 m-0">Código do prestador na Sancoop (contatar suporte caso não esteja exibindo)</label>
                                    <input readonly="readonly" type="text" value="{{ $prestador->sancoop_cod_coperado }} ({{ $prestador->sancoop_desc_prestador }})"
                                        class="form-control">
                            </div>
                            </div>

                            <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-control-label p-0 m-0">Usuário do prestador na Sancoop (contatar suporte caso não esteja exibindo)</label>
                                <input type="text" name="sancoop_user_coperado" id="sancoop_user_coperado" value="{{ old('sancoop_user_coperado', $prestador->sancoop_user_coperado) }}"
                                class="form-control @if($errors->has('sancoop_user_coperado')) form-control-danger campo @endif">
                            </div>
                             </div>
                            @endif

                            @if($instituicao->telemedicina_integrado == 1)
                            <div class="col-sm-12">
                            <div class="form-group">
                                <input type="checkbox" class="form-check-input medico-checkbox "
                                        id="telemedicina_integrado" name="telemedicina_integrado" value="1" @if(old('telemedicina_integrado', $prestador->prestadoresInstituicoesLocal[0]->telemedicina_integrado)=="1") checked @endif>
                                    <label class="form-check-label" for="telemedicina_integrado">Possui atendimento por Telemedicina</label>
                            </div>
                            </div>

                            @endif

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow-none bg-light">
                            <div class="row d-flex justify-content-between p-2 m-0">
                                <label class="form-control-label p-0 m-0">Exceções de procedimentos faturados/prestador</label>
                                <button type="button" class="btn btn-success" id="adiciona-excessao">+</button>
                            </div>
                        </div>
                        <div class="col-sm-12 p-0 m-0" id="excessao-lista">

                            @if(old('excessao'))
                                @for ($i = 0; $i < count(old('excessao')) ; $i ++)

                                    <div class="card shadow-none excessao-item p-0" id="{{ $i }}">
                                        <div class="row m-0 p-0">
                                            <div class="col-sm-12 bg-light border-bottom">
                                                <div class="row d-flex justify-content-between p-2 m-0">
                                                    <label class="form-control-label p-0 m-0">
                                                        <span class="title"></span>
                                                    </label>
                                                    <button type="button" id="remover-excessao"
                                                    onclick="javascript: $(this).parent().parent().parent().parent().remove();" class="btn btn-secondary" >x</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-2 m-0">
                                            <div class="col-sm-6 @if($errors->has("excessao.{$i}.procedimento_id")) has-danger @endif">
                                                <label class="form-control-label p-0 m-0">Procedimento<span class="text-danger">*</span></label>
                                                <select name="excessao[{{$i}}][procedimento_id]" class="form-control select2ProcedimentoPesquisaOld" required>
                                                </select>
                                                @if($errors->get("excessao.{$i}.procedimento_id"))
                                                    <small class="form-text text-danger">{{ $errors->first("excessao.{$i}.procedimento_id") }}</small>
                                                @endif
                                            </div>
                                            <div class="col-sm-6 @if($errors->has("excessao.{$i}.prestador_faturado_id")) has-danger @endif">
                                                <label class="form-control-label p-0 m-0">Prestador<span class="text-danger">*</span></label>
                                                <select name="excessao[{{$i}}][prestador_faturado_id]" class="form-control select2" required>
                                                    @foreach ($prestadores as $item)
                                                        <option value="{{$item->id}}" @if (old("excessao.{$i}.prestador_faturado_id") == $item->id)
                                                            selected
                                                        @endif>{{$item->prestador->nome}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->get("excessao.{$i}.prestador_faturado_id"))
                                                    <small class="form-text text-danger">{{ $errors->first("excessao.{$i}.prestador_faturado_id") }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @else
                                @for ($i = 0; $i < count($prestador->prestadoresInstituicoesLocal[0]->procedimentosExcessoes) ; $i ++)

                                    <div class="card shadow-none excessao-item p-0" id="{{ $i }}">
                                        <div class="row m-0 p-0">
                                            <div class="col-sm-12 bg-light border-bottom">
                                                <div class="row d-flex justify-content-between p-2 m-0">
                                                    <label class="form-control-label p-0 m-0">
                                                        <span class="title"></span>
                                                    </label>
                                                    <button type="button" id="remover-excessao"
                                                    onclick="javascript: $(this).parent().parent().parent().parent().remove();" class="btn btn-secondary" >x</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-2 m-0">
                                            <div class="col-sm-6 @if($errors->has("excessao.{$i}.procedimento_id")) has-danger @endif">
                                                <label class="form-control-label p-0 m-0">Procedimento<span class="text-danger">*</span></label>
                                                <select name="excessao[{{$i}}][procedimento_id]" class="form-control select2ProcedimentoPesquisaOld" required>
                                                    <option value="{{$prestador->prestadoresInstituicoesLocal[0]->procedimentosExcessoes[$i]->pivot->procedimento_id}}" >{{$prestador->prestadoresInstituicoesLocal[0]->procedimentosExcessoes[$i]->descricao}}</option>
                                                </select>
                                                @if($errors->get("excessao.{$i}.procedimento_id"))
                                                    <small class="form-text text-danger">{{ $errors->first("excessao.{$i}.procedimento_id") }}</small>
                                                @endif
                                            </div>
                                            <div class="col-sm-6 @if($errors->has("excessao.{$i}.prestador_faturado_id")) has-danger @endif">
                                                <label class="form-control-label p-0 m-0">Prestador<span class="text-danger">*</span></label>
                                                <select name="excessao[{{$i}}][prestador_faturado_id]" class="form-control select2" required style="width: 100%">
                                                    @foreach ($prestadores as $item)
                                                        <option value="{{$item->id}}" @if (old("excessao.{$i}.prestador_faturado_id", $prestador->prestadoresInstituicoesLocal[0]->procedimentosExcessoes[$i]->pivot->prestador_faturado_id) == $item->id)
                                                            selected
                                                        @endif>{{$item->prestador->nome}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->get("excessao.{$i}.prestador_faturado_id"))
                                                    <small class="form-text text-danger">{{ $errors->first("excessao.{$i}.prestador_faturado_id") }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endfor  
                            @endif
                            <div class="add-class-excessao"></div>
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
    <script type="text/template" id="base-excessao-item">
        <div class="card shadow-none excessao-item p-0">

            <div class="row m-0 p-0">
                <div class="col-sm-12 bg-light border-bottom">
                    <div class="row d-flex justify-content-between p-2 m-0">
                        <label class="form-control-label p-0 m-0">
                            <span class="title"></span>
                        </label>
                        <button type="button" id="adiciona-excessao"
                        onclick="javascript: $(this).parent().parent().parent().parent().remove();" class="btn btn-secondary" >x</button>
                    </div>
                </div>
            </div>

            <div class="row p-2 m-0">
                <div class="col-sm-6">
                    <label class="form-control-label p-0 m-0">Procedimento<span class="text-danger">*</span></label>
                    <select name="excessao[#][procedimento_id]" class="form-control select2ProcedimentoPesquisa" required>
                    </select>
                </div>
                <div class="col-sm-6 ">
                    <label class="form-control-label p-0 m-0">Prestador<span class="text-danger">*</span></label>
                    <select name="excessao[#][prestador_faturado_id]" class="form-control select2new" >
                        @foreach ($prestadores as $item)
                            <option value="{{$item->id}}" >{{$item->prestador->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </script>

    <script>
        var quantidade_excessao = 0;
        $( document ).ready(function() {
            quantidade_excessao = $(".excessao-item").length

            $(".select2ProcedimentoPesquisaOld").select2({
                placeholder: "Pesquise por procedimento",
                allowClear: true,
                minimumInputLength: 3,
                language: {
                searching: function () {
                    return 'Buscando procedimentos (aguarde antes de selecionar)…';
                },

                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                },
                },

                ajax: {
                    url:"{{route('instituicao.procedimentosAtendimentos.getProcedimentoGerais')}}",
                    dataType: 'json',
                    delay: 100,

                    data: function (params) {
                    return {
                        q: params.term || '', // search term
                        page: params.page || 1
                    };
                    },
                    processResults: function (data, params) {
                    params.page = params.page || 1;

                    // console.log(data.results)
                    return {
                        results: _.map(data.results, item => ({
                            id: Number.parseInt(item.id),
                            text: `${item.descricao}`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                    },
                    cache: true
                },

            })

            $('.telefone').each(function(){
                $(this).setMask('(99) 99999-9999', {
                    translation: { '9': { pattern: /[0-9]/, optional: false} }
                })
            });


            function personalidade(){
                // O campo vinculo relativo à personalidade é definido como
                // 5 = pessoa física
                // 6 = pessoa jurídica
                let values = $('[name="vinculos[]"]').val()
                const personalidade_fisica = values.includes('5')
                if(personalidade_fisica){
                    $('#campos-fisico-juridico #form-pessoa-juridica').hide();
                    $('#campos-fisico-juridico #form-pessoa-fisica').show();
                    requestDocmento('cpf');
                }
                const personalidade_juridica = values.includes('6')
                if(personalidade_juridica){
                    $('#campos-fisico-juridico #form-pessoa-fisica').show();
                    $('#campos-fisico-juridico #form-pessoa-juridica').show();
                    requestDocmento('cpf');
                    requestDocmento('cnpj');
                }
                if(!personalidade_fisica && !personalidade_juridica) {
                    $('#campos-fisico-juridico #form-pessoa-fisica').hide();
                    $('#campos-fisico-juridico #form-pessoa-juridica').hide();
                }
            }

            $("#adiciona-excessao").on('click', function(){
                $($('#base-excessao-item').html()).insertBefore(".add-class-excessao");

                $('.select2new').select2();
                $('.select2new').removeClass('select2new');
                $(".select2ProcedimentoPesquisa").select2({
                    placeholder: "Pesquise por procedimento",
                    allowClear: true,
                    minimumInputLength: 3,
                    language: {
                    searching: function () {
                        return 'Buscando procedimentos (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                    },

                    ajax: {
                        url:"{{route('instituicao.procedimentosAtendimentos.getProcedimentoGerais')}}",
                        dataType: 'json',
                        delay: 100,

                        data: function (params) {
                        return {
                            q: params.term || '', // search term
                            page: params.page || 1
                        };
                        },
                        processResults: function (data, params) {
                        params.page = params.page || 1;

                        // console.log(data.results)
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.descricao}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                        },
                        cache: true
                    },

                })
                $('.select2ProcedimentoPesquisa').removeClass('select2ProcedimentoPesquisa');

                $("[name^='excessao[#]']").each(function(index, element) {
                    const name = $(element).attr('name');

                    $(element).attr('name', name.replace('#',quantidade_excessao));
                })

                quantidade_excessao++;
            })


            function tipo(){
                var tipo = $('[name="tipo"]').val()
                if(tipo == 2 || tipo == 3 || tipo == 6 || tipo == 7 || tipo == 8 || tipo == 9 || tipo == 10 || tipo == 15){
                    $('#campos-medico').show();
                }else{
                    $('#campos-medico').hide();
                }
            }

            function vinculo(){
                let values = $('[name="vinculos[]"]').val();
                if(values){
                    if(values.includes('2') || values.includes('3')){
                        $('#campos-funcionario').show();
                    }
                    if(!values.includes('2') && !values.includes('3')){
                        $('#campos-funcionario').hide();
                    }
                    if(values.includes('1')){
                        $('#numero-cooperativa').show();
                    }
                    if(!values.includes('1')){
                        $('#numero-cooperativa').hide();
                    }
                }
            }

            $('.multiplas-especialidades').select2();
            $('.multiplos-vinculos').select2();
            // $('[name="tipo_conselho_id"]').select2();

            $('[name="tipo"]').on('change', function(){
                tipo();
            });
            $('[name="vinculos[]"]').on('change', function(){
                personalidade();
                vinculo();
            });
            $('input[name=identidade]').setMask('99.999.999-9', {
                translation: {'9': {pattern: /[0-9]/, optional: false}}
            })
            $('input[name=numero_cartao_sus]').setMask('999 9999 9999 9999', {
                translation: { '9': { pattern: /[0-9]/, optional: false} }
            })

            $('[name="especializacoes[]"]').select2()

            personalidade();
            vinculo();
            tipo()


        })

        function requestDocmento(doc) {
                $(`input[name="${doc}"]`).on('change',function (e) {
                    if( ($(this).val()).length == 18 || ($(this).val()).length == 14 ) {
                        $.ajax({
                            url: '{{route("instituicao.getprestador")}}',
                            method: 'POST', dataType: 'json',
                            data: { valor: $(this).val(), documento: doc, '_token': '{{ csrf_token() }}' },
                            success: function (response) {
                                console.log(response);
                                if (response.status==0) {
                                    /* Se o prestador já estiver regitrado
                                        e asssociado à esta instituicao */
                                    removePreviousAlertMessage();
                                    insertAlertMessage('prestador-indisponivel-message', doc);
                                    desblockCampos();
                                    limparCampos(doc);
                                    blockCampos(doc);
                                    blockButtons();
                                }
                                if (response.status==1) {
                                    /* O prestador já está registrado mas não está
                                        assossiado à essa instituicao */
                                    removePreviousAlertMessage();
                                    insertAlertMessage('prestador-permitido-message', doc);
                                    desblockCampos();
                                    limparCampos(doc);
                                    preencherCampos(response.data, doc);
                                    desblockButtons();
                                }
                                if (response.status==2) {
                                    /* O prestador ainda não está registrado */
                                    removePreviousAlertMessage();
                                    insertAlertMessage('prestador-disponivel-message', doc);
                                    desblockCampos();
                                    limparCampos(doc);
                                    desblockButtons();
                                }

                            }
                        })
                    }
                });
            }
    </script>
@endpush
