<div class="card-body">
                                    
    <form action="javascript:void(0)" id="FormTitular">
            <div class="row">
                  <div class="col-md-10">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         
                          <input type="text" id="pesquisa" wire:model.lazy="pesquisa" name="pesquisa" class="form-control" placeholder="Pesquise por nome...">
                        
                         
                    </div>
                  </div>
                  {{-- @can('habilidade_admin', 'cadastrar_usuario')
                      
                  
                                                      <div class="col-md-2">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                         <a href="{{ route('usuarios.create') }}">
                          <button type="button" class="btn waves-effect waves-light btn-block btn-info">Novo</button>
                         </a>
                    </div>
                 </div>
                 @endcan --}}
                                             </div>
          </form>

          <hr>


<table class="tablesaw table-bordered table-hover table" >
    <thead>
        <tr>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist">ID</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3">Nome</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">CPF</th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1">
                Telefone
            </th>
            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($usuarios as $usuario)
            <tr>
                <td class="title"><a href="javascript:void(0)">{{ $usuario->id }}</a></td>
                <td>{{ $usuario->nome }}</td>
                <td>{{ $usuario->cpf }}</td>
                <td>{{ $usuario->telefone }}</td>
                <td>
                    @can('habilidade_admin', 'editar_usuario')
                        <a href="{{ route('usuarios.edit', [$usuario]) }}">
                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                data-toggle="tooltip" data-placement="top" data-original-title="Editar">
                                        <i class="ti-pencil-alt"></i>
                                </button>
                        </a>
               @endcan
               @can('habilidade_admin', 'excluir_usuario')
                    <form action="{{ route('usuarios.destroy', [$usuario]) }}" method="post" class="d-inline form-excluir-registro">
                        @method('delete')
                        @csrf
                        <button type="button" class="btn btn-xs btn-secondary btn-excluir-registro"  aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Excluir">
                                <i class="ti-trash"></i>
                        </button>
                    </form>
                @endcan
                @can('habilidade_admin', 'visualizar_endereco_usuario')
                    <a href="{{ route('usuario_enderecos.index', [$usuario]) }}">
                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Endereço">
                                <i class="ti-home"></i>
                        </button>
                    </a>
                @endcan
                @can('habilidade_admin', 'dispositivo_usuario')
                    <a href="{{ route('usuario.usuarioDevice', [$usuario]) }}">
                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                        data-toggle="tooltip" data-placement="top" data-original-title="Visualizar Dispositivos">
                                <i class="mdi mdi-cellphone"></i>
                        </button>
                    </a>
                @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        {{-- <tr>
            <td colspan="5">
                {{ $usuarios->links() }}
            </td>
        </tr>  --}}
    </tfoot>
</table>
<div style="float: right">
    {{ $usuarios->links() }}
</div>
</div>