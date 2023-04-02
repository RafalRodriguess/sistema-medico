@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Prestador',
        'breadcrumb' => [
            'Prestadores' => route('instituicao.prestadores.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">
        <div class="card-body">
            <form action="{{ route('instituicao.prestadores.store') }}" method="post" enctype="multipart/form-data">
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
                    <div class="col-sm-6">
                        <div class="card shadow-none p-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input campo" name="ativo" value="1" checked id="ativoCheck">
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
                                    <input type="date" name="nascimento" alt="date"" id="date" value="{{ old('nascimento') }}"
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
                                        <option selected disabled value="0">Sexo</option>
                                        <option value="1" @if(old('sexo')=="1") selected @endif>Masculino</option>
                                        <option value="2" @if(old('sexo')=="2") selected @endif>Feminino</option>
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
                                    <input type="text" name="identidade"  value="{{ old('identidade') }}"
                                        class="form-control campo @if($errors->has('identidade')) form-control-danger @endif">
                                    @if($errors->has('identidade'))
                                        <small class="form-text text-danger">{{ $errors->first('identidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('identidade_orgao_expedidor')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Orgão Expedidor</label>
                                    <input type="text" name="identidade_orgao_expedidor"  value="{{ old('identidade_orgao_expedidor') }}"
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
                                    <label class="form-control-label p-0 m-0">Data de Expedição</label>
                                    <input type="text" name="identidade_data_expedicao" alt="date" value="{{ old('identidade_data_expedicao') }}"
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
                                    <input type="text" name="telefone" value="{{ old('telefone') }}"
                                        class="form-control campo telefone @if($errors->has('telefone')) form-control-danger @endif">
                                    @if($errors->has('telefone'))
                                        <div class="form-control-feedback">{{ $errors->first('telefone') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group @if($errors->has('telefone2')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Telefone 2</label>
                                    <input type="text" name="telefone2" value="{{ old('telefone2') }}"
                                        class="form-control campo telefone2 @if($errors->has('telefone2')) form-control-danger @endif">
                                    @if($errors->has('telefone2'))
                                        <div class="form-control-feedback">{{ $errors->first('telefone2') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('numero_cartao_sus')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Cartão SUS</label>
                                    <input type="text" name="numero_cartao_sus"  value="{{ old('numero_cartao_sus') }}"
                                        class="form-control campo @if($errors->has('numero_cartao_sus')) form-control-danger @endif">
                                    @if($errors->has('numero_cartao_sus'))
                                        <small class="form-text text-danger">{{ $errors->first('numero_cartao_sus') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('email')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Email <span class="text-danger">*</span></label>
                                    <input type="text" name="email"  value="{{ old('email') }}"
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
                                    <input type="text" name="nome_da_mae"  value="{{ old('nome_da_mae') }}"
                                        class="form-control campo @if($errors->has('nome_da_mae')) form-control-danger @endif">
                                    @if($errors->has('nome_da_mae'))
                                        <small class="form-text text-danger">{{ $errors->first('nome_da_mae') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group @if($errors->has('nome_do_pai')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome do Pai</label>
                                    <input type="text" name="nome_do_pai"  value="{{ old('nome_do_pai') }}"
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
                                    <input type="text" name="nacionalidade"  value="{{ old('nacionalidade') }}"
                                        class="form-control campo @if($errors->has('nacionalidade')) form-control-danger @endif">
                                    @if($errors->has('nacionalidade'))
                                        <small class="form-text text-danger">{{ $errors->first('nacionalidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('naturalidade')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Naturalidade</label>
                                    <input type="text" name="naturalidade"  value="{{ old('naturalidade') }}"
                                        class="form-control campo @if($errors->has('naturalidade')) form-control-danger @endif">
                                    @if($errors->has('naturalidade'))
                                        <small class="form-text text-danger">{{ $errors->first('naturalidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('carga_horaria_mensal')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Carga</label>
                                    <input type="number" name="carga_horaria_mensal"  value="{{ old('carga_horaria_mensal') }}"
                                        class="form-control campo @if($errors->has('carga_horaria_mensal')) form-control-danger @endif">
                                    @if($errors->has('carga_horaria_mensal'))
                                        <small class="form-text text-danger">{{ $errors->first('carga_horaria_mensal') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('tipo')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Atuação do Prestador <span class="text-danger">*</span></label>
                                    <select style="width: 100%" name="tipo" class="form-control campo
                                    @if($errors->has('tipo')) form-control-danger @endif">
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
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('instituicao_usuario_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Usuario vinculado</label>
                                    <select name="instituicao_usuario_id" class="form-control select2 @if($errors->has('instituicao_usuario_id')) form-control-danger @endif" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}" @if(old('instituicao_usuario_id')==$usuario->id) selected @endif>
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
                                <div class="form-check form-check-inline" style="">
                                    <input type="checkbox" class="form-check-input medico-checkbox"
                                        id="exibir_dataCheck" name="exibir_data" value="1" @if(old('exibir_data')=="1") checked @endif>
                                    <label class="form-check-label" for="exibir_dataCheck">Exibir data prontuario</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-check form-check-inline" style="">
                                    <input type="checkbox" class="form-check-input medico-checkbox"
                                        id="exibir_titulo_pacienteCheck" name="exibir_titulo_paciente" value="1" @if(old('exibir_titulo_paciente')=="1") checked @endif>
                                    <label class="form-check-label" for="exibir_titulo_pacienteCheck">Exibir titulo e nome paciente prontuarios</label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-check form-check-inline" style="">
                                    <input type="checkbox" class="form-check-input medico-checkbox"
                                        id="whatsapp_enviar_confirm_agenda_check" name="whatsapp_enviar_confirm_agenda" value="1" @if(old('whatsapp_enviar_confirm_agenda')=="1") checked @endif>
                                    <label class="form-check-label" for="whatsapp_enviar_confirm_agenda_check">whatsapp enviar confirmação de agenda</label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-check form-check-inline" style="">
                                    <input type="checkbox" class="form-check-input medico-checkbox"
                                        id="whatsapp_receber_agenda_check" name="whatsapp_receber_agenda" value="1" @if(old('whatsapp_receber_agenda')=="1") checked @endif>
                                    <label class="form-check-label" for="whatsapp_receber_agenda_check">whatsapp receber agenda diária</label>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="display: none;" id="campos-funcionario">
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('pis')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">PIS </label>
                                    <input type="text" name="pis"  value="{{ old('pis') }}"
                                        class="form-control campo @if($errors->has('pis')) form-control-danger @endif">
                                    @if($errors->has('pis'))
                                        <div class="form-control-feedback">{{ $errors->first('pis') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('pasep')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">PASEP </label>
                                    <input type="text" name="pasep"  value="{{ old('pasep') }}"
                                        class="form-control campo @if($errors->has('pasep')) form-control-danger @endif">
                                    @if($errors->has('pasep'))
                                        <div class="form-control-feedback">{{ $errors->first('pasep') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('nir')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">NIR </label>
                                    <input type="text" name="nir"  value="{{ old('nir') }}"
                                        class="form-control campo @if($errors->has('nir')) form-control-danger @endif">
                                    @if($errors->has('nir'))
                                        <div class="form-control-feedback">{{ $errors->first('nir') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group @if($errors->has('proe')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Proe </label>
                                    <input type="text" name="proe"  value="{{ old('proe') }}"
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
                                    <label class="form-control-label p-0 m-0">Número da Cooperativa</label>
                                    <input type="text" name="numero_cooperativa"  value="{{ old('numero_cooperativa') }}"
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
                                                    <option value="{{ $tipo }}" @if(old('tipo_conselho_id')==$tipo) selected @endif>{{ App\InstituicoesPrestadores::getTipoConselhoTexto($tipo) }}</option>
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
                                                <option value="AC" @if (old('conselho_uf') == 'AC')
                                                    selected="selected"
                                                @endif>Acre</option>
                                                <option value="AL" @if (old('conselho_uf') == 'AL')
                                                    selected="selected"
                                                @endif>Alagoas</option>
                                                <option value="AP" @if (old('conselho_uf') == 'AP')
                                                    selected="selected"
                                                @endif>Amapá</option>
                                                <option value="AM" @if (old('conselho_uf') == 'AM')
                                                    selected="selected"
                                                @endif>Amazonas</option>
                                                <option value="BA" @if (old('conselho_uf') == 'BA')
                                                    selected="selected"
                                                @endif>Bahia</option>
                                                <option value="CE" @if (old('conselho_uf') == 'CE')
                                                    selected="selected"
                                                @endif>Ceará</option>
                                                <option value="DF" @if (old('conselho_uf') == 'DF')
                                                    selected="selected"
                                                @endif>Distrito Federal</option>
                                                <option value="GO" @if (old('conselho_uf') == 'GO')
                                                    selected="selected"
                                                @endif>Goiás</option>
                                                <option value="ES" @if (old('conselho_uf') == 'ES')
                                                    selected="selected"
                                                @endif>Espírito Santo</option>
                                                <option value="MA" @if (old('conselho_uf') == 'MA')
                                                    selected="selected"
                                                @endif>Maranhão</option>
                                                <option value="MT" @if (old('conselho_uf') == 'MT')
                                                    selected="selected"
                                                @endif>Mato Grosso</option>
                                                <option value="MS" @if (old('conselho_uf') == 'MS')
                                                    selected="selected"
                                                @endif>Mato Grosso do Sul</option>
                                                <option value="MG" @if (old('conselho_uf') == 'MG')
                                                    selected="selected"
                                                @endif>Minas Gerais</option>
                                                <option value="PA" @if (old('conselho_uf') == 'PA')
                                                    selected="selected"
                                                @endif>Pará</option>
                                                <option value="PB" @if (old('conselho_uf') == 'PB')
                                                    selected="selected"
                                                @endif>Paraiba</option>
                                                <option value="PR" @if (old('conselho_uf') == 'PR')
                                                    selected="selected"
                                                @endif>Paraná</option>
                                                <option value="PE" @if (old('conselho_uf') == 'PE')
                                                    selected="selected"
                                                @endif>Pernambuco</option>
                                                <option value="PI" @if (old('conselho_uf') == 'PI')
                                                    selected="selected"
                                                @endif>Piauí­</option>
                                                <option value="RJ" @if (old('conselho_uf') == 'RJ')
                                                    selected="selected"
                                                @endif>Rio de Janeiro</option>
                                                <option value="RN" @if (old('conselho_uf') == 'RN')
                                                    selected="selected"
                                                @endif>Rio Grande do Norte</option>
                                                <option value="RS" @if (old('conselho_uf') == 'RS')
                                                    selected="selected"
                                                @endif>Rio Grande do Sul</option>
                                                <option value="RO" @if (old('conselho_uf') == 'RO')
                                                    selected="selected"
                                                @endif>Rondônia</option>
                                                <option value="RR" @if (old('conselho_uf') == 'RR')
                                                    selected="selected"
                                                @endif>Roraima</option>
                                                <option value="SP" @if (old('conselho_uf') == 'SP')
                                                    selected="selected"
                                                @endif>São Paulo</option>
                                                <option value="SC" @if (old('conselho_uf') == 'SC')
                                                    selected="selected"
                                                @endif>Santa Catarina</option>
                                                <option value="SE" @if (old('conselho_uf') == 'SE')
                                                    selected="selected"
                                                @endif>Sergipe</option>
                                                <option value="TO" @if (old('conselho_uf') == 'TO')
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
                        <div id="campos-medico" style="display: none;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group @if($errors->has('crm')) has-danger @endif">
                                                <label class="form-control-label p-0 m-0">CRM </label>
                                                <input type="text" name="crm"  value="{{ old('crm') }}"
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
                                        <select id="especialidades-select" class="form-control campo multiplas-especialidades" name="especialidades[]" multiple
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
                                                >{{ $especialidade->descricao }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('especialidades'))
                                            <small class="form-text text-danger">{{ $errors->first('especialidades') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group @if($errors->has('especializacoes')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Especializações <span class="text-danger">*</span></label>
                                        <select id="especializacoes-select-" class="form-control campo multiplas-especializacoes select2" name="especializacoes[][especializacoes_id]" multiple style="width: 100%">
                                            <option value="" disabled>Selecione</option>
                                            @foreach($especializacoes as $especializacao)
                                                <option value="{{$especializacao->id}}" >{{$especializacao->descricao}}</option>
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
                                                        id="anestesistaCheck" name="anestesista" value="1" @if(old('anestesista')=="1") checked @endif>
                                                    <label class="form-check-label" for="anestesistaCheck">Anestesista</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-check form-check-inline">
                                                    <input type="checkbox" class="form-check-input campo medico-checkbox "
                                                        id="auxiliarCheck" name="auxiliar" value="1" @if(old('auxiliar')=="1") checked @endif>
                                                    <label class="form-check-label" for="auxiliarCheck">Auxiliar</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-control-label p-0 m-0">Tipo prontuário principal:</label>
                                            <select class="form-control" name="tipo_prontuario" style="width: 100%">
                                                <option value="livre" @if(old('tipo_prontuario')=="livre") checked @endif>Livre</option>
                                                <option value="padrao" @if(old('tipo_prontuario')=="padrao") checked @endif>Padrão</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-control-label p-0 m-0">Tipo resumo:</label>
                                            <select class="form-control" name="resumo_tipo" style="width: 100%">
                                                <option value="1" @if(old('resumo_tipo')=="1") checked @endif>Fechado</option>
                                                <option value="2" @if(old('resumo_tipo')=="2") checked @endif>Aberto</option>
                                            </select>
                                        </div>
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
                                <div class="form-group @if($errors->has('nome_banco')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Banco</label>
                                    <input type="text" name="nome_banco" value="{{ old('nome_banco') }}"
                                        class="form-control campo @if($errors->has('nome_banco')) form-control-danger @endif">
                                    @if($errors->has('nome_banco'))
                                        <small class="form-text text-danger">{{ $errors->first('nome_banco') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('agencia')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Agencia</label>
                                    <input type="text" name="agencia" value="{{ old('agencia') }}"
                                        class="form-control campo @if($errors->has('agencia')) form-control-danger @endif">
                                    @if($errors->has('agencia'))
                                        <small class="form-text text-danger">{{ $errors->first('agencia') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('conta_bancaria')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Conta Bancaria</label>
                                    <input type="text" name="conta_bancaria" value="{{ old('conta_bancaria') }}"
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
                                    <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep') }}"
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
                                    <label class="form-control-label p-0 m-0">Cidade</label>
                                    <input id="cidade" type="text" name="cidade" value="{{ old('cidade') }}"
                                        class="form-control campo @if($errors->has('cidade')) form-control-danger @endif">
                                    @if($errors->has('cidade'))
                                        <small class="form-text text-danger">{{ $errors->first('cidade') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Bairro</label>
                                    <input id="bairro" type="text" name="bairro" value="{{ old('bairro') }}"
                                        class="form-control campo @if($errors->has('bairro')) form-control-danger @endif">
                                    @if($errors->has('bairro'))
                                        <small class="form-text text-danger">{{ $errors->first('bairro') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('rua')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Rua</label>
                                    <input type="text" name="rua" id="rua" value="{{ old('rua') }}"
                                        class="form-control campo @if($errors->has('rua')) form-control-danger @endif">
                                    @if($errors->has('rua'))
                                        <small class="form-text text-danger">{{ $errors->first('rua') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group @if($errors->has('numero')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Numero</label>
                                    <input type="text" name="numero" id="numero" value="{{ old('numero') }}"
                                        class="form-control @if($errors->has('numero')) form-control-danger campo @endif">
                                    @if($errors->has('numero'))
                                        <small class="form-text text-danger">{{ $errors->first('numero') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="row">
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
                                                    <label class="form-control-label p-0 m-0">Tipo de Documento<span class="text-danger">*</span></label>
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
                </div> --}}
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
                    <button type="submit" name="continue" value="1" class="btn btn-primary waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar e ir par agenda</button>
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

            function removePreviousAlertMessage() {
                $(`.prestador-alert-message`).each(function(){
                    $(this).remove();
                });
            }

            function insertAlertMessage(alert_id, campo){
                let campoInput = $(`.${campo}-campo`)[0];
                $(`.${campo}-campo .prestador-alert-message`).remove();
                let alert = $($(`#${alert_id}`).html())[0];
                campoInput.appendChild(alert)
            }

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

            function documentos(){
                function hasClass(elemento, classe) {
                    return (' ' + elemento.className + ' ').indexOf(' ' + classe + ' ') > -1;
                }
                // document.querySelector('#adiciona-documento').addEventListener('click', ()=>{
                //     let lista_documentos = document.querySelector('#documentos-lista')
                //     let id = lista_documentos.querySelectorAll('.documento-item').length
                //     let new_documento = $($('#base-documento-item').html())[0]
                //     new_documento.setAttribute('id', `${id}`);
                //     let timestamp = new Date().getTime();
                //     let newID = `arquivo_${timestamp}`;
                //     new_documento.querySelectorAll('.field').forEach((field)=>{
                //         if(hasClass(field, 'tipo')) field.name = `documentos[${id}][tipo]`
                //         if(hasClass(field, 'arquivo')) {
                //             field.name = `documentos[${id}][arquivo]`;
                //             field.id = newID;
                //         }
                //         if(hasClass(field, 'descricao')) field.name = `documentos[${id}][descricao]`
                //     })
                //     new_documento.querySelector('span.title').textContent = `Documento #${id}`;
                //     lista_documentos.appendChild(new_documento);
                //     $(`#${newID}`).on('change',function(){
                //         var fileName = $(this).val();
                //         $(this).next('.custom-file-label').html(fileName);
                //     });
                // })
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
                                    // limparCampos(doc);
                                    preencherCampos(response.data, doc);
                                    desblockButtons();
                                }
                                if (response.status==2) {
                                    /* O prestador ainda não está registrado */
                                    removePreviousAlertMessage();
                                    insertAlertMessage('prestador-disponivel-message', doc);
                                    desblockCampos();
                                    // limparCampos(doc);
                                    desblockButtons();
                                }

                            }
                        })
                    }
                });
            }

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

            function blockButtons(){
                $('#submit').prop('disabled', true);
                $('#adiciona-documento').prop('disabled', true);
            }

            function desblockButtons(){
                $('#submit').prop('disabled', false);
                $('#adiciona-documento').prop('disabled', false);
            }

            function blockCampos(doc_tipo) {
                $('select option').each(function(){
                    $(this).prop('disabled', true);
                });
                $('input').each(function(){
                    if($(this).attr('name')!=doc_tipo && $(this).attr('name')!='_token'){
                        $(this).prop('readonly', true);
                    }
                });
            }

            function desblockCampos() {
                $('option:disabled').each(function(){
                    $(this).prop('disabled', false);
                });
                $('input[readonly]').each(function(){
                    $(this).prop('readonly', false);
                });
            }

            function limparCampos(doc_tipo) {

                $('input').each(function(){
                    if($(this).attr('name')!=doc_tipo && $(this).attr('name')!='_token'){
                        $(this).val(null);
                    }
                });
            }

            function preencherCampos(data, doc_tipo){
                let selects = [
                    'personalidade', 'estado',
                    'identidade_uf', 'sexo'
                ];
                for(var campo in data ){
                    if(selects.includes(campo)){
                        let select = $(`select[name="${campo}"]`);
                        select.find('option').each(function(){
                            if($(this).val()==data[campo]){
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('disabled', true);
                            }
                        })
                    } else {
                        let input = $(`input[name="${campo}"]`);
                        input.val(data[campo])
                        if(input.prop('name')!=doc_tipo){
                            input.prop('readonly', true);
                        }
                    }
                }
                personalidade();
            }

            $('[name="tipo"]').select2();
            $('[name="tipo_conselho_id"]').select2();
            $('.multiplas-especialidades').select2().on('select2:select', () => $('#especializacoes-select').empty());
            $('.multiplos-vinculos').select2();

            $('[name="tipo"]').on('change', function(){
                tipo();
            });
            $('[name="vinculos[]"]').on('change', function(){
                vinculo();
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

            $('#especializacoes-select').select2({
                placeholder: "Selecione o procedimento",
                ajax: {
                    url: '{{route("instituicao.ajax.buscarespecializacoes")}}',
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    multiple: true,
                    data: function(params) {
                        return {
                            ids: $('#especialidades-select').val(),
                            search: params.term,
                            '_token': '{{csrf_token()}}',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.descricao
                                };
                            })
                        }
                    }
                },
                escapeMarkup: function(m) {
                    return m;
                }
            });

            personalidade();
            vinculo();
            tipo()
            documentos();
            requestDocmento('cpf');
            requestDocmento('cnpj');

        })
    </script>
@endpush


