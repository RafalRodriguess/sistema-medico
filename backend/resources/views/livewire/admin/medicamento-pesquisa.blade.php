<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         
                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por composição...">
                        
                         
                    </div>
                  </div>
                  @can('habilidade_admin', 'cadastrar_medicamentos')
                                                      <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('medicamentos.create') }}">
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
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Composição</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Código</th>
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Quantidade</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                    Unidade
                </th> --}}
                {{-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                    Via Administração
                </th> --}}
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medicamentos as $medicamento)
                <tr>
                    <td class="title"><a href="javascript:void(0)">{{ $medicamento->id }}</a></td>
                    <td>{{ $medicamento->componente }}</td>
                    <td>{{ $medicamento->codigo_externo }}</td>
                    {{-- <td>{{ str_replace(".", ",", $medicamento->quantidade) }}</td>
                    <td>{{ $medicamento->unidade }}</td> --}}
                    {{-- <td>{{ $medicamento->via_administracao }}</td> --}}
                    <td>
                    @can('habilidade_admin', 'editar_medicamentos')
                        <a href="{{ route('medicamentos.edit', [$medicamento]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        </a>
                    @endcan
                @can('habilidade_admin', 'excluir_medicamentos') 
                        <form action="{{ route('medicamentos.destroy', [$medicamento]) }}" method="post" class="d-inline form-excluir-registro">
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
                    {{ $medicamentos->links() }}
                </td>
            </tr>  --}}
        </tfoot>
    </table>
    <div style="float: right">
        {{ $medicamentos->links() }}
    </div>
</div>