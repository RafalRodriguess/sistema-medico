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
            @can('habilidade_instituicao_sessao', 'cadastrar_origem')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.origem.create') }}">
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
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Descrição</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Tipo</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Centro de Custo</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Situação</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($origens as $origem)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $origem->id }}</a></td>
                        <td>{{ $origem->descricao }}</td>
                        <td>{{ App\Origem::getTipoTexto($origem->tipo_id) }}</td>
                        <td>{{ $origem->centroCusto()->codigo }} {{ $origem->centroCusto()->descricao }}</td>
                        <td>@if ($origem->ativo==1) Ativo @else Inativo @endif </td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'habilidades_origem')
                                <a href="{{ route('instituicao.origem.habilidades', [$origem]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                            <i class="ti-lock"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'editar_origem')
                                <a href="{{ route('instituicao.origem.edit', [$origem]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                </a>
                            @endcan
                            
                            @can('habilidade_instituicao_sessao', 'excluir_origem')
                                <form action="{{ route('instituicao.origem.destroy', [$origem]) }}" method="post" class="d-inline form-excluir-registro">
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
        {{ $origens->links() }}
    </div>
</div>
