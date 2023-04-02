<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por componente...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_tipos_chamada_totem')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.totens.tipos-chamada.create') }}">
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
                    data-tablesaw-priority="3">
                    Descrição
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="2">
                    Senhas
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tipos as $tipo)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $tipo->id }}</a></td>
                    <td>{{ $tipo->descricao }}</td>
                    <td>{{ mb_strimwidth($tipo->gancho()->descricao, 0, 50, '...') }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_tipos_chamada_totem')
                            <a href="{{ route('instituicao.totens.tipos-chamada.edit', [$tipo]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_tipos_chamada_totem')
                            <form action="{{ route('instituicao.totens.tipos-chamada.destroy', [$tipo]) }}" method="post"
                                class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                    aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                    <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            {{-- <tr>
            <td colspan="5">
                {{ $procedimentos->links() }}
            </td>
        </tr> --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $tipos->links() }}
    </div>
</div>
