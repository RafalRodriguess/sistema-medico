<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por componente...">


                </div>
            </div>

            @can('habilidade_instituicao_sessao', 'cadastrar_solicitacoes_estoque')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.solicitacoes-estoque.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">
                    Tipo de destino
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">Destino</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">
                    Observação
                </th>
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Via Administração
            </th> --}}
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($solicitacoes as $solicitacao)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $solicitacao->id }}</a></td>
                    @switch($solicitacao->destino)
                        @case(1)
                            <td>Paciente</td>
                            <td>{{ $solicitacao->estoqueDestino()['agendamento_atendimento']->first()->pessoa->nome }}</td>
                            @break
                        @case(2)
                            <td>Setor</td>
                            <td>{{ $solicitacao->estoqueDestino()['setor']->first()->descricao }}</td>
                            @break
                        @case(3)
                            <td>Estoque</td>
                            <td>{{ $solicitacao->estoqueDestino()->first()->descricao }}</td>
                    @endswitch
                    <td>{{ $solicitacao->observacoes }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'atender_solicitacoes_estoque')
                            <a href="{{ route('instituicao.solicitacoes-estoque.atender.edit', [$solicitacao]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Atender solicitação">
                                    <i class="ti-check"></i>
                                </button>
                            </a>
                        @endcan
                        <a href="{{ route('instituicao.solicitacoes-estoque.atender.imprimir', [$solicitacao]) }}" target="_blank">
                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Imprimir solicitação">
                                <i class="ti-printer"></i>
                            </button>
                        </a>
                        @can('habilidade_instituicao_sessao', 'editar_solicitacoes_estoque')
                            <a href="{{ route('instituicao.solicitacoes-estoque.edit', [$solicitacao]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_solicitacoes_estoque')
                            <form action="{{ route('instituicao.solicitacoes-estoque.destroy', [$solicitacao]) }}" method="post"
                                class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                    aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
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
                {{ $procedimentos->links() }}
            </td>
        </tr> --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $solicitacoes->links() }}
    </div>
</div>
