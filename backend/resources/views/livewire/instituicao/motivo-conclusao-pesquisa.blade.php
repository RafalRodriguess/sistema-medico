<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por descrição...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_motivos_conclusoes')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.motivoConclusao.create') }}">
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
                <th scope="col">ID</th>
                <th scope="col">Descrição</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($motivos as $motivo)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $motivo->id }}</a></td>
                    <td>{{ $motivo->descricao }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_motivos_conclusoes')
                            <a href="{{ route('instituicao.motivoConclusao.edit', [$motivo]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_motivos_conclusoes')
                            <form action="{{ route('instituicao.motivoConclusao.destroy', [$motivo]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
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

