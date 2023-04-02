


<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa"  wire:model.lazy="pesquisa" name="pesquisa"
                        class="form-control" placeholder="Pesquise por descrição...">
                </div>
            </div>

            @can('habilidade_instituicao_sessao', 'cadastrar_instituicoes_transferencia')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.internacao.instituicoes-transferencia.create') }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>

    <div class="table-responsive">
        <table class="tablesaw table-bordered table-hover table" >
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Descrição</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">CNES</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($instituicoes_transferencia as $instituicao_transferencia)
                    <tr>
                        <td class="title">{{ $instituicao_transferencia->id }}</td>
                        <td>{{ $instituicao_transferencia->descricao }}</td>
                        <td>{{ $instituicao_transferencia->cnes }}</td>

                        <td>
                            @can('habilidade_instituicao_sessao', 'editar_instituicoes_transferencia')
                                <a href="{{ route('instituicao.internacao.instituicoes-transferencia.edit', [$instituicao_transferencia]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                </a>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'excluir_instituicoes_transferencia')
                                <form action="{{ route('instituicao.internacao.instituicoes-transferencia.destroy', [$instituicao_transferencia]) }}" method="post" class="d-inline form-excluir-registro">
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
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
    <div style="float: right">
        {{ $instituicoes_transferencia->links() }}
    </div>
</div>

