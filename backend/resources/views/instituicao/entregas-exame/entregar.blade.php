<div class="row col-12">
    <div class="form-group col-md-7">
        <label for="pessoa_id" class="form-label">Paciente<span class="text-danger">*</span></label>
        <select name="pessoa_id" id="pessoa_id" class="form-control">
            <option value=""></option>
        </select>
    </div>
    <div class="form-group col-md-5">
        <label for="local_entrega_id" class="form-label">Local de entrega<span class="text-danger">*</span></label>
        <select name="local_entrega_id" id="local_entrega_id" class="form-control" placeholder="Local de entrega">
            <option value=""></option>
            @foreach ($locais_entrega as $local_entrega)
                <option value="{{ $local_entrega->id }}"
                    @if ($local_entrega->id == old('local_entrega_id')) selected="selected" @endif>{{ $local_entrega->descricao }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="setor_exame_id" class="form-label">Setor<span class="text-danger">*</span></label>
        <select name="setor_exame_id" id="setor_exame_id" class="form-control" placeholder="Setor">
            <option value=""></option>
            @foreach ($setores as $setor)
                <option value="{{ $setor->id }}" @if ($setor->id == old('setor_id')) selected="selected" @endif>
                    {{ $setor->descricao }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="status" class="form-label">Situação<span class="text-danger">*</span></label>
        <select name="status" id="status_input" class="form-control" placeholder="Situação">
            @foreach ($statuses as $key => $status)
                <option value="{{ $key }}" @if ($key == old('status', -1)) selected="selected" @endif>
                    {{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-12">
        <label for="observacao" class="form-label">Observações</label>
        <textarea name="observacao" id="observacao" rows="4" class="form-control">{{ old('observacoes') }}</textarea>
    </div>
    <div class="form-group col-12 pt-3 mb-3" style="border: 1px dashed rgba(0,0,0,.1);border-width: 1px 0 0 0;">
        <h5 class="mb-3">Procedimentos:</h5>
        <div id="container-procedimentos">
        </div>
    </div>
</div>
<script type="text/template" id="template-convenio-procedimento" style="display: none">
    <div class="col-md-12 item-convenio-procedimento" el-id="#">
        <div class="row">
            <div class="form-group dados_parcela col-md-4">
                <label class="form-control-label">Convênio:</span></label>
                <select el-id="#"
                    class="form-control select-convenio selectfild2 convenio" style="width: 100%">
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group col-md-4 pr-0">
                <label class="form-control-label">Procedimento *</label>
                <select el-id="#" name="procedimentos[#]" id="convenio[#][procedimento_agenda]"
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
<script>
    var ultimo_id_procedimento = 0;
    var template_procedimentos = $('#template-convenio-procedimento');
    var container_procedimentos = $('#container-procedimentos');
    // Objeto que comanda o modal
    var ModalProcedimentos = {
        // Adiciona um procedimento extra
        adicionar: () => {
            const elemento = $(template_procedimentos.html().replaceAll('#', ultimo_id_procedimento));
            container_procedimentos.append(elemento);
            // Só exibe o botão de remover a partir do segundo procedimento
            if (ultimo_id_procedimento > 0) {
                elemento.find('.btnRemoverProcedimentos').removeClass('d-none');
            }

            // Inicializa o seletor de convênios
            elemento.find('.select-convenio').select2({
                placeholder: 'Selecione um convênio',
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
                const target = $(`.item-convenio-procedimento[el-id="${id}"]`).find('.procedimentos');
                if (element.val()) {
                    target.removeAttr('disabled');
                    target.val(null).trigger('change');
                } else {
                    target.attr('disabled', '');
                }
            });

            // Inicializa a busca de procedimentos
            elemento.find('.procedimentos').select2({
                placeholder: 'Selecione um procedimento',
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

            // Ação de adicionar procedimento
            elemento.find('.btnAddProcedimentos').on('click', (e) => {
                ModalProcedimentos.adicionar();
            });

            // Ação de remover procedimento
            elemento.find('.btnRemoverProcedimentos').on('click', (e) => {
                ModalProcedimentos.remover($(e.target).attr('el-id'));
            });
            ultimo_id_procedimento++;
        },
        // Remove um procedimento que não seja o primeiro
        remover: (id) => {
            if (id > 0) {
                $(`.item-convenio-procedimento[el-id="${id}"]`).remove();
            }
        }
    };

    window.__modal_ready = function() {
        $('#setor_exame_id, #local_entrega_id, #status_input').each((key, item) => {
            $(item).select2({
                placeholder: $(item).attr('placeholder')
            });
        });


        $("#pessoa_id").select2({
            placeholder: "Pesquise por nome do paciente",
            allowClear: true,
            minimumInputLength: 3,
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
            },
        });

        window.__modal_submit = (form) => {
            let data = form.serializeArray();
            
            $.ajax("{{ route('instituicao.entregas-exame.finalizar-entrega') }}", {
                method: 'POST',
                data: data,
                data_type: 'json',
                success: function(response) {
                    $.toast({
                        heading: 'Sucesso',
                        text: 'A entrega de exame registrada com sucesso',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000
                    });
                    // Fechando o modal
                    if(window.__modal_onsubmit) {
                        window.__modal_onsubmit();
                    }
                },
                error: function(response) {
                    if (response.responseJSON.errors) {
                        Object.keys(response.responseJSON.errors).forEach(function(key) {
                            $.toast({
                                heading: 'Erro',
                                text: response.responseJSON.errors[key][0],
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 9000
                            });

                        });
                    }
                }
            });
        };

        ModalProcedimentos.adicionar();
    }
</script>
