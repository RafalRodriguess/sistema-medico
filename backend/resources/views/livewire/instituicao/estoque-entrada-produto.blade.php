<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
            <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                    <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control"
                        placeholder="Pesquise por id ou produto...">
                </div>
            </div>
            @can('habilidade_instituicao_sessao', 'cadastrar_estoque_entrada_produtos')
                <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        <div>
                            <a href="{{ route('instituicao.estoque_entrada_produtos.create', $estoqueEntrada) }}">
                                <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                            </a>
                        </div>
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
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col
                        data-tablesaw-priority="3">ID Produto</th>

                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Quantidade</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Unidade</th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                        Valor de compra
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                        Valor de venda
                    </th>
                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5">
                        Lote
                    </th>

                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($estoqueEntradaProdutos as $item)
                    {{-- Removendo decimais caso desnecessário para melhorar a visibilidade de unidades inteiras --}}
                    @php
                        $quantidade = explode('.', $item->quantidade);
                        $quantidade = $quantidade[1] == '00' ? $quantidade[0] : implode(',', $quantidade);
                    @endphp
                    <tr>
                        <td>{{ $item->produtos->descricao }}</td>
                        <td class="text-right">{{ $quantidade }}</td>
                        <td>{{ $item->produtos->unidade->descricao }}</td>
                        <td>R$ {{ tobrl($item->valor_custo) }}</td>
                        <td>R$ {{ tobrl($item->valor) }}</td>
                        <td>{{ $item->lote }}</td>
                        <td>
                            <?php

                            ?>
                            @can('habilidade_instituicao_sessao', 'editar_estoque_entrada_produtos')
                                <a
                                    href="{{ route('instituicao.estoque_entrada_produtos.editar', [$estoqueEntrada, $item]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true"
                                        aria-expanded="false" data-toggle="tooltip" data-placement="top"
                                        data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('habilidade_instituicao_sessao', 'excluir_estoque_entrada')
                                <form
                                    action="{{ route('instituicao.estoque_entrada_produtos.destroy', [$estoqueEntrada, $item]) }}"
                                    method="POST" class="d-inline form-excluir-registro">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"
                                        aria-haspopup="true" aria-expanded="false" data-toggle="tooltip"
                                        data-placement="top" data-original-title="Excluir">
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
        <!--  -->
        {{ $estoqueEntradaProdutos->links() }}
    </div>
</div>
