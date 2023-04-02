<div>
    <div class="card-header">
        <div wire:ignore class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">

            <div class="col-md-6" style="padding-left:0px;">
                <input type="text" class="form-control" name="paciente" id="paciente"
                    placeholder="Pesquise o paciente por nome" value="{{ $busca }}">
            </div>

            <div class="col-md-3" style="padding-left:0px;">
                <select id="status_triagem" style="width: 100%" class="form-control">
                    @foreach ($status_triagem as $id => $status)
                        <option value="{{ $id }}" @if (($id == $status_triagem_id && !empty($status_triagem_id)) || ($id == 0 && empty($status_triagem_id))) selected @endif>
                            {{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3" style="padding-left:0px;">
                <select id="status_chamado" style="width: 100%" class="form-control">
                    @foreach ($status_chamado as $id => $status)
                        <option value="{{ $id }}" @if (($id == $status_chamado_id && !empty($status_chamado_id)) || ($id == 0 && empty($status_chamado_id))) selected @endif>
                            {{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 pt-3" style="padding-left:0px;">
                <select id="filas" style="width: 100%" class="form-control">
                    <option value=""></option>
                    @foreach ($filas_triagem as $fila)
                        <option value="{{ $fila->id }}" @if ($fila->id == $filas_triagem_id) selected @endif>
                            {{ $fila->descricao }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-sm-4" style="padding-left:0px;padding-top:15px;">
                <select id="faixa_idade" style="width: 100%" class="form-control">
                    @foreach ($faixas_idade as $idade => $texto)
                        <option value="{{ $idade }}" @if ($idade == $faixas_idade_selecionada) selected @endif>
                            {{ $texto }}</option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-3" style="padding-left:0px;padding-top:15px;">

                <div class="btn-group " role="group">
                    <button type="button" class="btn btn-default" data-action="toggle-datepicker" data-toggle="tooltip"
                        title="Escolher período">
                        <i class="fa fa-fw fa-calendar"></i>
                    </button>
                    <button type="button" class="btn btn-default" data-change-agenda="previous" title="Anterior">
                        <i class="mdi mdi-arrow-left-bold"></i>
                    </button>
                    <input type="text" class="datepicker form-control bg-white" readonly value="{{ $data }}">
                    <button type="button" class="btn btn-default" data-change-agenda="next" title="Próximo">
                        <i class="mdi mdi-arrow-right-bold"></i>
                    </button>
                </div>

            </div>


            <div style="padding-left:0px;padding-top:15px;">
                <div class="btn-group">
                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-action="refresh"
                        title="Atualizar">
                        <span class="mdi mdi-refresh"></span>
                    </button>
                    <button data-toggle="modal" data-toggle="tooltip" title="Ajuda" data-target="#modalHelp"
                        type="button" class="btn btn-default" title="Legenda">
                        <span class="fas fa-question"></span>
                    </button>
                </div>

            </div>

            <div class="col d-flex align-items-end">
                <div class="pt-3 pr-2 d-flex align-items-center" style="height: 100%">
                    <label for="tipo_ordenacao" class="m-0" style="width: max-content">Ordenar por:</label>
                </div>
                <select id="tipo_ordenacao" style="width: 100%" class="form-control">
                    @foreach ($tipos_ordenacao as $tipo => $texto)
                        <option value="{{ $tipo }}" @if ($tipo == $tipo_ordenacao_selecionado) selected @endif>
                            {{ $texto }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    {{-- CORPO DA LISTA COM OS RESULTADOS --}}
    <div wire:poll.10000ms class="card-body">

        <div wire:loading.class.remove="hidden" class='hidden' wire:loading.class="ui ui-vazio">
            <span style='font-size: 100px;' class="mdi mdi-refresh"></span>
        </div>


        <div wire:loading.remove class="agenda_dia">
            @if ($resultados->count() > 0)
                @foreach ($resultados as $senha)
                    <div class="agenda_horario_row">
                        <div class="agenda_horario">
                            <strong>{{ \Carbon\Carbon::parse($senha->horario_retirada)->format('H:i') }}</strong>
                        </div>
                        <div class="senha-atendimento">
                            @php
                                $status = $senha->status;
                                $paciente_atendido = \App\ChamadaTotem::passouPor($senha, 'consultorio');
                                if ($paciente_atendido && $senha->atendimentoUrgencia) {
                                    $paciente = $senha->atendimentoUrgencia->paciente;
                                } else {
                                    $paciente = $senha->getPaciente();
                                }
                            @endphp
                            <div class="agendamento {{ $status->cor_status }}">
                                <div class="agendamento_col agendamento-icone ">{!! $status->emoji_status !!}</div>
                                <div class="agendamento_col_right agendamento-senha">
                                    <span style="font-weight:bold;">Fila: {{ $senha->filaTriagem->descricao }}</span>
                                    <span style="font-weight:bold;">Senha: {{ $senha->senha }}</span>
                                </div>
                                <div
                                    class="agendamento_col_right agendamento-status d-flex align-items-center agendamento-paciente">
                                    <span style="font-weight:bold;"> <i class="fa fa-user"></i></span>
                                    <span style="font-weight:bold;">
                                        @if (!empty($paciente))
                                            {{ strtoupper($paciente->nome) }}
                                        @endif
                                    </span>
                                    @if (!empty($paciente) && !empty($paciente->telefone1 ?? $paciente->telefone2))
                                        <span style="font-weight:bold;">-</span>
                                        <span style="font-weight:bold;"> <i class="fa fa-phone"></i></span>
                                        <span style="font-weight:bold;">
                                            {{ $paciente->telefone1 ?? $paciente->telefone2 }}
                                        </span>
                                    @endif
                                    @if (!empty($senha->classificacao))
                                        <div class="classificacao-pill border ml-2" data-toggle="tooltip"
                                            title="Classificado como {{ $senha->classificacao->descricao }}">
                                            <div class="pill-color"
                                                style="background-color: {{ $senha->classificacao->cor ?? 'transparent' }};">
                                            </div>
                                            <div class="pill-text text-dark">{{ $senha->classificacao->descricao }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="agendamento_col agendamento-status d-flex align-items-center pl-2">
                                    {!! $status->icone_status !!}
                                </div>
                                <div class="agendamento_col_right agendamento_actions">
                                    <div class="btn-group">
                                        @if (\Gate::check('habilidade_instituicao_sessao', 'chamar_pacientes_atendimentos_urgencia') && !$paciente_atendido)
                                            <button data-id="{{$senha->id}}" type="button"
                                                class="btn chamar_paciente" data-toggle="tooltip"
                                                title="Chamar paciente">
                                                <span class="mdi mdi-check"></span>
                                            </button>
                                        @else
                                            <span class="btn chamar_paciente disabled"><span
                                                    class="mdi mdi-check"></span></span>
                                        @endif

                                        @if (\Gate::check('habilidade_instituicao_sessao', 'iniciar_atendimentos_urgencia') && !$paciente_atendido)
                                            <button data-id="{{ $senha->id }}" type="button"
                                                class=" btn iniciar_atendimento" data-toggle="tooltip"
                                                title="Iniciar atendimento">
                                                <span class="fas fa-user"></span>
                                            </button>
                                        @else
                                            <span class="btn iniciar_atendimento disabled"><span
                                                    class="fas fa-user"></span></span>
                                        @endif

                                        @if (\Gate::check('habilidade_instituicao_sessao', 'visualizar_atendimentos_urgencia') && $paciente_atendido)
                                            <button data-id="{{ $senha->id }}" type="button"
                                                class=" btn visualizar_atendimento" data-toggle="tooltip"
                                                title="Visualizar atendimento">
                                                <span class="fas fa-eye"></span>
                                            </button>
                                        @else
                                            <span class="btn visualizar_atendimento disabled"><span
                                                    class="fas fa-eye"></span></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div wire:loading.remove class="ui ui-vazio">
                    <span style='font-size: 100px;' class="mdi mdi-calendar-remove"></span>
                    <p class="lead">Não existem pacientes triados neste dia!</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Inicio modal de iniciar atendimento --}}
<div id="modal-fade-background" style="display: none"></div>
<div wire:ignore id="modal-container" class="modal" style="display: none">
    <div id="modal-content"></div>
</div>
{{-- Fim modal de iniciar atendimento --}}

{{-- Inicio modal de ajuda (legenda) --}}
<div wire:ignore class="modal inmodal" id="modalHelp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Legenda</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>


            </div>
            <div class="modal-body">
                <p>
                    Para identificar a situação atual de determinado atendimento de urgência, diversos indicadores
                    visuais
                    são exibidos na sua tela, estes são divididos em cores e ícones onde as cores indicam local e ícones
                    indicam
                    o status neste local.
                </p>


                <table class="table table-bordered table-estado-agendamento">
                    <colgroup>
                        <col style="width: 50px">
                        <col style="width: 50px">
                        <col style="width: 50px">
                        <col style="width: auto">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Aguardando</th>
                            <th>Chamado</th>
                            <th>Atendido</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left" colspan="4"><strong>Recepção</strong></td>
                        </tr>
                        <tr>
                            <td class="agendamento status-2">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="fas fa-clock d-block"></span>
                                </div>
                            </td>
                            <td class="agendamento status-2">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="fa fa-bullhorn d-block"></span>
                                </div>
                            </td>
                            <td class="agendamento status-1">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="far fa-meh d-block"></span>
                                </div>
                            </td>
                            <td>
                                Paciente está na etapa da recepção, podendo estar aguardando, ter sido chamado ou
                                atendido na recepção.
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" colspan="4"><strong>Triagem</strong></td>
                        </tr>
                        <tr>
                            <td class="agendamento status-1">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="fas fa-clock d-block"></span>
                                </div>
                            </td>
                            <td class="agendamento status-1">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="fa fa-bullhorn d-block"></span>
                                </div>
                            </td>
                            <td class="agendamento status-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="far fa-smile d-block"></span>
                                </div>
                            </td>
                            <td>
                                Paciente está na etapa da triagem, podendo estar aguardando, ter sido chamado ou triado.
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" colspan="4"><strong>Consultório</strong></td>
                        </tr>
                        <tr>
                            <td class="agendamento status-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="fas fa-clock d-block"></span>
                                </div>
                            </td>
                            <td class="agendamento status-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="fa fa-bullhorn d-block"></span>
                                </div>
                            </td>
                            <td class="agendamento status-5">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="far fa-heart d-block"></span>
                                </div>
                            </td>
                            <td>
                                Paciente está na etapa do consultório, podendo estar aguardando, ter sido chamado ou
                                atendido.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Fimd modal de ajuda (legenda) --}}

{{-- Inicio modal de senhas --}}
<div wire:ignore id="modal-senhas-container" class="modal" style="display: none; width: 100%; height: 100vh; z-index: 1080">
    <div class="modal-content">
        <div class="card">
            <div class="card-header">
                <h4>Chamar paciente para guiche?</h4>
            </div>
            <div class="card-body">
                <input type="hidden" id="modal-senha-id">
                <div class="form-group">
                    <label for="modal-guiche-id">Identificação do guichê <span class="text-danger">*</span></label>
                    <input type="text" id="modal-guiche-id" class="form-control">
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="cancelar-senha btn btn-secondary mr-2">Cancelar</button>
                <button class="chamar-senha btn btn-success">Chamar</button>
            </div>
        </div>
    </div>
</div>
{{-- Fim do modal de senhas --}}
@push('scripts')
    <script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>

    <script>
        // Modal de senhas
        const Senhas = {
            fecharModal() {
                $('#modal-senhas-container, #modal-fade-background').hide();
            },

            abrirModal(id) {
                $('#modal-senhas-container, #modal-fade-background').show();
                $('#modal-guiche-id').val(null);
                $('#modal-senha-id').val(id);
            },

            chamarGuiche() {
                const senha = $('#modal-senha-id').val();
                const local = $('#modal-guiche-id').val();
                $.ajax("{{ route('instituicao.totens.paineis.chamar') }}", {
                    method: "POST",
                    data: {
                        senha: senha,
                        origem: 'consultorio',
                        local: local,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        $.toast({
                            heading: 'Sucesso',
                            text: 'Senha chamada com sucesso!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000,
                            stack: 10
                        });
                        @this.call('render');
                    },
                    error: function(response) {
                        if (response.responseJSON.errors) {
                            Object.keys(response.responseJSON.errors).forEach(
                                function(key) {
                                    $.toast({
                                        heading: 'Erro',
                                        text: response.responseJSON
                                            .errors[key][0],
                                        position: 'top-right',
                                        loaderBg: '#ff6849',
                                        icon: 'error',
                                        hideAfter: 9000,
                                        stack: 10
                                    });

                                });
                        }
                    }
                }).then(() => {
                    this.fecharModal();
                });
            }
        };

        document.addEventListener("DOMContentLoaded", () => {
            window.livewire.hook('afterDomUpdate', () => {
                $('[data-toggle="tooltip"]').tooltip('dispose').tooltip();
            });
        });

        function callRenderPage() {
            @this.call('render');
        }

        // Método glabal para fechar o modal
        window.closeGenericModal = function() {
            $('#modal-container, #modal-fade-background').hide()
        }

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            $('.cancelar-senha').on('click', () => Senhas.fecharModal());
            $('.chamar-senha').on('click', () => Senhas.chamarGuiche());
            $('.chamar_paciente').on('click', (e) => {
                let id = $(e.target).attr("data-id");
                if(!id) {
                    id = $($(e.target).parents('button')[0]).attr('data-id');
                }
                Senhas.abrirModal(id);
            });

            @this.on('reset_icheck', function() {
                $('input[data-name="horario_vazio"]').iCheck('check');
                $('input[data-name="horario_disponivel"]').iCheck('check');
                $('input[data-name="horario_ausente"]').iCheck('check');
            })

            $('body').on('click', '[data-action="refresh"]', function(e) {
                @this.call('render');
            })

            // Montar modal genérico para cadastro

            $('body').on('click', '.iniciar_atendimento', function(e) {
                e.stopPropagation();
                $id = $(this).data('id');
                $('#modal-content').html('');

                Swal.fire({
                    title: "Confirmar!",
                    text: 'Deseja iniciar o atendimento ?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não, cancelar!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if (result.value) {
                        $.ajax("{{ route('instituicao.atendimentos-urgencia.modal-atendimento') }}", {
                            method: "POST",
                            data: {
                                senha: $id,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $('#modal-content').html(response)
                                $('#modal-container, #modal-fade-background').show()
                            },
                            error: function(response) {
                                $.toast({
                                    heading: 'Erro',
                                    text: 'Não foi possível executar esta ação, tente novamente mais tarde',
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 9000,
                                    stack: 10
                                });
                            }
                        }).then(() => {
                            if (window.__modal_ready) {
                                window.__modal_ready();
                            }
                        });
                    }
                });
            });

            // Montar modal genérico para visualização

            $('body').on('click', '.visualizar_atendimento', function(e) {
                e.stopPropagation();
                $id = $(this).data('id');
                $('#modal-content').html('');
                $.ajax("{{ route('instituicao.atendimentos-urgencia.modal-atendimento-visualizar') }}", {
                    method: "GET",
                    data: {
                        senha: $id
                    },
                    success: function(response) {
                        $('#modal-content').html(response)
                        $('#modal-container').show()
                        $('#modal-fade-background').show()
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Erro',
                            text: 'Não foi possível executar esta ação, tente novamente mais tarde',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 9000,
                            stack: 10
                        });
                    }
                }).then(() => {
                    if (window.__modal_ready) {
                        window.__modal_ready();
                    }
                });
            });

            // Fechar modal genérico quando clicar fora
            $('body').on('click', '#modal-fade-background', window.closeGenericModal)

            $('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            }).on('ifChanged', function(e) {
                if ($(this).data('name') == 'horario_vazio') {
                    if (e.target.checked == false) {
                        $('.vazio_passado').closest('.agenda_horario_row').css('display', 'none')
                    } else {
                        $('.vazio_passado').closest('.agenda_horario_row').css('display', 'flex')
                    }
                } else if ($(this).data('name') == 'horario_disponivel') {
                    if (e.target.checked == false) {
                        $('.horario_disponivel').closest('.agenda_horario_row').css('display', 'none')
                    } else {
                        $('.horario_disponivel').closest('.agenda_horario_row').css('display', 'flex')
                    }
                } else if ($(this).data('name') == 'horario_ausente') {
                    if (e.target.checked == false) {
                        $('.horario_cancelado').closest('.agenda_horario_row').css('display', 'none')
                    } else {
                        $('.horario_cancelado').closest('.agenda_horario_row').css('display', 'flex')
                    }
                }
            });

            // #region NEW

            // CHAMADA DE PACIENTE PARA ATENDIMENTO
            // $('body').on('click', '.chamar_paciente', function(e) {
            //     $id = $(this).data('id');
            //     e.stopPropagation();
            //     Swal.fire({
            //         title: "Confirmar!",
            //         text: 'Deseja chamar o paciente ?',
            //         icon: "warning",
            //         showCancelButton: true,
            //         confirmButtonColor: "#DD6B55",
            //         cancelButtonText: "Não, cancelar!",
            //         confirmButtonText: "Sim, confirmar!",
            //     }).then(function(result) {
            //         if (result.value) {
            //             $.ajax("{{ route('instituicao.ajax.chamar-paciente') }}", {
            //                 method: "POST",
            //                 data: {
            //                     id: $id,
            //                     '_token': '{{ csrf_token() }}'
            //                 },
            //                 success: function(response) {

            //                     $.toast({
            //                         heading: response.title,
            //                         text: response.text,
            //                         position: 'top-right',
            //                         loaderBg: '#ff6849',
            //                         icon: response.icon,
            //                         hideAfter: 3000,
            //                         stack: 10
            //                     });

            //                     if (response.icon == 'success') {

            //                         @this.call('render');
            //                     }

            //                 },
            //                 error: function(response) {
            //                     if (response.responseJSON.errors) {
            //                         Object.keys(response.responseJSON.errors).forEach(
            //                             function(key) {
            //                                 $.toast({
            //                                     heading: 'Erro',
            //                                     text: response.responseJSON
            //                                         .errors[key][0],
            //                                     position: 'top-right',
            //                                     loaderBg: '#ff6849',
            //                                     icon: 'error',
            //                                     hideAfter: 9000,
            //                                     stack: 10
            //                                 });

            //                             });
            //                     }
            //                 }
            //             })
            //         }
            //     });

            // });

            // Busca e escolha de pacientes

            $('#status_triagem').select2()
                .on('select2:select', function(e) {
                    var data = $('#status_triagem').select2("val");
                    @this.set('status_triagem_id', data);
                });

            $('#status_chamado').select2()
                .on('select2:select', function(e) {
                    var data = $('#status_chamado').select2("val");
                    @this.set('status_chamado_id', data);
                });

            let paciente_input_thread = null;
            $('#paciente').on('keyup', (e) => {
                if (paciente_input_thread) {
                    clearTimeout(paciente_input_thread);
                }
                paciente_input_thread = setTimeout(() => {
                    @this.set('busca', $('#paciente').val());
                }, 1000);
            });

            $('#filas').select2({
                placeholder: "Selecione uma fila",
                allowClear: true,
                language: {
                    searching: function() {
                        return 'Buscando resultados';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },
                },
            }).on('select2:select', function(e) {
                var data = $('#filas').select2("val");
                @this.set('filas_triagem_id', data);
            }).on('select2:unselecting', function(e) {
                @this.set('filas_triagem_id', null);
            });

            $('#faixa_idade').select2({
                placeholder: "Selecione uma faixa etária",
                language: {
                    searching: function() {
                        return 'Buscando resultados';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },
                },
            }).on('select2:select', function(e) {
                var data = $('#faixa_idade').select2("val");
                @this.set('faixas_idade_selecionada', data);
            });

            $('#tipo_ordenacao').on('change', (e) => {
                @this.set('tipo_ordenacao_selecionado', $(e.target).val());
            });

            // #endregion


            function formatState(item) {
                if (!item.id) {
                    return 'Selecione um médico';
                }
                opt = $(item.element);
                og = opt.closest('optgroup').attr('label');
                return $('<span><strong>' + og + '</strong>' + ' : ' + item.text + '</span>');
            };

            function formatState2(item) {
                if (!item.id) {
                    return 'Selecione um procedimento';
                }
                opt = $(item.element);
                og = opt.closest('optgroup').attr('label');
                return $('<span><strong>' + og + '</strong>' + ' : ' + item.text + '</span>');
            };

            $(".datepicker").datepicker({
                closeText: 'Fechar',
                prevText: '<Anterior',
                nextText: 'Próximo>',
                currentText: 'Hoje',
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
                    'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'
                ],
                dayNames: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira',
                    'Sexta-feira', 'Sabado'
                ],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 0,
                onSelect: function() {
                    $(this).attr('value', this.value);
                    @this.set('data', this.value);
                }
            })

            previousPeriodo = () => {
                date = new Date($(".datepicker").datepicker("getDate"))
                date.setDate(date.getDate() - 1);
                $(".datepicker").datepicker("setDate", date);
                @this.set('data', date.toLocaleDateString("pt-BR"));
            }

            nextPeriodo = () => {
                date = new Date($(".datepicker").datepicker("getDate"))
                date.setDate(date.getDate() + 1);
                $(".datepicker").datepicker("setDate", date);
                @this.set('data', date.toLocaleDateString("pt-BR"));
            }

            $('body').on('click', '[data-action="toggle-datepicker"]', function(e) {
                $(".datepicker").datepicker('show')
            })

            // $('#horario_disponivel').on('click',function(e){
            //     @this.set('horario_disponivel', !$(e.currentTarget).hasClass('active'));
            // })


            // $('#horario_agendado').on('click',function(e){

            //     @this.set('horario_agendado', !$(e.currentTarget).hasClass('active'));
            // })



            $('body').on('click', '[data-change-agenda]', function(e) {
                switch ($(e.currentTarget).data('changeAgenda')) {
                    case 'previous':
                        previousPeriodo();
                        break;
                    case 'next':
                        nextPeriodo();
                        break;
                }
                e.stopPropagation();
            })
        })
    </script>
