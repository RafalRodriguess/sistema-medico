<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por componente...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_modalidades_exame')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.modalidades.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Nova</button>
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
                    data-tablesaw-priority="3">Sigla</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                        data-tablesaw-priority="3">
                        instituição
                </th>
                <th scope="col"
                    data-tablesaw-priority="3">
                    Descrição
            </th>
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Via Administração
            </th> --}}
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($modalidades as $modalidade)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $modalidade->id }}</a></td>
                    <td>{{ $modalidade->sigla }}</td>
                    <td>{{ $modalidade->instituicao->nome }}</td>
                    <td>{{ mb_strimwidth($modalidade->descricao, 0, 50, '...') }}</td>
                    {{-- <td>{{ $procedimento->via_administracao }}</td> --}}
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_modalidades_exame')
                            <a href="{{ route('instituicao.modalidades.edit', [$modalidade]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_modalidades_exame')
                            <form action="{{ route('instituicao.modalidades.destroy', [$modalidade]) }}" method="post"
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
        {{ $modalidades->links() }}
    </div>
</div>
