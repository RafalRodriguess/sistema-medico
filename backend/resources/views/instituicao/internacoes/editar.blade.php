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

            <form action="{{ route('instituicao.internacoes.update', [$internacao]) }}" id="formInternacao" method="post">
                @method('put')
                @csrf
                <div class="row paciente">
                    <div class="col-md-8 form-group @if($errors->has('paciente_id')) has-danger @endif">
                        <input type="hidden" name="paciente_id", id="paciente_id" value="{{ old('paciente_id', $internacao->paciente_id) }}"/>
                        <label class="form-control-label p-0 m-0">Paciente <span class="text-danger">*</span></label>
                        <i class="mdi mdi-eye-outline modal_mostra_paciente btn btn-secondary btn-sm"></i>
                        <i class="mdi mdi-account-card-details modal_mostra_carteirinha btn btn-secondary btn-sm"></i>
                        <input type="text" name="paciente_nome" id="paciente_nome" class="form-control" disabled/>

                        @if($errors->has('paciente_id'))
                            <small class="form-control-feedback">{{ $errors->first('paciente_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('previsao_alta')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Previsao alta <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control p-0 m-0" value="{{ old('previsao_alta', $internacao->previsao_alta) }}"
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
                                <option {{ (old('origem_id', $internacao->origem_id) == $origem->id) ? 'selected' : '' }} value="{{ $origem->id }}">{{ $origem->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('origem_id'))
                            <small class="form-control-feedback">{{ $errors->first('origem_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('medico_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Médico</label>
                        <select class="form-control p-0 m-0 selectfild2" name="medico_id" id="medico_id" disabled>
                            <option value="" selected>Nenhum</option>
                            @foreach ($medicos as $medico)
                                <option {{ (old('medico_id', !empty($internacao_medicos[0]->medico_id) ? $internacao_medicos[0]->medico_id : null ) == $medico->id) ? 'selected' : '' }} value="{{ $medico->id }}">{{ $medico->nome }}</option>
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
                                <option {{ (old('especialidade_id', $internacao->especialidade_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
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
                        <select class="form-control @if($errors->has('acomodacao_id')) form-control-danger @endif" name="acomodacao_id" id="acomodacao_id" disabled>
                            <option value="" selected>Nenhum</option>
                            @foreach ($acomodacoes as $acomodacao)
                                <option {{ (old('acomodacao_id', !empty($leitos[0]->acomodacao_id) ? $leitos[0]->acomodacao_id : null) == $acomodacao->id) ? 'selected' : '' }} value="{{ $acomodacao->id }}">{{ $acomodacao->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('acomodacao_id'))
                            <small class="form-control-feedback">{{ $errors->first('acomodacao_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('unidade_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Unidade</label>
                        <select class="form-control selectfild2 @if($errors->has('unidade_id')) form-control-danger @endif" name="unidade_id" id="unidade_id" disabled>
                            <option value="" selected>Nenhum</option>
                            @foreach ($unidades as $unidade)
                                <option {{ (old('unidade_id', !empty($leitos[0]->unidade_id) ? $leitos[0]->unidade_id : null) == $unidade->id) ? 'selected' : '' }} value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('unidade_id'))
                            <small class="form-control-feedback">{{ $errors->first('unidade_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('leito_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Leito</label>
                        <input type="text" class="form-control" value="{{!empty($leitos[0]->leito_id) ? $leitos[0]->leito->descricao : ''}}" disabled>                      
                        {{-- <select class="form-control p-0 m-0 selectfild2" name="leito_id" id="leito_id" disabled>
                            <option value="" selected>Nenhum</option>
                        </select> --}}
                        @if($errors->has('leito_id'))
                            <small class="form-control-feedback">{{ $errors->first('leito_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class='row'>
                    <div class="col-md-3 form-group @if($errors->has('acompanhante')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Acompanhante</label>
                        <select class="form-control @if($errors->has('acompanhante')) form-control-danger @endif" name="acompanhante" id="acompanhante">
                            <option {{ (old('acompanhante', $internacao->acompanhante) == 0) ? 'selected' : '' }} value="0" >Não</option>
                            <option {{ (old('acompanhante', $internacao->acompanhante) == 1) ? 'selected' : '' }} value="1" >Sim</option>
                        </select>
                        @if($errors->has('acompanhante'))
                            <small class="form-control-feedback">{{ $errors->first('acompanhante') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('tipo_internacao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo</label>
                        <select class="form-control @if($errors->has('tipo_internacao')) form-control-danger @endif" name="tipo_internacao" id="tipo_internacao">
                            <option value="" >Nenhum</option>
                            <option {{ (old('tipo_internacao', $internacao->tipo_internacao) == 1) ? 'selected' : '' }} value="1" >Clínico</option>
                            <option {{ (old('tipo_internacao', $internacao->tipo_internacao) == 2) ? 'selected' : '' }} value="2" >Cirúrgico</option>
                            <option {{ (old('tipo_internacao', $internacao->tipo_internacao) == 3) ? 'selected' : '' }} value="3" >Materno-Infantil</option>
                            <option {{ (old('tipo_internacao', $internacao->tipo_internacao) == 4) ? 'selected' : '' }} value="4" >Neonatalogia</option>
                            <option {{ (old('tipo_internacao', $internacao->tipo_internacao) == 5) ? 'selected' : '' }} value="5" >Obstetrícia</option>
                            <option {{ (old('tipo_internacao', $internacao->tipo_internacao) == 6) ? 'selected' : '' }} value="6" >Pediatria</option>
                            <option {{ (old('tipo_internacao', $internacao->tipo_internacao) == 7) ? 'selected' : '' }} value="7" >Psiquiatria</option>
                            <option {{ (old('tipo_internacao', $internacao->tipo_internacao) == 8) ? 'selected' : '' }} value="8" >Outros</option>
                        </select>
                        @if($errors->has('tipo_internacao'))
                            <small class="form-control-feedback">{{ $errors->first('tipo_internacao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('reserva_leito')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Reserva de Leito</label>
                        <select class="form-control @if($errors->has('reserva_leito')) form-control-danger @endif" name="reserva_leito" id="reserva_leito">
                            <option {{ (old('reserva_leito', $internacao->reserva_leito) == 0) ? 'selected' : '' }} value="0" >Não</option>
                            <option {{ (old('reserva_leito', $internacao->reserva_leito) == 1) ? 'selected' : '' }} value="1" >Sim</option>
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
                                <option {{ (old('cid_id', $internacao->cid_id) == $cid->id) ? 'selected' : '' }} value="{{ $cid->id }}">{{ $cid->codigo }} - {{ $cid->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cid_id'))
                            <small class="form-control-feedback">{{ $errors->first('cid_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class='row'>
                    <div class="col-md-12 form-group @if($errors->has('observacao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Observação</label>
                        <textarea rows='4' class="form-control @if($errors->has('observacao')) form-control-danger @endif" name="observacao" id="observacao">{{ old('observacao', $internacao->obsercacao) }}</textarea>
                        @if($errors->has('observacao'))
                            <small class="form-control-feedback">{{ $errors->first('observacao') }}</small>
                        @endif
                    </div>
                </div>

                <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">

                <div class="itens_procedimentos row">
                    <div class="form-group col-md-12"><h5>Procedimentos:</h5> </div>
                    @include('instituicao.internacoes.procedimentos_salvos')
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
                    <div class="col-sm-6">
                        <div class="card shadow-none p-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input campo" name="possui_responsavel" value="1" @if(old('possui_responsavel', $internacao->possui_responsavel) == "1") checked @endif id="responsavel">
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
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Pai') ? 'selected' : '' }} value="Pai">Pai</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Mãe') ? 'selected' : '' }} value="Mãe">Mãe</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Avó') ? 'selected' : '' }} value="Avó">Avó</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Avo') ? 'selected' : '' }} value="Avô">Avô</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Tia') ? 'selected' : '' }} value="Tia">Tia</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Tio') ? 'selected' : '' }} value="Tio">Tio</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Madrastas') ? 'selected' : '' }} value="Madrasta">Madrasta</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Padrastro') ? 'selected' : '' }} value="Padrasto">Padrasto</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Irmão') ? 'selected' : '' }} value="Irmão">Irmão</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'irmã') ? 'selected' : '' }} value="Irmã">Irmã</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Primo') ? 'selected' : '' }} value="Primo">Primo</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Prima') ? 'selected' : '' }} value="Prima">Prima</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Sobrinha') ? 'selected' : '' }} value="Sobrinha">Sobrinha</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Sobrinho') ? 'selected' : '' }} value="Sobrinho">Sobrinho</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Cunhado') ? 'selected' : '' }} value="Cunhado">Cunhado</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Cunhada') ? 'selected' : '' }} value="Cunhada">Cunhada</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Amigo') ? 'selected' : '' }} value="Amigo">Amigo</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Amiga') ? 'selected' : '' }} value="Amiga">Amiga</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Filho') ? 'selected' : '' }} value="Filho">Filho</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Filha') ? 'selected' : '' }} value="Filha">Filha</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Namorada') ? 'selected' : '' }} value="Namorada">Namorada</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Namorado') ? 'selected' : '' }} value="Namorado">Namorado</option>
                                <option {{ (old('parentesco_responsavel', $internacao->parentesco_responsavel) == 'Outro') ? 'selected' : '' }} value="Outro">Outro</option>
                            </select>
                            @if($errors->has('parentesco_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('parentesco_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md-6 form-group @if($errors->has('nome_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('nome_responsavel', $internacao->nome_responsavel) }}"
                                name="nome_responsavel" id="nome_responsavel" >
                            @if($errors->has('nome_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('nome_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('estado_civil_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Estado Civil</label>
                            <select class="form-control @if($errors->has('estado_civil_responsavel')) form-control-danger @endif" name="estado_civil_responsavel" id="estado_civil_responsavel">
                                <option value="" selected>Nenhum</option>
                                <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Solteiro') ? 'selected' : '' }} value="Solteiro">Solteiro</option>
                                <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Casado') ? 'selected' : '' }} value="Casado">Casado</option>
                                <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Viúvo') ? 'selected' : '' }} value="Viúvo">Viúvo</option>
                                <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Divorciado') ? 'selected' : '' }} value="Divorciado">Divorciado</option>
                                <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Outro') ? 'selected' : '' }} value="Outro">Outro</option>
                            </select>
                            @if($errors->has('estado_civil_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('estado_civil_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('profissao_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Profissão</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('profissao_responsavel', $internacao->profissao_responsavel) }}"
                                name="profissao_responsavel" id="profissao_responsavel" >
                            @if($errors->has('profissao_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('profissao_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('nacionalidade_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nacionalidade</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('nacionalidade_responsavel', $internacao->nacionalidade_responsavel) }}"
                                name="nacionalidade_responsavel" id="nacionalidade_responsavel" >
                            @if($errors->has('nacionalidade_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('nacionalidade_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('telefone1_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Telefone 1 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-0 m-0 telefone" value="{{ old('telefone1_responsavel', $internacao->telefone1_responsavel) }}"
                                name="telefone1_responsavel" id="telefone1_responsavel" >
                            @if($errors->has('telefone1_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('telefone1_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('telefone2_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Telefone 2</label>
                            <input type="text" class="form-control p-0 m-0 telefone" alt='phone' value="{{ old('telefone2_responsavel', $internacao->telefone2_responsavel) }}"
                                name="telefone2_responsavel" id="telefone2_responsavel" >
                            @if($errors->has('telefone2_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('telefone2_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('cpf_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">CPF</label>
                            <input type="text" class="form-control p-0 m-0" alt='cpf' value="{{ old('cpf_responsavel', $internacao->cpf_responsavel) }}"
                                name="cpf_responsavel" id="cpf_responsavel" >
                            @if($errors->has('cpf_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('cpf_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('endereco_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Identidade</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('identidade_responsavel', $internacao->identidade_responsavel) }}"
                                name="identidade_responsavel" id="identidade_responsavel" >
                            @if($errors->has('identidade_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('identidade_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('contato_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Contato</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('contato_responsavel', $internacao->contato_responsavel) }}"
                                name="contato_responsavel" id="contato_responsavel" >
                            @if($errors->has('contato_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('contato_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-2 form-group @if($errors->has('cep_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">CEP</label>
                            <input type="text" class="form-control p-0 m-0 cep" alt='cep' value="{{ old('cep_responsavel', $internacao->cep_responsavel) }}"
                                name="cep_responsavel" id="cep_responsavel" >
                            @if($errors->has('cep_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('cep_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('endereco_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Endereço</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('endereco_responsavel', $internacao->endereco_responsavel) }}"
                                name="endereco_responsavel" id="endereco_responsavel" >
                            @if($errors->has('endereco_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('endereco_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md-2 form-group @if($errors->has('numero_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Número</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('numero_responsavel', $internacao->numero_responsavel) }}"
                                name="numero_responsavel" id="numero_responsavel" >
                            @if($errors->has('numero_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('numero_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('complemento_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Complemento</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('complemento_responsavel', $internacao->complemento_responsavel) }}"
                                name="complemento_responsavel" id="complemento_responsavel" >
                            @if($errors->has('complemento_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('complemento_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('bairro_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Bairro</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('bairro_responsavel', $internacao->bairro_responsavel) }}"
                                name="bairro_responsavel" id="bairro_responsavel" >
                            @if($errors->has('bairro_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('bairro_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('cidade_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Cidade</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('cidade_responsavel', $internacao->cidade_responsavel) }}"
                                name="cidade_responsavel" id="cidade_responsavel" >
                            @if($errors->has('cidade_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('cidade_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('uf_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Estado</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ old('uf_responsavel', $internacao->uf_responsavel) }}"
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
        var leitoId = {{old('leito_id', $internacao->leito_id) ? old('leito_id', $internacao->leito_id) : 0}};
        
        $(document).ready(function(){
            getPaciente($('#paciente_id').val())
            // getEspecialidade()
            getLeitos()
            responsavel()
            
            totalProcedimentos()

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
                $.ajax("{{ route('instituicao.internacoes.update', [$internacao]) }}", {
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
                            window.location="{{ route('instituicao.internacoes.edit', [$internacao]) }}";
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
            getPaciente()
        })

        // $('#medico_id').on('change', function(){
        //     getEspecialidade()
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
                        $('#paciente_id').val(retorno.id);
                        $('#paciente_nome').val(retorno.nome+' - '+retorno.cpf);
                        $("#modalPaciente").modal('hide');
                    }
                })
           }
        }

        // function getEspecialidade(){
        //     if($('#medico_id').val() == ''){
        //         $('#especialidade_id').find('option').filter(':not([value=""])').remove();
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
        //             success: function(retorno){
        //                 $('#especialidade_id').find('option').filter(':not([value=""])').remove();

        //                 for (i = 0; i < retorno.length; i++) {
        //                     var selected = '';
        //                     if(especialidadeId == retorno[i]['id']){
        //                         selected = "selected";
        //                     }else if({{old('especialidade_id', $internacao->especialidade_id)}} == retorno[i]['id']){
        //                         selected = "selected";
        //                     }
        //                    $('#especialidade_id').append("<option value = "+ retorno[i]['id'] +" "+selected+">" + retorno[i]['descricao'] + "</option>");
        //                 }
        //             }
        //         })
        //     }

        // }

        function getLeitos(){
            if($('#unidade_id').val() == ''){
                $('#leito_id').find('option').filter(':not([value=""])').remove();
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

                success: function (result) {
                    $("#modal_internacao").find("#tabela").html(result)
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

        var quantidade_convenio = $('.itens_procedimentos_row').find('.item-convenio').length;
        $(".mytooltip").tooltip();

        function getProcedimentos(element){
            var id = $(element).val()
            var prestador_id =  $('#medico_id').val()
            var options = $(element).parents(".itens_procedimentos_row").find('.procedimentos');

            if(prestador_id == ""){
                $.toast({
                    heading: "Falha",
                    text: "É necessario escolher um MÉDICO para lançar procedimentos",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: "error",
                    hideAfter: 5000,
                    stack: 10
                });
                
                $(element).val("")
                return;
            }

            $.ajax({
                url: "{{route('instituicao.agendamentos.getProcedimentos', ['convenio' => 'convenio_id', 'prestador' => 'prestador_id'])}}".replace('convenio_id', id).replace('prestador_id', prestador_id),
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
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
            // verificaMaiorTempoProcedimentos()
            if ($('.itens_procedimentos').find('.itens_procedimentos_row').length == 0) {
                addConvenio();
            }
        });

        function getValorProcedimento(element){
            valor = $('option:selected', element).attr('data-valor');
            var quantidade = $('option:selected', element).attr('data-exige-qtd');
            var cobrar = $('option:selected', element).attr('data-cobrar');

            if(quantidade == 'false'){
                $(element).parents(".itens_procedimentos_row").find('.exige_quantidade').css('display', 'none');
            }else{
                $(element).parents(".itens_procedimentos_row").find('.exige_quantidade').css('display', 'block');
            }

            if(cobrar == 'true'){
                valor = 0
            }

            $(element).parents(".itens_procedimentos_row").find('.qtd_procedimento').val(1);
            $(element).parents(".itens_procedimentos_row").find('.valor_procedimento').val(valor);
            $(element).parents(".itens_procedimentos_row").find('.valor_procedimento').setMask();

            totalProcedimentos()
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
                <select name="itens[#][procedimento]" id="itens[#][procedimento]" class="form-control selectfild2 procedimentos" onchange="getValorProcedimento(this)" disabled style="width: 100%">
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

