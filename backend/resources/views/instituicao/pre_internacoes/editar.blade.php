@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Editar pré internação',
        'breadcrumb' => [
            'Pre Internação' => route('instituicao.preInternacoes.index'),
            'editar',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">

        <div class="card-body">

            <div class="paciente"></div>

            <form action="{{ route('instituicao.preInternacoes.update', [$pre_internacao]) }}" method="post">
                @method('put')
                @csrf
                <div class="row">
                    @php
                        $paciente_nome = $pre_internacao->paciente->nome." ". $pre_internacao->paciente->cpf ? "- (".$pre_internacao->paciente->cpf.")" : "";
                    @endphp
                    <div class="col-md-8 form-group @if($errors->has('paciente_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Paciente</label>
                        <select class="form-control col-sm-11" name="paciente_id" id="paciente_id">
                            @if(empty(old('paciente_nome')))
                                <option value="{{$pre_internacao->paciente->id}}">{{$pre_internacao->paciente->nome}} {{$pre_internacao->paciente->cpf ? "- (".$pre_internacao->paciente->cpf.")" : ""}}</option>
                            @elseif(old('paciente_id'))
                                <option selected value="{{ old('paciente_id') }}">{{ old('paciente_nome') }}</option>
                            @endif

                            {{-- @foreach ($pacientes as $paciente)
                                <option {{($paciente->id == $pre_internacao->paciente_id) ? 'selected' : '' }} value="{{ $paciente->id }}">{{ $paciente->nome }} - {{ $paciente->cpf }}</option>
                            @endforeach --}}
                        </select>
                        <i class="mdi mdi-plus modal_add_paciente btn btn-secondary btn-sm"></i>
                        @if($errors->has('paciente_id'))
                            <small class="form-control-feedback">{{ $errors->first('paciente_id') }}</small>
                        @endif
                        <input type="hidden" name='paciente_nome' value="{{old('paciente_nome', $paciente_nome)}}">
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('previsao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Previsao</label>
                        <input type="datetime-local" class="form-control p-0 m-0" value="{{ str_replace(" ", "T", $pre_internacao->previsao) }}" name="previsao" id="previsao" >
                        @if($errors->has('previsao'))
                            <small class="form-control-feedback">{{ $errors->first('previsao') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                   <div class="col-md-4 form-group @if($errors->has('origem_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Origem</label>
                        <select class="form-control p-0 m-0 selectfild2" name="origem_id" id="origem_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($origens as $origem)
                                <option {{($origem->id == $pre_internacao->origem_id) ? 'selected' : '' }} value="{{ $origem->id }}">{{ $origem->descricao }}</option>
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
                                <option {{($medico->id == $pre_internacao->medico_id) ? 'selected' : '' }} value="{{ $medico->id }}">{{ $medico->nome }}</option>
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
                                <option {{ (old('especialidade_id', $pre_internacao->especialidade_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
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
                        <select class="form-control @if($errors->has('acomodacao_id')) form-control-danger @endif" name="acomodacao_id" id="acomodacao_id">
                            <option value="" selected>Nenhum</option>
                            @foreach ($acomodacoes as $acomodacao)
                                <option {{($acomodacao->id == $pre_internacao->acomodacao_id) ? 'selected' : '' }} value="{{ $acomodacao->id }}">{{ $acomodacao->descricao }}</option>
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
                                <option {{($unidade->id == $pre_internacao->unidade_id) ? 'selected' : '' }} value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
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
                            <option value="0" selected>Não</option>
                            <option value="1" selected>Sim</option>
                        </select>
                        @if($errors->has('acompanhante'))
                            <small class="form-control-feedback">{{ $errors->first('acompanhante') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('tipo_internacao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo</label>
                        <select class="form-control @if($errors->has('tipo_internacao')) form-control-danger @endif" name="tipo_internacao" id="tipo_internacao">
                            <option value="" selected>Nenhum</option>
                            <option {{($pre_internacao->tipo_internacao == 1) ? 'selected' : '' }} value="1" selected>Clínico</option>
                            <option {{($pre_internacao->tipo_internacao == 2) ? 'selected' : '' }} value="2" selected>Cirúrgico</option>
                            <option {{($pre_internacao->tipo_internacao == 3) ? 'selected' : '' }} value="3" selected>Materno-Infantil</option>
                            <option {{($pre_internacao->tipo_internacao == 4) ? 'selected' : '' }} value="4" selected>Neonatalogia</option>
                            <option {{($pre_internacao->tipo_internacao == 5) ? 'selected' : '' }} value="5" selected>Obstetrícia</option>
                            <option {{($pre_internacao->tipo_internacao == 6) ? 'selected' : '' }} value="6" selected>Pediatria</option>
                            <option {{($pre_internacao->tipo_internacao == 7) ? 'selected' : '' }} value="7" selected>Psiquiatria</option>
                            <option {{($pre_internacao->tipo_internacao == 8) ? 'selected' : '' }} value="8" selected>Outros</option>
                        </select>
                        @if($errors->has('tipo_internacao'))
                            <small class="form-control-feedback">{{ $errors->first('tipo_internacao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('reserva_leito')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Reserva de Leito</label>
                        <select class="form-control @if($errors->has('reserva_leito')) form-control-danger @endif" name="reserva_leito" id="reserva_leito">
                            <option {{($pre_internacao->reserva_leito == 0) ? 'selected' : '' }} value="0" selected>Não</option>
                            <option {{($pre_internacao->reserva_leito == 1) ? 'selected' : '' }} value="1" selected>Sim</option>
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
                                <option {{($cid->id == $pre_internacao->cid_id) ? 'selected' : '' }} value="{{ $cid->id }}">{{ $cid->codigo }} - {{ $cid->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cid_id'))
                            <small class="form-control-feedback">{{ $errors->first('cid_id') }}</small>
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
                    <div class="col-md-12 form-group @if($errors->has('observacao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Observação</label>
                        <textarea rows='4' class="form-control @if($errors->has('observacao')) form-control-danger @endif" name="observacao" id="observacao">{{ (old('observacao')) ? old('observacao') : $pre_internacao->observacao }}</textarea>
                        @if($errors->has('observacao'))
                            <small class="form-control-feedback">{{ $errors->first('observacao') }}</small>
                        @endif
                    </div>
                </div>

                <div class='row'>
                    <div class="col-sm-6">
                        <div class="card shadow-none p-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input campo" name="responsavel" value="1" {{ ($pre_internacao->possui_responsavel) ? 'checked' : '' }} @if(old('ativo')=="1") checked @endif id="responsavel">
                                <label class="form-check-label" for="responsavel">Responsável</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='responsavel'>
                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('parentesco_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Parentesco <span class="text-danger">*</span></label>
                            <select class="form-control @if($errors->has('parentesco_responsavel')) form-control-danger @endif" name="parentesco_responsavel" id="parentesco_responsavel">
                                <option value="" selected>Nenhum</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Pai') ? 'selected' : '' }} value="Pai">Pai</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Mãe') ? 'selected' : '' }} value="Mãe">Mãe</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Avó') ? 'selected' : '' }} value="Avó">Avó</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Avô') ? 'selected' : '' }} value="Avô">Avô</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Tia') ? 'selected' : '' }} value="Tia">Tia</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Tio') ? 'selected' : '' }} value="Tio">Tio</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Madrasta') ? 'selected' : '' }} value="Madrasta">Madrasta</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Padrasto') ? 'selected' : '' }} value="Padrasto">Padrasto</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Irmão') ? 'selected' : '' }} value="Irmão">Irmão</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Irmã') ? 'selected' : '' }} value="Irmã">Irmã</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Primo') ? 'selected' : '' }} value="Primo">Primo</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Prima') ? 'selected' : '' }} value="Prima">Prima</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Sobrinha') ? 'selected' : '' }} value="Sobrinha">Sobrinha</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Sobrinho') ? 'selected' : '' }} value="Sobrinho">Sobrinho</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Cunhado') ? 'selected' : '' }} value="Cunhado">Cunhado</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Cunhada') ? 'selected' : '' }} value="Cunhada">Cunhada</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Amigo') ? 'selected' : '' }} value="Amigo">Amigo</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Amiga') ? 'selected' : '' }} value="Amiga">Amiga</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Filho') ? 'selected' : '' }} value="Filho">Filho</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Filha') ? 'selected' : '' }} value="Filha">Filha</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Namorada') ? 'selected' : '' }} value="Namorada">Namorada</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Namorado') ? 'selected' : '' }} value="Namorado">Namorado</option>
                                <option {{($pre_internacao->parentesco_responsavel == 'Outro') ? 'selected' : '' }} value="Outro">Outro</option>
                            </select>
                            @if($errors->has('parentesco_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('parentesco_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md-6 form-group @if($errors->has('nome_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->nome_responsavel }}"
                                name="nome_responsavel" id="nome_responsavel" >
                            @if($errors->has('nome_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('nome_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('estado_civil_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Estado Civil</label>
                            <select class="form-control @if($errors->has('estado_civil_responsavel')) form-control-danger @endif" name="estado_civil_responsavel" id="estado_civil_responsavel">
                                <option value="" selected>Nenhum</option>
                                <option {{($pre_internacao->estado_civil_responsavel == 'Solteiro') ? 'selected' : '' }} value="Solteiro">Solteiro</option>
                                <option {{($pre_internacao->estado_civil_responsavel == 'Casado') ? 'selected' : '' }} value="Casado">Casado</option>
                                <option {{($pre_internacao->estado_civil_responsavel == 'Viúvo') ? 'selected' : '' }} value="Viúvo">Viúvo</option>
                                <option {{($pre_internacao->estado_civil_responsavel == 'Divorciado') ? 'selected' : '' }} value="Divorciado">Divorciado</option>
                                <option {{($pre_internacao->estado_civil_responsavel == 'Outro') ? 'selected' : '' }} value="Outro">Outro</option>
                            </select>
                            @if($errors->has('estado_civil_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('estado_civil_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('profissao_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Profissão</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->profissao_responsavel }}"
                                name="profissao_responsavel" id="profissao_responsavel" >
                            @if($errors->has('profissao_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('profissao_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('nacionalidade_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nacionalidade</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->nacionalidade_responsavel }}"
                                name="nacionalidade_responsavel" id="nacionalidade_responsavel" >
                            @if($errors->has('nacionalidade_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('nacionalidade_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('telefone1_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Telefone 1 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-0 m-0 telefone" value="{{ $pre_internacao->telefone1_responsavel }}"
                                name="telefone1_responsavel" id="telefone1_responsavel" >
                            @if($errors->has('telefone1_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('telefone1_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('telefone2_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Telefone 2</label>
                            <input type="text" class="form-control p-0 m-0 telefone" value="{{ $pre_internacao->telefone2_responsavel }}"
                                name="telefone2_responsavel" id="telefone2_responsavel" >
                            @if($errors->has('telefone2_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('telefone2_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('cpf_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">CPF</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->cpf_responsavel }}"
                                name="cpf_responsavel" id="cpf_responsavel" alt="cpf">
                            @if($errors->has('cpf_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('cpf_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('endereco_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Identidade</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->identidade_responsavel }}"
                                name="identidade_responsavel" id="identidade_responsavel">
                            @if($errors->has('identidade_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('identidade_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('contato_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Contato</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->contato_responsavel }}"
                                name="contato_responsavel" id="contato_responsavel">
                            @if($errors->has('contato_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('contato_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-2 form-group @if($errors->has('cep_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">CEP</label>
                            <input type="text" class="form-control p-0 m-0 cep" value="{{ $pre_internacao->cep_responsavel }}"
                                name="cep_responsavel" id="cep_responsavel" alt="cep">
                            @if($errors->has('cep_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('cep_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('endereco_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Endereço</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->endereco_responsavel }}"
                                name="endereco_responsavel" id="endereco_responsavel">
                            @if($errors->has('endereco_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('endereco_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md-2 form-group @if($errors->has('numero_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Número</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->numero_responsavel }}"
                                name="numero_responsavel" id="numero_responsavel" >
                            @if($errors->has('numero_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('numero_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('complemento_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Complemento</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->complemento_responsavel }}"
                                name="complemento_responsavel" id="complemento_responsavel">
                            @if($errors->has('complemento_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('complemento_responsavel') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md form-group @if($errors->has('bairro_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Bairro</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->bairro_responsavel }}"
                                name="bairro_responsavel" id="bairro_responsavel">
                            @if($errors->has('bairro_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('bairro_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('cidade_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Cidade</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->cidade_responsavel }}"
                                name="cidade_responsavel" id="cidade_responsavel">
                            @if($errors->has('cidade_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('cidade_responsavel') }}</small>
                            @endif
                        </div>

                        <div class="col-md form-group @if($errors->has('uf_responsavel')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Estado</label>
                            <input type="text" class="form-control p-0 m-0" value="{{ $pre_internacao->uf_responsavel }}"
                                name="uf_responsavel" id="uf_responsavel">
                            @if($errors->has('uf_responsavel'))
                                <small class="form-control-feedback">{{ $errors->first('uf_responsavel') }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.preInternacoes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>


        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // var especialidade = "{{ $pre_internacao->especialidade_id }}"
        var leito = "{{ $pre_internacao->leito_id }}"
        var medico = "{{ $pre_internacao->medico_id }}"
        var unidade = "{{ $pre_internacao->unidade_id }}"

        $(document).ready(function(){
            getPaciente()
            // getEspecialidade()
            getLeitos()
            responsavel()
            totalProcedimentos()

            $('.telefone').each(function(){
                $(this).setMask('(99) 99999-9999', {
                    translation: { '9': { pattern: /[0-9]/, optional: false} }
                })
            });

            $("#paciente_id").select2({
                placeholder: "Pesquise por nome do paciente",
                allowClear: true,
                minimumInputLength: 3,

                language: {
                    searching: function () {
                        return 'Buscando pacientes (aguarde antes de selecionar)…';
                    },

                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                    },
                },

                ajax: {
                    url:"{{route('instituicao.PreInternacoes.getPacientes')}}",
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
                                text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
            });

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
        })

        // $('#paciente_id').on('change', function(){
        //     $('[name="paciente_nome"]').val($('#paciente_id :selected').text())
        // })

        $('#paciente_id').on('change', function(){
            ('[name="paciente_nome"]').val($('#paciente_id :selected').text())
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
            }
        }

        function getPaciente(){
            if($('#paciente_id').val() == ''){
                $('#sexo').val('')
                $('#telefone1').val('')
                $('#telefone2').val('')
                $('#cidade').val('')
                $('#estado').val('')
            }else{
                id = $('#paciente_id').val();

                $.ajax({
                    url: "{{route('instituicao.PreInternacoes.getPaciente', ['paciente_id' => 'id'])}}".replace('id', id),
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        paciente_id: id
                    },
                    success: function(retorno){
                        $('#sexo').val(retorno['sexo'])
                        $('#telefone1').val(retorno['telefone1'])
                        $('#telefone2').val(retorno['telefone2'])
                        $('#cidade').val(retorno['cidade'])
                        $('#estado').val(retorno['estado'])
                    }
                })

            }

        }

        // function getEspecialidade(){
        //     if($('#medico_id').val() == ''){
        //         $('#especialidade_id').find('option').filter(':not([value=""])').remove();
        //         $('#especialidade_id').prop('disabled', true);
        //     }else{
        //         id = $('#medico_id').val();

        //         $.ajax({
        //             url: "{{route('instituicao.PreInternacoes.getEspecialidades', ['medico_id' => '_id'])}}".replace('_id', id),
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
        //                     var sel = ''

        //                     if(especialidade == retorno[i]['id']){
        //                         sel = "selected"
        //                     }

        //                     $('#especialidade_id').append("<option "+ sel +" value = "+ retorno[i]['id'] +" >" + retorno[i]['descricao'] + "</option>");
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
                id = $('#unidade_id').val();

                $.ajax({
                    url: "{{route('instituicao.PreInternacoes.getLeitos', ['unidade_id' => '_id'])}}".replace('_id', id),
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        unidade_id: id
                    },
                    beforeSend: () => {
                        $('#leito_id').prop('disabled', true);
                    },
                    success: function(retorno){
                        $('#leito_id').find('option').filter(':not([value=""])').remove();
                        var sel = ''
                        for (i = 0; i < retorno.length; i++) {
                            if(leito == retorno[i]['id'] && unidade == retorno[i]['unidade_id']){
                                sel = "selected"
                            }

                           $('#leito_id').append("<option "+ sel +" value = "+ retorno[i]['id'] +" >" + retorno[i]['descricao'] + "</option>");
                        }

                        $('#leito_id').prop('disabled', false);
                    }
                })

            }

        }

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
                    $('.paciente').html(retorno);
                    $('input').setMask();
                    $('#modalAddPaciente').modal('show');

                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader')
                },
            })
        });

        $('#salvar').on('click', function(e){
            e.preventDefault()
            var formData = new FormData($('#formPaciente')[0]);
            var paciente_id = $("input[name='id']").val()

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
                        $('#paciente_id').append("<option value = "+ response.dados.id +" selected>" + response.dados.nome +" - "+ response.dados.cpf +"</option>");
                        $('#paciente_id').change();
                        $("#modalAddPaciente").modal('hide');
                        console.log(response.dados);
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

                        } //end if.
                        else {
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

