
@csrf

<div class="row">

    <div class="col-sm-6">
        <div class="form-group @if($errors->has('personalidade')) has-danger @endif">
            <label class="form-control-label p-0 m-0">Personalidade <span class="text-danger">*</span></label>
            <select name="personalidade"
                class="form-control campo @if($errors->has('personalidade')) form-control-danger @endif">
                <option selected disabled>Personalidade</option>
                @foreach ($personalidades as $personalidade)
                    <option value="{{ $personalidade }}" @if ($pessoa->personalidade==$personalidade)
                        selected
                    @endif @if(old('personalidade')==$personalidade) selected @endif>{{ App\Pessoa::getPersonalidadeTexto($personalidade) }}</option>
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
                    <option value="{{ $tipo }}" @if ($pessoa->tipo==$tipo)
                        selected
                    @endif @if(old('tipo')==$tipo) selected @endif>{{ App\Pessoa::getTipoTexto($tipo) }}</option>
                @endforeach
            </select>
            @if($errors->has('tipo'))
                <small class="form-text text-danger">{{ $errors->first('tipo') }}</small>
            @endif
        </div>
    </div>

</div>

<!-- INTEGRAÇÃO ASAPLAN LEGENDA SITUAÇÕES DO PLANO -->
@if (!empty($instituicao) && $instituicao->integracao_asaplan == 1)

<div class="col-sm-12 p-0 m-0">

    <div class="card col-sm-12">

        <div class="row mb-3">
            <div class="col-sm-12 border-bottom bg-light p-3">
                <label class="form-control-label p-0 m-0">Dados do Associado</label>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="form-control-label p-0 m-0">Tipo de associado</label>

                    {{-- <input type="text" value="@if ($pessoa->asaplan_tipo == 1) {{ 'Titular' }} @elseif($pessoa->asaplan_tipo == 2) {{ 'Beneficiário' }} @else {{ 'Não Associado' }} @endif"
                        class="form-control campo" disabled> --}}

                        <select class="form-control campo" name="asaplan_tipo"
                        @if (!\Gate::check('habilidade_instituicao_sessao', 'editar_dados_integracao_pacientes_asaplan'))
                        disabled
                        @endif
                        >

                            <option value="" @if ($pessoa->asaplan_tipo=='')
                                selected
                            @endif>Não Associado</option>

                            <option value="1" @if ($pessoa->asaplan_tipo==1)
                                selected
                            @endif @if (old('asaplan_tipo') == 1)
                                selected="selected"
                            @endif>Titular</option>

                            <option value="2" @if ($pessoa->asaplan_tipo==2)
                                selected
                            @endif @if (old('asaplan_tipo') == 2)
                                selected="selected"
                            @endif>Beneficiário</option>
                
                        </select>
 
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="form-control-label p-0 m-0">Código no plano</label>
                    <input type="text" value="{{ old('asaplan_chave_plano', $pessoa->asaplan_chave_plano) }}" name="asaplan_chave_plano"
                        class="form-control campo" 
                        @if (!\Gate::check('habilidade_instituicao_sessao', 'editar_dados_integracao_pacientes_asaplan'))
                        disabled
                        @endif
                        >
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="form-control-label p-0 m-0">Situação no plano</label>
                    {{-- <input type="text" value="@if ($pessoa->asaplan_situacao_plano == 1) {{ 'Ativo' }} @elseif($pessoa->asaplan_situacao_plano == 2) {{ 'Suspenso' }} @elseif($pessoa->asaplan_situacao_plano == 3) {{ 'Cancelado' }} @else {{ 'Não Associado' }} @endif"
                        class="form-control campo" disabled> --}}

                        <select class="form-control campo" name="asaplan_situacao_plano"
                        @if (!\Gate::check('habilidade_instituicao_sessao', 'editar_dados_integracao_pacientes_asaplan'))
                        disabled
                        @endif
                        >

                            <option value="" @if ($pessoa->asaplan_situacao_plano=='')
                                selected
                            @endif>Não Associado</option>

                            <option value="1" @if ($pessoa->asaplan_situacao_plano==1)
                                selected
                            @endif @if (old('asaplan_situacao_plano') == 1)
                                selected="selected"
                            @endif>Ativo</option>

                            <option value="2" @if ($pessoa->asaplan_situacao_plano==2)
                                selected
                            @endif @if (old('asaplan_situacao_plano') == 2)
                                selected="selected"
                            @endif>Suspenso</option>

                            <option value="3" @if ($pessoa->asaplan_situacao_plano==3)
                                selected
                            @endif @if (old('asaplan_situacao_plano') == 3)
                                selected="selected"
                            @endif>Cancelado</option>
                
                        </select>

                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="form-control-label p-0 m-0">Nome do titular</label>
                    <input type="text" value="{{ old('asaplan_nome_titular', $pessoa->asaplan_nome_titular) }}" name="asaplan_nome_titular"
                        class="form-control campo" 
                        @if (!\Gate::check('habilidade_instituicao_sessao', 'editar_dados_integracao_pacientes_asaplan'))
                        disabled
                        @endif
                        >
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="form-control-label p-0 m-0">Filial</label>
                    
                    {{-- <input type="text" value="{{($filial_asaplan) ? $filial_asaplan->descricao : ''}}"
                        class="form-control campo" disabled> --}}
                        <select class="form-control campo" name="asaplan_filial"
                        @if (!\Gate::check('habilidade_instituicao_sessao', 'editar_dados_integracao_pacientes_asaplan'))
                        disabled
                        @endif
                        >

                        @if(!empty($filiais_instituicoes))
                            @foreach ($filiais_instituicoes as $filiail_instituicao)
                             <option value="{{ $filiail_instituicao->id }}" @if(old('asaplan_filial', $pessoa->asaplan_filial)==$filiail_instituicao->id) selected @endif>{{ $filiail_instituicao->descricao }}</option>
                            @endforeach
                        @endif;

                        </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="form-control-label p-0 m-0">Última atualização</label>
                    <input type="text" value="{{($pessoa->asaplan_ultima_atualizacao) ? date("d/m/Y", strtotime($pessoa->asaplan_ultima_atualizacao)) : ''}}"
                        class="form-control campo" disabled>
                </div>
            </div>

        </div>

    </div>

