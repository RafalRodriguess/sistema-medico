<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="input-group col-md-10 pr-0">
                <div class="col p-0">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa"
                        class="form-control" placeholder="Pesquise por paciente">
                    <a href="{{ route('instituicao.locais-entrega-exames.index') }}" class="btn btn-inset"><i
                            class="fas fa-times"></i></a>
                </div>

                <div class="input-group-append">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-info"><i
                            class="fas fa-search"></i></button>
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_locais_entrega_exames')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.locais-entrega-exames.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <div wire:poll.10000ms class="table-container">
        <table class="tablesaw table-bordered table-hover table">
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Descrição</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($locais as $local)
                    <tr>
                        <td>{{ $local->id }}</td>
                        <td>{{ $local->descricao }}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'editar_locais_entrega_exames')
                                <a href="{{ route('instituicao.locais-entrega-exames.edit', [$local]) }}"
                                    class="btn btn-sm btn-secondary iniciar-triagem-button" aria-haspopup="true"
                                    aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                    data-original-title="Editar local de entrega">
                                    <i class="ti-pencil-alt"></i>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'excluir_locais_entrega_exames')
                                <form class="deletar-triagem-form d-inline"
                                    action="{{ route('instituicao.locais-entrega-exames.destroy', [$local]) }}" method="post"
                                    class="d-inline form-excluir-registro">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary" aria-haspopup="true"
                                        aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                        data-original-title="Excluir">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div wire:ignore style="float: right">
        {{ $locais->links() }}
    </div>
</div>
@push('scripts')
    <script>
        function prepare() {
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
        }

        document.addEventListener("DOMContentLoaded", () => {
            window.livewire.hook('afterDomUpdate', () => {
                $('[data-toggle="tooltip"]').tooltip('dispose').tooltip();
                prepare();
            });
        });

        $(document).ready(() => prepare());
    </script>
@endpush
