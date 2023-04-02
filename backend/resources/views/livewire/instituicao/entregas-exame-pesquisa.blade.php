@push('estilos')
    <link rel="stylesheet" type="text/css" href="{{ asset('material/assets/plugins/daterangepicker/daterangepicker.css') }}" />
@endpush
<div wire:poll.30000ms class="position-relative">
    <div wire:loading.class.remove="d-none" class="d-none loading-overlay-simples" wire:loading.class="ui ui-vazio">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Carregando...</span>
        </div>
    </div>
    <div wire:loading.class.remove="opaque" class="opaque not-opaque card-body contents">
        <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                <div class="mx-auto col-md col-sm-10">
                    <div>
                        <div class="row">
                            <div class="input-group form-group col-12">
                                <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa"
                                    class="form-control" placeholder="Pesquise por paciente..."
                                    value="{{ $pesquisa }}">

                                <div class="input-group-append">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-info"><i
                                            class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-group">
                                <div class="form-group col-md-4">
                                    <select name="setor_id" id="setor_filter" class="form-control" placeholder="Setor">
                                        <option value="0">Todos os setores</option>
                                        @foreach ($setores as $setor)
                                            <option value="{{ $setor->id }}"
                                                @if ($setor->id == $setor_id) selected="selected" @endif>
                                                {{ $setor->descricao }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <select name="local_entrega_id" id="local_entrega_filter" class="form-control"
                                        placeholder="Local de entrega">
                                        <option value="0">Todos os locais</option>
                                        @foreach ($locais_entrega as $local_entrega)
                                            <option value="{{ $local_entrega->id }}"
                                                @if ($local_entrega->id == $local_entrega_id) selected="selected" @endif>
                                                {{ $local_entrega->descricao }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <select name="status" id="status_filter" class="form-control"
                                        placeholder="Situação">
                                        <option value="0">Qualquer situação</option>
                                        @foreach ($statuses as $id => $descricao)
                                            <option value="{{ $id }}"
                                                @if ($id == $status) selected="selected" @endif>
                                                {{ $descricao }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="form-group col-md-4 col-sm-6">
                                <input type="hidden" name="start" id="dataStart">
                                <input type="hidden" name="end" id="dataEnd">
                                <div id="reportrange"
                                    style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;border-radius: 0.25rem; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                            <div class="form-group px-3">
                                <button id="button-entregar" onclick="entregarExame()" type="button"
                                    class="btn waves-effect waves-light btn-block btn-info">Entregar exame</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <hr>
        <table class="tablesaw table-bordered table-hover table">
            <colgroup>
                <col style="width: auto">
                <col style="width: auto">
                <col style="width: 200px">
                <col style="width: 30%">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Situação</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                        data-tablesaw-priority="3">Data</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                        data-tablesaw-priority="3">
                        Paciente
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                        data-tablesaw-priority="3">
                        Descrição
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                        data-tablesaw-priority="3">
                        Local
                    </th>
                    {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                    Via Administração
                </th> --}}
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entregas as $entrega)
                    <tr>
                        <td>
                            <span
                                class="btn status-entrega status-entrega_{{ str_replace(' ', '-', strtolower(\App\EntregaExame::statuses[$entrega->status ?? 0])) }}">{{ \App\EntregaExame::statuses[$entrega->status ?? 0] }}</span>
                        </td>
                        <td>{{ (new DateTime($entrega->created_at))->format('d/m/Y') }}</td>
                        <td class="break-words">{{ $entrega->paciente->nome }}</td>
                        <td class="break-words">{{ mb_strimwidth($entrega->observacao, 0, 73, '...') }}</td>
                        <td class="break-words">{{ $entrega->localEntrega->descricao }}</td>
                        <td>
                            <div>
                                <button onclick="atualizarEntrega({{ $entrega->id }})"
                                    class="btn waves-effect waves-light btn-block btn-info" type="button"
                                    data-toggle="tooltip" data-placement="top"
                                    data-original-title="Alterar status da entrega">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div wire:ignore id="modal-cadastro" class="modal-generico" style="display: none">
    <div class="card border shadow-sm">
        <div class="card-header d-flex justify-content-end border-bottom p-2">
            <button type="button" onclick="$('#modal-cadastro').hide()" id="button-fechar-modal"
                class="btn btn-sm d-block"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body row" style="overflow-y: auto">
            <form id="form-modal" method="POST" class="row">
                @csrf
                <div id="conteudo-modal-cadastro" class="col-12"></div>
            </form>
        </div>
        <div class="card-footer">
            <div class="col-12 p-0 d-flex justify-content-end">
                <div class="d-flex">
                    <button type="button" class="__button-modal-close btn btn-danger mr-2">Cancelar</button>
                    <button type="button" target="modal-cadastro"
                        class="__button-modal-submit btn waves-effect waves-light btn-block btn-info">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('material/assets/plugins/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('material/assets/plugins/daterangepicker/daterangepicker.js') }}">
    </script>
    <script src="{{ asset('material/assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
    <script>
        const form_busca = $('#FormTitular')
        const modal = $('#modal-cadastro')

        function inicializarModal(url, data = {}) {
            $.ajax(url, {
                method: "GET",
                data: data,
            }).then((response) => {
                $('#conteudo-modal-cadastro').html(response);

                $('.__button-modal-close').on('click', function() {
                    modal.hide()
                });
                $('.__button-modal-submit').on('click', function(e) {
                    e.preventDefault()
                    window.__modal_submit($('#form-modal'));
                });
                modal.show();
                if (window.__modal_ready ?? false) {
                    window.__modal_ready();
                }
                window.__modal_onsubmit = () => {
                    modal.hide();
                    callRenderPage();
                };
            });
        }

        function entregarExame() {
            return inicializarModal("{{ route('instituicao.entregas-exame.entregar') }}")
        }

        function atualizarEntrega(id) {
            return inicializarModal("{{ route('instituicao.entregas-exame.atualizar') }}", {
                entrega: id
            });
        }

        const default_start = moment().startOf('month');
        const default_end = moment().endOf('month');
        // Codigo do seletor de período
        function loadData(start = null, end = null) {
            start = moment(start);
            end = moment(end);
            start = start._isValid ? start : default_start;
            end = end._isValid ? end : default_end;

            $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));

            $('.sk-spinner').parent().addClass('sk-loading');

            periodo = {
                "start": start.format('YYYY-MM-DD'),
                "end": end.format('YYYY-MM-DD'),
            }

            $("#dataStart").val(periodo.start).change();
            $("#dataEnd").val(periodo.end).change();

        }

        $('#reportrange').daterangepicker({
            startDate: default_start,
            endDate: default_end,
            opens: "left",
            locale: {
                "format": "DD/MM/YYYY",
                "applyLabel": "OK",
                "cancelLabel": "Cancelar",
                "fromLabel": "Início",
                "toLabel": "Fim",
                "customRangeLabel": "Definir datas",
                "daysOfWeek": [
                    "Dom",
                    "Seg",
                    "Ter",
                    "Qua",
                    "Qui",
                    "Sex",
                    "Sáb"
                ],
                "monthNames": [
                    "Janeiro",
                    "Fevereiro",
                    "Março",
                    "Abril",
                    "Maio",
                    "Junho",
                    "Julho",
                    "Agosto",
                    "Setembro",
                    "Outubro",
                    "Novembro",
                    "Dezembro"
                ],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov',
                    'Dez'
                ],
            },
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Ultimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mês': [moment().startOf('month'), moment().endOf('month')],
                'Último Mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            }
        }, loadData);

        $(document).ready(function() {
            $('#FormTitular select').each(function(key, item) {
                $(item).select2({
                    placeholder: $(item).attr('placeholder')
                })
            });
            $('#FormTitular select').on('select2:select', callRenderPage);
            window.livewire.hook('afterDomUpdate', onLivewireLoaded);
            const start = '{{ $start }}' ? '{{ $start }}' : null;
            const end = '{{ $end }}' ? '{{ $end }}' : null;
            loadData(start, end);
        });

        // Livewire
        function callRenderPage() {
            updateLivewire();
            @this.call('render');
        }

        function updateLivewire() {
            $('#FormTitular select, #FormTitular input').each((key, item) => {
                @this.set(item.name, $(item).val());
            });
        }

        function onLivewireLoaded(event) {
            $('#FormTitular select').each((key, item) => {
                $(item).select2({
                    placeholder: $(item).attr('placeholder'),
                });
            });
            loadData(event.data.start, event.data.end);
        }
    </script>
@endpush
