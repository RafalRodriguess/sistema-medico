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
            @can('habilidade_instituicao_sessao', 'cadastrar_atendimentos')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.atendimentos.create') }}">
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
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Descrição</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($atendimentos as $atendimento)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $atendimento->id }}</a></td>
                        
                        <td>{{ $atendimento->nome }}</td>
                        <td>{{ $atendimento->descricao }}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'habilidades_atendimentos')
                                <a href="{{ route('atendimentos.habilidades', [$atendimento]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                            <i class="ti-lock"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'editar_atendimentos')
                                <a href="{{ route('instituicao.atendimentos.edit', [$atendimento]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                <i class="ti-pencil-alt"></i>
                                        </button>
                                </a>
                            @endcan
                            
                            @can('habilidade_instituicao_sessao', 'excluir_atendimentos')
                                <form action="{{ route('instituicao.atendimentos.destroy', [$atendimento]) }}" method="post" class="d-inline form-excluir-registro">
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
            {{-- <tfoot>
                <tr>
                    <td colspan="6">
                        {{ $atendimentos->links() }}
                    </td>
                </tr> 
            </tfoot> --}}
        </table>
    </div>
    <div style="float: right">
        {{ $atendimentos->links() }}
    </div>
</div>