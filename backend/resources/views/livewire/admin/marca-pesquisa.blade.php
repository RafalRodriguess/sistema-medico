<div class="card-body">
                                   
    <form action="javascript:void(0)" id="FormTitular">
        <div class="row">
              <div class="col-md-10">
                <div class="form-group" style="margin-bottom: 0px !important;">
                     
                      <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                    
                     
                </div>
              </div>
              @can('habilidade_admin', 'cadastrar_marcas')
                                                  <div class="col-md-2">
                <div class="form-group" style="margin-bottom: 0px !important;">
                     <a href="{{ route('marcas.create') }}">
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
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="2">Imagem</th> --}}
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>

                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($marcas as $marca)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $marca->id }}</a></td>
                    {{-- <td>
                        <img src="
                        @if ($marca->imagem)
                            {{ \Storage::cloud()->url($marca->imagem)}}                                                                 
                        @endif
                        " alt="" style="width: 50px; height: 50px;">
                    </td> --}}
                    <td>{{ $marca->nome }}</td>
                    <td>
                        @can('habilidade_admin', 'editar_marcas')
                            <a href="{{ route('marcas.edit', [$marca]) }}">
                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-pencil-alt"></i>
                                    </button>
                            </a>
                        @endcan 
                   
                        @can('habilidade_admin', 'excluir_marcas')
                            <form action="{{ route('marcas.destroy', [$marca]) }}" method="post" class="d-inline form-excluir-registro">
                                @method('delete')
                                @csrf
                                <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false">
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
                    {{ $marcas->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $marcas->links() }}
    </div>
</div>