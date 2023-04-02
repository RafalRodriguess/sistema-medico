<style>
    .header-compact {
        pointer-eventsclear: none;
        cursor: help;
    }
</style>

<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por numero documento">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_estoque_entrada')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <a href="{{ route('instituicao.estoque_entrada.criar') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </form>

    <hr>


    <div class="table-responsive">
        <table class="tablesaw table-bordered table-hover table">
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Estoque</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                        data-tablesaw-priority="3">ID Tipo Documento</th>
                    <th scope="col" class="header-compact" data-tablesaw-sortable-col data-tablesaw-priority="2"
                        aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top"
                        data-original-title="Consignado">
                        Cons.
                    </th>
                    <th scope="col" class="header-compact" data-tablesaw-sortable-col data-tablesaw-priority="1"
                        aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" data-placement="top"
                        data-original-title="Contabiliza">
                        Cont.
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                        Numero do Documento
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                        Série
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                        ID Fornecedor
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                        Data Emissão
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                        Hora Entrada
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($estoqueEntradas as $item)
                    <tr>
                        <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                        <td>{{ $item->estoque->descricao }}</td>
                        <td>{{ $item->tipoDocumento->descricao }}</td>
                        <td class="text-center">
                            @if ($item->consignado)
                                <span class="text-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="text-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->contabiliza)
                                <span class="text-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="text-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </td>

                        <td>{{ $item->numero_documento }}</td>
                        <td>{{ $item->serie }}</td>
                        <td>{{ $item->pessoas->nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->data_emissao)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->data_hora_entrada)->format('H:i') }}</td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'editar_estoque_entrada')
                                <a href="{{ route('instituicao.estoque_entrada.editar', [$item]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                        aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                        data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'excluir_estoque_entrada')
                                <form action="{{ route('instituicao.estoque_entrada.destroy', [$item]) }}" method="post"
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

                            {{-- @can('habilidade_instituicao_sessao', 'cadastrar_estoque_entrada')
                                <a href="{{ route('instituicao.estoque_entrada_produtos.index', [$item]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                        aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                        data-original-title="Produtos">
                                        <i class="ti-user"></i>
                                    </button>
                                </a>
                            @endcan --}}

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="float: right">
        {{ $estoqueEntradas->links() }}
    </div>
</div>
