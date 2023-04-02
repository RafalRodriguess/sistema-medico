@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Convênio: {$convenio->nome}",
        'breadcrumb' => [
            'Convênios' => route('instituicao.convenio.index'),
            'Atualizar',
        ],
    ])
    @endcomponent


    <div class="card">
        <form action="{{ route('instituicao.convenio.update', [$convenio]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="nav-tabs-container col-12 p-0 m-0 custom-tabs">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-convenio-tab" data-toggle="tab" href="#nav-convenio" role="tab"
                            aria-controls="nav-convenio" aria-selected="true">Convênio</a>
                        <a class="nav-link" id="nav-complemento-tab" data-toggle="tab" href="#nav-complemento"
                            role="tab" aria-controls="nav-complemento" aria-selected="false">Complemento</a>
                        <a class="nav-link" id="nav-excecoes-tab" data-toggle="tab" href="#nav-excecoes" role="tab"
                            aria-controls="nav-excecoes" aria-selected="false">Exceções</a>
                    </div>
                </nav>
                <div class="tab-content card-body bg-white" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-convenio" role="tabpanel"
                        aria-labelledby="nav-convenio-tab">
                        <div class="row mb-3">
                            <div class="col-12 input-group align-items-center">
                                <h4 class="d-block my-0 mr-2">Dados cadastrais</h4>
                                <hr class="inline">
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6 col-sm-6 form-group @if ($errors->has('nome')) has-danger @endif">
                                <label class="form-control-label">Nome <span class="text-danger">*</span></label>
                                <input type="text" name="nome" value="{{ old('nome', $convenio->nome) }}"
                                    class="form-control @if ($errors->has('nome')) form-control-danger @endif">
                                @if ($errors->has('nome'))
                                    <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                                @endif
                            </div>

                            <div class="col-md-3 col-sm-6 form-group @if ($errors->has('apresentacoes_convenio_id')) has-danger @endif">
                                <label class="form-control-label">Tipo de Apresentação</label>
                                <select name="apresentacoes_convenio_id"
                                    class="select2 form-control @if ($errors->has('apresentacoes_convenio_id')) form-control-danger @endif"
                                    style="width: 100%">
                                    <option selected disabled hidden>Selecione ...</option>
                                    @foreach ($apresentacoes as $apresentacao)
                                        <option value="{{ $apresentacao->id }}"
                                            @if (old('apresentacoes_convenio_id', $convenio->apresentacoes_convenio_id) == $apresentacao->id) selected="selected" @endif>
                                            {{ $apresentacao->nome }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('apresentacoes_convenio_id'))
                                    <div class="form-control-feedback">{{ $errors->first('apresentacoes_convenio_id') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-sm-2 p-4 m-0">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="ativo" value="1"
                                        @if (old('ativo', $convenio->ativo) == '1') checked @endif id="ativoCheck">
                                    <label class="form-check-label" for="ativoCheck">Ativo</label>
                                </div>
                            </div>

                            <div class="col-sm-2 p-3 m-0">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="carteirinha_obg" value="1"
                                        @if (old('carteirinha_obg', $convenio->carteirinha_obg) == '1') checked @endif id="carteirinha_obgCheck">
                                    <label class="form-check-label" for="carteirinha_obgCheck">Carteirinha
                                        obrigatoria?</label>
                                </div>
                            </div>

                            <div class="col-sm-2 p-3 m-0">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="aut_obrigatoria" value="1"
                                        @if (old('aut_obrigatoria', $convenio->aut_obrigatoria) == '1') checked @endif id="aut_obgCheck">
                                    <label class="form-check-label" for="aut_obgCheck">Autorizaçõa obrigatoria?</label>
                                </div>
                            </div>
                            
                            @if ($instituicao->possui_convenio_terceiros)    
                                <div class="col-sm-2 p-4 m-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="possui_terceiros" value="1" @if(old('possui_terceiros', $convenio->possui_terceiros)=="1") checked @endif id="possui_terceiros">
                                        <label class="form-check-label" for="possui_terceiros">Convênio integrado com terceiros</label>
                                    </div>
                                </div>
                            @endif

                            <div class=" col-md-5 form-group @if($errors->has('divisao_tipo_guia')) has-danger @endif">
                                <label class="form-control-label">Divisão de guias: </label>
                                <select name="divisao_tipo_guia"
                                    class="form-control @if ($errors->has('divisao_tipo_guia')) form-control-danger @endif">
                                    <option value="0" @if (old('divisao_tipo_guia') == '0') selected="selected" @endif>
                                        Selecione</option>
                                    <option value="1" @if (old('divisao_tipo_guia', $convenio->divisao_tipo_guia) == '1') selected="selected" @endif>
                                        Consulta junto com SADT envio do tipo SADT</option>
                                    <option value="2" @if (old('divisao_tipo_guia', $convenio->divisao_tipo_guia) == '2') selected="selected" @endif>
                                        Consulta separado de SADT envio de cada tipo</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-4 form-group @if ($errors->has('razao_social')) has-danger @endif">
                                <label class="form-control-label">Razão Social</label>
                                <input type="text" name="razao_social"
                                    value="{{ old('razao_social', $convenio->razao_social) }}"
                                    class="form-control @if ($errors->has('razao_social')) form-control-danger @endif">
                                @if ($errors->has('razao_social'))
                                    <div class="form-control-feedback">{{ $errors->first('razao_social') }}</div>
                                @endif
                            </div>

                            <div class="col-md-4 col-sm-4 form-group @if ($errors->has('cnpj')) has-danger @endif">
                                <label class="form-control-label">CNPJ</label>
                                <input type="text" name="cnpj" alt="cnpj"
                                    value="{{ old('cnpj', $convenio->cnpj) }}"
                                    class="form-control @if ($errors->has('cnpj')) form-control-danger @endif">
                                @if ($errors->has('cnpj'))
                                    <div class="form-control-feedback">{{ $errors->first('cnpj') }}</div>
                                @endif
                            </div>

                            <div
                                class="col-md-4 col-sm-4 form-group @if ($errors->has('email')) has-danger @endif">
                                <label class="form-control-label">Email</span></label>
                                <input type="email" name="email" value="{{ old('email', $convenio->email) }}"
                                    class="form-control @if ($errors->has('email')) form-control-danger @endif">
                                @if ($errors->has('email'))
                                    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>

                        </div>

                        <div class="row">
                            <div
                                class="col-md-5 col-sm-4 form-group @if ($errors->has('responsavel')) has-danger @endif">
                                <label class="form-control-label">Responsavel</span></label>
                                <input type="text" name="responsavel"
                                    value="{{ old('responsavel', $convenio->responsavel) }}"
                                    class="form-control @if ($errors->has('responsavel')) form-control-danger @endif">
                                @if ($errors->has('responsavel'))
                                    <div class="form-control-feedback">{{ $errors->first('responsavel') }}</div>
                                @endif
                            </div>

                            <div
                                class="col-md-3 col-sm-4 form-group @if ($errors->has('cargo_responsavel')) has-danger @endif">
                                <label class="form-control-label">Cargo do responsável</span></label>
                                <input type="text" name="cargo_responsavel"
                                    value="{{ old('cargo_responsavel', $convenio->cargo_responsavel) }}"
                                    class="form-control @if ($errors->has('cargo_responsavel')) form-control-danger @endif">
                                @if ($errors->has('cargo_responsavel'))
                                    <div class="form-control-feedback">{{ $errors->first('cargo_responsavel') }}</div>
                                @endif
                            </div>

                            <div
                                class="col-md-4 col-sm-4 form-group @if ($errors->has('email_glossas')) has-danger @endif">
                                <label class="form-control-label">Email para recurso de Glossas</span></label>
                                <input type="email" name="email_glossas"
                                    value="{{ old('email_glossas', $convenio->email_glossas) }}"
                                    class="form-control @if ($errors->has('email_glossas')) form-control-danger @endif">
                                @if ($errors->has('email_glossas'))
                                    <div class="form-control-feedback">{{ $errors->first('email_glossas') }}</div>
                                @endif
                            </div>
                        </div>


                        <div class="row">

                            <div class="col-md-8 form-group @if ($errors->has('endereco')) has-danger @endif">
                                <label class="form-control-label">Endereço</span></label>
                                <input type="text" name="endereco" value="{{ old('endereco', $convenio->endereco) }}"
                                    class="form-control @if ($errors->has('fone_contato')) form-control-danger @endif">
                                @if ($errors->has('endereco'))
                                    <div class="form-control-feedback">{{ $errors->first('endereco') }}</div>
                                @endif
                            </div>

                            <div class="col-md-4 form-group @if ($errors->has('dt_inicio_contrato')) has-danger @endif">
                                <label class="form-control-label">Data Contrato</label>
                                <input type="date" name="dt_inicio_contrato"
                                    value="{{ old('dt_inicio_contrato', substr($convenio->dt_inicio_contrato, 0, 10)) }}"
                                    class="form-control @if ($errors->has('dt_inicio_contrato')) form-control-danger @endif">
                                @if ($errors->has('dt_inicio_contrato'))
                                    <div class="form-control-feedback">{{ $errors->first('dt_inicio_contrato') }}</div>
                                @endif
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4 form-group @if ($errors->has('cep')) has-danger @endif">
                                <label class="form-control-label">CEP</span></label>
                                <input type="text" name="cep" value="{{ old('cep', $convenio->cep) }}"
                                    class="form-control @if ($errors->has('cep')) form-control-danger @endif">
                                @if ($errors->has('cep'))
                                    <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                                @endif
                            </div>

                            <div class="col-md-4 form-group @if ($errors->has('fone_contato')) has-danger @endif">
                                <label class="form-control-label">Telefone de contato</span></label>
                                <input type="text" name="fone_contato"
                                    value="{{ old('fone_contato', $convenio->fone_contato) }}"
                                    class="form-control @if ($errors->has('fone_contato')) form-control-danger @endif">
                                @if ($errors->has('fone_contato'))
                                    <div class="form-control-feedback">{{ $errors->first('fone_contato') }}</div>
                                @endif
                            </div>

                            <div class="col-md-4 form-group @if ($errors->has('cgc')) has-danger @endif">
                                <label class="form-control-label">C.G.C.</span></label>
                                <input type="text" name="cgc" value="{{ old('cgc', $convenio->cgc) }}"
                                    class="form-control @if ($errors->has('cgc')) form-control-danger @endif">
                                @if ($errors->has('cgc'))
                                    <div class="form-control-feedback">{{ $errors->first('cgc') }}</div>
                                @endif
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group @if ($errors->has('inscricao_municipal')) has-danger @endif">
                                <label class="form-control-label">Inscrição Municipal</span></label>
                                <input type="text" name="inscricao_municipal"
                                    value="{{ old('inscricao_municipal', $convenio->inscricao_municipal) }}"
                                    class="form-control @if ($errors->has('inscricao_municipal')) form-control-danger @endif">
                                @if ($errors->has('inscricao_municipal'))
                                    <div class="form-control-feedback">{{ $errors->first('inscricao_municipal') }}</div>
                                @endif
                            </div>

                            <div class="col-md-4 form-group @if ($errors->has('inscricao_estadual')) has-danger @endif">
                                <label class="form-control-label">Inscrição Estadual</span></label>
                                <input type="text" name="inscricao_estadual"
                                    value="{{ old('inscricao_estadual', $convenio->inscricao_estadual) }}"
                                    class="form-control @if ($errors->has('inscricao_estadual')) form-control-danger @endif">
                                @if ($errors->has('inscricao_estadual'))
                                    <div class="form-control-feedback">{{ $errors->first('inscricao_estadual') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-12 input-group align-items-center">
                                <h4 class="d-block my-0 mr-2">Dados do faturamento</h4>
                                <hr class="inline">
                            </div>
                        </div>
                        <div class="row">
                            <div
                                class="col-md-3 col-sm-4 form-group @if ($errors->has('tipo_convenio')) has-danger @endif">
                                <label class="form-control-label">Tipo de convênio</label>
                                <select name="tipo_convenio"
                                    class="form-control @if ($errors->has('tipo_convenio')) form-control-danger @endif">
                                    <option selected disabled hidden>Selecione ...</option>
                                    @foreach ($opcoes_tipo_convenio as $id => $opcao)
                                        <option value="{{ $id }}"
                                            @if (old('tipo_convenio', $convenio->tipo_convenio) == $id) selected="selected" @endif>
                                            {{ $opcao }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('tipo_convenio'))
                                    <div class="form-control-feedback">{{ $errors->first('tipo_convenio') }}</div>
                                @endif
                            </div>
                            <div
                                class="col-md-3 col-sm-3 form-group @if ($errors->has('categoria_obrigatoria')) has-danger @endif">
                                <label class="form-control-label">Categoria obrigatoria?</label>
                                <select name="categoria_obrigatoria"
                                    class="form-control @if ($errors->has('categoria_obrigatoria')) form-control-danger @endif">
                                    <option selected disabled hidden>Selecione ...</option>
                                    <option value="1" @if (old('categoria_obrigatoria', $convenio->categoria_obrigatoria) == 1) selected="selected" @endif>
                                        Sim</option>
                                    <option value="0" @if (old('categoria_obrigatoria', $convenio->categoria_obrigatoria) == 0) selected="selected" @endif>
                                        Não</option>
                                </select>
                                @if ($errors->has('categoria_obrigatoria'))
                                    <div class="form-control-feedback">{{ $errors->first('categoria_obrigatoria') }}</div>
                                @endif
                            </div>
                            <div
                                class="col-md-3 col-sm-3 form-group @if ($errors->has('guia_obrigatoria')) has-danger @endif">
                                <label class="form-control-label">Guia obrigatoria?</label>
                                <select name="guia_obrigatoria"
                                    class="form-control @if ($errors->has('guia_obrigatoria')) form-control-danger @endif">
                                    <option selected disabled hidden>Selecione ...</option>
                                    @foreach ($opcoes_guia_obrigatoria as $id => $opcao)
                                        <option value="{{ $id }}"
                                            @if (old('guia_obrigatoria', $convenio->guia_obrigatoria) == $id) selected="selected" @endif>
                                            {{ $opcao }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('guia_obrigatoria'))
                                    <div class="form-control-feedback">{{ $errors->first('guia_obrigatoria') }}</div>
                                @endif
                            </div>
                            <div
                                class="col-md-3 col-sm-3 form-group @if ($errors->has('abate_devolucao')) has-danger @endif">
                                <label class="form-control-label">Abate devolução?</label>
                                <select name="abate_devolucao"
                                    class="form-control @if ($errors->has('abate_devolucao')) form-control-danger @endif">
                                    <option selected disabled hidden>Selecione ...</option>
                                    <option value="1" @if (old('abate_devolucao', $convenio->abate_devolucao) == 1) selected="selected" @endif>
                                        Sim</option>
                                    <option value="0" @if (old('abate_devolucao', $convenio->abate_devolucao) == 0) selected="selected" @endif>
                                        Não</option>
                                </select>
                                @if ($errors->has('abate_devolucao'))
                                    <div class="form-control-feedback">{{ $errors->first('abate_devolucao') }}</div>
                                @endif
                            </div>
                            <div
                                class="col-md-3 col-sm-3 form-group @if ($errors->has('filantropia')) has-danger @endif">
                                <label class="form-control-label">Filantropia?</label>
                                <select name="filantropia"
                                    class="form-control @if ($errors->has('filantropia')) form-control-danger @endif">
                                    <option selected disabled hidden>Selecione ...</option>
                                    <option value="1" @if (old('filantropia', $convenio->filantropia) == 1) selected="selected" @endif>
                                        Sim</option>
                                    <option value="0" @if (old('filantropia', $convenio->filantropia) == 0) selected="selected" @endif>
                                        Não</option>
                                </select>
                                @if ($errors->has('filantropia'))
                                    <div class="form-control-feedback">{{ $errors->first('filantropia') }}</div>
                                @endif
                            </div>
                            <div
                                class="col-md-3 col-sm-3 form-group @if ($errors->has('fatura_p_alta')) has-danger @endif">
                                <label class="form-control-label">Fatura p/ alta?</label>
                                <select name="fatura_p_alta"
                                    class="form-control @if ($errors->has('fatura_p_alta')) form-control-danger @endif">
                                    <option selected disabled hidden>Selecione ...</option>
                                    <option value="1" @if (old('fatura_p_alta', $convenio->fatura_p_alta) == 1) selected="selected" @endif>
                                        Sim</option>
                                    <option value="0" @if (old('fatura_p_alta', $convenio->fatura_p_alta) == 0) selected="selected" @endif>
                                        Não</option>
                                </select>
                                @if ($errors->has('fatura_p_alta'))
                                    <div class="form-control-feedback">{{ $errors->first('fatura_p_alta') }}</div>
                                @endif
                            </div>
                            <div
                                class="col-md-3 col-sm-3 form-group @if ($errors->has('desc_conta')) has-danger @endif">
                                <label class="form-control-label">Desc conta?</label>
                                <select name="desc_conta"
                                    class="form-control @if ($errors->has('desc_conta')) form-control-danger @endif">
                                    <option selected disabled hidden>Selecione ...</option>
                                    <option value="1" @if (old('desc_conta', $convenio->desc_conta) == 1) selected="selected" @endif>
                                        Sim</option>
                                    <option value="0" @if (old('desc_conta', $convenio->desc_conta) == 0) selected="selected" @endif>
                                        Não</option>
                                </select>
                                @if ($errors->has('desc_conta'))
                                    <div class="form-control-feedback">{{ $errors->first('desc_conta') }}</div>
                                @endif
                            </div>

                            <div
                                class="col-md-3 col-sm-4 form-group {{ $errors->has('versao_tiss_id') ? 'has-danger' : '' }}">
                                <label class="form-control-label">Versão TISS</label>
                                <select name="versao_tiss_id"
                                    class="form-control  {{ $errors->has('versao_tiss_id') ? 'form-control-danger' : '' }}">
                                    <option selected disabled hidden>Selecione ...</option>
                                    @foreach ($versoes_tiss as $versao)
                                        <option value="{{ $versao->id }}"
                                            {{ old('versao_tiss_id', $convenio->versao_tiss_id) == $versao->id ? 'selected="selected"' : '' }}>
                                            {{ $versao->versao }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('versao_tiss_id'))
                                    <div class="form-control-feedback">
                                        {{ $errors->first('versao_tiss_id') }}
                                    </div>
                                @endif
                            </div>

                            @if ($instituicao->possui_faturamento_sancoop == 1)
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-control-label p-0 m-0">Código do convênio na Sancoop (contatar
                                            suporte caso não esteja exibindo ou nome divergente)</label>
                                        <input readonly="readonly" type="text"
                                            value="{{ $convenio->sancoop_cod_convenio }} ({{ $convenio->sancoop_desc_convenio }})"
                                            class="form-control">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row my-3">
                            <div class="col-12 input-group align-items-center">
                                <h4 class="d-block my-0 mr-2">Logo</h4>
                                <hr class="inline">
                            </div>
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('imagem') ? 'has-danger' : '' }}">
                                    <label style="cursor: pointer;display:block;" data-toggle="tooltip"
                                        title="Foto" data-original-title="Mude sua logo">
                                        <img class="rounded center" alt="Logo" id="image"
                                            src="{{ $convenio->imagem ? Storage::cloud()->url($convenio->imagem) : asset('material/assets/images/default_logo.png') }}"
                                            style="display:block;cursor: pointer;margin-left:auto;  margin-right: auto;">
                                        <input type="file" class='sr-only' name="imagem" id="input">
                                    </label>
                                    @if ($errors->has('imagem'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('imagem') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="nav-complemento" role="tabpanel"
                        aria-labelledby="nav-complemento-tab">
                        <div class="row">
                            <div class="col-12 input-group align-items-center mb-2">
                                <h4 class="d-block my-0 mr-2">Dados do financeiro</h4>
                                <hr class="inline">
                            </div>
                            <div class="col-md-8 form-group @if ($errors->has('pessoas_id')) has-danger @endif">
                                <label class="form-control-label">Fornecedor</label>
                                <select name="pessoas_id"
                                    class="form-control select2 @if ($errors->has('pessoas_id')) form-control-danger @endif"
                                    style="width: 100%!important">
                                    <option selected disabled hidden>Selecione ...</option>
                                    @foreach ($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->id }}"
                                            @if (old('pessoas_id', $convenio->pessoas_id) == $fornecedor->id) selected="selected" @endif>
                                            {{ $fornecedor->nome }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pessoas_id'))
                                    <div class="form-control-feedback">{{ $errors->first('pessoas_id') }}</div>
                                @endif
                            </div>
                            <div
                                class="col-md-4 col-sm-6 form-group @if ($errors->has('forma_agrupamento')) has-danger @endif">
                                <label class="form-control-label">Forma de Agrupamento</label>
                                <select name="forma_agrupamento"
                                    class="form-control @if ($errors->has('forma_agrupamento')) form-control-danger @endif"
                                    title="Selecione como será a forma de agrupamento dos materiais médicos para o faturamento">
                                    <option selected disabled hidden>Selecione ...</option>
                                    @foreach ($opcoes_forma_agrupamento as $id => $opcao)
                                        <option value="{{ $id }}"
                                            @if (old('forma_agrupamento', $convenio->forma_agrupamento) == $id) selected="selected" @endif>
                                            {{ $opcao }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('forma_agrupamento'))
                                    <div class="form-control-feedback">{{ $errors->first('forma_agrupamento') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 p-0 input-group">
                                <div class="col-12 input-group align-items-center mb-2">
                                    <h4 class="d-block my-0 mr-2">Controle de retorno de atendimento</h4>
                                    <hr class="inline">
                                </div>
                                <div
                                    class="col-md-4 col-sm-6 form-group @if ($errors->has('retorno_atendimento_ambulatorio')) has-danger @endif">
                                    <label class="form-control-label">Ambulatório</label>
                                    <div class="input-group">
                                        <input name="retorno_atendimento_ambulatorio" type="number" min="0"
                                            class="form-control @if ($errors->has('retorno_atendimento_ambulatorio')) form-control-danger @endif"
                                            value="{{ old('retorno_atendimento_ambulatorio', $convenio->retorno_atendimento_ambulatorio) }}">
                                        <button class="btn btn-secondary ml-1" type="button" data-toggle="modal"
                                            data-target="#configuracao-retorno-ambulatorio"><i
                                                class="fas fa-cogs"></i></button>
                                    </div>
                                    @if ($errors->has('retorno_atendimento_ambulatorio'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('retorno_atendimento_ambulatorio') }}</div>
                                    @endif
                                    @php
                                        $ammount_options = count($opcoes_campos_retorno);
                                    @endphp
                                    @component('components.configuracao-retorno-modal', [
                                        'modal_id' => 'configuracao-retorno-ambulatorio',
                                        'title' => 'Configurações do retorno ambulatorial',
                                        'options' => $opcoes_campos_retorno,
                                        'grupo_id' => $tipos_grupos_atendimento['retorno_atendimento_ambulatorio'],
                                        'valores' => $convenio->controlesRetorno()->where('grupo', '=', $tipos_grupos_atendimento['retorno_atendimento_ambulatorio'])->get(),
                                    ])
                                    @endcomponent
                                </div>
                                <div
                                    class="col-md-4 col-sm-6 form-group @if ($errors->has('retorno_atendimento_externo')) has-danger @endif">
                                    <label class="form-control-label">Externo</label>
                                    <div class="input-group">
                                        <input name="retorno_atendimento_externo" type="number" min="0"
                                            class="form-control @if ($errors->has('retorno_atendimento_externo')) form-control-danger @endif"
                                            value="{{ old('retorno_atendimento_externo', $convenio->retorno_atendimento_externo) }}">
                                        <button class="btn btn-secondary ml-1" type="button" data-toggle="modal"
                                            data-target="#configuracao-retorno-externo"><i
                                                class="fas fa-cogs"></i></button>
                                    </div>
                                    @if ($errors->has('retorno_atendimento_externo'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('retorno_atendimento_externo') }}</div>
                                    @endif
                                    @component('components.configuracao-retorno-modal', [
                                        'modal_id' => 'configuracao-retorno-externo',
                                        'title' => 'Configurações do retorno externo',
                                        'options' => $opcoes_campos_retorno,
                                        'grupo_id' => $tipos_grupos_atendimento['retorno_atendimento_externo'],
                                        'convenio' => $convenio,
                                        'valores' => $convenio->controlesRetorno()->where('grupo', '=', $tipos_grupos_atendimento['retorno_atendimento_externo'])->get(),
                                    ])
                                    @endcomponent
                                </div>
                                <div
                                    class="col-md-4 col-sm-6 form-group @if ($errors->has('retorno_atendimento_urgencia')) has-danger @endif">
                                    <label class="form-control-label">Urgência</label>
                                    <div class="input-group">
                                        <input name="retorno_atendimento_urgencia" type="number" min="0"
                                            class="form-control @if ($errors->has('retorno_atendimento_urgencia')) form-control-danger @endif"
                                            value="{{ old('retorno_atendimento_urgencia', $convenio->retorno_atendimento_urgencia) }}">
                                        <button class="btn btn-secondary ml-1" type="button" data-toggle="modal"
                                            data-target="#configuracao-retorno-urgencia"><i
                                                class="fas fa-cogs"></i></button>
                                    </div>
                                    @if ($errors->has('retorno_atendimento_urgencia'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('retorno_atendimento_urgencia') }}</div>
                                    @endif
                                    @component('components.configuracao-retorno-modal', [
                                        'modal_id' => 'configuracao-retorno-urgencia',
                                        'title' => 'Configurações do retorno urgência',
                                        'options' => $opcoes_campos_retorno,
                                        'grupo_id' => $tipos_grupos_atendimento['retorno_atendimento_urgencia'],
                                        'valores' => $convenio->controlesRetorno()->where('grupo', '=', $tipos_grupos_atendimento['retorno_atendimento_urgencia'])->get(),
                                    ])
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="col-12 input-group align-items-center mb-2">
                                    <h4 class="d-block my-0 mr-2">Quando o paciente estiver internado</h4>
                                    <hr class="inline">
                                </div>
                                <div
                                    class="pr-4 col-md-12 col-sm-6 form-group d-flex flex-column justify-content-end @if ($errors->has('permitir_atendimento_ambulatorial')) has-danger @endif">
                                    <div class="d-flex flex-wrap-revert align-items-center">
                                        <input type="checkbox" name="permitir_atendimento_ambulatorial"
                                            @if (old('permitir_atendimento_ambulatorial', $convenio->permitir_atendimento_ambulatorial)) checked="checked" @endif
                                            class="form-control checkbox @if ($errors->has('permitir_atendimento_ambulatorial')) form-control-danger @endif">
                                        <label class="form-control-label ml-2 mb-0">Permitir atendimento
                                            ambulatorial</label>
                                    </div>
                                    @if ($errors->has('permitir_atendimento_ambulatorial'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('permitir_atendimento_ambulatorial') }}</div>
                                    @endif
                                </div>
                                <div
                                    class="pr-4 col-md-12 col-sm-6 form-group d-flex flex-column justify-content-end mb-0 @if ($errors->has('permitir_atendimento_externo')) has-danger @endif">
                                    <div class="d-flex flex-wrap-revert align-items-center">
                                        <input type="checkbox" name="permitir_atendimento_externo"
                                            @if (old('permitir_atendimento_externo', $convenio->permitir_atendimento_externo)) checked="checked" @endif
                                            class="form-control checkbox @if ($errors->has('permitir_atendimento_externo')) form-control-danger @endif">
                                        <label class="form-control-label ml-2 mb-0">Permitir atendimento externo</label>
                                    </div>
                                    @if ($errors->has('permitir_atendimento_externo'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('permitir_atendimento_externo') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 input-group align-items-center mb-2">
                                <h4 class="d-block my-0 mr-2">Geração do disquete CIH</h4>
                                <hr class="inline">
                            </div>
                            <div class="col-md-6 form-group @if ($errors->has('fonte_de_remuneracao')) has-danger @endif">
                                <label class="form-control-label">Fonte de remuneração/Financiamento</label>
                                <select name="fonte_de_remuneracao"
                                    class="form-control @if ($errors->has('fonte_de_remuneracao')) form-control-danger @endif">
                                    <option selected disabled hidden>Selecione ...</option>
                                    @foreach ($opcoes_fonte_de_remuneracao as $id => $opcao)
                                        <option value="{{ $id }}"
                                            @if (old('fonte_de_remuneracao', $convenio->fonte_de_remuneracao) == $id) selected="selected" @endif>
                                            {{ $opcao }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('fonte_de_remuneracao'))
                                    <div class="form-control-feedback">{{ $errors->first('fonte_de_remuneracao') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 form-group @if ($errors->has('registro_ans')) has-danger @endif">
                                <label class="form-control-label">Registro ANS da operadora</label>
                                <input name="registro_ans"
                                    class="form-control @if ($errors->has('registro_ans')) form-control-danger @endif"
                                    value="{{ old('registro_ans', $convenio->registro_ans) }}">
                                @if ($errors->has('registro_ans'))
                                    <div class="form-control-feedback">{{ $errors->first('registro_ans') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 input-group align-items-center mb-2">
                                <h4 class="d-block my-0 mr-2">Carteira</h4>
                                <hr class="inline">
                            </div>
                            <div
                                class="col-md-4 col-sm-6 form-group @if ($errors->has('carteira_pede')) has-danger @endif">
                                <label class="form-control-label">Pede carteira?</label>
                                <select name="carteira_pede"
                                    class="form-control @if ($errors->has('carteira_pede')) form-control-danger @endif">
                                    <option value="1" @if (old('carteira_pede', $convenio->carteira_pede) == 1) selected="selected" @endif>
                                        Sim</option>
                                    <option value="0" @if (old('carteira_pede', $convenio->carteira_pede) == 0) selected="selected" @endif>
                                        Não</option>
                                </select>
                                @if ($errors->has('carteira_pede'))
                                    <div class="form-control-feedback">{{ $errors->first('carteira_pede') }}</div>
                                @endif
                            </div>
                            <div
                                class="col-md-4 col-sm-6 form-group @if ($errors->has('carteira_verif_elig')) has-danger @endif">
                                <label class="form-control-label">Verif. Elig.?</label>
                                <select name="carteira_verif_elig"
                                    class="form-control @if ($errors->has('carteira_verif_elig')) form-control-danger @endif">
                                    <option value="1" @if (old('carteira_verif_elig', $convenio->carteira_verif_elig) == 1) selected="selected" @endif>
                                        Sim</option>
                                    <option value="0" @if (old('carteira_verif_elig', $convenio->carteira_verif_elig) == 0) selected="selected" @endif>
                                        Não</option>
                                </select>
                                @if ($errors->has('carteira_verif_elig'))
                                    <div class="form-control-feedback">{{ $errors->first('carteira_verif_elig') }}</div>
                                @endif
                            </div>
                            <div
                                class="col-md-4 col-sm-6 form-group @if ($errors->has('carteira_obg')) has-danger @endif">
                                <label class="form-control-label">Verif. Cart. Obg.?</label>
                                <select name="carteira_obg"
                                    class="form-control @if ($errors->has('carteira_obg')) form-control-danger @endif">
                                    <option value="1" @if (old('carteira_obg', $convenio->carteira_obg) == 1) selected="selected" @endif>
                                        Sim</option>
                                    <option value="0" @if (old('carteira_obg', $convenio->carteira_obg) == 0) selected="selected" @endif>
                                        Não</option>
                                </select>
                                @if ($errors->has('carteira_obg'))
                                    <div class="form-control-feedback">{{ $errors->first('carteira_obg') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 input-group align-items-center mb-2">
                                <h4 class="d-block my-0 mr-2">Leitura de carteira de beneficiário</h4>
                                <hr class="inline">
                            </div>
                            <div
                                class="col-md-6 form-group d-flex flex-column justify-content-end @if ($errors->has('limite_contas_pre_remessa')) has-danger @endif">
                                <div class="d-flex flex-wrap-revert align-items-center">
                                    <label class="form-control-label ml-2 mb-0">Limite de contas na pré-remessa</label>
                                    <input type="number" name="limite_contas_pre_remessa"
                                        value="{{ old('limite_contas_pre_remessa', $convenio->limite_contas_pre_remessa) }}"
                                        class="form-control checkbox @if ($errors->has('limite_contas_pre_remessa')) form-control-danger @endif">
                                </div>
                                @if ($errors->has('limite_contas_pre_remessa'))
                                    <div class="form-control-feedback">{{ $errors->first('limite_contas_pre_remessa') }}
                                    </div>
                                @endif
                            </div>
                            <div
                                class="col-md-6 form-group d-flex flex-column justify-content-center @if ($errors->has('fechar_conta_amb_sem_impressao')) has-danger @endif">
                                <div class="d-flex flex-wrap-revert align-items-center">
                                    <label class="form-control-label mr-2 mb-0">Fechar conta ambulatorial sem
                                        imprimir</label>
                                    <input type="checkbox" name="fechar_conta_amb_sem_impressao"
                                        @if (old('fechar_conta_amb_sem_impressao', $convenio->fechar_conta_amb_sem_impressao)) checked="checked" @endif
                                        class="form-control checkbox @if ($errors->has('fechar_conta_amb_sem_impressao')) form-control-danger @endif">
                                </div>
                                @if ($errors->has('fechar_conta_amb_sem_impressao'))
                                    <div class="form-control-feedback">
                                        {{ $errors->first('fechar_conta_amb_sem_impressao') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 p-0">
                                <div class="col-12 input-group align-items-center mb-2">
                                    <h4 class="d-block my-0 mr-2">Faixa de guias</h4>
                                    <hr class="inline">
                                </div>
                                <div
                                    class="col-12 form-group d-flex flex-column justify-content-end @if ($errors->has('quantidade_alerta_faixa')) has-danger @endif">
                                    <label class="form-control-label">Quantidade para alerta de fim de faixa</label>
                                    <div class="col-3 p-0">
                                        <input type="number" name="quantidade_alerta_faixa"
                                            value="{{ old('quantidade_alerta_faixa', $convenio->quantidade_alerta_faixa) }}"
                                            class="form-control @if ($errors->has('quantidade_alerta_faixa')) form-control-danger @endif">
                                    </div>
                                    @if ($errors->has('quantidade_alerta_faixa'))
                                        <div class="form-control-feedback">{{ $errors->first('quantidade_alerta_faixa') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 p-0">
                                <div class="col-12 input-group align-items-center mb-2">
                                    <h4 class="d-block my-0 mr-2">Tratamento de oncologia</h4>
                                    <hr class="inline">
                                </div>
                                <div class="col-12 form-group @if ($errors->has('tipo_cobranca_oncologia')) has-danger @endif">
                                    <label class="form-control-label">Tipo de cobrança</label>
                                    <select name="tipo_cobranca_oncologia"
                                        class="form-control @if ($errors->has('tipo_cobranca_oncologia')) form-control-danger @endif">
                                        <option selected disabled hidden>Selecione ...</option>
                                        @foreach ($opcoes_tipo_cobranca_oncologia as $id => $opcao)
                                            <option value="{{ $id }}"
                                                @if (old('tipo_cobranca_oncologia', $convenio->tipo_cobranca_oncologia) == $id) selected="selected" @endif>
                                                {{ $opcao }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('tipo_cobranca_oncologia'))
                                        <div class="form-control-feedback">{{ $errors->first('tipo_cobranca_oncologia') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-excecoes" role="tabpanel" aria-labelledby="nav-excecoes-tab">
                        <div class="row">
                            <div class="col-12 input-group align-items-center mb-2">
                                <h4 class="d-block my-0 mr-2">Selecione um procedimento para adicionar como exceção</h4>
                                <hr class="inline">
                            </div>
                            <div class="col-md-8 mx-auto form-group">
                                <label class="form-control-label">Procedimentos</label>
                                <div class="input-group">
                                    <div style="flex: 1">
                                        <select id="select-procedimentos-excecao" class="form-control select2"
                                            style="width: 100%">
                                            <option selected disabled hidden>Selecione ...</option>
                                            @foreach ($procedimentos as $procedimento)
                                                <option value="{{ $procedimento->id }}">{{ $procedimento->descricao }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" onclick="adicionarExcecao()" class="btn btn-danger"><i
                                            class="fas fa-ban"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 mx-auto">
                                <table class="table table-bordered table-stripped">
                                    <colgroup>
                                        <col style="width: 100px">
                                        <col style="width: auto">
                                        <col style="width: 50px">
                                    </colgroup>
                                    <tbody id="lista-procedimentos-excecao"></tbody>
                                </table>
                                @if ($errors->has('excecoes.*.procedimentos_id'))
                                    <div class="col-8 mt-2 mb-0 ml-0 mr-0 alert alert-danger">
                                        <small>{{ $errors->first('excecoes.*.procedimentos_id') }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group text-right">
                <a href="{{ route('instituicao.convenio.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                            class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                </a>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i
                        class="mdi mdi-check"></i> Salvar</button>
            </div>
        </form>
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
                    <div>
                        <img style="max-width: 100%;" id="imageModal" src="">
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" style='margin:0;' class="btn btn-secondary"
                        data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="crop">Definir</button>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/template" id="template-procedimentos-excecao">
        <tr>
            <input type="hidden" default_name="excecoes[][procedimentos_id]">
            <td class="id_text"></td>
            <td class="description_text"></td>
            <td>
                <button onclick="removerExcecao(this)" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
            </td>
        </tr>
    </script>
    <script>
        const excecao_select = $('#select-procedimentos-excecao')
        const excecao_lista = $('#lista-procedimentos-excecao')
        const template = $('#template-procedimentos-excecao')
        const selected = Array.from({!! $convenio->excecoesProcedimentos()->get() !!})

        function removerExcecao(button) {
            const row = $($(button).parents()[1])
            const id = selected.indexOf(row.find('input').val())
            selected.splice(id)
            renderListaExcecoes()
        }

        function adicionarExcecao(item = null, render = true) {
            const option = excecao_select.find(`[value="${excecao_select.val()}"]`)
            if (item === null) {
                item = {
                    id: option.val(),
                    descricao: option.text()
                }
            }
            // Caso não seja válido o id ou o id já esteja inserido, retorne
            if ((!item.id && item.id != 0) || selected.find(el => el.id == item.id)) {
                return
            }
            selected.push(item)
            if (render) {
                renderListaExcecoes()
            }
        }

        function renderListaExcecoes() {
            excecao_lista.empty()
            selected.forEach((item) => {
                const element = $(template.html())
                element.find('input').val(item.id)
                element.find('input').attr('name', element.find('input').attr('default_name'))
                element.find('.id_text').text(item.id)
                element.find('.description_text').text(item.descricao)
                excecao_lista.append(element)
            })
        }


        $(document).ready(function() {
            $('.select2').select2()
            $('[name="apresentacoes_convenio_id"]').select2()
            // $('[name="pessoas_id"]').select2()
            $('.checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
                increaseArea: '90%'
            })

            // Método que atualiza os inputs dos checks que existem dentro dos pop-ups de configuração
            const check_config_checkboxes = function(check) {
                if ($(check).prop('checked')) {
                    $($(check).parents()[1]).find('input[type="hidden"]').each((key, element) => {
                        $(element).attr('name', $(element).attr('default_name'))
                    })
                } else {
                    $($(check).parents()[1]).find('input[type="hidden"]').each((key, element) => {
                        $(element).removeAttr('name')
                    })
                }
            }
            $('.switch-checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
                increaseArea: '90%'
            }).on('ifChanged', (event) => check_config_checkboxes(event.target))

            // Inicializando os checks do popup de configuração
            $('.switch-checkbox').each((key, item) => check_config_checkboxes(item))

            // Inicializando lista de excecoes
            selected.forEach(function(item) {
                adicionarExcecao(item, false)
            })
            renderListaExcecoes()

            let blobImage;
            let input = document.getElementById('input');
            let image = document.getElementById('image');
            let imageModal = document.getElementById('imageModal');
            let newInput = document.getElementById("input");

            input.addEventListener('change', function(e) {
                var files = e.target.files;
                var done = function(url) {
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
                        reader.onload = function(e) {
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            $('#modal').on('shown.bs.modal', function() {
                cropper = new Cropper(imageModal, {
                    aspectRatio: 4 / 4
                });
            }).on('hidden.bs.modal', function() {
                cropper.destroy();
                cropper = null;
            });

            document.getElementById('crop').addEventListener('click', function() {
                let initialImage;
                let canvas;

                if (cropper) {
                    canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                    });

                    initialImage = image.src;

                    image.src = canvas.toDataURL();

                    canvas.toBlob(function(blob) {
                        const info = blob.type.split('/');
                        let list = new DataTransfer();
                        const file = new File([blob], 'image.' + info[1], {
                            type: blob.type,
                        });
                        list.items.add(file);
                        let myFileList = list.files;
                        newInput.files = myFileList;
                    });
                }
                $('#modal').modal('hide');
            });
        })
    </script>
@endpush
