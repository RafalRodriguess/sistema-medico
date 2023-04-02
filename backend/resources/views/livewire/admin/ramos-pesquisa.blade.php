<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                </div>
            </div>
            @can('habilidade_admin', 'cadastrar_ramo')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                    <a href="{{ route('ramos.create') }}">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                    </a>
                </div>
            @endcan
        </div>
    </form>

    <hr>

    <table class="tablesaw table-bordered table-hover table" >
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="2">Descricão</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Ações</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($ramos as $ramo)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $ramo->id }}</a></td>
                    <td>{{ $ramo->descricao }}</td>
                    <td>
                        @can('habilidade_admin', 'editar_ramo')
                            <a href="{{ route('ramos.edit', [$ramo]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan
                        @can('habilidade_admin', 'excluir_ramo')
                            <form action="{{ route('ramos.destroy', [$ramo]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                        <i class="ti-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @can('habilidade_admin', 'habilidades_ramo')
                            <a href="{{ route('ramos.habilidades', [$ramo]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                        <i class="ti-lock"></i>
                                </button>
                            </a>
                        @endcan
                    
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>

        </tfoot>
    </table>

    <div style="float: right">
        {{ $ramos->links() }}
    </div>
</div>