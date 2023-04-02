<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="mx-auto col-md col-sm-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <div class="input-group">
                        <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                            placeholder="Pesquise por componente...">
                    </div>
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_motivos_baixa')
                <div wire:ignore class="col-md-2">
                    <div class="form-group">
                        <a href="{{ route('instituicao.motivos-baixa.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>


    <table class="tablesaw table-bordered table-hover table">
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">descricao</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($motivos as $motivo)
                <tr>
                    <td>{{ $motivo->id }}</td>
                    <td>{{ $motivo->descricao }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_motivos_baixa')
                            <a href="{{ route('instituicao.motivos-baixa.edit', $motivo) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar registro">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_motivos_baixa')
                            <form action="{{ route('instituicao.motivos-baixa.destroy', $motivo) }}" method="post"
                                class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                    aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Excluir registro">
                                    <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="float: right">
        {{ $motivos->links() }}
    </div>
</div>
@push('estilos')
    <style>
        button[disabled] {
            pointer-events: none;
        }
    </style>
@endpush
