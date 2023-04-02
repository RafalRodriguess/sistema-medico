<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-6">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         
                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                            
                         
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group" style="margin-bottom: 0px !important;" wire:ignore>
                         
                        <select name="categoria_id" class="form-control selectfild2" wire:model="categoria">
                        <option value="0">Todas</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>

                         
                    </div>
                  </div>
                    @can('habilidade_comercial_sessao', 'cadastrar_sub_categoria')
                        <div class="col-md-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <a href="{{ route('comercial.sub_categorias.create') }}">
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
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Categoria</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sub_categorias as $sub_categoria)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $sub_categoria->id }}</a></td>
                <td>{{ $sub_categoria->nome }}</td>
                <td>{{ $sub_categoria->categoria->nome }}</td>
                <td>
                 @can('habilidade_comercial_sessao', 'editar_sub_categoria')
                    <a href="{{ route('comercial.sub_categorias.edit', [$sub_categoria]) }}">
                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                <i class="ti-pencil-alt"></i>
                        </button>
                    </a>
               @endcan
               @can('habilidade_comercial_sessao', 'excluir_sub_categoria')
                    <form action="{{ route('comercial.sub_categorias.destroy', [$sub_categoria]) }}" method="post" class="d-inline form-excluir-registro">
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
            <td colspan="5">
                {{ $sub_categorias->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $sub_categorias->links() }}
</div>
</div>