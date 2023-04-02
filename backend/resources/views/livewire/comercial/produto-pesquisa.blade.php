<div class="card-body">

    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                <div class="row col-md-10">
                  <div class="col-md-4">
                    <div class="form-group" style="margin-bottom: 0px !important;">

                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">


                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>

                        <select name="categoria_id" class="form-control selectfild2" wire:model="categoria">
                        <option value="0">Todas Categorias</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>


                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>

                        <select name="marca_id" class="form-control selectfild2" wire:model="marca">
                        <option value="0">Todas Marcas</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id }}">
                                    {{ $marca->nome }}
                                </option>
                            @endforeach
                        </select>


                    </div>
                  </div>
                </div>
                    @can('habilidade_comercial_sessao', 'cadastrar_produto')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <a href="{{ route('comercial.produtos.create') }}">
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
            <th></th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Preço</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">Quantidade</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Categoria
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                marca
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produtos as $produto)
            <tr @if (!$produto->exibir)
                style="background: #ff767696;color: black;"
            @endif>
                <td class="title"><a href="javascript:void(0)">{{ $produto->id }}</a></td>
                <td>
                    <img src="{{ \Storage::cloud()->url($produto->imagem) }}" alt="" style="height: 50px;">
                </td>
                <td>{{ $produto->nome }}</td>
                <td class="text-right">
                    @if($produto->promocao)
                        R$ {{ number_format($produto->preco_promocao, 2, ',', '.') }}
                        <small class="d-block">De: R$ {{ number_format($produto->preco, 2, ',', '.') }}</small>
                        <small class="d-block">
                            Válido de
                            {{ $produto->promocao_inicio->format('d/m/Y') }}
                            a
                            {{ $produto->promocao_final->format('d/m/Y') }}
                        </small>
                    @else
                        R$ {{ number_format($produto->preco, 2, ',', '.') }}
                    @endif
                </td>
                <td class="text-right">{{ $produto->quantidade }}</td>
                <td>
                    {{ $produto->categorias->nome }}
                    @if($produto->sub_categorias)
                    > {{ $produto->sub_categorias->nome }}
                    @endif
                </td>
                <td>{{ isset($produto->marcas->nome)?$produto->marcas->nome:''}}</td>
                <td>

                @can('habilidade_comercial_sessao', 'editar_produto')
                    <a href="{{ route('comercial.produtos.edit', [$produto]) }}">
                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                    <i class="ti-pencil-alt"></i>
                            </button>
                    </a>
                @endcan
                @can('habilidade_comercial_sessao', 'excluir_produto')
                    <form action="{{ route('comercial.produtos.destroy', [$produto]) }}" method="post" class="d-inline form-excluir-registro">
                        @method('delete')
                        @csrf
                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                <i class="ti-trash"></i>
                        </button>
                    </form>
                @endcan
                @can('habilidade_comercial_sessao', 'promocao_produto')
                    <a href="{{ route('comercial.produtos.promocao', [$produto]) }}">
                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Promoção">
                                    <i class="ti-money"></i>
                            </button>
                    </a>
                @endcan
                @can('habilidade_comercial_sessao', 'estoque_produto')
                    <a href="{{ route('comercial.produtos.estoque', [$produto]) }}">
                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Estoque">
                                    <i class="ti-layers-alt"></i>
                            </button>
                    </a>
                @endcan
                @can('habilidade_comercial_sessao', 'visualizar_perguntas')
                    <a href="{{ route('comercial.produtoPerguntas.index', [$produto]) }}">
                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                            data-toggle="tooltip" data-placement="top" data-original-title="Perguntas">
                                    <i class="ti-comment"></i>
                            </button>
                    </a>
                @endcan
                @can('habilidade_comercial_sessao', 'desativar_produto')
                    <form action="{{ route('comercial.produtos.desativar', [$produto]) }}" method="post" class="d-inline form-ativar-desativar">
                        @method('put')
                        @csrf
                        <input type="hidden" name="exibir" value="{{ $produto->exibir }}">
                        <button type="button" class="btn btn-xs btn-secondary btn-ativar-desativar"  aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top"
                        @if ($produto->exibir)
                            data-original-title="Não exibir"
                        @else
                            data-original-title="Exibir"
                        @endif
                        >
                        @if ($produto->exibir)
                            <i class="ti-close"></i>
                        @else
                            <i class="ti-check"></i>
                        @endif
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
                {{ $produtos->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $produtos->links() }}
</div>
</div>
