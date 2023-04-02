<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-12 form-group">
                <div class="input-group">
                    <div class="col p-0">
                        <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa"
                            class="form-control" placeholder="Pesquise por paciente">
                        <a href="{{ route('instituicao.faturamento-sus.bindings') }}" class="btn btn-inset"><i
                                class="fas fa-times"></i></a>
                    </div>

                    <div class="input-group-append">
                        <button type="submit" class="btn waves-effect waves-light btn-block btn-info"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <div class="table-container">
        <table class="tablesaw table-bordered table-hover table">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
                <col style="width: 0;">
            </colgroup>
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Procedimento interno</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1"
                        data-tablesaw-priority="persist">Código interno</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                        data-tablesaw-priority="3">Procedimento SUS</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($procedimentos as $procedimento)
                    <tr>
                        <td>{{ $procedimento->id }}</td>
                        <td>{{ $procedimento->descricao }}</td>
                        <td>{{ $procedimento->cod }}</td>
                        <td>
                            <form action="{{ route('instituicao.faturamento-sus.bind') }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id_procedimento" value="{{ $procedimento->id }}">
                                <select data-id="{{ $procedimento->id }}" name="id_sus" class="vinculo-select" style="width: 100%">
                                    @if (!empty($procedimento->procedimentoVinculado))
                                        <option value="{{ $procedimento->procedimentoVinculado->id }}">{{ $procedimento->procedimentoVinculado->CO_PROCEDIMENTO . ' - ' . $procedimento->procedimentoVinculado->NO_PROCEDIMENTO }}</option>
                                    @endif
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="status-slot-{{ $procedimento->id }}">
                                <span class="btn status-default text-primary" aria-haspopup="true"
                                aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                data-original-title="Procedimento com valor original salvo"><i class="fas fa-save"></i></span>
                                <span class="btn status-updated text-success" aria-haspopup="true"
                                aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                data-original-title="Procedimento atualizado" style="display: none"><i class="fas fa-check"></i></span>
                                <div class="spinner-border text-primary status-processing" role="status" style="width: 1rem; height: 1rem; display: none">
                                    <span class="sr-only">Processando...</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div wire:ignore style="float: right">
        {{ $procedimentos->links() }}
    </div>
</div>
@push('scripts')
    <script>
        function showStatus(procedimento, status)
        {
            const slot = $('.status-slot-'+procedimento);
            slot.children().each((key, item) => $(item).hide());
            switch(status) {
                case 0:
                    slot.find('.status-default').show();
                    $(`[data-id="${procedimento}"]`).val(null).trigger('change');
                    break;
                case 1:
                    slot.find('.status-processing').show();
                    break;
                case 2:
                    slot.find('.status-updated').show();
                    break;
            }
        }

        function atualizarVinculo(select) {
            const procedimento = select.attr('data-id');
            const form = $(select.parents('form')[0]);
            const formData = new FormData(form[0]);

            showStatus(procedimento, 1);

            $.ajax(form.attr('action'), {
                type: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Vínculo atualizado com sucesso',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 10
                    });

                    showStatus(procedimento, 2);
                },
                error: function (response) {
                    if(response.responseJSON && response.responseJSON.errors){
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
                    } else {
                        $.toast({
                            heading: 'Erro',
                            text: 'Não foi possível salvar a alteração tente novamente mais tarde!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 9000,
                            stack: 10
                        });
                    }

                    showStatus(procedimento, 0);
                }
            });
        }

        function prepare() {
            $('.vinculo-select').select2({
                placeholder: 'Selecione um procedimento',
                minimumInputLength: 3,
                allowClear: true,
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
                    url: "{{ route('instituicao.ajax.get-vinculos-sus') }}",
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
                            results: $.map(data.data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.CO_PROCEDIMENTO + ' - ' + obj.NO_PROCEDIMENTO
                                };
                            }),
                            pagination: {
                                more: data.next ? true : false
                            }
                        }
                    }
                },
                escapeMarkup: function(m) {
                    return m;
                }
            }).on('select2:select', (e) => atualizarVinculo($(e.target)))
            .on('select2:unselecting', (e) => atualizarVinculo($(e.target)));

            $('[data-toggle="tooltip"]').tooltip();
        }

        document.addEventListener("DOMContentLoaded", () => {
            window.livewire.hook('afterDomUpdate', () => {
                $('[data-toggle="tooltip"]').tooltip('dispose').tooltip();
                prepare();
            });
        });

        $(document).ready(() => {
            prepare();
        });
    </script>
@endpush
