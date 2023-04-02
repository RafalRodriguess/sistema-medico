<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por tipo de contagem">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_estoque_inventario')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.estoque_inventario.criar') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Estoque</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">Data</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Hora</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                    Status
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                    Tipo de Contagem
                </th>

                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estoqueInventario as $item)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                    <td>{{ $item->estoque->descricao }}</td>

                    <td>{{ \Carbon\Carbon::parse($item->data)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->hora)->format('H:i') }}</td>
                    <td class="p-0">
                        @if ($item->aberta == 1)
                            <span class="btn text-success btn-block mx-auto" aria-haspopup="true"
                            aria-expanded="false" data-toggle="tooltip" data-placement="top"
                            data-original-title="Aberto para edições"><i class="fas fa-lock-open"></i></span>
                        @else
                            <span class="btn text-danger btn-block mx-auto" aria-haspopup="true"
                            aria-expanded="false" data-toggle="tooltip" data-placement="top"
                            data-original-title="Fechado pelo usuário {{ $item->usuario->nome }}"><i class="fas fa-lock"></i>
                                {{ $item->usuario->nome }}
                            </span>
                        @endif
                    </td>
                    <td>{{ $item->tipo_contagem }}</td>
                    <td>
                        @if(\Gate::check('habilidade_instituicao_sessao', 'editar_estoque_inventario') && ($item->aberta == 1 || $item->usuario_id == $usuario_logado->id))
                            <a href="{{ route('instituicao.estoque_inventario.editar', [$item]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                    data-original-title="Atualizar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @else
                            <button disabled type="button" class="btn btn-xs btn-secondary disabled">
                                <i class="ti-pencil-alt"></i>
                            </button>
                        @endif
                        @if(\Gate::check('habilidade_instituicao_sessao', 'excluir_estoque_inventario') && ($item->aberta == 1 || $item->usuario_id == $usuario_logado->id))
                            <form action="{{ route('instituicao.estoque_inventario.destroy', [$item]) }}" method="post"
                                class="d-inline form-excluir-registro">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                    aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                    data-original-title="Excluir">
                                    <i class="ti-trash"></i>
                                </button>
                            </form>
                        @else
                            <button disabled type="button" class="btn btn-xs btn-secondary disabled">
                                <i class="ti-trash"></i>
                            </button>
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            {{-- <tr>

        </tr>  --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $estoqueInventario->links() }}
    </div>
</div>
