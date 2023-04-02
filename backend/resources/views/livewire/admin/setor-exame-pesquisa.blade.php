<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                </div>
            </div>
            @can('habilidade_admin', 'cadastrar_setor_exame')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                    <a href="{{ route('setorExame.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="2">Instituição</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Ações</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($setores as $setor)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $setor->id }}</a></td>
                    <td>{{ $setor->descricao }}</td>
                    <td>{{ $setor->instituicao->nome }}</td>
                    <td>
                        @can('habilidade_admin', 'editar_setor_exame')
                            <a href="{{ route('setorExame.edit', [$setor]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan
                        
                        @can('habilidade_admin', 'excluir_setor_exame')
                            <form action="{{ route('setorExame.desativar', [$setor]) }}" method="post" class="d-inline form-ativar-desativar">
                                @method('put')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-ativar-desativar"  aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" @if ($setor->ativo == 1)
                                    data-original-title="Desativar"
                                @else
                                    data-original-title="Ativar"
                                @endif>
                                @if ($setor->ativo)
                                    <i class="ti-close"></i>
                                @else
                                    <i class="ti-check"></i>
                                @endif
                                </button>
                            </form>
                        @endcan
                    
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>

        </tfoot>
    </table>

    <div style="float: right">
        {{ $setores->links() }}
    </div>
</div>