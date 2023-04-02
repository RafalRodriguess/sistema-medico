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
            @can('habilidade_instituicao_sessao', 'cadastrar_unidade_internacao')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.internacao.unidade-internacao.create') }}">
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
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Tipo</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Localização</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">Hospital Dia</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6">Ativo</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unidades_internacoes as $unidade_internacao)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $unidade_internacao->id }}</a></td>
                        
                        <td>{{ $unidade_internacao->nome }}</td>
                        <td>{{ App\UnidadeInternacao::getTipoUnidadeTexto($unidade_internacao->tipo_unidade) }}</td>
                        <td>{{ $unidade_internacao->localizacao }}</td>
                        <td>@if ($unidade_internacao->hospital_dia=='1') Sim @else Não @endif</td>
                        <td>@if ($unidade_internacao->ativo=='1') Sim @else Não @endif</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'habilidades_unidade_internacao')
                                <a href="{{ route('instituicao.internacao.unidade-internacao.habilidades', [$unidade_internacao]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Habilidades">
                                        <i class="ti-lock"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'editar_unidade_internacao')
                                <a href="{{ route('instituicao.internacao.unidade-internacao.edit', [$unidade_internacao]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                </a>
                            @endcan
                            
                            @can('habilidade_instituicao_sessao', 'excluir_unidade_internacao')
                                <form action="{{ route('instituicao.internacao.unidade-internacao.destroy', [$unidade_internacao]) }}" method="post" class="d-inline form-excluir-registro">
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_leitos')
                                <a href="{{ route('instituicao.internacao.leitos.index', [$unidade_internacao]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Leitos">
                                        <i class="ti-more-alt"></i>
                                    </button>
                                </a>
                            @endcan
                        
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="float: right">
        {{ $unidades_internacoes->links() }}
    </div>
</div>
