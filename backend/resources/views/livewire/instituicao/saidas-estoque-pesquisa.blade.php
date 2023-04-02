<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">

                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por componente...">


                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_saida_estoque')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.saidas-estoque.create') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Nova</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>

    <div class="row">
        <div class="col-12 table-container">
            <table class="tablesaw table-bordered table-hover table">
                <thead>
                    <tr>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                            data-tablesaw-priority="3">Origem</th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                                data-tablesaw-priority="3">Destino</th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                            data-tablesaw-priority="2">
                            Tipo de destino
                        </th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                            data-tablesaw-priority="2">
                            Centro de custo
                        </th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                            data-tablesaw-priority="2">
                            Usuário
                        </th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                            data-tablesaw-priority="2">
                            Produtos
                        </th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                            data-tablesaw-priority="2">
                            Data
                        </th>
                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($saidas as $saida)
                        <tr>
                            <td class="title"><a href="javascript:void(0)">{{ $saida->id }}</a></td>
                            <td>{{ $saida->estoque->descricao }}</td>
                            <td>
                                @if($saida->tipo_destino == 1)
                                    {{ $saida->paciente->nome }}
                                @elseif($saida->tipo_destino == 2 && !empty($saida->agendamento->pessoa))
                                    {{ $saida->agendamento->pessoa->nome }}
                                @endif
                            </td>
                            <td>
                                {{ !empty($saida->tipo_destino) ? (\App\SaidaEstoque::destino_saida[$saida->tipo_destino] ?? '') : 'Não especificado' }}
                            </td>
                            <td>{{ !empty($saida->centroDeCusto) ? $saida->centroDeCusto->descricao : '' }}</td>
                            <td>{{ $saida->usuario->nome }}</td>
                            <td>
                                <ul class="m-0 pl-3">
                                    @if (!empty($saida->produtosBaixa))
                                        @foreach ($saida->produtosBaixa as $key => $produto)
                                            @if($key < 4 && !empty($produto->produtos))
                                                <li>{{ $produto->produtos->descricao }}</li>
                                            @else 
                                                <li>...</li>
                                                @break
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </td>
                            <td>{{ (new \DateTime($saida->updated_at))->format('d/m/Y') }}</td>
                            <td>
                                @can('habilidade_instituicao_sessao', 'editar_saida_estoque')
                                    <a href="{{ route('instituicao.saidas-estoque.edit', [$saida]) }}">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                            aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                            data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                        </button>
                                    </a>
                                @endcan
                                @can('habilidade_instituicao_sessao', 'excluir_saida_estoque')
                                    <form action="{{ route('instituicao.saidas-estoque.destroy', [$saida]) }}"
                                        method="post" class="d-inline form-excluir-registro">
                                        @method('delete')
                                        @csrf
                                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                            aria-haspopup="true" aria-expanded="false" data-toggle="tooltip"
                                            data-placement="top" data-original-title="Excluir">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                                @if ($saida->tipo_destino == 2 && \Gate::check('habilidade_instituicao_sessao', 'visualizar_saida_estoque'))
                                    <a href="{{ route('instituicao.saidas-estoque.imprimir', [$saida]) }}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                            aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                            data-original-title="Imprimir">
                                            <i class="ti-printer"></i>
                                        </button>
                                    </a>
                                @else
                                    <a>
                                        <button type="button" disabled class="disabled btn btn-xs btn-secondary" aria-haspopup="true"
                                            aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                            data-original-title="Imprimir">
                                            <i class="ti-printer"></i>
                                        </button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div style="float: right">
        {{ $saidas->links() }}
    </div>
</div>