@endpush

@push('estilos')
    <style>
        #modal-senhas-container {
            position: fixed;
            left: 50%;
            top: 1rem;
            transform: translateX(-50%);
            z-index: 1080;
            max-height: calc(100vh - 2rem);
            max-width: calc(95vw - 1rem);
            width: max-content;
            height: max-content;
            overflow-y: auto;
        }
        #modal-senhas-container .modal-content {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: unset;
        }
        .classificacao-pill {
            display: flex;
            align-items: center;
            background: white;
            height: max-content;
            line-height: 1.75em;
            font-size: 1em;
            width: max-content;
            overflow: hidden;
            border-radius: 5px;
            padding-right: 0.5rem;
            position: relative;
        }

        .classificacao-pill .pill-color {
            padding: 1em 0.5rem;
            height: inherit;
            margin-right: 0.5rem;
        }

        .table-estado-agendamento th {
            font-weight: normal !important;
            border-right: 1px solid #e7eaec !important;
        }

        .table-estado-agendamento .agendamento {
            border-radius: unset !important;
            border-right: unset !important;
            font-size: 1.5em !important;
            vertical-align: middle;
        }

        .agenda_dia .agendamento .agendamento-icone * {
            font-size: 16px !important;
        }

        #modal-container {
            position: fixed;
            left: 50%;
            top: 1rem;
            transform: translateX(-50%);
            z-index: 1080;
            max-height: calc(100vh - 2rem);
            max-width: calc(95vw - 1rem);
            width: max-content;
            height: max-content;
            overflow-y: auto;
        }

        #modal-fade-background {
            width: 100vw;
            height: 100vh;
            position: fixed;
            z-index: 1079;
            background: rgba(0, 0, 0, 0.2);
            top: 0;
            left: 0;
        }

        #modal-content {
            max-width: inherit;
            height: max-content;
        }

        .telefone_display {
            background: none;
            border: none;
            display: inline;
            color: inherit;
            font: inherit;
        }

        .scrollable {
            overflow-y: scroll;
            margin-bottom: 10px;
            max-height: 600px;
        }

        .noWrap {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .agendamento .btn {

            background-color: inherit;
            color: inherit;
            border: inherit;

        }

        /* .agendamento .btn:hover, .agendamento .btn:focus, .agendamento .btn:active {
                box-shadow: none;
                background-color: rgba(255, 255, 255, .5) !important;
                color: #fff;
                } */

        .agendamento .btn:hover,
        .agendamento .btn:focus {
            box-shadow: none;
            background-color: rgba(255, 255, 255, .43);
        }

        .agendamento .agendamento_col,
        .agendamento .agendamento_col_right,
            {
            padding: 5px;
            font-size: 12px;
            padding-top: 0;
            padding-bottom: 0;
        }

        .agendamento .agendamento-senha {
            flex-basis: 40%;
        }

        .agendamento .agendamento-senha>* {
            padding-right: 0.75rem;
        }

        .agendamento .agendamento-status {
            flex-basis: 20%;
        }

        .agendamento .agendamento-icone .fa,
        .agendamento .agendamento-icone .far,
        .agendamento .agendamento-icone .fas {
            font-size: 20px;
            vertical-align: middle;

        }

        .agendamento .agendamento-icone {
            text-align: center;
            flex-basis: 5%;
        }

        .agendamento .agendamento-paciente {
            flex-basis: 40%;
        }

        .agendamento .agendamento_actions {
            flex-basis: 15%;
            text-align: right;
        }

        .agendamento .agendamento_actions .btn-group .disabled {
            pointer-events: none;
        }

        .agendamento.status-0 {
            background-color: #cc0404;
            border-color: #cc0404;
            color: white;
        }

        .agendamento.status-1 {
            background-color: #26c6da;
            border-color: #26c6da;
            color: #1b5c64;
        }

        .agendamento.status-2 {
            background-color: #78909C;
            border-color: #78909C;
            color: #fff;
        }

        .agendamento.status-3 {
            background-color: #009688;
            border-color: #009688;
            color: #fff;
        }

        .agendamento.status-4 {
            background-color: #ffcf8e;
            border-color: #ffcf8e;
            color: #81653f;
        }

        .agendamento.status-5 {
            background-color: #745af2;
            border-color: #745af2;
            color: #fff;
        }

        .agendamento.status-6 {
            background-color: #26c6da;
            border-color: #26c6da;
            color: #1b5c64;
        }


        .table>thead>tr>th,
        .table>tbody>tr>th,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>tbody>tr>td,
        .table>tfoot>tr>td {
            border-top: 1px solid #e7eaec;
            line-height: 1.42857;
            padding: 8px;
            vertical-align: top;
        }

        .table-estado-agendamento td:first-child {
            width: 45px;
            font-size: 2em;
            text-align: center;
            vertical-align: middle;
        }

        .table-estado-agendamento td:last-child {
            font-size: 13px;
            line-height: 1.5;
        }

        .hidden {
            display: none;
        }

        .ui-vazio .fa {
            font-size: 96px;
        }

        .btn-group label {
            color: #000 !important;
            margin-bottom: 0px;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #fff;
            opacity: 1;
            pointer-events: none;
        }

        .datepicker {
            border-radius: 0px;
            text-align: center;
        }

        .datepicker:focus {
            border-color: #ced4da;
            box-shadow: none;
        }

        .btn-default {
            color: inherit;
            background: white;
            border: 1px solid #ced4da;
        }

        .btn-default:hover,
        .btn-default:focus,
        .open .dropdown-toggle.btn-default {
            color: inherit;
            border: 1px solid #d2d2d2;
            box-shadow: none;
        }


        .btn-default:hover:focus {
            background: inherit;
        }

        .btn-default:hover {

            background-color: #e6e6e6;
        }


        .btn-default:active,
        .btn-default.active,
        .open .dropdown-toggle.btn-default {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15) inset;
            background-color: inherit;
        }

        .card .card-header {
            background: #ffffff;
            border-bottom: 1px solid #0000001a;
        }

        .ui {
            padding-top: 100px;
            padding-bottom: 100px;
            text-align: center !important;
        }

        .agenda_horario_row {
            display: flex;
            padding-top: 2.5px;
            padding-bottom: 2.5px;

        }

        .agenda_horario_row+.agenda_horario_row {
            border-top: 1px solid #dfdfdf;
        }

        .agenda_horario_row .agenda_horario.horario_passado {
            color: #ccc;
        }

        .agenda_horario_row .agenda_horario {
            flex: 0 0 45px;
            align-self: center;
            line-height: 30px;
            vertical-align: middle;
            text-align: center;
        }

        .agenda_horario_row .agendamento {
            display: flex;
            max-width: 100%;
            border-radius: 5px;
            margin-bottom: 2px;
        }



        .senha-atendimento {
            flex-grow: 1;
        }

        .agendamento {
            border-radius: 5px;
            margin-bottom: 2px;
        }

        .agendamento .agendamento_col,
        .agendamento .agendamento_col_right {
            vertical-align: middle;
            font-size: 12px;
            line-height: 35px;
        }

        .agendamento .agendamento_col_right {
            display: flex;
        }

        .agendamento .agendamento_col_right.agendamento-paciente>* {
            margin-right: 0.5rem;
        }

        .agendamento .agendamento_col_right.agendamento-paciente>* i {
            margin-left: 0.25rem;
        }

        .agendamento .agendamento_texto {
            padding-left: 30px;
            flex-basis: 95%;
        }

        .agendamento.agendamento_empty {
            background-color: #eaeaea;
            color: #aaa;
        }

        .agendamento.agendamento_past {
            background-color: #f9f9f9;
            color: #aaa;
        }




        .agendamento_texto {
            font-style: italic;
        }

        .agendamento.agendamento_intervalo {
            background-color: #616161;
            border-color: #616161;
            color: #fff;
        }

        .agenda_horario_row.is_current {
            border-radius: 5px;
            background-color: rgba(47, 64, 80, .15);
        }
    </style>
@endpush
