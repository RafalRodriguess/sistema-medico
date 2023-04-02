<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por componente...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_setores_exame')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.setores.create') }}">
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
                    data-tablesaw-priority="3">Descricao</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">
                    Tipo
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">
                    Ativo
                </th>
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Via Administração
            </th> --}}
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($setores as $setor)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{$setor->id }}</a></td>
                    <td>{{$setor->descricao }}</td>
                    <td>{{$setor->tipo }}</td>
                    <td>@if($setor->ativo) <i class="fas fa-check text-success"></i> @else <i class="fas fa-times text-danger"></i> @endif</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_setores_exame')
                            <a href="{{ route('instituicao.setores.edit', [$setor]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'editar_setores_exame')
                            <form action="{{ route('instituicao.setores.switch', [$setor]) }}" method="post"
                                class="d-inline form-editar-registro">
                                @method('put')
                                @csrf
                                <input type="hidden" name="ativo" value="{{ !$setor->ativo }}">
                                <button type="submit" class="btn btn-xs btn-secondary"
                                    data-toggle="tooltip" data-placement="top" data-original-title="{{ $setor->ativo == 1 ? 'Desativar' : 'Ativar' }}">
                                    @if(!$setor->ativo) <i class="fas fa-check"></i> @else <i class="fas fa-times"></i> @endif
                                </button>
                            </form>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_setores_exame')
                            <form action="{{ route('instituicao.setores.destroy', [$setor]) }}" method="post"
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
        {{ $setores->links() }}
    </div>
</div>
