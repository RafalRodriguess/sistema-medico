<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa"
                        wire:model.lazy="pesquisa" name="pesquisa"
                        class="form-control"
                        placeholder="Pesquise por nome...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_centro_de_custo')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.financeiro.cc.create') }}">
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
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Código</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Descrição</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($centros_custos as $centro_custo)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $centro_custo->id }}</a></td>

                        <td>{{ $centro_custo->codigo }}</td>
                        <td>{{ $centro_custo->descricao }}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'habilidades_centro_de_custo')
                                <a href="{{ route('instituicao.financeiro.cc.habilidades', [$centro_custo]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                            <i class="ti-lock"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'editar_centro_de_custo')
                                <a href="{{ route('instituicao.financeiro.cc.edit', [$centro_custo]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                </a>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'excluir_centro_de_custo')
                                <form action="{{ route('instituicao.financeiro.cc.destroy', [$centro_custo]) }}" method="post" class="d-inline form-excluir-registro">
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
        {{ $centros_custos->links() }}
    </div>
</div>
