<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por numero documento">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_estoque_baixa_produtos')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.estoque_baixa_produtos.criar') }}">
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
                    data-tablesaw-priority="3">Setor</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                    data-tablesaw-priority="3">Usuário</th>

                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                    Data Baixa
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                    Hora Baixa
                </th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estoqueBaixas as $item)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                    <td>{{ $item->estoque->descricao }}</td>
                    <td>
                        @if (!empty($item->setorExame))
                            {{ $item->setorExame->descricao }}
                        @endif
                    </td>


                    <td>{{ $item->usuario->nome }}</td>

                    <td>{{ \Carbon\Carbon::parse($item->data_emissao)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->data_hora_baixa)->format('H:i') }}</td>
                    <td>
                        @can('habilidade_instituicao_sessao', 'editar_estoque_baixa_produtos')
                            <a href="{{ route('instituicao.estoque_baixa_produtos.editar', [$item]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                    aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                    data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                                </button>
                            </a>
                        @endcan
                        @can('habilidade_instituicao_sessao', 'excluir_estoque_baixa_produtos')
                            <form action="{{ route('instituicao.estoque_baixa_produtos.destroy', [$item]) }}" method="post"
                                class="d-inline form-excluir-registro">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                    aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                    data-original-title="Excluir">
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

        </tr>  --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $estoqueBaixas->links() }}
    </div>
</div>
