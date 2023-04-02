<div class="card-body">
    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                        <div class="form-group" style="margin-bottom: 0px !important;">
                            <input type="text" id="pesquisa"
                                wire:model.lazy="pesquisa" name="pesquisa"
                                class="form-control"
                                placeholder="Pesquise por id ou produto...">
                        </div>
                    </div>
                   @can('habilidade_instituicao_sessao', 'cadastrar_estoque_inventario_produtos')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <div>
                                <a href="{{ route('instituicao.estoque_inventario_produtos.create',$estoqueInventario) }}">
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
    <table class="tablesaw table-bordered table-hover table" >
        <thead>
            <tr>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">ID Produto</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Lote</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Quantidade</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Quantidade Inventário</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Salvar Quantidade Inventário</th>


                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>

            @foreach($estoqueInventarioProdutos as $item)
                <tr>
                    <td>{{ $item->produtos->descricao }}</td>
                    <td>{{ $item->lote }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <form action="{{ route('instituicao.estoque_inventario_produtos.updateInventario',[$estoqueInventario, $item]) }}" method="post" enctype="multipart/form-data">
                        @csrf <!-- {{ csrf_field() }} -->
                        <td>
                            <div class="col-sm-4"  >
                                <div class="form-group @if($errors->has('quantidade_inventario')) has-danger @endif">
                                    <input type="number" name="quantidade_inventario" value="{{ $item->quantidade_inventario }}"
                                        class="form-control @if($errors->has('quantidade_inventario')) form-control-danger @endif">
                                    @if($errors->has('quantidade_inventario'))
                                        <div class="form-control-feedback">{{ $errors->first('quantidade_inventario') }}</div>
                                    @endif
                                </div>
                            </div>

                        </td>
                        <td>
                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>

                        <td>
                    </form>
                        @can('habilidade_instituicao_sessao', 'editar_estoque_inventario_produtos')
                            <a href="{{ route('instituicao.estoque_inventario_produtos.editar', [$estoqueInventario, $item]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan
                    @can('habilidade_instituicao_sessao', 'excluir_estoque_entrada')
                        <form action="{{ route('instituicao.estoque_inventario_produtos.destroy', [$estoqueInventario, $item]) }}" method="POST" class="d-inline form-excluir-registro">
                            @csrf
                            <input type="hidden" name="id" value="{{$item->id}}">
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
</div>
<div style="float: right">
<!--  -->
{{$estoqueInventarioProdutos->links()}}
</div>
</div>
