@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar instituicao #{$instituicao->id} {$instituicao->nome}",
        'breadcrumb' => [
            'Instituição' => route('instituicoes.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <input type="hidden" id="idinstituicao" value="{{ $instituicao->id }}">
            <form action="{{ route('instituicoes.update', [$instituicao]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf

                <div class="row">
                    <div class="col-md-6 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Nome *</span></label>
                        <input type="text" name="nome" value="{{ old('nome', $instituicao->nome) }}"
                            class="form-control @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                            <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>

                    <div class="col-md-6 form-group @if($errors->has('razao_social')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Razão Social *</label>
                        <input type="text" name="razao_social" value="{{ old('razao_social', $instituicao->razao_social) }}"
                            class="form-control @if($errors->has('razao_social')) form-control-danger @endif">
                        @if($errors->has('razao_social'))
                            <div class="form-control-feedback">{{ $errors->first('razao_social') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('cnpj')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">CNPJ *</label>
                        <input type="text" name="cnpj" id="cnpj" alt="cnpj" value="{{ old('cnpj', $instituicao->cnpj) }}"
                            class="form-control @if($errors->has('cnpj')) form-control-danger @endif">
                        @if($errors->has('cnpj'))
                            <div class="form-control-feedback">{{ $errors->first('cnpj') }}</div>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('inscricao_estadual')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Inscrição Estadual</label>
                        <input type="text" name="inscricao_estadual" value="{{ old('inscricao_estadual', $instituicao->inscricao_estadual) }}"
                            class="form-control @if($errors->has('inscricao_estadual')) form-control-danger @endif">
                        @if($errors->has('inscricao_estadual'))
                            <div class="form-control-feedback">{{ $errors->first('inscricao_estadual') }}</div>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('inscricao_municipal')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Inscrição Municipal</label>
                        <input type="text" name="inscricao_municipal" value="{{ old('inscricao_municipal', $instituicao->inscricao_municipal) }}"
                            class="form-control @if($errors->has('inscricao_municipal')) form-control-danger @endif">
                        @if($errors->has('inscricao_municipal'))
                            <div class="form-control-feedback">{{ $errors->first('inscricao_municipal') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('cnes')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">CNES</label>
                        <input type="text" name="cnes" value="{{ old('cnes', $instituicao->cnes) }}"
                            class="form-control @if($errors->has('cnes')) form-control-danger @endif">
                        @if($errors->has('cnes'))
                            <div class="form-control-feedback">{{ $errors->first('cnes') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo *</label>
                        <select name="tipo" class="form-control">
                            <?php $tipos = App\Instituicao::getTipos(); ?>
                            @foreach($tipos as $tipo)
                                @if($instituicao->tipo == $tipo)
                                    <option value="{{ $tipo }}" selected>{{ App\Instituicao::getTipoText($tipo) }}</option>
                                @endif
                                @if($instituicao->tipo != $tipo)
                                    <option value="{{ $tipo }}">{{ App\Instituicao::getTipoText($tipo) }}</option>
                                @endif
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                            <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group @if($errors->has('ramo_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Ramo *</label>
                        <select name="ramo_id"  class="form-control">
                            <option value="" disabled selected>Selecione</option>
                            @foreach($ramos as $ramo)
                                <option value="{{ $ramo->id }}" {{($ramo->id == $instituicao->ramo_id) ? 'selected': ''}}>{{ $ramo->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('ramo_id'))
                            <div class="form-control-feedback">{{ $errors->first('ramo_id') }}</div>
                        @endif
                    </div>


                    <div class="col-md-5 form-group @if($errors->has('email')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">E-mail *</label>
                        <input type="email" name="email" name="example-email" value="{{ old('email', $instituicao->email) }}"
                            class="form-control @if($errors->has('email')) form-control-danger @endif">
                        @if($errors->has('email'))
                            <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('telefone')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Telefone *</label>
                        <input type="text" name="telefone" alt="phone" value="{{ old('telefone', $instituicao->telefone) }}"
                            class="form-control  @if($errors->has('telefone')) form-control-danger @endif">
                        @if($errors->has('telefone'))
                            <div class="form-control-feedback">{{ $errors->first('telefone') }}</div>
                        @endif
                    </div>
                </div>

                <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">

                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('cep')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">CEP *</label>
                        <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep',$instituicao->cep) }}"
                            class="form-control  @if($errors->has('cep')) form-control-danger @endif">
                        @if($errors->has('cep'))
                            <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('rua')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Rua *</label>
                        <input type="text" name="rua" id="rua" value="{{ old('rua', $instituicao->rua) }}"
                            class="form-control  @if($errors->has('rua')) form-control-danger @endif">
                        @if($errors->has('rua'))
                            <div class="form-control-feedback">{{ $errors->first('rua') }}</div>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('numero')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Numero *</label>
                        <input type="text" name="numero" value="{{ old('numero', $instituicao->numero) }}"
                            class="form-control  @if($errors->has('numero')) form-control-danger @endif">
                        @if($errors->has('numero'))
                            <div class="form-control-feedback">{{ $errors->first('numero') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('bairro')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Bairro *</label>
                        <input type="text" name="bairro" id="bairro" value="{{ old('bairro', $instituicao->bairro) }}"
                            class="form-control  @if($errors->has('bairro')) form-control-danger @endif">
                        @if($errors->has('bairro'))
                            <div class="form-control-feedback">{{ $errors->first('bairro') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group @if($errors->has('cidade')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Cidade *</label>
                        <input type="text" name="cidade" id="cidade" value="{{ old('cidade', $instituicao->cidade) }}"
                            class="form-control  @if($errors->has('cidade')) form-control-danger @endif">
                        @if($errors->has('cidade'))
                            <div class="form-control-feedback">{{ $errors->first('cidade') }}</div>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('estado')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Estado *</label>
                        <select class="form-control @if($errors->has('estado')) form-control-danger @endif" name="estado" id="estado" >
                            <option value="">Selecione</option>
                            <option value="AC" @if (old('estado', $instituicao->estado) == 'AC')
                                selected="selected"
                            @endif>Acre</option>
                            <option value="AL" @if (old('estado', $instituicao->estado) == 'AL')
                                selected="selected"
                            @endif>Alagoas</option>
                            <option value="AP" @if (old('estado', $instituicao->estado) == 'AP')
                                selected="selected"
                            @endif>Amapá</option>
                            <option value="AM" @if (old('estado', $instituicao->estado) == 'AM')
                                selected="selected"
                            @endif>Amazonas</option>
                            <option value="BA" @if (old('estado', $instituicao->estado) == 'BA')
                                selected="selected"
                            @endif>Bahia</option>
                            <option value="CE" @if (old('estado', $instituicao->estado) == 'CE')
                                selected="selected"
                            @endif>Ceará</option>
                            <option value="DF" @if (old('estado', $instituicao->estado) == 'DF')
                                selected="selected"
                            @endif>Distrito Federal</option>
                            <option value="GO" @if (old('estado', $instituicao->estado) == 'GO')
                                selected="selected"
                            @endif>Goiás</option>
                            <option value="ES" @if (old('estado', $instituicao->estado) == 'ES')
                                selected="selected"
                            @endif>Espírito Santo</option>
                            <option value="MA" @if (old('estado', $instituicao->estado) == 'MA')
                                selected="selected"
                            @endif>Maranhão</option>
                            <option value="MT" @if (old('estado', $instituicao->estado) == 'MT')
                                selected="selected"
                            @endif>Mato Grosso</option>
                            <option value="MS" @if (old('estado', $instituicao->estado) == 'MS')
                                selected="selected"
                            @endif>Mato Grosso do Sul</option>
                            <option value="MG" @if (old('estado', $instituicao->estado) == 'MG')
                                selected="selected"
                            @endif>Minas Gerais</option>
                            <option value="PA" @if (old('estado', $instituicao->estado) == 'PA')
                                selected="selected"
                            @endif>Pará</option>
                            <option value="PB" @if (old('estado', $instituicao->estado) == 'PB')
                                selected="selected"
                            @endif>Paraiba</option>
                            <option value="PR" @if (old('estado', $instituicao->estado) == 'PR')
                                selected="selected"
                            @endif>Paraná</option>
                            <option value="PE" @if (old('estado', $instituicao->estado) == 'PE')
                                selected="selected"
                            @endif>Pernambuco</option>
                            <option value="PI" @if (old('estado', $instituicao->estado) == 'PI')
                                selected="selected"
                            @endif>Piauí­</option>
                            <option value="RJ" @if (old('estado', $instituicao->estado) == 'RJ')
                                selected="selected"
                            @endif>Rio de Janeiro</option>
                            <option value="RN" @if (old('estado', $instituicao->estado) == 'RN')
                                selected="selected"
                            @endif>Rio Grande do Norte</option>
                            <option value="RS" @if (old('estado', $instituicao->estado) == 'RS')
                                selected="selected"
                            @endif>Rio Grande do Sul</option>
                            <option value="RO" @if (old('estado', $instituicao->estado) == 'RO')
                                selected="selected"
                            @endif>Rondônia</option>
                            <option value="RR" @if (old('estado', $instituicao->estado) == 'RR')
                                selected="selected"
                            @endif>Roraima</option>
                            <option value="SP" @if (old('estado', $instituicao->estado) == 'SP')
                                selected="selected"
                            @endif>São Paulo</option>
                            <option value="SC" @if (old('estado', $instituicao->estado) == 'SC')
                                selected="selected"
                            @endif>Santa Catarina</option>
                            <option value="SE" @if (old('estado', $instituicao->estado) == 'SE')
                                selected="selected"
                            @endif>Sergipe</option>
                            <option value="TO" @if (old('estado', $instituicao->estado) == 'TO')
                                selected="selected"
                            @endif>Tocantins</option>
                        </select>
                        @if($errors->has('estado'))
                            <div class="form-control-feedback">{{ $errors->first('estado') }}</div>
                        @endif
                    </div>

                    <div class="col-md-6 form-group @if($errors->has('complemento')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Complemento</label>
                        <input type="text" name="complemento" id="complemento" value="{{ old('complemento', $instituicao->complemento) }}"
                            class="form-control  @if($errors->has('complemento')) form-control-danger @endif">
                        @if($errors->has('complemento'))
                            <div class="form-control-feedback">{{ $errors->first('complemento') }}</div>
                        @endif
                    </div>
                </div>


                <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">



                <div class="form-group @if($errors->has('imagem')) has-danger @endif">
                    <label class="form-control-label p-0 m-0">Logo</label>
                    <label style="cursor: pointer;display:block;" data-toggle="tooltip" title="Logo" data-original-title="Mude sua logo">
                            <img style="display:block;cursor: pointer;margin-left:auto;  margin-right: auto;" class="rounded center" alt="Logo" id="image"
                            @if ($instituicao->imagem)
                                src="{{ \Storage::cloud()->url($instituicao->imagem) }}"
                            @else
                                src="{{ asset('material/assets/images/default_logo.png') }} "
                            @endif>
                            <input type="file" class='sr-only'  id="input" >

                    </label>

                    @if($errors->has('imagem'))
                        <div class="form-control-feedback">{{ $errors->first('imagem') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <input type="checkbox" id="finalizar_consultorio" name="finalizar_consultorio" class="filled-in" @if (old('finalizar_consultorio', $instituicao->finalizar_consultorio) == 1)
                    checked
                    @endif value="1"/>
                    <label for="finalizar_consultorio">Finalizar antedimento quando finalizar atendimento no consultorio<label>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="ausente_agenda" name="ausente_agenda" class="filled-in" @if (old('ausente_agenda', $instituicao->ausente_agenda) == 1)
                    checked
                    @endif value="1"/>
                    <label for="ausente_agenda">Atendimentos ausente/desmarcados fora da agenda<label>
                </div>
                
                <div class="form-group">
                    <input type="checkbox" id="desconto_por_procedimento_agenda" name="desconto_por_procedimento_agenda" class="filled-in" @if (old('desconto_por_procedimento_agenda', $instituicao->desconto_por_procedimento_agenda) == 1)
                    checked
                    @endif value="1"/>
                    <label for="desconto_por_procedimento_agenda">Liberar desconto por procedimento no agendamento<label>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="possui_convenio_terceiros" name="possui_convenio_terceiros" class="filled-in" @if (old('possui_convenio_terceiros', $instituicao->possui_convenio_terceiros) == 1) checked @endif value="1"/>
                    <label for="possui_convenio_terceiros">Possui convenio com integração de terceiros<label>
                </div>

                <div class="form-group codigo_acesso_terceiros @if($errors->has('codigo_acesso_terceiros')) has-danger @endif" style="display: none">
                    <label class="form-control-label p-0 m-0">Codigo de acesso de terceiros</label>
                    <input type="text" name="codigo_acesso_terceiros" id="codigo_acesso_terceiros" value="{{ old('codigo_acesso_terceiros') }}"
                        class="form-control  @if($errors->has('codigo_acesso_terceiros')) form-control-danger @endif">
                    @if($errors->has('codigo_acesso_terceiros'))
                        <div class="form-control-feedback">{{ $errors->first('codigo_acesso_terceiros') }}</div>
                    @endif
                </div>

                <!-- CAMPO PARA CONSULTAR COOPERADO NA SANCOOP CASO EXIATA -->
                <div class="form-group">
                    <input type="checkbox" id="possui_faturamento_sancoop" name="possui_faturamento_sancoop" class="filled-in" @if (old('possui_faturamento_sancoop', $instituicao->possui_faturamento_sancoop) == 1)
                    checked
                    @endif value="1"/>
                    {{-- OCULTADO VERIFICAÇÃO DE COOPERADO NA SANCOOP ATÉ RESPOSTA DE GUILHERME--}}
                    {{-- <input type="checkbox" onclick="verifica_existe_cooperado_sancoop()" id="possui_faturamento_sancoop" name="possui_faturamento_sancoop" class="filled-in" @if (old('possui_faturamento_sancoop', $instituicao->possui_faturamento_sancoop) == 1)
                    checked
                    @endif value="1"/> --}}
                    <label for="possui_faturamento_sancoop">Possui faturamento integrado Sancoop<label>
                </div>
                

                @if($instituicao->possui_faturamento_sancoop == 1)
                <div class="form-group">
                    <label class="form-control-label p-0 m-0">Código da Instituição na Sancoop (contatar suporte caso não esteja exibindo)</label>
                        <input readonly="readonly" type="text" value="{{ $instituicao->sancoop_cod_instituicao }}"
                            class="form-control">
                </div>
                @endif

                <div class="form-group">
                    <label class="form-control-label p-0 m-0">Enviar guias para a sancoop</label>
                    <select name="sancoop_automacao_envio_guias" class="form-control">
                        <option value="">Selecione</option>
                        <option value="diariamente" @if (old('sancoop_automacao_envio_guias', $instituicao->sancoop_automacao_envio_guias) == 'diariamente')
                            selected="selected"
                        @endif>Diariamente</option>
                        <option value="semanalmente_sexta" @if (old('sancoop_automacao_envio_guias', $instituicao->sancoop_automacao_envio_guias) == 'semanalmente_sexta')
                            selected="selected"
                        @endif>Semanalmente (sexta feira)</option>
                        <option value="manualmente" @if (old('sancoop_automacao_envio_guias', $instituicao->sancoop_automacao_envio_guias) == 'manualmente')
                            selected="selected"
                        @endif>Manualmente</option>
                    </select>
                    @if($errors->has('sancoop_automacao_envio_guias'))
                        <div class="form-control-feedback">{{ $errors->first('sancoop_automacao_envio_guias') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <input type="checkbox" id="enviar_pesquisa_satisfacao_atendimentos" name="enviar_pesquisa_satisfacao_atendimentos" class="filled-in" @if (old('enviar_pesquisa_satisfacao_atendimentos', $instituicao->enviar_pesquisa_satisfacao_atendimentos) == 1)
                    checked
                    @endif value="1"/>
                    <label for="enviar_pesquisa_satisfacao_atendimentos">Enviar pesquisa de satisfação do atendimento<label>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="automacao_whatsapp" name="automacao_whatsapp" class="filled-in" @if (old('automacao_whatsapp', $instituicao->automacao_whatsapp) == 1)
                    checked
                    @endif value="1"/>
                    <label for="automacao_whatsapp">Possui automação de Whatsapp <b>*obs:</b> sistema reconhece "1 - Confirmar, 2 - Desmarcar, 3 - Remarcar" e botões caso aparecelho tenha compatibilidade<label>
                </div>

                <div class="form-group automacao_whatsapp" @if ($instituicao->automacao_whatsapp == 1) style="display:block" @endif>
                    <input type="checkbox" id="automacao_whatsapp_botoes" name="automacao_whatsapp_botoes" class="filled-in" @if (old('automacao_whatsapp_botoes', $instituicao->automacao_whatsapp_botoes) == 1)
                    checked
                    @endif value="1"/>
                    <label for="automacao_whatsapp_botoes">Whatsapp enviar botões na mensagem<label>
                </div>

                <div class="form-group automacao_whatsapp" @if ($instituicao->automacao_whatsapp == 1) style="display:block" @endif>
                    <input type="checkbox" id="automacao_whatsapp_aniversario" name="automacao_whatsapp_aniversario" class="filled-in" @if (old('automacao_whatsapp_aniversario', $instituicao->automacao_whatsapp_aniversario) == 1)
                    checked
                    @endif value="1"/>
                    <label for="automacao_whatsapp_aniversario">Whatsapp mensagem de aniversário<label>
                </div>


                {{-- @if ($instituicao->automacao_whatsapp == 1) --}}

                <div class="form-group automacao_whatsapp" @if ($instituicao->automacao_whatsapp == 1) style="display:block" @endif>
                    <label class="form-control-label p-0 m-0">Regras de envio automação de Whatsapp</label>
                    <select name="automacao_whatsapp_regra_envio" class="form-control">
                        <option value="">Selecione</option>
                        <option value="segunda_enviar_sexta" @if (old('automacao_whatsapp_regra_envio', $instituicao->automacao_whatsapp_regra_envio) == 'segunda_enviar_sexta')
                            selected="selected"
                        @endif>Atendimentos de segunda feira disparar envios na sexta feira antecedente</option>
                    </select>
                    @if($errors->has('automacao_whatsapp_regra_envio'))
                        <div class="form-control-feedback">{{ $errors->first('automacao_whatsapp_regra_envio') }}</div>
                    @endif
                </div>

                <div class="form-group automacao_whatsapp" @if ($instituicao->automacao_whatsapp == 1) style="display:block" @endif>
                    <label class="form-control-label p-0 m-0">Horário envio de agenda diária para o prestador</label>
                    <select name="automacao_whatsapp_horario_agenda_prestador" class="form-control">
                        <option value="">Selecione</option>
                        <option value="16:00" @if (old('automacao_whatsapp_horario_agenda_prestador', $instituicao->automacao_whatsapp_horario_agenda_prestador) == '16:00')
                            selected="selected"
                        @endif>16:00</option>
                        <option value="17:00" @if (old('automacao_whatsapp_horario_agenda_prestador', $instituicao->automacao_whatsapp_horario_agenda_prestador) == '17:00')
                            selected="selected"
                        @endif>17:00</option>
                        <option value="18:00" @if (old('automacao_whatsapp_horario_agenda_prestador', $instituicao->automacao_whatsapp_horario_agenda_prestador) == '18:00')
                            selected="selected"
                        @endif>18:00</option>
                        <option value="19:00" @if (old('automacao_whatsapp_horario_agenda_prestador', $instituicao->automacao_whatsapp_horario_agenda_prestador) == '19:00')
                            selected="selected"
                        @endif>19:00</option>
                        <option value="20:00" @if (old('automacao_whatsapp_horario_agenda_prestador', $instituicao->automacao_whatsapp_horario_agenda_prestador) == '20:00')
                            selected="selected"
                        @endif>20:00</option>
                        <option value="21:00" @if (old('automacao_whatsapp_horario_agenda_prestador', $instituicao->automacao_whatsapp_horario_agenda_prestador) == '21:00')
                            selected="selected"
                        @endif>21:00</option>
                    </select>
                    @if($errors->has('automacao_whatsapp_horario_agenda_prestador'))
                        <div class="form-control-feedback">{{ $errors->first('automacao_whatsapp_horario_agenda_prestador') }}</div>
                    @endif
                </div>

                <div class="form-group automacao_whatsapp @if($errors->has('kentro_fila_empresa')) has-danger @endif" @if ($instituicao->automacao_whatsapp == 1) style="display:block" @endif>
                    <label class="form-control-label">Código da fila da instituição no sistema de teceiro</span></label>
                    <input type="text" name="kentro_fila_empresa" value="{{ old('kentro_fila_empresa', $instituicao->kentro_fila_empresa) }}"
                        class="form-control @if($errors->has('kentro_fila_empresa')) form-control-danger @endif">
                    @if($errors->has('kentro_fila_empresa'))
                        <div class="form-control-feedback">{{ $errors->first('kentro_fila_empresa') }}</div>
                    @endif
                </div>

                <div class="form-group automacao_whatsapp" @if ($instituicao->automacao_whatsapp == 1) style="display:block" @endif>

                    <div class="row col-sm-12">
                        <div class="row">

                            <div class="form-group col-sm-12">
                                <label class="form-control-label">
                                    <strong>*Variáveis automáticas disponíveis e sitaxe: {paciente}, {data}, {hora}, {profissional}, {empresa}. </strong>
                                </label>
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="form-control-label">Mensagem de confirmação</label>
                                <textarea rows="4" class="form-control" name="kentro_msg_confirmacao">{{old('kentro_msg_confirmacao',$instituicao->kentro_msg_confirmacao)}}</textarea>
                            </div>
    
                            <div class="form-group col-sm-4">
                                <label class="form-control-label">Mensagem de feedback de confirmação</label>
                                <textarea rows="4" class="form-control" name="kentro_msg_resposta_confirmacao">{{old('kentro_msg_resposta_confirmacao',$instituicao->kentro_msg_resposta_confirmacao)}}</textarea>
                            </div>
    
                            <div class="form-group col-sm-4">
                                <label class="form-control-label">Mensagem de feedback de remarcação</label>
                                <textarea rows="4" class="form-control" name="kentro_msg_resposta_remarcacao">{{old('kentro_msg_resposta_remarcacao',$instituicao->kentro_msg_resposta_remarcacao)}}</textarea>
                            </div>
    
                            <div class="form-group col-sm-4">
                                <label class="form-control-label">Mensagem de pesquisa de satisfação</label>
                                <textarea rows="4" class="form-control" name="kentro_msg_pesquisa_satisfacao">{{old('kentro_msg_pesquisa_satisfacao',$instituicao->kentro_msg_pesquisa_satisfacao)}}</textarea>
                            </div>
    
                            <div class="form-group col-sm-4">
                                <label class="form-control-label">Mensagem de feedback de pesquisa de satisfação</label>
                                <textarea rows="4" class="form-control" name="kentro_msg_resposta_pesquisa_satisfacao">{{old('kentro_msg_resposta_pesquisa_satisfacao',$instituicao->kentro_msg_resposta_pesquisa_satisfacao)}}</textarea>
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="form-control-label">Mensagem de aniversariantes</label>
                                <textarea rows="4" class="form-control" name="kentro_msg_aniversario">{{old('kentro_msg_aniversario',$instituicao->kentro_msg_aniversario)}}</textarea>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- @endif --}}


                <div class="form-group">
                    <input type="checkbox" id="possui_api_bb" name="apibb_possui" class="filled-in" @if (old('apibb_possui', $instituicao->apibb_possui) == 1) checked @endif value="1"/>
                    <label for="possui_api_bb">Possui Api Banco do Brasil<label>
                </div>

                <div class="row col-sm-12 api_bb" style="display: {{$instituicao->apibb_possui ? 'block' : 'none'}};">
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="form-control-label">Código cedente</label>
                            <textarea class="form-control" name="apibb_codigo_cedente">{{old('apibb_codigo_cedente',$instituicao->apibb_codigo_cedente)}}</textarea>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="form-control-label">Indicador pix</label>
                            <textarea class="form-control" name="apibb_indicador_pix">{{old('apibb_indicador_pix',$instituicao->apibb_indicador_pix)}}</textarea>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="form-control-label">Cliente id</label>
                            <textarea class="form-control" name="apibb_client_id">{{old('apibb_client_id',$instituicao->apibb_client_id)}}</textarea>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="form-control-label">Senha cliente</label>
                            <textarea class="form-control" name="apibb_client_secret">{{old('apibb_client_secret',$instituicao->apibb_client_secret)}}</textarea>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="form-control-label">Getway app key</label>
                            <textarea class="form-control" name="apibb_gw_dev_app_key">{{old('apibb_gw_dev_app_key',$instituicao->apibb_gw_dev_app_key)}}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label class="form-control-label">Nº Dias para pagamento</label>
                            <input class="form-control" name="dias_pagamento" value="{{old('dias_pagamento',$instituicao->dias_pagamento)}}">
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="form-control-label">Percentual Juros</label>
                            <input class="form-control" name="p_juros" value="{{old('p_juros',$instituicao->p_juros)}}" alt="money">
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="form-control-label">Percentual multa</label>
                            <input class="form-control" name="p_multa" value="{{old('p_multa',$instituicao->p_multa)}}" alt="money">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="telemedicina_integrado" name="telemedicina_integrado" class="filled-in" @if (old('telemedicina_integrado', $instituicao->telemedicina_integrado) == 1)
                    checked
                    @endif value="1"/>
                    <label for="telemedicina_integrado">Possui Telemedicina <b>*obs:</b> caso tenha integração com whatsapp, mensagens de confirmação possuem informações com link da telechamada para o paciente<label>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="integracao_asaplan" name="integracao_asaplan" class="filled-in" @if (old('integracao_asaplan', $instituicao->integracao_asaplan) == 1)
                    checked
                    @endif value="1"/>
                    <label for="integracao_asaplan">Possui integração Asaplan <b>*obs:</b> caso possua, contatar equipe desenvolvimento para liberar credenciais<label>
                </div>

                <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">

                <div class="form-group text-right">
                    <a href="{{ route('instituicoes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal inmodal" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>

                <h5 class="modal-title">Defina a logo</h5>

            </div>
            <div class="modal-body">
                <div >
                <img style="max-width: 100%;" id="imageModal" src="">
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" style='margin:0;' class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="crop">Definir</button>

            </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        $("#possui_convenio_terceiros").on('click', function(){
            cdTerceiros();
        })

        function cdTerceiros(){
            if($("#possui_convenio_terceiros").is(':checked')){
               $(".codigo_acesso_terceiros").css('display', 'block')
            }else{
               $(".codigo_acesso_terceiros").css('display', 'none')
            }
        }

        $('input').setMask()

        $('input[name=cnes]').setMask('9999999999', {
            translation: {
                '9': {
                    pattern: /[0-9]/, optional: false
                }
            }
        })

        $('input[name=dias_pagamento]').setMask('9999999999', {
            translation: {
                '9': {
                    pattern: /[0-9]/, optional: false
                }
            }
        })

        $("#max_parcela").TouchSpin({
            min: 0,
            max: 30,
            step: 1,
            initval: 1
        }).on('change',function(){
            $("#free_parcela").trigger("touchspin.updatesettings", {max: this.value});
        })

        $("#free_parcela").TouchSpin({
            min: 0,
            max: 30,
            step: 1,
            initval: 1
        })

        $("#taxa_tectotum").TouchSpin({
            min: 0,
            max: 99,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 1,
            initval: 3,
            prefix: '%'
        })

        $("#valor_parcela").TouchSpin({
            min: 0,
            max: 99,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 1,
            initval: 2,
            prefix: '%'
        })


        var blobImage;
        var input= document.getElementById('input');
        var image= document.getElementById('image');
        var imageModal= document.getElementById('imageModal');

        input.addEventListener('change',function(e){
            var files = e.target.files;
            var done = function (url) {
            input.value = '';
            imageModal.src = url;
            $('#modal').modal('show');
            };
            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                done(reader.result);
                };
                reader.readAsDataURL(file);
            }
            }
        });

        $('#modal').on('shown.bs.modal', function () {
            cropper = new Cropper(imageModal,{
                aspectRatio: 4/3
            });
            }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

        document.getElementById('crop').addEventListener('click', function () {
            var initialAvatarURL;
            var canvas;

            if (cropper) {
                canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                });
                initialAvatarURL = image.src;
                image.src = canvas.toDataURL();

                canvas.toBlob(function (blob) {
                    blobImage = blob;
                });
            }
            $('#modal').modal('hide');
        });

        $("form").submit(function(e){
            e.preventDefault()

            var formData = new FormData($(this)[0]);
            if(blobImage){
                formData.append('imagem', blobImage, 'imagem.jpg');
            }

            $.ajax("{{ route('instituicoes.update', [$instituicao]) }}", {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
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
                    document.location.reload(true);
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


        //VERIFICANDO EXISTENCIA COOPERADO API SANCOOP
        function verifica_existe_cooperado_sancoop()
        {
            var cnpj = $("#cnpj").val();
            var idinstituicao = $("#idinstituicao").val();

            if (cnpj != '') {

                $.ajax({
                    url: "{{ route('getprestadorSancoop', [$instituicao]) }}",
                    type: "GET",
                    data: {
                        cnpj: cnpj,
                        idinstituicao : idinstituicao,
                        // '_token': '{{csrf_token()}}',
                    },
                    datatype: "json",
                    success: function(result) {
                        console.log(result)
                        if (result == 'erro') {
                            // alert('Ocorreu um erro ao enviar as guias. Entrar em contato imediadamente no suporte Asa Saúde (38) 9 9826 6833');
                            // alert('Ocorreu um erro ao enviar as guias. Entrar em contato imediadamente no suporte Asa Saúde (38) 9 9826 6833 ')
                        } else {
                            // swal("Guia(s) enviadas!", "Acompanhe o processamento neste mesmo módulo.", "success");
                            // alert('Guia(s) enviadas!", "Acompanhe o processamento neste mesmo módulo. ')
                        }

                        // setTimeout(function() {
                        //     location.reload(1);
                        // }, 2000);
                    }
                });
            } else {
                alert('Prencha o CNPJ da Instituição.')
                this.checked = false;
            }
        }

        $( document ).ready(function() {
            $("#possui_api_bb").on('change', function(e){
                if($(this).is(":checked")){
                    $('.api_bb').css('display', 'block');
                }else{
                    $('.api_bb').css('display', 'none');
                }
            })

            $("#automacao_whatsapp").on('change', function(e){
                if($(this).is(":checked")){
                    $('.automacao_whatsapp').css('display', 'block');
                }else{
                    $('.automacao_whatsapp').css('display', 'none');
                }
            })

            cdTerceiros()
        });

    </script>
@endpush

