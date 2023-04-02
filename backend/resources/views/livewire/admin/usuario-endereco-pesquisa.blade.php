<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         
                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise...">
                        
                         
                    </div>
                  </div>
                  @can('habilidade_admin', 'cadastrar_endereco_usuario')
                                                      <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('usuario_enderecos.create', [$usuario])}}">
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
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Endereço</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Cidade</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Estado
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($enderecos as $endereco)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $endereco->id }}</a></td>
            <td>{{ $endereco->rua }}, {{$endereco->numero}} - {{$endereco->bairro}}</td>
                <td>{{ $endereco->cidade }}</td>
                <td>{{ $endereco->estado }}</td>
                <td>
                @can('habilidade_admin', 'editar_endereco_usuario')
                        <a href="{{ route('usuario_enderecos.edit', [$usuario, $endereco]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        </a>
                @endcan
               @can('habilidade_admin', 'excluir_endereco_usuario')
                    <form action="{{ route('usuario_enderecos.destroy', ['usuario' => $usuario,
                        'endereco' => $endereco]) }}" method="post" class="d-inline form-excluir-registro">
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
                {{ $enderecos->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $enderecos->links() }}
</div>
</div>