</div>

@endif
<!-- FIM INTEGRAÇÃO ASAPLAN -->

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
                        <label class="form-control-label p-0 m-0">CPF @if(!empty($campos_obg->cpf)) <span class="text-danger">*</span> @endif</label>
                        <input type="text" name="cpf" data-prev="{{ $pessoa->cpf }}" alt="cpf" value="{{ old('cpf', $pessoa->cpf) }}"
                            class="form-control campo @if($errors->has('cpf')) form-control-danger @endif">
                        @if($errors->has('cpf'))
                            <small class="form-text text-danger">{{ $errors->first('cpf') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Nome <span class="text-primary">*</span></label>
                        <input type="text" name="nome" value="{{ old('nome', $pessoa->nome) }}"
                            class="form-control campo @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                            <small class="form-text text-danger">{{ $errors->first('nome') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('nascimento')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Dt Nascimento @if(!empty($campos_obg->nascimento)) <span class="text-danger">*</span> @endif</label>
                        <input type="date" name="nascimento" value="{{ old('nascimento', $pessoa->nascimento) }}" class="form-control campo @if($errors->has('nascimento')) form-control-danger @endif" >
                        @if($errors->has('nascimento'))
                            <small class="form-text text-danger">{{ $errors->first('nascimento') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('telefone1')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Telefone 1 @if(!empty($campos_obg->telefone1)) <span class="text-danger">*</span> @endif</label>
                        <input type="text" name="telefone1" value="{{ old('telefone1', $pessoa->telefone1) }}"
                            class="form-control campo telefone @if($errors->has('telefone1')) form-control-danger @endif">
                        @if($errors->has('telefone1'))
                            <small class="form-text text-danger">{{ $errors->first('telefone1') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('telefone2')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Telefone 2 @if(!empty($campos_obg->telefone2)) <span class="text-danger">*</span> @endif</label>
                        <input type="text" name="telefone2" value="{{ old('telefone2', $pessoa->telefone2) }}"
                            class="form-control campo telefone @if($errors->has('telefone2')) form-control-danger @endif">
                        @if($errors->has('telefone2'))
                            <small class="form-text text-danger">{{ $errors->first('telefone2') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('telefone3')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Telefone 3 @if(!empty($campos_obg->telefone3)) <span class="text-danger">*</span> @endif</label>
                        <input type="text" name="telefone3" value="{{ old('telefone3', $pessoa->telefone3) }}"
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

                                <option value="{{ $item }}" @if(old('sexo', $pessoa->sexo)==$item) selected @endif>{{ App\Pessoa::getSexoTexto($item) }}</option>
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
                        <input type="text" name="email" value="{{ old('email', $pessoa->email) }}"
                            class="form-control campo @if($errors->has('email')) form-control-danger @endif">
                        @if($errors->has('email'))
                            <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group @if($errors->has('identidade')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Identidade</label>
                        <input type="text" name="identidade" id="identidade" value="{{ old('identidade', $pessoa->identidade) }}" class="form-control campo @if($errors->has('identidade')) form-control-danger @endif">
                        @if($errors->has('identidade'))
                            <small class="form-text text-danger">{{ $errors->first('identidade') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group @if($errors->has('orgao_expedidor')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Orgão expedidor</label>
                        <input type="text" name="orgao_expedidor" value="{{ old('orgao_expedidor', $pessoa->orgao_expedidor) }}" class="form-control campo @if($errors->has('orgao_expedidor')) form-control-danger @endif">
                        @if($errors->has('orgao_expedidor'))
                            <small class="form-text text-danger">{{ $errors->first('orgao_expedidor') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group @if($errors->has('data_emissao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Emissão</label>
                        <input type="date" name="data_emissao" value="{{ old('data_emissao', $pessoa->data_emissao) }}" class="form-control campo @if($errors->has('data_emissao')) form-control-danger @endif" >
                        @if($errors->has('data_emissao'))
                            <small class="form-text text-danger">{{ $errors->first('data_emissao') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group @if($errors->has('nome_mae')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Nome da mãe</label>
                        <input type="text" name="nome_mae" value="{{ old('nome_mae', $pessoa->nome_mae)}}"
                            class="form-control campo @if($errors->has('nome_mae')) form-control-danger @endif">
                        @if($errors->has('nome_mae'))
                            <small class="form-text text-danger">{{ $errors->first('nome_mae') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group @if($errors->has('nome_pai')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Nome do pai</label>
                        <input type="text" name="nome_pai" value="{{ old('nome_pai', $pessoa->nome_pai) }}"
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
                                <option value="{{ $item }}" @if(old('estado_civil', $pessoa->estado_civil)==$item) selected @endif>{{ $item }}</option>
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
                        <input type="text" name="naturalidade" value="{{ old('naturalidade', $pessoa->naturalidade) }}"
                            class="form-control campo @if($errors->has('naturalidade')) form-control-danger @endif">
                        @if($errors->has('naturalidade'))
                            <small class="form-text text-danger">{{ $errors->first('naturalidade') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('profissao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Profissão</label>
                        <input type="text" name="profissao" value="{{ old('profissao', $pessoa->profissao) }}"
                            class="form-control campo @if($errors->has('profissao')) form-control-danger @endif">
                        @if($errors->has('profissao'))
                            <small class="form-text text-danger">{{ $errors->first('profissao') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('indicacao_descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Indicação</label>
                        <input type="text" name="indicacao_descricao" value="{{ old('indicacao_descricao', $pessoa->indicacao_descricao) }}"
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
                        <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep', $pessoa->cep) }}"
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
                            <option value="AC" @if ($pessoa->estado=="AC")
                                selected
                            @endif @if (old('estado') == 'AC')
                                selected="selected"
                            @endif>Acre</option>
                            <option value="AL" @if ($pessoa->estado=="AL")
                                selected
                            @endif @if (old('estado') == 'AL')
                                selected="selected"
                            @endif>Alagoas</option>
                            <option value="AP" @if ($pessoa->estado=="AP")
                                selected
                            @endif @if (old('estado') == 'AP')
                                selected="selected"
                            @endif>Amapá</option>
                            <option value="AM" @if ($pessoa->estado=="AM")
                                selected
                            @endif @if (old('estado') == 'AM')
                                selected="selected"
                            @endif>Amazonas</option>
                            <option value="BA" @if ($pessoa->estado=="BA")
                                selected
                            @endif @if (old('estado') == 'BA')
                                selected="selected"
                            @endif>Bahia</option>
                            <option value="CE" @if ($pessoa->estado=="CE")
                                selected
                            @endif @if (old('estado') == 'CE')
                                selected="selected"
                            @endif>Ceará</option>
                            <option value="DF" @if ($pessoa->estado=="DF")
                                selected
                            @endif @if (old('estado') == 'DF')
                                selected="selected"
                            @endif>Distrito Federal</option>
                            <option value="GO" @if ($pessoa->estado=="GO")
                                selected
                            @endif @if (old('estado') == 'GO')
                                selected="selected"
                            @endif>Goiás</option>
                            <option value="ES" @if ($pessoa->estado=="ES")
                                selected
                            @endif @if (old('estado') == 'ES')
                                selected="selected"
                            @endif>Espírito Santo</option>
                            <option value="MA" @if ($pessoa->estado=="MA")
                                selected
                            @endif @if (old('estado') == 'MA')
                                selected="selected"
                            @endif>Maranhão</option>
                            <option value="MT" @if ($pessoa->estado=="MT")
                                selected
                            @endif @if (old('estado') == 'MT')
                                selected="selected"
                            @endif>Mato Grosso</option>
                            <option value="MS" @if ($pessoa->estado=="MS")
                                selected
                            @endif @if (old('estado') == 'MS')
                                selected="selected"
                            @endif>Mato Grosso do Sul</option>
                            <option value="MG" @if ($pessoa->estado=="MG")
                                selected
                            @endif @if (old('estado') == 'MG')
                                selected="selected"
                            @endif>Minas Gerais</option>
                            <option value="PA" @if ($pessoa->estado=="PA")
                                selected
                            @endif @if (old('estado') == 'PA')
                                selected="selected"
                            @endif>Pará</option>
                            <option value="PB" @if ($pessoa->estado=="PB")
                                selected
                            @endif @if (old('estado') == 'PB')
                                selected="selected"
                            @endif>Paraiba</option>
                            <option value="PR" @if ($pessoa->estado=="PR")
                                selected
                            @endif @if (old('estado') == 'PR')
                                selected="selected"
                            @endif>Paraná</option>
                            <option value="PE" @if ($pessoa->estado=="PE")
                                selected
                            @endif @if (old('estado') == 'PE')
                                selected="selected"
                            @endif>Pernambuco</option>
                            <option value="PI" @if ($pessoa->estado=="PI")
                                selected
                            @endif @if (old('estado') == 'PI')
                                selected="selected"
                            @endif>Piauí­</option>
                            <option value="RJ" @if ($pessoa->estado=="RJ")
                                selected
                            @endif @if (old('estado') == 'RJ')
                                selected="selected"
                            @endif>Rio de Janeiro</option>
                            <option value="RN" @if ($pessoa->estado=="RN")
                                selected
                            @endif @if (old('estado') == 'RN')
                                selected="selected"
                            @endif>Rio Grande do Norte</option>
                            <option value="RS" @if ($pessoa->estado=="RS")
                                selected
                            @endif @if (old('estado') == 'RS')
                                selected="selected"
                            @endif>Rio Grande do Sul</option>
                            <option value="RO" @if ($pessoa->estado=="RO")
                                selected
                            @endif @if (old('estado') == 'RO')
                                selected="selected"
                            @endif>Rondônia</option>
                            <option value="RR" @if ($pessoa->estado=="RR")
                                selected
                            @endif @if (old('estado') == 'RR')
                                selected="selected"
                            @endif>Roraima</option>
                            <option value="SP" @if ($pessoa->estado=="SP")
                                selected
                            @endif @if (old('estado') == 'SP')
                                selected="selected"
                            @endif>São Paulo</option>
                            <option value="SC" @if ($pessoa->estado=="SC")
                                selected
                            @endif @if (old('estado') == 'SC')
                                selected="selected"
                            @endif>Santa Catarina</option>
                            <option value="SE" @if ($pessoa->estado=="SE")
                                selected
                            @endif @if (old('estado') == 'SE')
                                selected="selected"
                            @endif>Sergipe</option>
                            <option value="TO" @if ($pessoa->estado=="TO")
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

                <div class="col-sm-4">
                    <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Cidade @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                        <input id="cidade" type="text" name="cidade" value="{{ old('cidade', $pessoa->cidade) }}"
                            class="form-control campo @if($errors->has('cidade')) form-control-danger @endif">
                        @if($errors->has('cidade'))
                            <small class="form-text text-danger">{{ $errors->first('cidade') }}</small>
                        @endif
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-4">
                    <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Bairro @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                        <input id="bairro" type="text" name="bairro" value="{{ old('bairro', $pessoa->bairro) }}"
                            class="form-control campo @if($errors->has('bairro')) form-control-danger @endif">
                        @if($errors->has('bairro'))
                            <small class="form-text text-danger">{{ $errors->first('bairro') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group @if($errors->has('rua')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Rua @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                        <input type="text" name="rua" id="rua" value="{{ old('rua', $pessoa->rua) }}"
                            class="form-control campo @if($errors->has('rua')) form-control-danger @endif">
                        @if($errors->has('rua'))
                            <small class="form-text text-danger">{{ $errors->first('rua') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group @if($errors->has('numero')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Numero @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                        <input type="text" name="numero" id="numero" value="{{ old('numero', $pessoa->numero) }}"
                            class="form-control @if($errors->has('numero')) form-control-danger campo @endif">
                        @if($errors->has('numero'))
                            <small class="form-text text-danger">{{ $errors->first('numero') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group @if($errors->has('complemento')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Complemento</label>
                        <input type="text" name="complemento" id="complemento" value="{{ old('complemento', $pessoa->complemento) }}"
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
                        <input type="text" name="cnpj" data-prev="{{ $pessoa->cnpj }}" alt="cnpj" value="{{ old('cnpj', $pessoa->cnpj) }}"
                            class="form-control campo @if($errors->has('cnpj')) form-control-danger @endif">
                        @if($errors->has('cnpj'))
                            <small class="form-text text-danger">{{ $errors->first('cnpj') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('nome_fantasia')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Nome Fantasia<span class="text-danger">*</span></label>
                        <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia', $pessoa->nome_fantasia) }}"
                            class="form-control campo @if($errors->has('nome_fantasia')) form-control-danger @endif">
                        @if($errors->has('nome_fantasia'))
                            <small class="form-text text-danger">{{ $errors->first('nome_fantasia') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-7">
                    <div class="form-group @if($errors->has('razao_social')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Razão Social<span class="text-danger">*</span></label>
                        <input type="text" name="razao_social" value="{{ old('razao_social', $pessoa->razao_social) }}"
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
                        <label class="form-control-label p-0 m-0">Site <span class="text-danger">*</span></label>
                        <input type="text" name="site" value="{{ old('site', $pessoa->site) }}"
                            class="form-control campo @if($errors->has('site')) form-control-danger @endif">
                        @if($errors->has('site'))
                            <small class="form-text text-danger">{{ $errors->first('site') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('banco')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Banco<span class="text-danger">*</span></label>
                        <input type="text" name="banco" value="{{ old('banco', $pessoa->banco) }}"
                            class="form-control campo @if($errors->has('banco')) form-control-danger @endif">
                        @if($errors->has('banco'))
                            <small class="form-text text-danger">{{ $errors->first('banco') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('agencia')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Agencia<span class="text-danger">*</span></label>
                        <input type="text" name="agencia" value="{{ old('agencia', $pessoa->agencia) }}"
                            class="form-control campo @if($errors->has('agencia')) form-control-danger @endif">
                        @if($errors->has('agencia'))
                            <small class="form-text text-danger">{{ $errors->first('agencia') }}</small>
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group @if($errors->has('conta_corrente')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Conta Corrente<span class="text-danger">*</span></label>
                        <input type="text" name="conta_corrente" value="{{ old('conta_corrente', $pessoa->conta_corrente) }}"
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
                    class="form-control campo @if($errors->has('obs')) form-control-danger @endif">{{ old('obs', $pessoa->obs) }}</textarea>
                @if($errors->has('obs'))
                    <small class="form-text text-danger">{{ $errors->first('obs') }}</small>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <input type="checkbox" id="gerar_via_acompanhante" name="gerar_via_acompanhante" class="filled-in" @if (old('gerar_via_acompanhante', $pessoa->gerar_via_acompanhante) == 1)
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
                            <option value="{{ $relacao }}" @if ($pessoa->referencia_relacao==$relacao)
                                selected
                            @endif @if(old('referencia_relacao')==$relacao) selected @endif>{{ $relacao }}</option>
                        @endforeach
                        @if (!in_array($pessoa->referencia_relacao, $referencia_relacoes))
                            <option value="{{ $pessoa->referencia_relacao }}">{{ $pessoa->referencia_relacao }}</option>
                        @endif
                    </select>
                    @if($errors->has('referencia_relacao'))
                        <small class="form-text text-danger">{{ $errors->first('referencia_relacao') }}</small>
                    @endif
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group @if($errors->has('referencia_nome')) has-danger @endif">
                    <label class="form-control-label p-0 m-0">Nome</label>
                    <input type="text" name="referencia_nome" value="{{ old('referencia_nome', $pessoa->referencia_nome) }}"
                        class="form-control campo @if($errors->has('referencia_nome')) form-control-danger @endif">
                    @if($errors->has('referencia_nome'))
                        <small class="form-text text-danger">{{ $errors->first('referencia_nome') }}</small>
                    @endif
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group @if($errors->has('referencia_telefone')) has-danger @endif">
                    <label class="form-control-label p-0 m-0">Telefone</label>
                    <input type="text" name="referencia_telefone" value="{{ old('referencia_telefone', $pessoa->referencia_telefone) }}"
                        class="form-control campo telefone @if($errors->has('referencia_telefone')) form-control-danger @endif">
                    @if($errors->has('referencia_telefone'))
                        <small class="form-text text-danger">{{ $errors->first('referencia_telefone') }}</small>
                    @endif
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group @if($errors->has('referencia_documento')) has-danger @endif">
                    <label class="form-control-label p-0 m-0">Documento</label>
                    <input type="text" name="referencia_documento" alt="cpf" value="{{ old('referencia_documento', $pessoa->referencia_documento) }}"
                        class="form-control campo @if($errors->has('referencia_documento')) form-control-danger @endif">
                    @if($errors->has('referencia_documento'))
                        <small class="form-text text-danger">{{ $errors->first('referencia_documento') }}</small>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>

<div class="form-group text-right pb-2 no_print">

    <button type="submit" id="salvar" class="btn btn-success waves-effect waves-light m-r-10">
        <i class="mdi mdi-check"></i> Salvar
    </button>
</div>
