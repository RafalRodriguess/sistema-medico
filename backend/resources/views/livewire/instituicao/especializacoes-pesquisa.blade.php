<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_especializacao')
            <div class="col-md-2">
                <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.especializacoes.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="2">Descrição</th>
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="5">Instituições</th> --}}
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($especializacoes as $especializacao)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $especializacao->id }}</a></td>
                    <td>{{ $especializacao->descricao }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_especializacao')
                            <a href="{{ route('instituicao.especializacoes.edit', [$especializacao]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_especializacao')
                            <form action="{{ route('instituicao.especializacoes.destroy', [$especializacao]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false">
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
        {{ $especializacoes->links() }}
    </div>
</div>

