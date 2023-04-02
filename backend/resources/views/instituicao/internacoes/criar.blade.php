@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Internação',
        'breadcrumb' => [
            'Internação' => route('instituicao.internacoes.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">

        <div class="card-body">

            <form action="{{ route('instituicao.internacoes.store') }}" id="formInternacao" method="post">
                @csrf
                <div class="modal_paciente_add"></div>
                <input type="hidden" name="internacao_id", id="internacao_id" value='{{ old('internacao_id') }}' />
                <div class="row paciente">
                    <div class="col-md-8 form-group @if($errors->has('paciente_id')) has-danger @endif">
                        <input type="hidden" name="paciente_id", id="paciente_id" value="{{ old('paciente_id') }}"/>
                        <label class="form-control-label p-0 m-0">Paciente <span class="text-danger">*</span></label>
                        <i class="mdi mdi-magnify modal_pesquia_paciente btn btn-secondary btn-sm"></i>
                        <i class="mdi mdi-eye-outline modal_mostra_paciente btn btn-secondary btn-sm"></i>
                        <i class="mdi mdi-account-card-details modal_mostra_carteirinha btn btn-secondary btn-sm"></i>
                        <i class="mdi mdi-plus modal_add_paciente btn btn-secondary btn-sm"></i>
                        <input type="text" name="paciente_nome" id="paciente_nome" class="form-control" disabled/>

                        @if($errors->has('paciente_id'))
                            <small class="form-control-feedback">{{ $errors->first('paciente_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('previsao_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Previsao alta <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control p-0 m-0" value="{{ old('previsao_alta') }}"
                            name="previsao_alta" id="previsao_alta" >
                        @if($errors->has('previsao_alta'))
                            <small class="form-control-feedback">{{ $errors->first('previsao_alta') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                   <div class="col-md-4 form-group @if($errors->has('origem_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Origem</label>
                        <select class="form-control p-0 m-0 selectfild2" name="origem_id" id="origem_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($origens as $origem)
                                <option {{ (old('origem_id') == $origem->id) ? 'selected' : '' }} value="{{ $origem->id }}">{{ $origem->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('origem_id'))
                            <small class="form-control-feedback">{{ $errors->first('origem_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('medico_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Médico</label>
                        <select class="form-control p-0 m-0 selectfild2" name="medico_id" id="medico_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($medicos as $medico)
                                <option {{ (old('medico_id') == $medico->id) ? 'selected' : '' }} value="{{ $medico->id }}">{{ $medico->nome }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('medico_id'))
                            <small class="form-control-feedback">{{ $errors->first('medico_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('especialidade_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Especialidade</label>
                        <select class="form-control p-0 m-0 selectfild2" name="especialidade_id" id="especialidade_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($especialidades as $item)
                                <option {{ (old('especialidade_id') == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('especialidade_id'))
                            <small class="form-control-feedback">{{ $errors->first('especialidade_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md form-group @if($errors->has('acomodacao_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Acomodação</label>
                        <select class="form-control @if($errors->has('acomodacao_id')) form-control-danger @endif select2" name="acomodacao_id" id="acomodacao_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($acomodacoes as $acomodacao)
                                <option {{ (old('acomodacao_id') == $acomodacao->id) ? 'selected' : '' }} value="{{ $acomodacao->id }}">{{ $acomodacao->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('acomodacao_id'))
                            <small class="form-control-feedback">{{ $errors->first('acomodacao_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('unidade_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Unidade</label>
                        <select class="form-control selectfild2 @if($errors->has('unidade_id')) form-control-danger @endif" name="unidade_id" id="unidade_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($unidades as $unidade)
                                <option {{ (old('unidade_id') == $unidade->id) ? 'selected' : '' }} value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('unidade_id'))
                            <small class="form-control-feedback">{{ $errors->first('unidade_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('leito_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Leito</label>
                        <select class="form-control p-0 m-0 selectfild2" name="leito_id" id="leito_id" disabled>
                            <option value="" selected>Nenhum</option>
                        </select>
                        @if($errors->has('leito_id'))
                            <small class="form-control-feedback">{{ $errors->first('leito_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class='row'>
                    <div class="col-md-3 form-group @if($errors->has('acompanhante')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Acompanhante</label>
                        <select class="form-control @if($errors->has('acompanhante')) form-control-danger @endif" name="acompanhante" id="acompanhante">
                            <option {{ (old('acompanhante') == 0) ? 'selected' : '' }} value="0" >Não</option>
                            <option {{ (old('acompanhante') == 1) ? 'selected' : '' }} value="1" >Sim</option>
                        </select>
                        @if($errors->has('acompanhante'))
                            <small class="form-control-feedback">{{ $errors->first('acompanhante') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('tipo_internacao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo</label>
                        <select class="form-control @if($errors->has('tipo_internacao')) form-control-danger @endif" name="tipo_internacao" id="tipo_internacao">
                            <option value="" >Nenhum</option>
                            <option {{ (old('tipo_internacao') == 1) ? 'selected' : '' }} value="1" >Clínico</option>
                            <option {{ (old('tipo_internacao') == 2) ? 'selected' : '' }} value="2" >Cirúrgico</option>
                            <option {{ (old('tipo_internacao') == 3) ? 'selected' : '' }} value="3" >Materno-Infantil</option>
                            <option {{ (old('tipo_internacao') == 4) ? 'selected' : '' }} value="4" >Neonatalogia</option>
                            <option {{ (old('tipo_internacao') == 5) ? 'selected' : '' }} value="5" >Obstetrícia</option>
                            <option {{ (old('tipo_internacao') == 6) ? 'selected' : '' }} value="6" >Pediatria</option>
                            <option {{ (old('tipo_internacao') == 7) ? 'selected' : '' }} value="7" >Psiquiatria</option>
                            <option {{ (old('tipo_internacao') == 8) ? 'selected' : '' }} value="8" >Outros</option>
                        </select>
                        @if($errors->has('tipo_internacao'))
                            <small class="form-control-feedback">{{ $errors->first('tipo_internacao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('reserva_leito')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Reserva de Leito</label>
                        <select class="form-control @if($errors->has('reserva_leito')) form-control-danger @endif" name="reserva_leito" id="reserva_leito">
                            <option value="0" {{ (old('reserva_leito') == 0) ? 'selected' : '' }}>Não</option>
                            <option value="1" {{ (old('reserva_leito') == 1) ? 'selected' : '' }}>Sim</option>
                        </select>
                        @if($errors->has('reserva_leito'))
                            <small class="form-control-feedback">{{ $errors->first('reserva_leito') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('cid_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Cid</label>
                        <select class="form-control @if($errors->has('cid_id')) form-control-danger @endif" name="cid_id" id="cid_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($cids as $cid)
                                <option {{ (old('cid_id') == $cid->id) ? 'selected' : '' }} value="{{ $cid->id }}">{{ $cid->codigo }} - {{ $cid->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cid_id'))
                            <small class="form-control-feedback">{{ $errors->first('cid_id') }}</small>
                        @endif
                    </div>
                </div>

                <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">

                <div class="itens_procedimentos row">
                    @include('instituicao.internacoes.procedimentos')
                    <div class="col-md-9"></div>
                    <div class="form-group col-md-12 add-class" >
                        <span alt="default" class="add-convenio fas fa-plus-circle">
                            <a class="mytooltip" href="javascript:void(0)">
                                <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar convenio procedimento"></i>
                            </a>
                        </span>
                      </div>
                    <div class="form-group col-md-3">
                        <label class="form-control-label">Total</label>
                        <input class="form-control" alt="decimal" type="text" readonly id="total_procedimentos" name="total_procedimentos">
                    </div>
                </div>

                <div class='row'>
                    <div class="col-md-12 form-group @if($errors->has('observacao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Observação <span class="text-danger">*</span></label>
                        <textarea rows='4' class="form-control @if($errors->has('observacao')) form-control-danger @endif" name="observacao" id="observacao">{{ old('observacao') }}</textarea>
                        @if($errors->has('observacao'))
                            <small class="form-control-feedback">{{ $errors->first('observacao') }}</small>
                        @endif
                    </div>
                </div>

                <div class='row'>
                    <div class="col-sm-6">
                        <div class="card shadow-none p-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input campo" name="possui_responsavel" value="1" @if(old('possui_responsavel')=="1") checked @endif id="responsavel">
                                <label class="form-check-label" for="responsavel">Responsável</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='responsavel' style="display:none">
                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('parentesco_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Parentesco <span class="text-danger">*</span></label>
                            <select class="form-control @if($errors->has('parentesco_responsavel')) form-control-danger @endif" name="parentesco_responsavel" id="parentesco_responsavel">
                                <option value="" selected>Nenhum</option>
                                <option value="Pai" {{ (old('parentesco_responsavel') == 'Pai') ? 'selected' : '' }}>Pai</option>
                                <option value="Mãe" {{ (old('parentesco_responsavel') == 'Mãe') ? 'selected' : '' }}>Mãe</option>
                                <option value="Avó" {{ (old('parentesco_responsavel') == 'Avó') ? 'selected' : '' }}>Avó</option>
                                <option value="Avô" {{ (old('parentesco_responsavel') == 'Avô') ? 'selected' : '' }}>Avô</option>
                                <option value="Tia" {{ (old('parentesco_responsavel') == 'Tia') ? 'selected' : '' }}>Tia</option>
                                <option value="Tio" {{ (old('parentesco_responsavel') == 'Tio') ? 'selected' : '' }}>Tio</option>
                                <option value="Madrasta" {{ (old('parentesco_responsavel') == 'Madrasta') ? 'selected' : '' }}>Madrasta</option>
                                <option value="Padrasto" {{ (old('parentesco_responsavel') == 'Padrasto') ? 'selected' : '' }}>Padrasto</option>
                                <option value="Irmão" {{ (old('parentesco_responsavel') == 'Irmão') ? 'selected' : '' }}>Irmão</option>
                                <option value="Irmã" {{ (old('parentesco_responsavel') == 'Irmã') ? 'selected' : '' }}>Irmã</option>
                                <option value="Primo" {{ (old('parentesco_responsavel') == 'Primo') ? 'selected' : '' }}>Primo</option>
                                <option value="Prima" {{ (old('parentesco_responsavel') == 'Prima') ? 'selected' : '' }}>Prima</option>
                                <option value="Sobrinha" {{ (old('parentesco_responsavel') == 'Sobrinha') ? 'selected' : '' }}>Sobrinha</option>
                                <option value="Sobrinho" {{ (old('parentesco_responsavel') == 'Sobrinho') ? 'selected' : '' }}>Sobrinho</option>
                                <option value="Cunhado" {{ (old('parentesco_responsavel') == 'Cunhado') ? 'selected' : '' }}>Cunhado</option>
                                <option value="Cunhada" {{ (old('parentesco_responsavel') == 'Cunhada') ? 'selected' : '' }}>Cunhada</option>
                                <option value="Amigo" {{ (old('parentesco_responsavel') == 'Amigo') ? 'selected' : '' }}>Amigo</option>
                                <option value="Amiga" {{ (old('parentesco_responsavel') == 'Amiga') ? 'selected' : '' }}>Amiga</option>
                                <option value="Filho" {{ (old('parentesco_responsavel') == 'Filho') ? 'selected' : '' }}>Filho</option>
                                <option value="Filha" {{ (old('parentesco_responsavel') == 'Filha') ? 'selected' : '' }}>Filha</option>
                                <option value="Namorada" {{ (old('parentesco_responsavel') == 'Namorada') ? 'selected' : '' }}>Namorada</option>
                                <option value="Namorado" {{ (old('parentesco_responsavel') == 'Namorado') ? 'selected' : '' }}>Namorado</option>
                                <option value="Outro" {{ (old('parentesco_responsavel') == 'Outro') ? 'selected' : '' }}>Outro</option>
                            </select>
                            @if($errors->has('parentesco_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('parentesco_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md-6 form-group @if($errors->has('nome_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('nome_responsavel') }}"
                                name="nome_responsavel" id="nome_responsavel" >
                            @if($errors->has('nome_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('nome_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('estado_civil_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Estado Civil</label>
                            <select class="form-control @if($errors->has('estado_civil_responsavel')) form-control-danger @endif" name="estado_civil_responsavel" id="estado_civil_responsavel">
                                <option value="" selected>Nenhum</option>
                                <option value="Solteiro" {{ (old('estado_civil_responsavel') == 'Solteiro') ? 'selected' : '' }}>Solteiro</option>
                                <option value="Casado" {{ (old('estado_civil_responsavel') == 'Casado') ? 'selected' : '' }}>Casado</option>
                                <option value="Viúvo" {{ (old('estado_civil_responsavel') == 'Viúvo') ? 'selected' : '' }}>Viúvo</option>
                                <option value="Divorciado" {{ (old('estado_civil_responsavel') == 'Divorciado') ? 'selected' : '' }}>Divorciado</option>
                                <option value="Outro" {{ (old('estado_civil_responsavel') == 'Outro') ? 'selected' : '' }}>Outro</option>
                            </select>
                            @if($errors->has('estado_civil_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('estado_civil_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('profissao_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Profissão</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('profissao_responsavel') }}"
                                name="profissao_responsavel" id="profissao_responsavel" >
                            @if($errors->has('profissao_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('profissao_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('nacionalidade_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nacionalidade</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('nacionalidade_responsavel') }}"
                                name="nacionalidade_responsavel" id="nacionalidade_responsavel" >
                            @if($errors->has('nacionalidade_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('nacionalidade_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('telefone1_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Telefone 1 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('telefone1_responsavel') }}"
                                name="telefone1_responsavel" id="telefone1_responsavel" >
                            @if($errors->has('telefone1_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('telefone1_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('telefone2_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Telefone 2</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('telefone2_responsavel') }}"
                                name="telefone2_responsavel" id="telefone2_responsavel" >
                            @if($errors->has('telefone2_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('telefone2_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('cpf_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">CPF</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('cpf_responsavel') }}"
                                name="cpf_responsavel" id="cpf_responsavel" >
                            @if($errors->has('cpf_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('cpf_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('endereco_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Identidade</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('identidade_responsavel') }}"
                                name="identidade_responsavel" id="identidade_responsavel" >
                            @if($errors->has('identidade_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('identidade_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('contato_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Contato</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('contato_responsavel') }}"
                                name="contato_responsavel" id="contato_responsavel" >
                            @if($errors->has('contato_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('contato_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-2 form-group @if($errors->has('cep_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">CEP</label>
                            <input type="text" class="form-control p-0 m-0 cep" value="{{ old('cep_responsavel') }}"
                                name="cep_responsavel" id="cep_responsavel" >
                            @if($errors->has('cep_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('cep_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('endereco_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Endereço</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('endereco_responsavel') }}"
                                name="endereco_responsavel" id="endereco_responsavel" >
                            @if($errors->has('endereco_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('endereco_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md-2 form-group @if($errors->has('numero_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Número</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('numero_responsavel') }}"
                                name="numero_responsavel" id="numero_responsavel" >
                            @if($errors->has('numero_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('numero_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('complemento_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Complemento</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('complemento_responsavel') }}"
                                name="complemento_responsavel" id="complemento_responsavel" >
                            @if($errors->has('complemento_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('complemento_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('bairro_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Bairro</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('bairro_responsavel') }}"
                                name="bairro_responsavel" id="bairro_responsavel" >
                            @if($errors->has('bairro_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('bairro_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('cidade_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Cidade</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('cidade_responsavel') }}"
                                name="cidade_responsavel" id="cidade_responsavel" >
                            @if($errors->has('cidade_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('cidade_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('uf_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Estado</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('uf_responsavel') }}"
                                name="uf_responsavel" id="uf_responsavel" >
                            @if($errors->has('uf_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('uf_responsavel') }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.internacoes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>

            <div id="modal_internacao"></div>

            <div id="ver_paciente"></div>

            <div id="ver_pre_internacao"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var leitoId = null;
        // var especialidadeId = null;
        $(document).ready(function(){
            getPaciente($('#paciente_id').val())
            responsavel()

            $("#cid_id").select2({
                placeholder: "Pesquise por descricao do CID",
                allowClear: true,
                // minimumInputLength: 3,

                language: {
                    searching: function () {
                        return 'Buscando CIDs (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                },

                ajax: {
                    url:"{{route('instituicao.PreInternacoes.getCids')}}",
                    dataType: 'json',
                    type: 'get',
                    delay: 100,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },

                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.codigo} - ${item.descricao}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
            });

            $('#formInternacao').on('submit', function(e){
                e.preventDefault()
                var formData = new FormData($(this)[0]);
                $.ajax("{{ route('instituicao.internacoes.store') }}", {
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
                            window.location="{{ route('instituicao.internacoes.index') }}";
                        }
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') ;
                    },
                    error: function (response) {
                        if(response.responseJSON.errors){
                            Object.keys(response.responseJSON.errors).forEach(function(key) {
                                $.toast({
                                    heading: 'Erro',
                                    text: response.responseJSON.errors[key][0],
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 9000,
                                    stack: 10
                                });

                            });
                        }
                    }
                })
            })
        })

        $('#paciente_id').on('change', function(){
            getPaciente($("#paciente_id").val())
            getPreInternacao()
        })

        // $('#medico_id').on('change', function(){
        //     getEspecialidade();
        // })

        $('#unidade_id').on('change', function(){
            getLeitos()
        })

        $('#responsavel').on('change', function(){
            responsavel()
        })

        function responsavel(){
            let tipo = $('#responsavel:checked').val()

            if(tipo == 1){
                $('.responsavel').css('display','block')
            }else{
                $('.responsavel').css('display','none')
                $('#parentesco_responsavel').val('')
                $('#nome_responsavel').val('')
                $('#estado_civil_responsavel').val('')
                $('#profissao_responsavel').val('')
                $('#nacionalidade_responsavel').val('')
                $('#telefone1_responsavel').val('')
                $('#telefone2_responsavel').val('')
                $('#identidade_responsavel').val('')
                $('#cpf_responsavel').val('')
                $('#contato_responsavel').val('')
                $('#cep_responsavel').val('')
                $('#endereco_responsavel').val('')
                $('#numero_responsavel').val('')
                $('#complemento_responsavel').val('')
                $('#bairro_responsavel').val('')
                $('#cidade_responsavel').val('')
                $('#uf_responsavel').val('')
            }
        }

        function  getPreInternacao(dados){
            var id = $("#paciente_id").val()

            if(id != ''){

                var url = "{{ route('instituicao.internacoes.getPreInternacoes')}}";
                var type = 'post';
                var data = {
                    '_token': '{{csrf_token()}}',
                    'dados': dados
                };

                var modal = 'mostraPreInternacao';
                $('#loading').removeClass('loading-off');
                $('#ver_pre_internacao').load(url, data, function(resposta, status) {
                    $('#' + modal).modal();
                    $('#loading').addClass('loading-off');
                });
            }
        }

        function selectPreInternacao(dados){
            dados = JSON.parse(dados),

            especialidadeId = dados.especialidade_id
            leitoId = dados.leito_id

            $('#acomodacao_id').val(dados.acomodacao_id).change()
            $('#origem_id').val(dados.origem_id).change()
            $('#cid_id').val(dados.cid_id).change()
            $('#medico_id').val(dados.medico_id).change()            
            $('#unidade_id').val(dados.unidade_id).change()
            $('#acompanhante').val(dados.acompanhante).change()
            $('#tipo_internacao').val(dados.tipo_internacao).change()
            $('#observacao').val(dados.observacao).change()
            $('#reserva_leito').val(dados.reserva_leito).change()
            $('#internacao_id').val(dados.id).change()
            if(dados.possui_responsavel){
                $('#responsavel').prop('checked', true).change()
                $('.responsavel').css('display','block').change()
                $('#parentesco_responsavel').val(dados.parentesco_responsavel).change()
                $('#nome_responsavel').val(dados.nome_responsavel).change()
                $('#estado_civil_responsavel').val(dados.estado_civil_responsavel).change()
                $('#profissao_responsavel').val(dados.profissao_responsavel).change()
                $('#nacionalidade_responsavel').val(dados.nacionalidade_responsavel).change()
                $('#telefone1_responsavel').val(dados.telefone1_responsavel).change()
                $('#telefone2_responsavel').val(dados.telefone2_responsavel).change()
                $('#identidade_responsavel').val(dados.identidade_responsavel).change()
                $('#cpf_responsavel').val(dados.cpf_responsavel).change()
                $('#contato_responsavel').val(dados.contato_responsavel).change()
                $('#cep_responsavel').val(dados.cep_responsavel).change()
                $('#endereco_responsavel').val(dados.endereco_responsavel).change()
                $('#numero_responsavel').val(dados.numero_responsavel).change()
                $('#complemento_responsavel').val(dados.complemento_responsavel).change()
                $('#bairro_responsavel').val(dados.bairro_responsavel).change()
                $('#cidade_responsavel').val(dados.cidade_responsavel).change()
                $('#uf_responsavel').val(dados.uf_responsavel).change()
            }else{
                $('#responsavel').prop('checked', false).change()
                $('.responsavel').css('display','none').change()
                $('#parentesco_responsavel').val('').change()
                $('#nome_responsavel').val('').change()
                $('#estado_civil_responsavel').val('').change()
                $('#profissao_responsavel').val('').change()
                $('#nacionalidade_responsavel').val('').change()
                $('#telefone1_responsavel').val('').change()
                $('#telefone2_responsavel').val('').change()
                $('#identidade_responsavel').val('').change()
                $('#cpf_responsavel').val('').change()
                $('#contato_responsavel').val('').change()
                $('#cep_responsavel').val('').change()
                $('#endereco_responsavel').val('').change()
                $('#numero_responsavel').val('').change()
                $('#complemento_responsavel').val('').change()
                $('#bairro_responsavel').val('').change()
                $('#cidade_responsavel').val('').change()
                $('#uf_responsavel').val('').change()
            }

            if(Array.isArray(dados.procedimentos) && dados.procedimentos.length){
                $('.itens_procedimentos_row').remove();

                for(var i = 0; i < dados.procedimentos.length; i++){
                    quantidade_convenio++;
                    
                    console.log(dados.procedimentos)

                    html_text = '<div><div class="col-md-12 itens_procedimentos_row"><div class="row">'+            
                            '<div class="col-md-12"><a href="javascrit:void(0)" class="small remove-convenio">(remover)</a></div>'+
                            '<div class="form-group dados_parcela col-md-4">'+
                                '<label class="form-control-label">Convênio:</span></label>'+
                                '<input type="hidden" readonly name="itens['+i+'][convenio]" value="'+dados.procedimentos[i].convenios_id+'" />'+
                                '<input type="text" readonly readonly class="form-control item-convenio" value="'+dados.procedimentos[i].convenios.nome+'" />'+
                            '</div>'+
                            '<div class="form-group col-md-4">'+
                                '<label class="form-control-label">Procedimento</label>'+
                                '<input type="hidden" readonly name="itens['+i+'][procedimento]" value="'+dados.procedimentos[i].id+'" />'+
                                '<input type="text" readonly class="form-control " value="'+dados.procedimentos[i].procedimento_instituicao.procedimento.descricao+'" />'+
                            '</div>'+
                            
                            '<div class="form-group col-md-2 exige_quantidade">'+
                                '<label class="form-control-label">Qtd</span></label>'+
                                '<input type="text" readonly class="form-control qtd_procedimentor" name="itens['+i+'][qtd_procedimento]" value="'+dados.procedimentos[i].pivot.quantidade_procedimento+'" >'+
                            '</div>'+
                            
                            '<div class="form-group col-md-2">'+
                                '<label class="form-control-label">Valor *</span></label>'+
                                '<input type="text" readonly alt="decimal" class="form-control mask_item valor_procedimento" name="itens['+i+'][valor]" id="itens['+i+'][valor]" value="'+dados.procedimentos[i].pivot.valor+'" readonly>'+
                            '</div>'+
                       '</div></div><div>';

                    $($(html_text).html()).insertBefore(".add-class");

                    $('.mask_item').setMask();
                    $('.mask_item').removeClass('mask_item');
                    $(".selectfild2").select2();

                    // $("[name^='itens[#]']").each(function(index, element) {
                    //     const name = $(element).attr('name');
                        
                    //     if(name == 'itens[#][convenio]'){
                    //         setTimeout(function(){
                    //             $(element).val(dados.procedimentos[i].convenios_id).change();
                    //             procedimento_select = dados.procedimentos[i].pivot.proc_conv_id;
                    //         }, 2000);
                    //     }else if(name == 'itens[#][qtd_procedimento]'){
                    //         // $(element).val(dados.procedimentos[i].pivot.quantidade_procedimento).change();
                    //     }else if(name == 'itens[#][valor]'){
                    //         // setTimeout($(element).val(dados.procedimentos[i].pivot.valor).change(), 2000);
                    //     }

                    //     $(element).attr('name', name.replace('#', quantidade_convenio));

                    //     // console.log (name, element);
                    //     $(element).val()
                    // })
                }
            }

            $("#mostraPreInternacao").modal('hide');

        }

        function getPaciente(id){
           if(id != ''){
                $.ajax({
                    url: "{{route('instituicao.internacoes.getPaciente')}}",
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        paciente_id: id
                    },
                    success: function(retorno){
                        if(retorno.icon == 'error'){
                            $.toast({
                                heading: retorno.title,
                                text: retorno.text,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: retorno.icon,
                                hideAfter: 9000,
                                stack: 10
                            });
                        }else{
                            $('#paciente_id').val(retorno.id);
                            $('#paciente_nome').val(retorno.nome+' - '+retorno.cpf);
                            $("#modalPaciente").modal('hide');
                            if(retorno.pre_internacoes.length > 0){
                                getPreInternacao(retorno.pre_internacoes)
                            }else{
                                $('#acomodacao_id').val('').change()
                                $('#origem_id').val('').change()
                                $('#cid_id').val('').change()
                                $('#medico_id').val('').change()
                                $('#especialidade_id').val('').change()
                                $('#unidade_id').val('').change()
                                $('#leito_id').val('').change()
                                $('#acompanhante').val(0).change()
                                $('#tipo_internacao').val('').change()
                                $('#observacao').val('').change()
                                $('#reserva_leito').val(0).change()
                                $('#internacao_id').val('').change()

                                $('#responsavel').prop('checked', false)
                                $('.responsavel').css('display','none')
                                $('#parentesco_responsavel').val('').change()
                                $('#nome_responsavel').val('').change()
                                $('#estado_civil_responsavel').val('').change()
                                $('#profissao_responsavel').val('').change()
                                $('#nacionalidade_responsavel').val('').change()
                                $('#telefone1_responsavel').val('').change()
                                $('#telefone2_responsavel').val('').change()
                                $('#identidade_responsavel').val('').change()
                                $('#cpf_responsavel').val('').change()
                                $('#contato_responsavel').val('').change()
                                $('#cep_responsavel').val('').change()
                                $('#endereco_responsavel').val('').change()
                                $('#numero_responsavel').val('').change()
                                $('#complemento_responsavel').val('').change()
                                $('#bairro_responsavel').val('').change()
                                $('#cidade_responsavel').val('').change()
                                $('#uf_responsavel').val('').change()

                                especialidadeId = null
                                leitoId = null
                            }
                        }
                    }
                })
           }
        }

        // function getEspecialidade(){
        //     if($('#medico_id').val() == ''){
        //         $('#especialidade_id').find('option').filter(':not([value=""])').remove();
        //         $('#especialidade_id').prop('disabled', true);
        //     }else{
        //         $('#especialidade_id').find('option').filter(':not([value=""])').remove();
        //         id = $('#medico_id').val();

        //         $.ajax({
        //             url: "{{route('instituicao.internacoes.getEspecialidades')}}",
        //             type: 'post',
        //             data: {
        //                 "_token": "{{ csrf_token() }}",
        //                 medico_id: id
        //             },
        //             beforeSend: () => {
        //                 $('#especialidade_id').prop('disabled', true);
        //             },
        //             success: function(retorno){
        //                 $('#especialidade_id').find('option').filter(':not([value=""])').remove();
                        
        //                 for (i = 0; i < retorno.length; i++) {
        //                     var selected = '';
        //                     if(especialidadeId == retorno[i]['id']){
        //                         selected = "selected";
        //                     }
        //                    $('#especialidade_id').append("<option {{ (old('especialidade_id') == "+ retorno[i]['id'] +") ? 'selected' : '' }} value = "+ retorno[i]['id'] +" "+selected+">" + retorno[i]['descricao'] + "</option>");
        //                 }

        //                 $('#especialidade_id').prop('disabled', false);
        //             }
        //         })
        //     }

        // }

        function getLeitos(){
            if($('#unidade_id').val() == ''){
                $('#leito_id').find('option').filter(':not([value=""])').remove();
                $('#leito_id').prop('disabled', true);
            }else{
                $('#leito_id').find('option').filter(':not([value=""])').remove();
                id = $('#unidade_id').val();

                $.ajax({
                    url: "{{route('instituicao.internacoes.getLeitos')}}",
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        unidade_id: id
                    },
                    beforeSend: () => {
                        $('#leito_id').prop('disabled', true);
                    },
                    success: function(retorno){
                        for (i = 0; i < retorno.length; i++) {
                            var selected = '';
                            if(leitoId == retorno[i]['id']){
                                selected = "selected";
                            }
                           $('#leito_id').append("<option value = "+ retorno[i]['id'] +"  "+selected+">" + retorno[i]['descricao'] + "</option>");
                        }
                        $('#leito_id').prop('disabled', false);
                    }
                })

            }
        }

        $('.paciente').on('click', '.modal_mostra_paciente', function(){
            var id = $("#paciente_id").val()

            if(id != ''){

                var url = "{{ route('instituicao.internacoes.verPaciente') }}";
                var data = {
                    '_token': '{{csrf_token()}}',
                    'paciente_id': id
                };
                var modal = 'modalVerPaciente';

                $('#loading').removeClass('loading-off');
                $('#ver_paciente').load(url, data, function(resposta, status) {
                    $('#' + modal).modal();
                    $('#loading').addClass('loading-off');
                });
            }else{
                $.toast({
                    heading: 'Erro',
                    text: 'Campo paciente não esta preenchido, selecione um paciente para visualizar seus dados!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 9000,
                    stack: 10
                });
            }

        })

        $('.paciente').on('click', '.modal_pesquia_paciente', function(){
            var url = "{{ route('instituicao.internacoes.pesquisaPaciente') }}";
            var data = {
                '_token': '{{csrf_token()}}'
            };
            var modal = 'modalPaciente';

            $('#loading').removeClass('loading-off');
            $('#modal_internacao').load(url, data, function(resposta, status) {
                $('#' + modal).modal();
                $('#loading').addClass('loading-off');
                $("#cpf").setMask()
            });

        })

        $('.paciente').on('click', '.modal_mostra_carteirinha', function(){
            var id = $("#paciente_id").val()

            if(id != ''){
                var url = "{{ route('instituicao.internacoes.getCarteirinha') }}";
                var data = {
                    '_token': '{{csrf_token()}}',
                    'paciente_id': id
                };
                var modal = 'mostraCarteirinha';

                $('#loading').removeClass('loading-off');
                $('#modal_internacao').load(url, data, function(resposta, status) {
                    $('#' + modal).modal();
                    $('#loading').addClass('loading-off');
                    $("#cpf").setMask()
                });
            }else{
                $.toast({
                    heading: 'Erro',
                    text: 'Campo paciente não esta preenchido, selecione um paciente para visualizar seus dados!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 9000,
                    stack: 10
                });

            }

        })

        $("#modal_internacao").on('submit', '#formPesquisarPaciente', function(e){
            e.preventDefault()

            var formData = new FormData($(this)[0]);

            $.ajax({
                url: "{{route('instituicao.internacoes.getPaciente')}}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {
                    $("#modal_internacao").find("#tabela").html(result)
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                },
                error: function (response) {
                    if(response.responseJSON.errors){
                        Object.keys(response.responseJSON.errors).forEach(function(key) {
                            $.toast({
                                heading: 'Erro',
                                text: response.responseJSON.errors[key][0],
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 9000,
                                stack: 10
                            });

                        });
                    }
                }
            })
        })

        $(".modal_add_paciente").on("click", function(){
            paciente_id = $("#paciente_id").val();
            
            $.ajax({
                url: "{{route('instituicao.PreInternacoes.addPacienteModal')}}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "paciente_id": paciente_id
                },
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(retorno){
                    $('.modal_paciente_add').html(retorno);
                    $('input').setMask();
                    $('#modalAddPaciente').modal('show');

                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                },
            })
        });

        $(".modal_paciente_add").on("click", "#salvar", function(e){
            e.preventDefault()
            var formData = new FormData($('#formPaciente')[0]);
            var paciente_id = $("input[name='id']").val() ? $("input[name='id']").val() : 0;

            $.ajax("{{route('instituicao.PreInternacoes.salvarPaciente', ['paciente_id' => 'pacienteId'])}}".replace('pacienteId', paciente_id), {
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
                        $('#paciente_id').val(response.dados.id);
                        // $("#paciente_nome").val(response.dados.nome)
                        $('#paciente_id').change();
                        $("#modalAddPaciente").modal('hide');
                    }
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') ;
                },
                error: function (response) {
                    if(response.responseJSON.errors){
                        Object.keys(response.responseJSON.errors).forEach(function(key) {
                            $.toast({
                                heading: 'Erro',
                                text: response.responseJSON.errors[key][0],
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 9000,
                                stack: 10
                            });

                        });
                    }
                }
            })
        });

        var quantidade_convenio = $('.itens_procedimentos_row').find('.item-convenio').length;
        $(".mytooltip").tooltip();

        function getProcedimentos(element){
            var id = $(element).val()
            var prestador_id =  $('#medico_id').val() ? $('#medico_id').val() : 0
            var options = $(element).parents(".itens_procedimentos_row").find('.procedimentos');

            $.ajax({
                url: "{{route('instituicao.PreInternacoes.getProcedimentos', ['convenio' => 'convenio_id'])}}".replace('convenio_id', id),
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    prestador_id: prestador_id
                },
                datatype: "json",
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function(result) {
                    if(result != null){
                        procedimentos = result
                        $(element).parents(".itens_procedimentos_row").find('.valor_procedimento').val('');
                        $(element).parents(".itens_procedimentos_row").find('.valor_procedimento').setMask();
                        options.prop('disabled', false);
                        options.find('option').filter(':not([value=""])').remove();
                        

                        $.each(procedimentos, function (key, value) {
                                    // $('<option').val(value.id).text(value.Nome).appendTo(options);
                            options.append('<option value='+value.instituicao_procedimentos_convenios[0].pivot.id+' data-valor='+value.instituicao_procedimentos_convenios[0].pivot.valor+' data-exige-qtd='+value.procedimento.exige_quantidade+' data-cobrar='+value.procedimento.n_cobrar_agendamento+' data-tempo='+value.procedimento.duracao_atendimento+' data-compromisso='+value.procedimento.compromisso_id+'>'+value.procedimento.descricao+'</option>')
                            //options += '<option value="' + key + '">' + value + '</option>';
                        });

                        if(options.data('val')){
                            options.val(options.data('val')).change();
                        }

                        if(options.data('valor')){
                            $(element).parents(".itens_procedimentos_row").find('.valor_procedimento').val(options.data('valor')).change();
                        }
                    }
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                }
            });

        }

        $('.itens_procedimentos').on('click', '.add-convenio', function(){
            addConvenio();
            $('[data-toggle="tooltip"]').tooltip()
        });

        function addConvenio(){
            quantidade_convenio++;

            $($('#itens_procedimentos').html()).insertBefore(".add-class");

            $('.mask_item').setMask();
            $('.mask_item').removeClass('mask_item');
            $(".selectfild2").select2();

            $("[name^='itens[#]']").each(function(index, element) {
                const name = $(element).attr('name');
                $(element).attr('name', name.replace('#',quantidade_convenio));
            })
        }

        $('.itens_procedimentos').on('click', '.itens_procedimentos_row .remove-convenio', function(e){
            e.preventDefault()

            $(e.currentTarget).parents('.itens_procedimentos_row').remove();
            totalProcedimentos()

            if ($('.itens_procedimentos').find('.itens_procedimentos_row').length == 0) {
                addConvenio();
            }
        });

        function getValorProcedimento(element){
            valor = $('option:selected', element).attr('data-valor');
            var quantidade = $('option:selected', element).attr('data-exige-qtd');
            
            if(quantidade == 'false'){
                $(element).parents(".itens_procedimentos_row").find('.exige_quantidade').css('display', 'none');
            }else{
                $(element).parents(".itens_procedimentos_row").find('.exige_quantidade').css('display', 'block');
            }

            $(element).parents(".itens_procedimentos_row").find('.qtd_procedimento').val(1);
            $(element).parents(".itens_procedimentos_row").find('.valor_procedimento').val(valor);
            $(element).parents(".itens_procedimentos_row").find('.valor_procedimento').setMask();

            totalProcedimentos()
        }

        function getNovoValor(element){

            console.log($(element).parents(".itens_procedimentos_row").find('.procedimentos option:selected'));

            var valor_procedimento = retornaFormatoValor($(element).parents(".itens_procedimentos_row").find('.procedimentos option:selected').attr('data-valor'))
            var quantidade_procedimento = $(element).val();

            var valor_novo = quantidade_procedimento * valor_procedimento;

            $(element).parents(".itens_procedimentos_row").find('.valor_procedimento').val(valor_novo);
            $(element).parents(".itens_procedimentos_row").find('.valor_procedimento').setMask();
            // totalProcedimentos();
            // calculaValorNovoDescricao(element);
        }

        function totalProcedimentos(){

            var total_procedimentos = 0;

            $(".valor_procedimento").each(function(index, element) {
                var valor_procedimento = retornaFormatoValor($(element).val())
                total_procedimentos = parseFloat(valor_procedimento) + parseFloat(total_procedimentos);
            })

            $("#total_procedimentos").val(total_procedimentos.toFixed(2))
            $("#total_procedimentos").setMask()
        }

        function retornaFormatoValor(valor){
            console.log(valor);
            
            var novo = valor;
            novo = novo.replace('.','')
            novo = novo.replace(',','.')
            return novo;
        }

        $(".cep").blur(function() {

            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if(validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    $('#endereco_responsavel').val('')
                    $('#bairro_responsavel').val('')
                    $('#cidade_responsavel').val('')
                    $('#uf_responsavel').val('')
                    

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#endereco_responsavel").val(dados.logradouro);
                            $("#bairro_responsavel").val(dados.bairro);
                            $("#cidade_responsavel").val(dados.localidade);
                            $("#uf_responsavel").val(dados.uf);

                        } else {
                            //CEP pesquisado não foi encontrado.
                            // limpa_formulário_cep();
                            // $('#fretes_input').css('display', 'none');
                            swal("CEP não encontrado.");

                        }
                    });
                } //end if.
                else {
                    //cep é inválido.
                    // limpa_formulário_cep();
                    swal("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                // limpa_formulário_cep();
            }
        });

    </script>
@endpush

<script type="text/template" id="itens_procedimentos">
    <div class="col-md-12 itens_procedimentos_row">
        <div class="row">
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
            </div>
            
            <div class="form-group dados_parcela col-md-4">
                <label class="form-control-label">Convênio:</span></label>
                <select name="itens[#][convenio]" class="form-control selectfild2 convenio" style="width: 100%" onchange="getProcedimentos(this)">
                    <option value="">Selecione um convênio</option>
                    @foreach ($convenios as $item)
                        <option value="{{$item->id}}">{{$item->nome}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="form-control-label">Procedimento *</label>
                <select name="itens[#][procedimento]" class="form-control selectfild2 procedimentos" onchange="getValorProcedimento(this)" disabled style="width: 100%">
                  <option value="">Selecione um procedimento</option>
                </select>
            </div>
            <div class="form-group col-md-2 exige_quantidade">
              <label class="form-control-label">Qtd *</span></label>
              <input type="number" class="form-control qtd_procedimento" name="itens[#][qtd_procedimento]" value='1' onchange="getNovoValor(this)">
            </div>
            
            <div class="form-group col-md-2">
                <label class="form-control-label">Valor R$ *</span></label>
                <input type="text" alt="decimal" class="form-control mask_item  valor_procedimento" name="itens[#][valor]" readonly>
            </div>
        </div>
    </div>
  </script>
