<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por Produto">
                    </div>
                  </div>
                    @can('habilidade_instituicao_sessao', 'cadastrar_estoque_baixa_produtos')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <a href="{{ route('instituicao.produtos_baixa.criar', [$estoqueBaixa]) }}">
                                <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                                </a>
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
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">Produto</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Quantidade</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Lote</th>

        </tr>
    </thead>
    <tbody>

        @foreach($produtosBaixa as $item)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $item->id }}</a></td>
                <td>{{ $item->produtos->descricao}}</td>
                    <td>{{ $item->quantidade}}</td>


                <td>{{ $item->lote }}</td>
                <td>
                @can('habilidade_instituicao_sessao', 'editar_estoque_baixa_produtos')
                    <a href="{{ route('instituicao.produtos_baixa.editar', [$estoqueBaixa, $item]) }}">
                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                            </button>
                    </a>
                @endcan
                @can('habilidade_instituicao_sessao', 'excluir_estoque_baixa_produtos')
                    <form action="{{ route('instituicao.produtos_baixa.destroy', [$estoqueBaixa, $item]) }}" method="post" class="d-inline form-excluir-registro">
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
  <div style="float: right">
         {{ $produtosBaixa->links() }}
     </div>
</div>
