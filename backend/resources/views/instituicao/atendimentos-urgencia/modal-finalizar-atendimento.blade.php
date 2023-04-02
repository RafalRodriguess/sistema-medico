<div id="atendimento-modal" class="card">
    <form action="{{ route('instituicao.atendimentos-urgencia.finalizar-atendimento', $senha) }}" id="formAgendamento"
        method="post">
        <input type="hidden" name="senhas_triagem_id" value="{{ $senha->id }}">
        <div class="modal-header">
            <h3 class="modal-title">Iniciar atendimento</h4>
                <button type="button" class="close close-button" aria-hidden="true">×</button>
        </div>
        <div class="atendimento-modal-body">
            @csrf
            <div class="row col-12 px-0 pt-2 m-0">
                <div class="col-12 p-0">
                    <div class="row col-12 px-0 pt-2 m-0">
                        <div class="form-group col-md-4 col-sm-4">
                            <label for="paciente-select" class="control-label">Nome do paciente</label>
                            <span id="exibir-nome-paciente" type="text"
                                class="form-control d-block">{{ $senha->getPaciente()->nome }}</span>
                        </div>
                        @php
                            if ($atendimento_urgencia) {
                                $paciente = $atendimento_urgencia->paciente;
                            } else {
                                $paciente = $senha->getPaciente();
                            }
                        @endphp
                        <div class="form-group col-md-5 col-sm-5">
                            <input type="hidden" id="paciente-input" name="paciente_id" value="{{ $paciente->id ?? null }}">
                            <label for="paciente-select" class="control-label">Vinculo Paciente <span
                                    class="text-danger">*</span></label>
                            <select id="paciente-select" style="width: 100%" class="form-control">
                                @if ($paciente->exists())
                                    <option value="{{ json_encode($paciente) }}" selected>
                                        {{ $paciente->nome }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-3 col-sm-3 d-flex align-items-end">
                            @if (!$paciente->exists())
                                <a target="_blank" href="{{ route('instituicao.pessoas.create', $paciente->toArray()) }}"
                                    class="btn btn-primary">Cadastrar Novo</a>
                            @else
                                <a target="_blank" href="{{ route('instituicao.pessoas.create') }}"
                                    class="btn btn-primary">Cadastrar Novo</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 form-group">
                        <hr class="m-0">
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label for="origem-select" class="control-label">Origem <span class="text-danger">*</span></label>
                    <select select2-label="Selecione uma origem" name="origens_id" id="origem-select"
                        class="form-control ajax-origem-select" style="width: 100%">
                        @if ($atendimento_urgencia)
                            <option value="{{ $atendimento_urgencia->origem->id }}" selected>
                                {{ $atendimento_urgencia->origem->descricao }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="data_atendimento" class="control-label">Data <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="data_atendimento" name="data"
                        value="@if ($atendimento_urgencia) {{ $atendimento_urgencia->data }} @else {{ date('d/m/Y') }} @endif"
                        readonly>
                </div>
                <div class="form-group col-md-3">
                    <label for="hora_atendimento" class="control-label">Hora <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="hora_atendimento" name="hora"
                        value="@if ($atendimento_urgencia) {{ $atendimento_urgencia->hora }}" readonly @else {{ date('H:i') }}" @endif>
                </div>

                <div class="form-group
                        col-md-2">
                    <label for="data_atendimento" class="control-label">Senha </label>
                    <input type="text" class="form-control" value="{{ $senha->valor }}" disabled>
                </div>


                <div class="form-group col-md-4">
                    <label for="local-procedencia-select" class="control-label">Local de procedência</label>
                    <select select2-label="Selecione um local de procedência" name="local_procedencia_id"
                        id="local-procedencia-select" class="form-control ajax-origem-select" style="width: 100%">
                        @if ($atendimento_urgencia)
                            <option value="{{ $atendimento_urgencia->procedencia->id }}" selected>
                                {{ $atendimento_urgencia->procedencia->descricao }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="destino-select" class="control-label">Destino</label>
                    <select select2-label="Selecione um destino" name="destino_id" id="destino-select"
                        class="form-control ajax-origem-select" style="width: 100%">
                        @if ($atendimento_urgencia)
                            <option value="{{ $atendimento_urgencia->destino->id }}" selected>
                                {{ $atendimento_urgencia->destino->descricao }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="medico-select" class="control-label">Prestador <span
                            class="text-danger">*</span></label>
                    <select id="medico-select" style="width: 100%" name="id_prestador" class="form-control">
                        @if ($atendimento_urgencia)
                            <option value="{{ $atendimento_urgencia->prestador->id }}" selected>
                                {{ $atendimento_urgencia->prestador->nome }}</option>
                        @endif
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="especialidades-select" class="control-label">Especialidade <span
                            class="text-danger">*</span></label>
                    <select select2-label="Selecione uma especialidade" name="especialidades_id"
                        id="especialidades-select" class="form-control" style="width: 100%">
                        @if ($atendimento_urgencia && !empty($atendimento_urgencia->especialidade))
                            <option value="{{ $atendimento_urgencia->especialidade->id }}" selected>
                                {{ $atendimento_urgencia->especialidade->descricao }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="carater-atendimento-select" class="control-label">Caráter de atendimento</label>
                    <select select2-label="Selecione um caráter de atendimento" name="atendimentos_id"
                        id="carater-atendimento-select" class="form-control select2-generic" style="width: 100%">
                        <option value="" hidden></option>
                        @foreach ($carateres_atendimento as $carater)
                            <option value="{{ $carater->id }}" @if ($atendimento_urgencia && $atendimento_urgencia->caraterAtendimento->id == $carater->id) selected @endif>
                                {{ $carater->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="cid-input" class="control-label">CID</label>
                    <input id="cid-input" type="text" class="form-control" name="cid"
                        value="@if ($atendimento_urgencia) {{ $atendimento_urgencia->cid }} @endif">
                </div>

                <div class="form-group col-md-9">
                    <label for="observacao-input" class="control-label">Observações </label>
                    <textarea id="observacao-input" type="text" rows="6" class="form-control" name="observacoes">
                        @if ($atendimento_urgencia)
{{ $atendimento_urgencia->observacoes }}
@endif
                    </textarea>
                </div>


                <div class="row col-12">
                    <div class="col-12 form-group">
                        <hr class="m-0">
                    </div>
                    <div class="col-12">
                        <div id="vincular-carteirinha" class="row">
                            <div class="form-group col-md-6">
                                <label for="carteirinha_id" class="control-label">Carteirinha </label>
                                <select class="form-control select2carteirinhaagenda" name="carteirinha_id"
                                    id="carteirinha_id" style="width: 100%">
                                    @if ($atendimento_urgencia)
                                        <option value="{{ $atendimento_urgencia->carteirinha->id }}">
                                            {{ $atendimento_urgencia->carteirinha->carteirinha }}</option>
                                    @endif
                                </select>
                            </div>
                            @can('habilidade_instituicao_sessao', 'cadastrar_carteirinha')
                                <div class="form-group col-md-4">
                                    <label for="cadastro-manual-carteirinha" class="control-label">Nova
                                        carteirinha?</label>
                                    <div class="col-12 p-0">
                                        <input type="checkbox" name="cadastro-manual-carteirinha"
                                            id="cadastro-manual-carteirinha" class="checkbox">
                                    </div>
                                </div>
                            @endcan
                        </div>
                        @can('habilidade_instituicao_sessao', 'cadastrar_carteirinha')
                            <div id="cadastrar-carteirinha" class="row" style="display: none">
                                <div class="col-12">
                                    <h4 class="mb-3">Cadastrar nova carteirinha</h5>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="carteirinha_convenio_id" class="form-control-label p-0 m-0">Convênio
                                            <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="carteirinha[convenio_id]"
                                            id='carteirinha_convenio_id' aria-placeholder="Selecione o convênio"
                                            style="width: 100%">
                                            <option value=''></option>
                                        </select>
                                        @if ($errors->has('carteirinha.convenio_id'))
                                            <small
                                                class="form-text text-danger">{{ $errors->first('carteirinha.convenio_id') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for='carteirinha_plano_id' class="form-control-label p-0 m-0">Plano <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2" name="carteirinha[plano_id]"
                                            id='carteirinha_plano_id' aria-placeholder="Selecione o plano"
                                            style="width: 100%">
                                            <option value=''></option>
                                        </select>
                                        @if ($errors->has('carteirinha.plano_id'))
                                            <small
                                                class="form-text text-danger">{{ $errors->first('carteirinha.plano_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="carteirinha_n_carteirinha" class="form-control-label p-0 m-0">Nº
                                        Carteirinha <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="carteirinha[carteirinha]"
                                        id="carteirinha_n_carteirinha" placeholder="Número da carteirinha">
                                    @if ($errors->has('carteirinha.carteirinha'))
                                        <small
                                            class="form-text text-danger">{{ $errors->first('carteirinha.carteirinha') }}</small>
                                    @endif
                                </div>

                                <div class="col-sm">
                                    <label for="carteirinha_validade" class="form-control-label p-0 m-0">Validade <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="carteirinha[validade]"
                                        id="carteirinha_validade" placeholder="Validade" min="{{ date('Y-m-d') }}">
                                    @if ($errors->has('carteirinha.validade'))
                                        <small
                                            class="form-text text-danger">{{ $errors->first('carteirinha.validade') }}</small>
                                    @endif
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>

                <div class="form-group col-12 p-0">
                    <div class="col-12 form-group m-0">
                        <hr class="mt-0">
                        <h4 class="mb-3">Procedimentos:</h5>
                    </div>
                    @if ($errors->has('carteirinha.validade'))
                        <div class="form-group">
                            <small
                                class="form-text text-danger">{{ $errors->first('procedimentos.*.procedimentos') }}</small>
                        </div>
                    @endif
                    <div id="container-procedimentos" class="col-12 p-0">
                        @php
                            $count = 0;
                        @endphp
                        @if ($atendimento_urgencia && count($atendimento_urgencia->procedimentosAtendimentoUrgencia) > 0)
                            @foreach ($atendimento_urgencia->procedimentosAtendimentoUrgencia as $procedimento_atendimento)
                                @php
                                    $count++;
                                @endphp
                                <div class="col-md-12 item-convenio-procedimento" el-id="{{ $count }}">
                                    <div class="row">
                                        <div class="form-group dados_parcela col-md-4">
                                            <label class="form-control-label">Convênio <span
                                                    class="text-primary">*</span></label>
                                            <select el-id="{{ $count }}"
                                                name="procedimentos[{{ $count }}][id_convenio]"
                                                class="form-control select-convenio selectfild2 convenio"
                                                style="width: 100%">
                                                <option value="{{ $procedimento_atendimento->convenio->id }}"
                                                    selected>{{ $procedimento_atendimento->convenio->nome }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4 pr-0">
                                            <label class="form-control-label">Procedimento <span
                                                    class="text-primary">*</span></label>
                                            <select el-id="{{ $count }}"
                                                name="procedimentos[{{ $count }}][id_procedimento]"
                                                id="convenio[{{ $count }}][procedimento_agenda]"
                                                class="form-control selectfild2 procedimentos" disabled
                                                style="width: 100%">
                                                <option
                                                    value="{{ $procedimento_atendimento->instituicaoProcedimento->id }}"
                                                    selected>{{ $procedimento_atendimento->procedimento->descricao }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col pl-2">
                                            <button type="button"
                                                class="btn btn-md p-0 text-primary btnAddProcedimentos"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Adicionar outro procedimento"
                                                data-original-title="Adicionar outro procedimento">
                                                <i class="mdi mdi-plus-circle"></i>
                                            </button>
                                            <button type="button" el-id="{{ $count }}"
                                                class="btn btn-md p-0 ml-1 text-danger btnRemoverProcedimentos d-none"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Remover procedimento"
                                                data-original-title="Remover procedimento">
                                                <i el-id="{{ $count }}" class="mdi mdi-minus-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="button" class="btn btn-danger modal-cancelar">Cancelar</button>
            <button type="submit" class="btn btn-success modal-confirmar">Confirmar</button>
        </div>
    </form>
</div>

{{-- TEMPLATE PROCEDIMENTO --}}
<script type="text/template" id="template-convenio-procedimento" style="display: none">
    <div class="col-md-12 item-convenio-procedimento" el-id="#">
        <div class="row">
            <div class="form-group dados_parcela col-md-4">
                <label class="form-control-label">Convênio <span class="text-primary">*</span></label>
                <select el-id="#" name="procedimentos[#][id_convenio]"
                    class="form-control select-convenio selectfild2 convenio" style="width: 100%">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group col-md-4 pr-0">
                <label class="form-control-label">Procedimento <span class="text-primary">*</span></label>
                <select el-id="#" name="procedimentos[#][id_procedimento]" id="convenio[#][procedimento_agenda]"
                    class="form-control selectfild2 procedimentos" disabled
                    style="width: 100%">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group col pl-2">
                <button type="button" class="btn btn-md p-0 text-primary btnAddProcedimentos" data-toggle="tooltip"
                    data-placement="top" title="Adicionar outro procedimento"
                    data-original-title="Adicionar outro procedimento">
                    <i class="mdi mdi-plus-circle"></i>
                </button>
                <button type="button" el-id="#"
                    class="btn btn-md p-0 ml-1 text-danger btnRemoverProcedimentos d-none" data-toggle="tooltip"
                    data-placement="top" title="Remover procedimento" data-original-title="Remover procedimento">
                    <i el-id="#" class="mdi mdi-minus-circle"></i>
                </button>
            </div>
        </div>
    </div>
</script>
{{-- /TEMPLATE --}}

<script>
    var quantidade_convenio = 0;
    var ultimo_id_procedimento = parseInt({{ $count }});
    var template_procedimentos = $('#template-convenio-procedimento');
    var container_procedimentos = $('#container-procedimentos');

    // Objeto que comanda os procedimentos
    var ModalProcedimentos = {
        // Adiciona um procedimento extra
        adicionar: () => {
            const elemento = $(template_procedimentos.html().replaceAll('#', ultimo_id_procedimento));
            container_procedimentos.append(elemento);
            // Só exibe o botão de remover a partir do segundo procedimento
            if (ultimo_id_procedimento > 0) {
                elemento.find('.btnRemoverProcedimentos').removeClass('d-none');
            }
            ultimo_id_procedimento++;
            ModalProcedimentos.preparar(elemento);
        },
        // Remove um procedimento que não seja o primeiro
        remover: (id) => {
            if (id > 0) {
                $(`.item-convenio-procedimento[el-id="${id}"]`).remove();
            }
        },

        preparar: (parent) => {
            // Inicializa o seletor de convênios
            parent.find('.select-convenio').each((key, item) => {
                $(item).select2({
                    placeholder: 'Selecione um convênio',
                    dropdownParent: $(item).parent(),
                    ajax: {
                        url: "{{ route('instituicao.buscar-convenios') }}",
                        method: 'POST',
                        dataType: 'json',
                        delay: 100,
                        data: function(params) {
                            return {
                                search: params.term || '', // search term
                                page: params.page || 1,
                                '_token': '{{ csrf_token() }}'
                            }
                        },
                        processResults: function(data, params) {
                            let items = [];
                            if (data.convenios && data.convenios.data) {
                                items = data.convenios.data;
                            }
                            return {
                                results: items.map((item) => {
                                    return {
                                        text: item.nome,
                                        id: item.id
                                    }
                                }),
                                pagination: {
                                    more: data.next_page ?? ''
                                }
                            };
                        }
                    }
                }).on('select2:select', (e) => {
                    const element = $(e.target);
                    const id = element.attr('el-id');
                    const target = $(`.item-convenio-procedimento[el-id="${id}"]`).find(
                        '.procedimentos');
                    if (element.val()) {
                        target.removeAttr('disabled');
                        target.val(null).trigger('change');
                    } else {
                        target.attr('disabled', '');
                    }
                })
            });

            // Inicializa a busca de procedimentos
            parent.find('.procedimentos').each((key, item) => {
                const id = $(item).attr('el-id');

                if (id !== undefined && id !== null && $(`.select-convenio[el-id="${id}"]`).val()) {
                    $(item).removeAttr('disabled');
                }

                $(item).select2({
                    placeholder: 'Selecione um procedimento',
                    dropdownParent: $(item).parent(),
                    ajax: {
                        url: "{{ route('instituicao.buscar-procedimentos-instituicao') }}",
                        method: 'POST',
                        dataType: 'json',
                        delay: 100,
                        data: function(params) {
                            const id = $(this).attr('el-id');
                            const convenio = $(`.select-convenio[el-id="${id}"]`).val();
                            return {
                                search: params.term || '', // search term
                                page: params.page || 1,
                                convenio_id: convenio || 0,
                                '_token': '{{ csrf_token() }}'
                            }
                        },
                        processResults: function(data, params) {
                            let items = [];
                            if (data.procedimentos && data.procedimentos.data) {
                                items = data.procedimentos.data;
                            }
                            return {
                                results: items.map((item) => {
                                    return {
                                        text: `#${item.procedimento_id} ${item.procedimento_descricao}`,
                                        id: item.id
                                    }
                                }),
                                pagination: {
                                    more: data.next_page ?? ''
                                }
                            };
                        }
                    }
                });
            });

            // Ação de adicionar procedimento
            parent.find('.btnAddProcedimentos').on('click', (e) => {
                ModalProcedimentos.adicionar();
            });

            // Ação de remover procedimento
            parent.find('.btnRemoverProcedimentos').on('click', (e) => {
                ModalProcedimentos.remover($(e.target).attr('el-id'));
            });
        }
    };

    window.__modal_ready = function() {


        $('.telefone').each(function() {
            $(this).setMask('(99) 99999-9999', {
                translation: {
                    '9': {
                        pattern: /[0-9]/,
                        optional: false
                    }
                }
            })
        });

        $('.cpf').each(function() {
            $(this).setMask('999.999.999-99', {
                translation: {
                    '9': {
                        pattern: /[0-9]/,
                        optional: false
                    }
                }
            })
        });


        $(".select2agenda").select2({
            placeholder: "Pesquise por nome ou cpf",
            allowClear: true,
            minimumInputLength: 3,
            language: {
                searching: function() {
                    return 'Buscando paciente (aguarde antes de selecionar)…';
                },

                inputTooShort: function(input) {
                    console.log(input.minimum)
                    return "Digite " + (input.minimum - input.input.length) +
                        " caracteres para pesquisar";
                },
            },
            ajax: {
                url: "{{ route('instituicao.agendamentos.getPacientes') }}",
                dataType: 'json',
                delay: 100,

                data: function(params) {
                    return {
                        q: params.term || '', // search term
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: _.map(data.results, item => ({
                            id: Number.parseInt(item.id),
                            text: `${item.nome} - (${item.cpf})`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },

        }).on('select2:select', function(e) {
            var data = e.params.data;
            $.ajax({
                url: "{{ route('instituicao.agendamentos.getPaciente', ['pessoa' => 'pessoa_id']) }}"
                    .replace('pessoa_id', data.id),
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                datatype: "json",
                processData: false,
                contentType: false,
                success: function(result) {
                    $("#telefone_paciente_agenda").val(result.telefone1)
                }
            });
        })


        $(".selectfild2_convenio").select2();
        $(".valor_mask").setMask();

        // Função de fechar modal genérico
        $('#atendimento-modal').on('click', '.close-button', window.closeGenericModal)
        // Função de fechar modal genérico
        $('#atendimento-modal').on('click', '.modal-cancelar', window.closeGenericModal)
        // Método genérico de enviar formulário ajax
        $("#formAgendamento").on('submit', function(e) {
            e.preventDefault()

            var formData = new FormData($(this)[0]);
            $.ajax($("#formAgendamento").attr('action'), {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    window.closeGenericModal();
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Agendamento cadastrado com sucesso',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 10
                    });
                    callRenderPage();
                },
                error: function(response) {
                    if (response.responseJSON && response.responseJSON.errors) {
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

        // Dropdown de médico
        $('#medico-select').select2({
            placeholder: "Busque o prestador",
            dropdownParent: $('#medico-select').parent(),
            ajax: {
                url: "{{ route('instituicao.ajax.buscaprestador') }}",
                type: 'post',
                dataType: 'json',
                quietMillis: 20,
                data: function(params) {
                    return {
                        search: params.term,
                        '_token': '{{ csrf_token() }}',
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.results, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.prestador.nome
                            };
                        }),
                        pagination: {
                            more: data.pagination.more
                        }
                    }
                }
            },
            escapeMarkup: function(m) {
                return m;
            }
        });

        $('#paciente-select').select2({
            placeholder: "Pesquise por nome do paciente",
            minimumInputLength: 3,
            dropdownParent: $('#paciente-select').parent(),
            ajax: {
                url: "{{ route('instituicao.contasPagar.getPacientes') }}",
                dataType: 'json',
                type: 'get',
                delay: 100,

                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page || 1
                    };
                },

                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: _.map(data.results, item => ({
                            id: JSON.stringify(item),
                            text: `${item.nome} #${item.id} ${(item.cpf) ? '- ('+item.cpf+')': ''} ${(item.telefone1) ? '- ' +item.telefone1 : ''}`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 3,
            language: {
                searching: function() {
                    return 'Buscando pacientes';
                },

                noResults: function() {
                    return 'Nenhum resultado encontrado';
                },

                inputTooShort: function(input) {
                    return "Digite " + (input.minimum - input.input.length) +
                        " caracteres para pesquisar";
                },
            }
        }).on('select2:select', e => {
            const data = JSON.parse($(e.target).val());
            $('#exibir-nome-paciente').text(data.nome);
            $('#paciente-input').val(data.id);
        });

        $('#especialidades-select').select2({
            placeholder: "Busque uma especialidade",
            dropdownParent: $('#especialidades-select').parent(),
            allowClear: true,
            ajax: {
                url: "{{ route('instituicao.ajax.buscarespecialidades') }}",
                type: 'post',
                dataType: 'json',
                quietMillis: 20,
                data: function(params) {
                    return {
                        search: params.term,
                        '_token': '{{ csrf_token() }}',
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

        // Mascara do input de horas
        $('#hora_atendimento').setMask('29:59', {
            numericInput: true,
            translation: {
                '9': {
                    pattern: /[0-9]/,
                    optional: false
                },
                '5': {
                    pattern: /[0-5]/,
                    optional: false
                },
                '9': {
                    pattern: /[0-9]/,
                    optional: false
                },
                '2': {
                    pattern: /[0-2]/,
                    optional: true
                }
            }
        }).on('change', function(e) {
            const val = e.target.value.split(':')
            if (parseInt(val[0]) > 23) {
                e.target.value = '23:' + val[1]
            }
        });

        $('.atendimento-modal-body .select2-generic').each((key, item) => {
            $(item).select2({
                placeholder: $(item).attr('select2-label'),
                dropdownParent: $(item).parent()
            })
        });

        // Inserindo o template de origens em todos os selects de origem
        $('.atendimento-modal-body .ajax-origem-select').each((key, item) => {
            $(item).select2({
                dropdownParent: $(item).parent(),
                placeholder: $(item).attr('select2-label'),
                delay: 100,
                ajax: {
                    url: "{{ route('instituicao.ajax.buscar-origem') }}",
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            search: params.term,
                            '_token': '{{ csrf_token() }}',
                            page: params.page || 1
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: $.map(response.data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.descricao
                                };
                            }),
                            pagination: {
                                more: response.next_page_url ? true : false
                            }
                        }
                    },
                    cache: true
                },
                escapeMarkup: function(m) {
                    return m;
                }
            })

        });

        $('.checkbox').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
            increaseArea: '50%'
        });

        $('#cadastro-manual-carteirinha').on('ifChanged', function(e) {
            if ($(e.target).prop('checked')) {
                $('#cadastrar-carteirinha').show();
                $('#carteirinha_id').prop('disabled', true)
            } else {
                $('#cadastrar-carteirinha').hide();
                $('#carteirinha_id').removeAttr('disabled')
            }
        });


        $("#carteirinha_id").select2({
            placeholder: "Pesquise por carteirinha",
            dropdownParent: $("#carteirinha_id").parent(),
            allowClear: true,
            language: {
                searching: function() {
                    return 'Buscando carteirinha (aguarde antes de selecionar)…';
                },

                inputTooShort: function(input) {
                    return "Digite " + (input.minimum - input.input.length) +
                        " caracteres para pesquisar";
                },
            },

            ajax: {
                url: "{{ route('instituicao.agendamentos.getCarteirinhas') }}",
                dataType: 'json',
                delay: 100,

                data: function(params) {
                    return {
                        q: params.term || '', // search term
                        page: params.page || 1,
                        pessoa: $("#paciente-select").val()
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: _.map(data.results, item => ({
                            id: Number.parseInt(item.id),
                            text: `${item.carteirinha} (${item.convenio[0].nome})`,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },

        });

        $("#carteirinha_convenio_id").select2({
            placeholder: $("#carteirinha_convenio_id").attr('aria-placeholder'),
            dropdownParent: $("#carteirinha_convenio_id").parent(),
            language: {
                searching: function() {
                    return 'Buscando convênios';
                },
            },
            ajax: {
                url: "{{ route('instituicao.contasReceber.getConvenios') }}",
                dataType: 'json',
                type: 'get',
                delay: 100,

                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page || 1,
                        '_token': '{{ csrf_token() }}'
                    };
                },

                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: _.map(data.results, item => ({
                            id: Number.parseInt(item.id),
                            text: item.nome,
                        })),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
        }).on('select2:select', function(e) {
            const val = $("#carteirinha_convenio_id").val();
            if (val) {
                $("#carteirinha_plano_id").val('').trigger('change');
                $("#carteirinha_plano_id").removeAttr('disabled');
            }
        });

        if (!$("#carteirinha_convenio_id").val()) {
            $("#carteirinha_plano_id").prop('disabled', true);
        }

        $("#carteirinha_plano_id").select2({
            placeholder: $("#carteirinha_plano_id").attr('aria-placeholder'),
            dropdownParent: $("#carteirinha_plano_id").parent(),
            language: {
                searching: function() {
                    return 'Buscando convênios';
                },
            },
            ajax: {
                url: "{{ route('instituicao.ajax.get-convenios-planos') }}",
                dataType: 'json',
                type: 'POST',
                delay: 100,

                data: function(params) {
                    return {
                        convenio_id: $("#carteirinha_convenio_id").val(),
                        search: params.term,
                        page: params.page || 1,
                        '_token': '{{ csrf_token() }}'
                    };
                },

                processResults: function(response, params) {
                    params.page = params.page || 1;
                    return {
                        results: _.map(response.data, item => ({
                            id: Number.parseInt(item.id),
                            text: `${item.nome}`,
                        })),
                        pagination: {
                            more: response.next_page_url ? true : false
                        }
                    };
                },
                cache: true
            }
        });

        // configureCarteirinha();
        if (ultimo_id_procedimento > 0) {
            ModalProcedimentos.preparar($('#container-procedimentos'));
        } else {
            ModalProcedimentos.adicionar();
        }
    };
</script>

<style>
    .form-control.disabled,
    .form-control[disabled],
    .form-control[readonly] {
        background-color: #e9ecef;
        opacity: 1;
        pointer-events: none;
    }
</style>
