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
            @can('habilidade_instituicao_sessao', 'cadastrar_configuracao_prontuario')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.configuracaoProntuario.create') }}">
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
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Descrição</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prontuarios as $item)
                    {{-- <tr data-toggle="collapse" data-target="#example_{{$item->id}}" class="accordion-toggle"> --}}
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                        <td>{{ $item->descricao }}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'editar_configuracao_prontuario')
                                <a href="{{ route('instituicao.configuracaoProntuario.edit', [$item]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                                <i class="ti-pencil-alt"></i>
                                        </button>
                                </a>
                            @endcan
                            
                            @can('habilidade_instituicao_sessao', 'desativar_configuracao_prontuario')
                                <form action="{{ route('instituicao.configuracaoProntuario.destroy', [$item]) }}" method="post" class="d-inline form-ativar-desativar">
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-xs btn-secondary btn-ativar-desativar"  aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Desativar/Ativar">
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
        {{ $prontuarios->links() }}
    </div>
</div>
