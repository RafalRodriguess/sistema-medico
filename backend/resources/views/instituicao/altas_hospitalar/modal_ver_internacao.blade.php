<div id="modalVerInternacao" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Visualizar Paciente</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form>
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <form action="javascript:void(0)">
                            <div class="row paciente">
                                <div class="col-md-8 form-group @if($errors->has('paciente_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Paciente</label>
                                    <input type="text" name="paciente_nome" id="paciente_nome" value="{{$internacao->paciente->nome}}" class="form-control" readonly/>
                                </div>
            
                                <div class="col-md-3 form-group @if($errors->has('previsao_alta')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Previsao alta</label>
                                    <input type="datetime-local" class="form-control p-0 m-0" value="{{$internacao->previsao_alta}}" name="previsao_alta" id="previsao_alta" readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                               <div class="col-md-4 form-group @if($errors->has('origem_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Origem</label>
                                    <input type="text" class="form-control p-0 m-0" value="{{$internacao->origem_id ? $internacao->origem->descricao : "Nenhum"}}" readonly>
                                </div>
            
                                <div class="col-md-4 form-group @if($errors->has('medico_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Médico</label>
                                    <input type="text" class="form-control p-0 m-0" value="{{($internacao->internacaoMedicos->count() > 0) ? end($internacao->internacaoMedicos)[0]->medico->nome : "Nenhum"}}" readonly>
                                </div>
            
                                <div class="col-md-4 form-group @if($errors->has('especialidade_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Especialidade</label>
                                    <input type="text" class="form-control p-0 m-0" readonly value="{{ $internacao->especialidade_id ? $internacao->especialidade->descricao : "Nenhum"}}" readonly >
                                </div>
                            </div>
            
                            <div class="row">
                                <div class="col-md form-group @if($errors->has('acomodacao_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Acomodação</label>
                                    <input type="text" class="form-control p-0 m-0" readonly value="{{($internacao->internacaoLeitos->count() > 0) ? end($internacao->internacaoLeitos)[0]->acomodacao->descricao : "Nenhum"}}" readonly >
                                </div>
            
                                <div class="col-md form-group @if($errors->has('unidade_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Unidade</label>
                                    <input type="text" class="form-control p-0 m-0" readonly value="{{($internacao->internacaoLeitos->count() > 0) ? end($internacao->internacaoLeitos)[0]->unidade->nome : "Nenhum"}}" readonly >
                                </div>
            
                                <div class="col-md-4 form-group @if($errors->has('leito_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Leito</label>
                                    <input type="text" class="form-control" value="{{($internacao->internacaoLeitos->count() > 0) ? end($internacao->internacaoLeitos)[0]->leito->descricao : "Nenhum"}}" readonly>                      
                                    @if($errors->has('leito_id'))
                                        <small class="form-control-feedback">{{ $errors->first('leito_id') }}</small>
                                    @endif
                                </div>
                            </div>
            
                            <div class='row'>
                                <div class="col-md-3 form-group @if($errors->has('acompanhante')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Acompanhante</label>
                                    <select class="form-control @if($errors->has('acompanhante')) form-control-danger @endif" name="acompanhante" id="acompanhante" readonly>
                                        <option {{ (old('acompanhante', $internacao->acompanhante) == 0) ? 'selected' : '' }} value="0" >Não</option>
                                        <option {{ (old('acompanhante', $internacao->acompanhante) == 1) ? 'selected' : '' }} value="1" >Sim</option>
                                    </select>
                                    @if($errors->has('acompanhante'))
                                        <small class="form-control-feedback">{{ $errors->first('acompanhante') }}</small>
                                    @endif
                                </div>
            
                                <div class="col-md form-group @if($errors->has('tipo_internacao')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Tipo</label>
                                    <select class="form-control @if($errors->has('tipo_internacao')) form-control-danger @endif" name="tipo_internacao" id="tipo_internacao" readonly>
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
                                </div>
            
                                <div class="col-md-2 form-group @if($errors->has('reserva_leito')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Reserva de Leito</label>
                                    <select class="form-control @if($errors->has('reserva_leito')) form-control-danger @endif" name="reserva_leito" id="reserva_leito" readonly>
                                        <option {{ (old('reserva_leito', $internacao->reserva_leito) == 0) ? 'selected' : '' }} value="0" >Não</option>
                                        <option {{ (old('reserva_leito', $internacao->reserva_leito) == 1) ? 'selected' : '' }} value="1" >Sim</option>
                                    </select>
                                </div>

                                <div class="col-md form-group @if($errors->has('cid_id')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Cid</label>
                                    <input type="text" class="form-control" value="{{ $internacao->cid_id ? $internacao->cid->codigo." - ".$internacao->cid->descricao: "Nenhum" }}" readonly>                      
                                    

                                </div>
                            </div>
            
                            <div class='row'>
                                <div class="col-md-12 form-group @if($errors->has('observacao')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Observação</label>
                                    <textarea rows='4' class="form-control @if($errors->has('observacao')) form-control-danger @endif" name="observacao" id="observacao">{{ old('observacao', $internacao->obsercacao) }}</textarea>
                                </div>
                            </div>
            
                            <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">
            
                            <div class="itens_procedimentos row">
                                <div class="form-group col-md-12"><h5>Procedimentos:</h5> </div>
                                @foreach($internacao->procedimentos as $key => $value)
                                    <div class="col-md-12 itens_procedimentos_row row">
                                        <div class="form-group dados_parcela col-md-4">
                                            <label class="form-control-label">Convênio:</span></label>
                                            <input type="hidden" readonly name="itens[{{$key}}][convenio]" value="{{$value->convenios_id}}" />
                                            <input type="text" readonly readonly class="form-control item-convenio" value="{{$value->convenios->nome}}" />
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-control-label">Procedimento</label>
                                            <input type="hidden" readonly name="itens[{{$key}}][procedimento]" value="{{$value->id}}" />
                                            <input type="text" readonly class="form-control " value="{{$value->procedimentoInstituicao->procedimento->descricao}}" />
                                        </div>
                                        
                                        <div class="form-group col-md-2 exige_quantidade">
                                            <label class="form-control-label">Qtd</span></label>
                                            <input type="text" readonly class="form-control qtd_procedimentor" name="itens[{{$key}}][qtd_procedimento]" value="{{$value->pivot->quantidade_procedimento}}" {{-- onchange="getNovoValor(this)"--}}> 
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label class="form-control-label">Valor *</span></label>
                                            <input type="text" readonly alt="decimal" class="form-control valor_mask valor_procedimento" name="itens[{{$key}}][valor]" id="itens[{{$key}}][valor]" value="{{$value->pivot->valor}}" readonly>
                                        </div>
                                    </div>
                            
                                @endforeach
                                <div class="col-md-9"></div>
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
            
                            <div class='responsavel' @if(old('possui_responsavel', $internacao->possui_responsavel) == "0")  style="display:none" @endif>
                                <div class='row'>
                                    <div class="col-md form-group @if($errors->has('parentesco_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Parentesco</label>
                                        <select class="form-control @if($errors->has('parentesco_responsavel')) form-control-danger @endif" name="parentesco_responsavel" id="parentesco_responsavel" readonly>
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
                                    </div>
            
                                    <div class="col-md-6 form-group @if($errors->has('nome_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nome</label>
                                        <input type="text" class="form-control p-0 m-0" value="{{ old('nome_responsavel', $internacao->nome_responsavel) }}"
                                            name="nome_responsavel" id="nome_responsavel" readonly>
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('estado_civil_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Estado Civil</label>
                                        <select class="form-control @if($errors->has('estado_civil_responsavel')) form-control-danger @endif" readonly name="estado_civil_responsavel" id="estado_civil_responsavel">
                                            <option value="" selected>Nenhum</option>
                                            <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Solteiro') ? 'selected' : '' }} value="Solteiro">Solteiro</option>
                                            <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Casado') ? 'selected' : '' }} value="Casado">Casado</option>
                                            <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Viúvo') ? 'selected' : '' }} value="Viúvo">Viúvo</option>
                                            <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Divorciado') ? 'selected' : '' }} value="Divorciado">Divorciado</option>
                                            <option {{ (old('estado_civil_responsavel', $internacao->estado_civil_responsavel) == 'Outro') ? 'selected' : '' }} value="Outro">Outro</option>
                                        </select>
                                    </div>
                                </div>
            
                                <div class='row'>
                                    <div class="col-md form-group @if($errors->has('profissao_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Profissão</label>
                                        <input type="text" class="form-control p-0 m-0" readonly value="{{ old('profissao_responsavel', $internacao->profissao_responsavel) }}"
                                            name="profissao_responsavel" id="profissao_responsavel" >
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('nacionalidade_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nacionalidade</label>
                                        <input type="text" class="form-control p-0 m-0" value="{{ old('nacionalidade_responsavel', $internacao->nacionalidade_responsavel) }}"
                                            name="nacionalidade_responsavel" id="nacionalidade_responsavel" readonly >
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('telefone1_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 1</label>
                                        <input type="text" class="form-control p-0 m-0 telefone" readonly value="{{ old('telefone1_responsavel', $internacao->telefone1_responsavel) }}"
                                            name="telefone1_responsavel" id="telefone1_responsavel" >
                                        @if($errors->has('telefone1_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('telefone1_responsavel') }}</small>
                                        @endif
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('telefone2_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 2</label>
                                        <input type="text" class="form-control p-0 m-0 telefone" alt='phone' readonly value="{{ old('telefone2_responsavel', $internacao->telefone2_responsavel) }}"
                                            name="telefone2_responsavel" id="telefone2_responsavel" >
                                        @if($errors->has('telefone2_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('telefone2_responsavel') }}</small>
                                        @endif
                                    </div>
                                </div>
            
                                <div class='row'>
                                    <div class="col-md form-group @if($errors->has('cpf_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">CPF</label>
                                        <input type="text" class="form-control p-0 m-0" alt='cpf' readonly value="{{ old('cpf_responsavel', $internacao->cpf_responsavel) }}"
                                            name="cpf_responsavel" id="cpf_responsavel" >
                                        @if($errors->has('cpf_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('cpf_responsavel') }}</small>
                                        @endif
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('endereco_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Identidade</label>
                                        <input type="text" class="form-control p-0 m-0" readonly value="{{ old('identidade_responsavel', $internacao->identidade_responsavel) }}"
                                            name="identidade_responsavel" id="identidade_responsavel" >
                                        @if($errors->has('identidade_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('identidade_responsavel') }}</small>
                                        @endif
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('contato_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Contato</label>
                                        <input type="text" class="form-control p-0 m-0" readonly value="{{ old('contato_responsavel', $internacao->contato_responsavel) }}"
                                            name="contato_responsavel" id="contato_responsavel" >
                                        @if($errors->has('contato_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('contato_responsavel') }}</small>
                                        @endif
                                    </div>
                                </div>
            
                                <div class='row'>
                                    <div class="col-md-2 form-group @if($errors->has('cep_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">CEP</label>
                                        <input type="text" class="form-control p-0 m-0 cep" alt='cep' readonly value="{{ old('cep_responsavel', $internacao->cep_responsavel) }}"
                                            name="cep_responsavel" id="cep_responsavel" >
                                        @if($errors->has('cep_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('cep_responsavel') }}</small>
                                        @endif
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('endereco_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Endereço</label>
                                        <input type="text" class="form-control p-0 m-0" readonly value="{{ old('endereco_responsavel', $internacao->endereco_responsavel) }}"
                                            name="endereco_responsavel" id="endereco_responsavel" >
                                        @if($errors->has('endereco_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('endereco_responsavel') }}</small>
                                        @endif
                                    </div>
            
                                    <div class="col-md-2 form-group @if($errors->has('numero_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Número</label>
                                        <input type="text" class="form-control p-0 m-0" readonly value="{{ old('numero_responsavel', $internacao->numero_responsavel) }}"
                                            name="numero_responsavel" id="numero_responsavel" >
                                        @if($errors->has('numero_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('numero_responsavel') }}</small>
                                        @endif
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('complemento_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Complemento</label>
                                        <input type="text" class="form-control p-0 m-0" readonly value="{{ old('complemento_responsavel', $internacao->complemento_responsavel) }}"
                                            name="complemento_responsavel" id="complemento_responsavel" >
                                        @if($errors->has('complemento_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('complemento_responsavel') }}</small>
                                        @endif
                                    </div>
                                </div>
            
                                <div class='row'>
                                    <div class="col-md form-group @if($errors->has('bairro_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Bairro</label>
                                        <input type="text" class="form-control p-0 m-0" readonly value="{{ old('bairro_responsavel', $internacao->bairro_responsavel) }}"
                                            name="bairro_responsavel" id="bairro_responsavel" >
                                        @if($errors->has('bairro_responsavel'))
                                            <small class="form-control-feedback">{{ $errors->first('bairro_responsavel') }}</small>
                                        @endif
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('cidade_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Cidade</label>
                                        <input type="text" class="form-control p-0 m-0" readonly value="{{ old('cidade_responsavel', $internacao->cidade_responsavel) }}"
                                            name="cidade_responsavel" id="cidade_responsavel" >
                                    </div>
            
                                    <div class="col-md form-group @if($errors->has('uf_responsavel')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Estado</label>
                                        <input type="text" class="form-control p-0 m-0" value="{{ old('uf_responsavel', $internacao->uf_responsavel) }}"
                                            name="uf_responsavel" id="uf_responsavel" readonly  >
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>
