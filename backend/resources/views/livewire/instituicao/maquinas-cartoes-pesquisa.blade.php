<div class="card-body">    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                        <input type="text" id="pesquisa" wire:model.lazy="pesquisa" 
                        name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_maquina_cartao')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.maquinasCartoes.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>

    <div class="table-responsive">
        <table class="tablesaw table-bordered table-hover table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descricao</th>
                    <th>Código</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maquinas as $item)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                        <td>{{ $item->descricao }}</td>
                        <td>{{ $item->codigo }}</td><td>
                            @can('habilidade_instituicao_sessao', 'editar_maquina_cartao')
                                <a href="{{ route('instituicao.maquinasCartoes.edit', [$item]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'excluir_maquina_cartao')
                                <form action="{{ route('instituicao.maquinasCartoes.destroy', [$item]) }}" method="post" class="d-inline form-excluir-registro">
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
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
                        {{ $prestador->links() }}
                    </td>
                </tr>  --}}
            </tfoot>
        </table>
    </div>

    <div style="float: right">
        {{ $maquinas->links() }}
    </div>
</div>
