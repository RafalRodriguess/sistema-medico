<div>
    <div class="card-header">
        <div class="row" id="formTitular">
            <div class="col-6 form-group">
                <div class="input-group">
                    <input type="text" id="pesquisa" class="form-control" wire:model.lazy="pesquisa" name="pesquisa"
                        placeholder="Pesquise por paciente">
                </div>

            </div>

            <div class="col-md-3 form-group">
                <select name="fila_triagem_id" style="width: 100%" class="form-control required-select">
                    <option value="" @if (empty($fila_triagem_id)) selected @endif>Todas as filas</option>
                    @foreach ($filas_triagem as $fila)
                        <option value="{{ $fila->id }}" @if ($fila->id == $fila_triagem_id) selected @endif>
                            ({{ $fila->identificador }})
                            {{ $fila->descricao }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 form-group">
                <select id="filtro-origem-select" name="origem_id" style="width: 100%"
                    class="form-control optional-select" aria-placeholder="Filtre por origem">
                    <option value=""></option>
                </select>
            </div>

            <div class="col-md-3">

                <div class="btn-group " role="group">
                    <button type="button" class="btn btn-default" data-action="toggle-datepicker"
                        title="Escolher período">
                        <i class="fa fa-fw fa-calendar"></i>
                    </button>
                    <button type="button" class="btn btn-default" data-change-agenda="previous" title="Anterior">
                        <i class="mdi mdi-arrow-left-bold"></i>
                    </button>
                    <input type="text" class="datepicker form-control" readonly value="{{ $data }}">
                    <button type="button" class="btn btn-default" data-change-agenda="next" title="Próximo">
                        <i class="mdi mdi-arrow-right-bold"></i>
                    </button>
                </div>

            </div>

            <div class="col d-flex align-items-end justify-content-end">
                <div class="d-flex">
                    <div class="pr-2 d-flex align-items-center">
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
    </div>

    {{-- CORPO DA LISTA COM OS RESULTADOS --}}
    <div wire:poll.30000ms class="card-body">

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
                                $paciente_atendido = \App\ChamadaTotem::passouPor($senha, 'triagem');
                                if ($paciente_atendido && $senha->atendimentoUrgencia) {
                                    $paciente = $senha->atendimentoUrgencia->paciente;
                                } else {
                                    $paciente = $senha->getPaciente();
                                }
                            @endphp
                            <div class="agendamento {{ $paciente_atendido ? 'status-5' : 'status-3' }}">
                                <div class="agendamento_col agendamento-icone ">
                                    @switch($senha->status->etapa)
                                        @case(0)
                                            <span class="iniciar-triagem-button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="Sendo atendido na recepção"><i
                                                    class="fas fa-tag"></i></span>
                                        @break

                                        @case(1)
                                            <span class="iniciar-triagem-button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="Chamado para triagem"><i class="fas fa-clock"></i></span>
                                        @break

                                        @default
                                            <span class="iniciar-triagem-button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="Triagem completa"><i class="fas fa-check"></i></span>
                                    @endswitch
                                </div>
                                <div class="agendamento_col_right agendamento-senha">
                                    <span style="font-weight:bold;">Fila: {{ $senha->filaTriagem->descricao }}</span>
                                    <span style="font-weight:bold;">Senha: {{ $senha->senha }}</span>
                                </div>
                                <div
                                    class="agendamento_col_right agendamento-paciente agendamento-status d-flex align-items-center">
                                    <span style="font-weight:bold;"> <i class="fa fa-user"></i></span>
                                    <span style="font-weight:bold;">
                                        @if (!empty($paciente))
                                            {{ strtoupper($paciente->nome) }}
                                        @endif
                                    </span>
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
                                    @if ($senha->filaTriagem->prioridade == 1)
                                        <span data-toggle="tooltip" title="Fila com prioridade" class="mx-1"><i
                                                class="fas fa-exclamation"></i></span>
                                    @endif
                                </div>
                                <div class="agendamento_col_right agendamento_actions">
                                    <div class="btn-group">
                                        @if (!$paciente_atendido)
                                            @if (!\App\ChamadaTotem::passouPor($senha, 'guiche') && !$senha->chamado)
                                                <button onclick="abrirModal('{{$senha->id}}')" type="button"
                                                    class="btn chamar_paciente" data-toggle="tooltip"
                                                    title="Chamar na recepção">
                                                    <span class="mdi mdi-check"></span>
                                                </button>
                                            @else
                                                <span class="btn disabled"><span
                                                        class="mdi mdi-check"></span></span>
                                            @endif
                                            @can('habilidade_instituicao_sessao', 'editar_triagens')
                                                <button href="{{ route('instituicao.triagens.edit', [$senha]) }}"
                                                    type="button" class="btn iniciar-triagem-button"
                                                    aria-haspopup="true" aria-expanded="false" data-toggle="tooltip"
                                                    data-placement="top" data-original-title="Iniciar triagem">
                                                    <i class="mdi mdi-stethoscope"></i>
                                                </button>
                                            @endcan
                                            <button class="btn" disabled><i class="ti-eye"></i></button>
                                            <button class="btn" disabled><i class="ti-trash"></i></button>
                                        @else
                                            <span class="btn disabled"><span
                                                    class="mdi mdi-check"></span></span>
                                            <button class="btn" disabled><i
                                                    class="mdi mdi-stethoscope"></i></button>
                                            @can('habilidade_instituicao_sessao', 'visualizar_triagens')
                                                <a class="btn"
                                                    href="{{ route('instituicao.triagens.show', [$senha]) }}"
                                                    aria-haspopup="true" aria-expanded="false" data-toggle="tooltip"
                                                    data-placement="top" data-original-title="Visualizar triagem">
                                                    <i class="ti-eye"></i>
                                                </a>
                                            @endcan
                                            @can('habilidade_instituicao_sessao', 'excluir_triagens')
                                                <form class="deletar-triagem-form d-inline"
                                                    action="{{ route('instituicao.triagens.destroy', [$senha]) }}"
                                                    method="post" class="d-inline form-excluir-registro">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="btn" aria-haspopup="true"
                                                        aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                                        data-original-title="Excluir">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
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

{{-- Inicio modal de chamar senha --}}
<div id="modal-fade-background" style="display: none"></div>
<div wire:ignore id="modal-container" class="modal" style="display: none; width: 100%; height: 100vh;">
    <div id="modal-content">
        <div class="card">
            <div class="card-header">
                <h4>Chamar paciente no guinchê?</h4>
            </div>
            <div class="card-body">
                <input type="hidden" id="modal-senha-id">
                <div class="form-group">
                    <label for="modal-guiche-id">Guinchê (Opcional)</label>
                    <input type="text" id="modal-guiche-id" class="form-control">
                </div>
            </div>
            <div class="card-footer text-right">
                <button onclick="fecharModal()" class="btn btn-secondary mr-2">Cancelar</button>
                <button onclick="chamarGuiche()" class="btn btn-success">Chamar</button>
            </div>
        </div>
    </div>
</div>
{{-- Fim modal de chamar senha --}}
@push('scripts')
    <script>
        function fecharModal() {
            $('#modal-container, #modal-fade-background').hide();
        }

        function abrirModal(id) {
            $('#modal-container, #modal-fade-background').show();
            $('#modal-guiche-id').val(null);
            $('#modal-senha-id').val(id);
        }

        function chamarGuiche() {
            const senha = $('#modal-senha-id').val();
            const local = $('#modal-guiche-id').val();
            $.ajax("{{ route('instituicao.totens.paineis.chamar') }}", {
                method: "POST",
                data: {
                    senha: senha,
                    origem: 'guiche',
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
                fecharModal();
            });
        }

        function prepare() {

            $('#FormTitular .optional-select').each((key, item) => {
                $(item).select2({
                    placeholder: $(item).attr('aria-placeholder'),
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
                    var data = $(item).select2("val");
                    @this.set($(item).attr('name'), data);
                }).on('select2:unselecting', function(e) {
                    @this.set($(item).attr('name'), null);
                });
            });

            $('#FormTitular .required-select').each((key, item) => {
                $(item).select2({
                    placeholder: $(item).attr('aria-placeholder'),
                    language: {
                        searching: function() {
                            return 'Buscando resultados';
                        },

                        noResults: function() {
                            return 'Nenhum resultado encontrado';
                        },
                    },
                }).on('select2:select', function(e) {
                    var data = $(item).select2("val");
                    @this.set($(item).attr('name'), data);
                });
            });

            let pesquisa_input_thread = null;
            $('#pesquisa').on('keyup', (e) => {
                if (pesquisa_input_thread) {
                    clearTimeout(pesquisa_input_thread);
                }
                pesquisa_input_thread = setTimeout(() => {
                    @this.set('pesquisa', $('#pesquisa').val());
                }, 500);
            });

            $('#tipo_ordenacao').on('change', (e) => {
                @this.set('tipo_ordenacao_selecionado', $(e.target).val());
            });

            $('#filtro-origem-select').select2({
                dropdownParent: $('#filtro-origem-select').parent(),
                placeholder: 'Filtre por origem',
                minimumInputLength: 3,
                language: {
                    searching: function() {
                        return 'Buscando resultados';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                },
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
            });

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
                dayNames: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira',
                    'Sabado'
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
            });

            $('.iniciar-triagem-button').on('click', (e) => {
                const element = $(e.target)
                const location = element.attr('href') ?? $(element.parents('button')[0]).attr('href');
                Swal.fire({
                    title: "Confirmar!",
                    text: 'Deseja chamar o paciente e iniciar a triagem?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Não, cancelar!",
                    confirmButtonText: "Sim, confirmar!",
                }).then(function(result) {
                    if (result.value) {
                        document.location = location;
                    }
                });
            });

            $('.deletar-triagem-form').on('submit', (e) => {
                let form = $(e.target);
                if (!form.attr('__trg_submited')) {
                    e.preventDefault();

                    Swal.fire({
                        title: "Confirmar!",
                        text: 'Deseja deletar a triagem?',
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        cancelButtonText: "Não, cancelar!",
                        confirmButtonText: "Sim, confirmar!",
                    }).then(function(result) {
                        if (result.value) {
                            form.attr('__trg_submited', 1);
                            form.submit();
                        }
                    });
                }
            });

            $('#tipo_ordenacao').on('change', (e) => {
                @this.set('tipo_ordenacao_selecionado', $(e.target).val());
            });
        }

        function previousPeriodo() {
            date = new Date($(".datepicker").datepicker("getDate"))
            date.setDate(date.getDate() - 1);
            $(".datepicker").datepicker("setDate", date);
            @this.set('data', date.toLocaleDateString("pt-BR"));
        }

        function nextPeriodo() {
            date = new Date($(".datepicker").datepicker("getDate"))
            date.setDate(date.getDate() + 1);
            $(".datepicker").datepicker("setDate", date);
            @this.set('data', date.toLocaleDateString("pt-BR"));
        }

        $('body').on('click', '[data-action="toggle-datepicker"]', function(e) {
            $(".datepicker").datepicker('show')
        })

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

        document.addEventListener("DOMContentLoaded", () => {
            window.livewire.hook('afterDomUpdate', () => {
                $('[data-toggle="tooltip"]').tooltip('dispose').tooltip();
                prepare();
            });
        });

        $(document).ready(() => {
            prepare();
        })
    </script>
@endpush

@push('estilos')
    <style>
        #modal-content {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
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
