<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">

                  </div>
                    @can('habilidade_admin', 'visualizar_estoque_entrada_produtos')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">

                            @if ($estoqueEntradaProduto->total()<1)
                            <div>
                                 <a href="{{ URL::previous() }}">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-info">Voltar</button>
                                </a>
                            </div>
                            <br>
                            <div>
                               <a href="{{ route('estoque_entrada_produtos.create',$id_entrada) }}">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                                </a>
                            </div>
                            @else
                               <a href="{{ route('estoque_entrada_produtos.create',[$estoqueEntradaProduto[0]->id_entrada]) }}">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                                </a>
                            @endif
                            </div>
                        </div>
                    @endcan
                </div>
    </form>

          <hr>


<table class="tablesaw table-bordered table-hover table" >
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">ID Entrada</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">ID Produto</th>

            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Quantidade</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Lote
            </th>

            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($estoqueEntradaProduto as $estoqueEntradaProdutos)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $estoqueEntradaProdutos->id }}</a></td>
                <td>
                </td>
                <td>{{ $estoqueEntradaProdutos->id_entrada }}</td>
                <td>{{ $estoqueEntradaProdutos->id_produto }} - {{ $estoqueEntradaProdutos->descricao }}</td>
                <td>{{ $estoqueEntradaProdutos->quantidade }}</td>
                <td>{{ $estoqueEntradaProdutos->lote }}</td>
                <td>
                <?php


                ?>
                    @can('habilidade_admin', 'editar_estoque_entrada_produtos')
                        <a href="{{ route('estoque_entrada_produtos.editar', [$estoqueEntradaProdutos]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        </a>
                    @endcan
                 @can('habilidade_admin', 'excluir_estoque_entrada_produtos')
                    <form action="{{ route('estoque_entrada_produtos.destroy', [$estoqueEntradaProdutos]) }}" method="get" class="d-inline form-excluir-registro">
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
    <tfoot>
        {{-- <tr>

        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
<!--  -->
</div>
</div>
